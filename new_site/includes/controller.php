<?php
	isset($_GET['item']) ? $mod=$_GET['item'] : $mod='resturants';
	$wp_api = false;
	$ifrm = false;
	if(isset($_REQUEST["wp_api"])) 
	{
		$wp_api = true;
		$host = "";
		
		if(isset($_GET["host"])) 
		{
			$host = urldecode($_GET["host"]);
			$_SESSION["host_for_wp_api"] = $host;
		} 
		else if(isset($_SESSION["host_for_wp_api"])) 
		{
			$host = $_SESSION["host_for_wp_api"];
		} 
		else 
		{
			$host = (empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "https://" : "http://") . $_SERVER["SERVER_NAME"];
		}
		$settings = $objRestaurant->getRestaurantDesignSettings($host);
		if($_REQUEST["wp_api"] == "save_settings") 
		{
			require("save_wp_api_settings.php");
			die(0);
		} 
		else if($_REQUEST["wp_api"] == "load_resturant") 
		{
			if($objRestaurant->status==0)
			{
				$include=$site_root_path. "views/wordpress/wp_notavailable.php";
			} 
			else
			{
				if($settings->appearence == 1) 
				{
					$include=$site_root_path . "views/wordpress/wp-menu_1.php";
				} 
				else 
				{
					$include=$site_root_path . "views/wordpress/wp-menu_2.php";
				}
			}
		} 
		else if($_REQUEST["wp_api"] == "product") 
		{
			$include=$site_root_path. "views/wordpress/wp-product.php";
		} 
		else if($_REQUEST["wp_api"]=='addtofavorite') 
		{
			$include=$site_root_path. "views/wordpress/wp-addtofavorite.php";
		}
		else if($_REQUEST["wp_api"]=='favdetail') 
		{
			$include=$site_root_path. "views/wordpress/wp-favdetail.php";
		}
		else if($_REQUEST["wp_api"] == "cart") 
		{
			$include=$site_root_path. "views/wordpress/wp-cart.php";
		} 
		else if($_REQUEST["wp_api"] == "login") 
		{
			$include=$site_root_path. "views/wordpress/wp-login.php";
		}
		else if($_REQUEST["wp_api"] == "forgotpassword") 
		{
			$include=$site_root_path. "views/wordpress/wp-forgotpassword.php";
		} 
		else if($_REQUEST["wp_api"] == "account") 
		{
			$include=$site_root_path. "views/wordpress/wp-account.php";
		} 
		else if($_REQUEST["wp_api"] == "checkout") 
		{
			$include=$site_root_path. "views/wordpress/wp-checkout.php";
		} 
		else if($_REQUEST["wp_api"] == "confirmpayments") 
		{
			$include=$site_root_path. "views/wordpress/wp-orderform.php";
		} 
		else if($_REQUEST["wp_api"] == "submitorder") 
		{
			$include=$site_root_path. "views/wordpress/wp-submit_order.php";
		} 
		else if($_REQUEST["wp_api"] == "addtip" || $_REQUEST["wp_api"] == "redeemcoupon") 
		{
			$include=$site_root_path. "views/wordpress/wp-cart_stats.php";
		} 
		else if($_REQUEST["wp_api"] == "logout") 
		{
			$include=$site_root_path. "views/wordpress/wp-logout.php";
		} 
		else if($_REQUEST["wp_api"] == "editaccount") 
		{
			$include=$site_root_path. "views/wordpress/wp-editaccount.php";
		} 
		else if($_REQUEST["wp_api"] == "orderhistory") 
		{
			$include=$site_root_path. "views/wordpress/wp-orderhistory.php";
		} 
		else if($_REQUEST["wp_api"] == "mailinglist") 
		{
			$include=$site_root_path. "views/wordpress/wp-mailing_list.php";
		} 
		else if($_REQUEST["wp_api"] == "valutec") 
		{
			$include=$site_root_path. "views/valutec/tab_register_card_popup.php";	
		} 
		else if($_REQUEST["wp_api"] == "register_card") 
		{
			$include=$site_root_path. "views/valutec/tab_register_card.php";		
		} 
		else if($_REQUEST["wp_api"] == "thankyou") 
		{
			$include=$site_root_path. "views/wordpress/wp-thankyou.php";
		} 
		else if($_REQUEST["wp_api"] == "failed") 
		{
			$include=$site_root_path. "views/wordpress/wp-transaction_failed.php";
		} 
		else if($_REQUEST["wp_api"] == "tos") 
		{
			$include=$site_root_path. "views/wordpress/wp-tos.php";
		} 
		else if($_REQUEST["wp_api"] == "privacypolicy") 
		{
			$include=$site_root_path. "views/wordpress/wp-privacypolicy.php";
		}
		else if($_REQUEST["wp_api"] == "refundpolicy") 
		{
			$include=$site_root_path. "views/wordpress/wp-refundpolicy.php";
		}
		else if($_REQUEST["wp_api"] == "resturants") 
		{
			if ($mod=="reordercc")
			{
				$include=$site_root_path. "views/cdyne/customer_cards.php";
			}
			else if ($mod=="reordermobile")
			{
				$include=$site_root_path. "views/cdyne/customer_mobile.php";
			}
			else if($mod=='reordercm') 
			{
				$include=$site_root_path. "views/cdyne/customercm.php";					
			} 
		}
	}
	else if(isset($_REQUEST["ifrm"])) 
	{
		$ifrm = true;
		if($_REQUEST["ifrm"] == "load_resturant") 
		{
			if($objRestaurant->status==0)
			{
				$include=$site_root_path. "views/ifrm/if_notavailable.php";
			} 
			else
			{
				$include=$site_root_path . "views/ifrm/if-menu_2.php";
			}
		} 
		else if($_REQUEST["ifrm"] == "product") 
		{
			$include=$site_root_path. "views/ifrm/if-product.php";
		} 
		else if($_REQUEST["ifrm"]=='addtofavorite') 
		{
			$include=$site_root_path. "views/ifrm/if-addtofavorite.php";
		}
		else if($_REQUEST["ifrm"]=='favdetail') 
		{
			$include=$site_root_path. "views/ifrm/if-favdetail.php";
		}
		else if($_REQUEST["ifrm"] == "cart") 
		{
			$include=$site_root_path. "views/ifrm/if-cart.php";
		} 
		else if($_REQUEST["ifrm"] == "login") 
		{
			$include=$site_root_path. "views/ifrm/if-login.php";
		}
		else if($_REQUEST["ifrm"] == "forgotpassword") 
		{
			$include=$site_root_path. "views/ifrm/if-forgotpassword.php";
		} 
		else if($_REQUEST["ifrm"] == "account") 
		{
			$include=$site_root_path. "views/ifrm/if-account.php";
		} 
		else if($_REQUEST["ifrm"] == "checkout") 
		{
			$include=$site_root_path. "views/ifrm/if-checkout.php";
		} 
		else if($_REQUEST["ifrm"] == "confirmpayments") 
		{
			$include=$site_root_path. "views/ifrm/if-orderform.php";
		} 
		else if($_REQUEST["ifrm"] == "submitorder") 
		{
			$include=$site_root_path. "views/ifrm/if-submit_order.php";
		} 
		else if($_REQUEST["ifrm"] == "addtip" || $_REQUEST["ifrm"] == "redeemcoupon") 
		{
			$include=$site_root_path. "views/ifrm/if-cart_stats.php";
		} 
		else if($_REQUEST["ifrm"] == "logout") 
		{
			$include=$site_root_path. "views/ifrm/if-logout.php";
		} 
		else if($_REQUEST["ifrm"] == "editaccount") 
		{
			$include=$site_root_path. "views/ifrm/if-editaccount.php";
		} 
		else if($_REQUEST["ifrm"] == "orderhistory") 
		{
			$include=$site_root_path. "views/ifrm/if-orderhistory.php";
		} 
		else if($_REQUEST["ifrm"] == "mailinglist") 
		{
			$include=$site_root_path. "views/ifrm/if-mailing_list.php";
		} 
		else if($_REQUEST["ifrm"] == "valutec") 
		{
			$include=$site_root_path. "views/valutec/tab_register_card_popup.php";	
		} 
		else if($_REQUEST["ifrm"] == "register_card") 
		{
			$include=$site_root_path. "views/valutec/tab_register_card.php";		
		} 
		else if($_REQUEST["ifrm"] == "thankyou") 
		{
			$include=$site_root_path. "views/ifrm/if-thankyou.php";
		} 
		else if($_REQUEST["ifrm"] == "failed") 
		{
			$include=$site_root_path. "views/ifrm/if-transaction_failed.php";
		} 
		else if($_REQUEST["ifrm"] == "tos") 
		{
			$include=$site_root_path. "views/ifrm/if-tos.php";
		} 
		else if($_REQUEST["ifrm"] == "privacypolicy") 
		{
			$include=$site_root_path. "views/ifrm/if-privacypolicy.php";
		}
		else if($_REQUEST["ifrm"] == "refundpolicy") 
		{
			$include=$site_root_path. "views/ifrm/if-refundpolicy.php";
		}
		else if($_REQUEST["ifrm"] == "resturants") 
		{
			if ($mod=="reordercc")
			{
				$include=$site_root_path. "views/cdyne/customer_cards.php";
			}
			else if ($mod=="reordermobile")
			{
				$include=$site_root_path. "views/cdyne/customer_mobile.php";
			}
			else if($mod=='reordercm') 
			{
				$include=$site_root_path. "views/cdyne/customercm.php";					
			} 
		}
		else if($_REQUEST["ifrm"]=='checkfbid') 
		{
			$include=$site_root_path. "views/customer/ajax.php";
		}
	}
	else 
	{
		if($objRestaurant->status==0)
		{
			$_GET['ajax']=1;
			$include=$site_root_path. "views/restaurant/notavailable.php";
		} 
		else
		{	 
			if($mod=='resturants') 
			{
				$include=$site_root_path. "views/restaurant/menu.php";
			}
			else if($mod=='product') 
			{
				$include=$site_root_path. "views/restaurant/product.php";
			}
			else if($mod=='addtofavorite') 
			{
				$include=$site_root_path. "views/restaurant/addtofavorite.php";
			}
			else if($mod=='favdetail') 
			{
				$include=$site_root_path. "views/customer/favdetail.php";
			}
			else if($mod=='cart') 
			{
				$include=$site_root_path. "views/cart/cart.php";
			}
			else if($mod=='login') 
			{
				$include=$site_root_path. "views/customer/login.php";
			}
			else if($mod=='login_') 
			{
				$include=$site_root_path. "views/customer/login_.php";
			}
			else if($mod=='forgotpassword') 
			{
				$include=$site_root_path. "views/customer/forgotpassword.php";
			}
			else if($mod=='account') 
			{
				$include=$site_root_path. "views/customer/account.php";
			}
			else if($mod=='editaccount') 
			{
				$include=$site_root_path. "views/customer/editaccount.php";
			}
			else if($mod=='register') 
			{
				$include=$site_root_path. "views/customer/register.php";
			}
			else if($mod=='logout') 
			{
				$include=$site_root_path. "views/customer/logout.php";
			}
			else if($mod=='checkout') 
			{
				$include=$site_root_path. "views/cart/checkout.php";
			}
			else if($mod=='addtip' || $mod=='redeemcoupon') 
			{
				$include=$site_root_path. "views/cart/cart_stats.php";
			}
			else if($mod=='submitorder') 
			{
				$include=$site_root_path. "views/cart/submit_order.php";
			}
			else if($mod=='confirmpayments') 
			{
				$include=$site_root_path. "views/cart/orderform.php";
			}
			else if($mod=='thankyou') 
			{
				$include=$site_root_path. "views/restaurant/thankyou.php";
			}
			else if($mod=='failed') 
			{
				$include=$site_root_path. "views/cart/transaction_failed.php";
			}
			else if($mod=='orderhistory') 
			{
				$include=$site_root_path. "views/customer/orderhistory.php";
			}
			else if($mod=='valutec') 
			{
				$include=$site_root_path. "views/valutec/tab_register_card_popup.php";	
			}
			else if($mod=='register_card') 
			{
				$include=$site_root_path. "views/valutec/tab_register_card.php";		
			}
			else if($mod=='mailinglist') 
			{
				$include=$site_root_path. "views/restaurant/mailing_list.php";		
			} 
			else if($mod=='businesshours') 
			{
				$include=$site_root_path. "views/restaurant/businesshours.php";			
			} 
			else if($mod=='failed') 
			{
				$include=$site_root_path. "views/cart/transaction_failed.php";	
		    } 
			else if($mod=='myfavourites') 
			{
				$include=$site_root_path. "views/customer/favorites.php";	
		    } 
			else if($mod=='cdyne') 
			{
				$include=$site_root_path. "views/cdyne/index.php";	
			} 
			else if($mod=='reordermobile') 
			{
				$include=$site_root_path. "views/cdyne/customer_mobile.php";
			} 
			else if($mod=='report_abandoned_cart_error') 
			{
				$include = $site_root_path . "views/cart/report_abandoned_cart_error.php";
			} 
			else if($mod=='reordercc') 
			{
				$include=$site_root_path. "views/cdyne/customer_cards.php";					
			} 
			else if($mod=='reordercm') 
			{
				$include=$site_root_path. "views/cdyne/customercm.php";					
			} 
			else if($mod=='tos') 
			{
				$include=$site_root_path. "views/main/tos.php";
			} 
			else if($mod=='privacypolicy') 
			{
				$include=$site_root_path. "views/main/privacypolicy.php";
			} 
			else if($mod=='refundpolicy') 
			{
				$include=$site_root_path. "views/main/refundpolicy.php";
		    }
                    else if($mod=='favindex') 
                    {
				$include=$site_root_path. "views/restaurant/ajax.php";
		    }
                    else if (($mod=='checkfbid') || ($mod=='delsavedtoken'))
                    {
				$include=$site_root_path. "views/customer/ajax.php";
		    }
                    else if($mod=='savedtokens') //For Main website, WordPress and Facebook - Gulfam //01 April 2014
                    {
				$include=$site_root_path. "views/customer/savedtokens.php";
		    }
                    else if($mod=='resetpassword') 
                    {
                        $include=$site_root_path. "views/customer/resetpassword.php";
		    }
                    else
                    {
                            $include=$site_root_path. "views/restaurant/menu.php";
                            $mod='resturants';
                    }
		}//ELSE REST
	}
?>