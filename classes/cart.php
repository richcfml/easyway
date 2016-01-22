<?php
class cart
{
    public $restaurant_id;
    public $products;
    public $sub_total;
    public $delivery_type;
    public $sales_tax_ratio;
    public $rest_delivery_charges;
    public $driver_tip;
    public $coupon_discount;
    public $coupon_code;
    public $order_id;
    const Delivery = 1;
    const Pickup = 2;
    const None = 0;
    public $order_created;
    public $vip_discount;
    public $payment_menthod;
    public $PNRef;
    const GATEWAY = 1;
    const CASH = 2;

    public $invoice_number;
	
    function __construct() 
    {
        $this->products=array();
        $this->delivery_type=cart::None;
        $this->sub_total=0.00;
        $this->driver_tip=0.00;
        $this->coupon_discount=0.00;
        $this->order_created=0;
        $this->vip_discount=0.00;
        $this->PNRef="";
    }
    
    public function addfavorites($products) 
    {
        foreach($products as $product)
        {
            $this->addProduct($product);
        }
    }
    
    public function clear() 
    {
        $this->products=array();
        $this->sub_total =0;
        $_SESSION['CART']=serialize($this);
    }
        
    public function showCartItem($productId,$updateCartProductIndex)
    {
        return $this->products[$updateCartProductIndex];
    }
	
    public function addProduct(&$product,$updateCartProductIndex=-1)
    {
        $debug=false;
        $this->destroyclone();
        if($debug) echo '<pre>'.print_r($product,true).'</pre>';
        if($updateCartProductIndex>=0)
        {
            unset($this->products[$updateCartProductIndex]);
            $this->products=array_values($this->products);
        }
        $cartProductIndex=0;
        $cartProducts=$this->products;
        $addProductToCart=true;
        foreach($cartProducts as $earlierCartObj)
        {
            if($debug) 
            {
                echo __LINE__."  ".$earlierCartObj->prd_id."  ".$product->prd_id;
            }
            
            if($earlierCartObj->prd_id == $product->prd_id)
            {
                if($debug) 
                {
                    echo __LINE__;
                }
                $sameAttributes=false;
                $sameAttributeCount=0;
                $cartAttributeCount=count($earlierCartObj->attributes);
                $productAttributeCount=count($product->attributes);
                    
                if($productAttributeCount==$cartAttributeCount)
                {
                    foreach($product->attributes as $productAttributeId=>$productAttribute)
                    {    
                        if(isset($earlierCartObj->attributes[$productAttributeId]))
                        {
                            if($debug) 
                            {
                                echo __LINE__."  ".$productAttributeId;
                            }
                            $sameAttributeCount++;
                        }
                    }
                        
                    if($sameAttributeCount==$productAttributeCount)
                    {    
                        if($debug)
                        {
                            echo __LINE__;
                        }
                        $sameAttributes=true;
                    }
                }
                
                $sameAssociations=false;
                $sameAssociationCount=0;
                $cartAssociationCount=count($earlierCartObj->associations);
                $productAssociationCount=count($product->associations);
                
                if($productAssociationCount==$cartAssociationCount)
                {
                    foreach($product->associations as $productAssociationId=>$cartProductAssociation)
                    {
                        if(isset($earlierCartObj->associations[$productAssociationId]))
                        {
                            if($debug) 
                            {
                                echo __LINE__."  ".$productAssociationId;
                            }
                            $sameAssociationCount++;
                        }
                    }
                    
                    if($sameAssociationCount==$productAssociationCount)
                    {    
                        if($debug) 
                        {
                            echo __LINE__;
                        }
                        $sameAssociations=true;
                    }
                }
                    
                if($sameAttributes && $sameAssociations)
                {    
                    if($debug) 
                    {
                        echo __LINE__;
                    }
                    $this->products[$cartProductIndex]->quantity+=$product->quantity;
                    $addProductToCart=false;
                }
            }
            $cartProductIndex++;
        }
        if($addProductToCart)
        {
            $this->products[]=$product;
        }
        $this->products=array_values($this->products);
        $cartProducts=$this->products;
        $cartSubTotal=0;
        foreach($cartProducts as $cartProduct)
        {
            if($debug) 
            {
                "cartSubTotal:".$cartSubTotal." item total:".($cartProduct->sale_price * $cartProduct->quantity);
            }
            $cartSubTotal +=($cartProduct->sale_price * $cartProduct->quantity);
        }
            
        $this->sub_total=$cartSubTotal;
        if($debug) 
        {
            echo '<pre>'.print_r($this,true).'</pre>';
        }
        $_SESSION['CART']=serialize($this);
    }
		
    public function updateProduct(&$product,$index)
    {	 
        $oldProduct=$this->products[$index];
	$this->sub_total -=($oldProduct->sale_price * $oldProduct->quantity);
		 
        $this->products[$index]=$product;
        $this->sub_total +=($product->sale_price * $product->quantity);
        $_SESSION['CART']=serialize($this);		 
    }

    public function totalItems()
    {
	return count($this->products);
    }
    
    public function isempty()
    {
        return count($this->products) ==0 ? true:false;
    } 
 
    public function sales_tax()
    {
        return round((($this->sales_tax_ratio/100)  * ($this->sub_total-$this->coupon_discount)),2);
    }
		
    public function setdriver_tip($tip)
    {
        $this->driver_tip=$tip;
        $this->save(); 
    }
    
    public function setdelivery_type($delivery_type)
    {
        $this->delivery_type=$delivery_type;
        if($delivery_type==cart::Pickup)// $this->driver_tip=0.00;
        $this->coupon_discount=0.00;
        $this->vip_discount=0.00;
        $this->save();
    }
                
    public function delivery_charges()
    {
        if($this->delivery_type==cart::Delivery)
        {
            return $this->rest_delivery_charges;
        }
	else
        {
            return 0;
	}
    }
			
    public function grand_total($includevip=1) 
    {
        if(count($this->products)==0) 
        {
            return 0;
        }

        $subtotal=($this->sub_total + $this->sales_tax() + $this->delivery_charges() + $this->driver_tip)."<br/>";
        $grandTotal=0;
        
        if($includevip)
        {
            $grandTotal = ($subtotal-($this->coupon_discount + $this->vip_discount)) ;
        }
	else 
        {
            $grandTotal = ($subtotal - $this->coupon_discount);
        }

        return $grandTotal;			
    }
    
    public function remove_Item($item_Index) 
    {
        $this->coupon_discount=0;
	$this->vip_discount=0;
	$this->sub_total -=($this->products[$item_Index]->sale_price   * $this->products[$item_Index]->quantity);
	unset($this->products[$item_Index]);
	$newarr= array_values($this->products);
	if(count($newarr)==0)
	{
            $this->products=array();
            $this->sub_total=0;
	}
	else
        {
            $this->products=$newarr;
	}
				 
	$this->save();
    }

    public function save()
    {
        $_SESSION['CART']=serialize($this);
    }

    public function apply_vip_discount($discount)
    {
        $this->vip_discount = currencyToNumber($discount);
        //$this->vip_discount=preg_replace("/[^0-9.]+/","",$discount); 
        $this->vip_discount=  currencyToNumber($discount);
        if(!is_numeric($this->vip_discount))
        {
            $this->vip_discount=0.0;
        }
    
        if($this->vip_discount>$this->grand_total(0))
        {
            $this->vip_discount=$this->grand_total(0);
        }
    
        $this->vip_discount=$this->vip_discount;
        $this->save();
    }
			
    public function redeemcoupon($coupon_code)
    {
        $this->coupon_discount=0.00;
        $this->vip_discount=0.00;
        $this->coupon_code='';

	$coupon	= dbAbstract::returnRealEscapedString(addslashes($coupon_code));	
        $mSQL = "SELECT * FROM coupontbl WHERE coupon_code = '$coupon' AND resturant_id=".$this->restaurant_id;
	$couponQry = dbAbstract::Execute($mSQL);
						
        $msg='';
        
        while (@$couponRs = dbAbstract::returnObject($couponQry)) 
        {
            $coupon_discount = @$couponRs->coupon_discount;
            $coupon_discount=  currencyToNumber($coupon_discount);
            //$coupon_discount=preg_replace("/[^0-9.]+/","",$coupon_discount); 
            $discount_in = @$couponRs->discount_in; // discount offered in  = $ or % 
            $current_time = date("Hi");

            if( strtotime($couponRs->coupon_date)>=strtotime(date("Y-m-d")) )
            {
                if( strtotime($couponRs->coupon_date) ==strtotime(date("Y-m-d"))  && $couponRs->coupon_time<$current_time)
                {
                    return 'This coupon is expired, you cannot use it anymore';				 
                }
                else
                {					
                    if (intval ($couponRs->min_order_total) > intval($this->sub_total))
                    {
                        return 'No code applied  This Coupon is valid for orders of $'. $couponRs->min_order_total .' or more';
                        break;
                    }
                    else
                    {
                        if( ! $this->ApplyCoupon($couponRs)) 
                        {
                            return 'No coupon code applied';
                        }
                    }		
                }
            }//COUPON IS NOT EXPIRED
            else 
            {
                return 'This coupon is expired, you cannot use it anymore';
            }
            $this->save();				
            return $msg;
        }
        return 'Invalid or wrong coupon code';
    }//function
		
    public function ApplyCoupon($couponRs) 
    {
        $coupon_discount = @$couponRs->coupon_discount;
				$coupon_discount=currencyToNumber($coupon_discount); 
				//$coupon_discount=preg_replace("/[^0-9.]+/","",$coupon_discount); 
        $discount_in = @$couponRs->discount_in; // discount offered in  = $ or % 
        $this->coupon_discount=0;
        $this->coupon_code='';
        $this->coupon_discount=0;
        
        if ($couponRs->coupon_type==1) 
        {
            if ($discount_in == "%") 
            {
                $this->coupon_discount = (($this->sub_total/100)*$coupon_discount);
            } 
            else if ($discount_in == "$") 
            {
                $this->coupon_discount = $coupon_discount;
            }
            $this->coupon_code=@$couponRs->coupon_code;
            return true;

        }
        else if ($couponRs->coupon_type==3)
        {
            $items_1_arr = explode ( ',',$couponRs->coupon_items1 );
            $applied=false;
            foreach($this->products as $product)
            {
                foreach($items_1_arr as $discount_cat)
                {
                    if ($discount_cat==$product->category_id)
                    {
                        if ($discount_in == "%")
                        {
                            $this->coupon_discount += round(((($product->sale_price*$product->quantity)/100)*$coupon_discount),2);
                        }
                        else if ($discount_in == "$") 
                        {
                            $this->coupon_discount += $coupon_discount;
                        }
                        $this->coupon_code=@$couponRs->coupon_code;
                        $applied=true;
                    }
                }
            }
            if ($applied)	
            {
                $this->coupon_discount = $this->coupon_discount;
            }
            return $applied;	
        }///Coupon type is 3 applied to full categories

    }
		
	//Modified01082013
    public function createNewOrder($userId,$userAddress,$serving_date,$asap,$payment_method,$invoice_number,$payment_approve,$type,$cc,$data_2,$transaction_id, $platform_used, $is_guest=0,$del_special_notes, $pCarkTokenOrderTbl = "")
    {
		global $loggedinuser;
        $mSQL = "UPDATE customer_registration SET orders_count=orders_count+1 WHERE id=$userId";
        dbAbstract::Update($mSQL);
        
        $mSQL = "SELECT phone, payment_gateway FROM resturants WHERE id=".$this->restaurant_id;
        $mPhoneRow = dbAbstract::ExecuteObject($mSQL);
        $mPhone = $mPhoneRow->phone;
        
        $mSQL = "INSERT INTO ordertbl 
                    SET UserID=$userId
                      , Totel='". $this->grand_total() ."'
                      , driver_tip='". $this->driver_tip ."'
                      , OrderDate='". date("Y-m-d") ."'
                      , Approve=0
                      , payment_approv	=". $payment_approve ."
                      , a_dotnet_invoice_number='". $invoice_number ."'
                      , ItemName=''
                      , DeliveryAddress='". prepareStringForMySQL($userAddress) ."'
                      , DelSpecialReq='". prepareStringForMySQL($del_special_notes) ."'
                      , DesiredDeliveryDate='". $serving_date ."'
                      , Tax='". $this->sales_tax() ."'
                      , coupons='". $this->coupon_code ."'
                      , coupon_discount='". $this->coupon_discount ."'
                      , submit_time='".  date("Y-m-d H:i:s") ."'
                      , cat_id='".  $this->restaurant_id ."'
                      , delivery_chagres='".  $this->delivery_charges() ."'
                      , order_receiving_method='".  ($this->delivery_type==cart::Pickup ?"Pickup":"Delivery") ."'
                      , asap_order='". $asap ."'
                      , json_sent='0'
                      , vip_discount='0'
                      , transaction_id='".$transaction_id  ."'
                      , platform_used='".$platform_used  ."'
                      , is_guest='".$is_guest  ."'
                      , cdata='".$cc  ."'
                      , payment_method='". $payment_method ."'
                      , PhoneNumber='". prepareStringForMySQL($mPhone) ."'";
        Log::write("Add new order details into ordertbl - cart.php", "QUERY --".$mSQL, 'order', 0 , 'user');
        $this->order_id = dbAbstract::Insert($mSQL, 0 ,2);	

        if (($mPhoneRow->payment_gateway=="suregate") && ($pCarkTokenOrderTbl!=""))
        {
            $mSQL = "UPDATE ordertbl SET CardToken='".$pCarkTokenOrderTbl."' WHERE OrderID=".$this->order_id;
            dbAbstract::Update($mSQL);
        }

        $this->saveOrderdetail();

        if($payment_method=='Credit Card' && $cc!='' && $data_2!='')
        {
            $mSQL = "select count(*) as total from general_detail  where id_2=$userId and data_type=$type and data_1=$cc";
            $result = dbAbstract::ExecuteObject($mSQL);
            if (($result->total==0) && ($type!=0) && ($cc!=0))
            {
                $mSQL = "insert into general_detail(id_2,data_type,data_1,data_2) values($userId ,'$type' ,'$cc','$data_2')";
				if($loggedinuser->ssoUserId > 0){
					$mSQL = "insert into general_detail(sso_user_id, id_2,data_type,data_1,data_2) values('".$loggedinuser->ssoUserId."', $userId ,'$type' ,'$cc','$data_2')";
				}
				
				dbAbstract::Insert($mSQL);
            }
        }
        $this->order_created=1;	
        $this->save();
    }
		
    private function saveOrderdetail()
    {
        $mProductCount = 1;
        foreach ($this->products as $product)
        {
            $str_attributes='';
            $str_assoc='';

            foreach($product->attributes as $attr)
            {
                $str_attributes .= '~'.trim($attr->Title);
                if($attr->Price!=0)
                {
                    $str_attributes .= '|'.$attr->Price;
                }
            }

            foreach($product->associations as $association)
            {
                    $str_assoc .= '~'.trim($association->item_title).'|'.$association->retail_price;				
            }

            $str_attributes=trim($str_attributes,'~');
            $str_assoc=trim($str_assoc,'~');

                
            $mSQL = "INSERT INTO orderdetails (pid, extra, orderid, quantity,ItemName,item_for,RequestNote,od_rest_price,associations,retail_price) VALUES ('" . $product->prd_id . "', '" . prepareStringForMySQL($str_attributes) . "','" . $this->order_id . "','" . $product->quantity . "','" . prepareStringForMySQL($product->item_title) . "','" . prepareStringForMySQL($product->item_for) . "','" . prepareStringForMySQL($product->requestnote) . "', '', '" . prepareStringForMySQL($str_assoc) . "', '" . prepareStringForMySQL($product->retail_price) . "')";
            Log::write("Add new order details into orderdetails - cart.php", "QUERY --".$mSQL, 'order', 0 , 'user');
            $mOrderDetailsID =  dbAbstract::Insert($mSQL, 0, 2);		
            if($result > 0)
            {
                foreach($product->attributes as $attr)
                {	
                    $mPrice = '0';
                    if (trim($attr->Price)!="")
                    {
                            $mPrice = trim(str_replace("$", "", $attr->Price));
                    }

                    $mAttrQry = "SELECT IFNULL(option_name, '') AS option_name, IFNULL(Type, 0) AS Type, IFNULL(attr_name, '') AS attr_name, IFNULL(extra_charge, 0 ) AS extra_charge FROM attribute WHERE id=".$attr->id;
                    $mAttrRes = dbAbstract::Execute($mAttrQry);
                    $mAttrRow = dbAbstract::ExecuteObject($mAttrQry);
                    $mTempLimit = explode("~", $mAttrRow->attr_name);
                    $mLimit = 0;
                    if (isset($mTempLimit[2]))		
                    {
                            if (is_numeric($mTempLimit[2]))
                            {
                                    $mLimit = trim($mTempLimit[2]);
                            }
                    }

                    $mSQL = "INSERT INTO orderdetails_attribute_options (OrderID, OrderDetailsID, AttributeID, AttributeTitle, AttributePrice, OptionName, Type, `Limit`, LimitPrice, ProductCount) VALUES (".$this->order_id.", ".$mOrderDetailsID.", ".$attr->id.", '".prepareStringForMySQL($attr->Title)."', '".prepareStringForMySQL($mPrice)."', '".prepareStringForMySQL($mAttrRow->option_name)."', ".$mAttrRow->Type.", ".$mLimit.", ".$mAttrRow->extra_charge.", ".$mProductCount.")";
                    dbAbstract::Insert($mSQL);
                }

                Log::write("Add new order details into orderdetails - cart.php", "Insert Successful for orderId = " . $this->order_id . "'", 'order', 0 , 'user');
            }
            else
            {
                Log::write("Add new order details into orderdetails - cart.php", "Insert Failed for orderId = " . $this->order_id . "'", 'order', 0 , 'user');
            }

            $mProductCount++;
        }
    }
		
    public function OrderDeliveryMethod()
    {
        return ($this->delivery_type==cart::Pickup ?"Pickup":"Delivery");
    }
    
    public function destroysession()
    {
        if(isset($_SESSION['CART']))
        {
            unset($_SESSION['CART']);
        }
        
        if(isset($_SESSION["EcommerceID"]))
        {
            unset($_SESSION["EcommerceID"]);
        }
    }
	
	
    public function createclone()
    {
        if(isset($_SESSION['CART']))
        {
            $_SESSION['ClONECART']=serialize($this);
        }
    }
    
    public function myclone()
    {
        if(isset($_SESSION['ClONECART']))
        {
           return unserialize($_SESSION['ClONECART']);
        }
        else
        {
           return NULL;
        }
    }
    
    public function destroyclone()
    {
        if(isset($_SESSION['ClONECART']))
        {
            unset($_SESSION['ClONECART']);
        }
    }
    
    public function increaseQuantity($qty,$item_Index)
    {
        $this->sub_total -=($this->products[$item_Index]->sale_price   * $this->products[$item_Index]->quantity);
        $this->sub_total +=($this->products[$item_Index]->sale_price   * $qty);
        $this->products[$item_Index]->quantity = $qty;
        $this->save();
    }
 }//CLASS
 
?>