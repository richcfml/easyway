<style>
	.ui-combobox {
		position: relative;
		display: inline-block;
	}
	.ui-combobox-toggle {
		position: absolute;
		top: 0;
		bottom: 0;
		margin-left: -1px;
		padding: 0;
		/* adjust styles for IE 6/7 */
		*height: 1.7em;
		*top: 0.1em;
	}
	.ui-combobox-input {
		margin: 0;
		padding: 0.1em;
	}
 

	</style>
<?
$source=0;
$destination=0;
$errMessage='';
		if (! empty ( $_POST )) {
				 extract ( $_POST ) ;
				 
				if($source=="0") $errMessage="Please select source restaurant name";
				else if($destination=="0") $errMessage="Please select destination restaurant name";
				else if($source==$destination) $errMessage="Source and destination hotels are same, please select different hotels";
				else{
					     $mysqlqry=dbAbstract::Execute("select * from menus where rest_id=".$source ." Order by id",1);
		 
		 				 while($menu_rs = dbAbstract::returnObject($mysqlqry,1)){
							 $newMenuId = dbAbstract::Insert("insert into menus(rest_id,menu_name,menu_desc,menu_ordering,status) values (
							 '". $destination ."','". $menu_rs->menu_name ."','". $menu_rs->menu_desc ."','". $menu_rs->menu_ordering ."'
							 ,'". $menu_rs->status ."')", 1, 2);
							  
							   $mysqlcatqry=dbAbstract::Execute("select * from categories where menu_id =".$menu_rs->id ."  Order by  cat_id",1);
							   
							  		while($cat_rs = dbAbstract::returnObject($mysqlcatqry,1)){ 
									
									 $newCatId = dbAbstract::Insert("insert into categories(`parent_id`, `menu_id`, `cat_name`, `cat_des`, `cat_ordering`, `status`) values (
											 '". $destination ."','". $newMenuId ."','". $cat_rs->cat_name ."','". $cat_rs->cat_des ."','". $cat_rs->cat_ordering ."'
											 ,'". $cat_rs->status ."')", 1, 2);
											  	
												$mysql_cat_products=  dbAbstract::Execute("select * from product where sub_cat_id =".$cat_rs->cat_id ." Order by  prd_id",1);
													while($product_rs = dbAbstract::returnObject($mysql_cat_products,1)){ 
													
													$newProductID = dbAbstract::Insert("insert into product(
													`cat_id`, `sub_cat_id`, `item_num`, `item_title`, `item_code`, `item_des`, `retail_price`, `sale_price`, `item_image`, `feature_sub_cat`, `Alt_tag`, `Ptitle`, `Meta_des`, `Meta_tag`, `imagethumb`, `status` 
													
													) values ( '". $destination ."', '". $newCatId ."', '". $product_rs->item_num ."'
													, '". $product_rs->item_title ."'
													, '". $product_rs->item_code ."'
													, '". $product_rs->item_des ."'
													, '". $product_rs->retail_price ."'
													, '". $product_rs->sale_price ."'
													, '". $product_rs->item_image ."'
													, '". $product_rs->feature_sub_cat ."'
													, '". $product_rs->Alt_tag ."'
													, '". $product_rs->Ptitle ."'
													, '". $product_rs->Meta_des ."'
													, '". $product_rs->Meta_tag ."'
													, '". $product_rs->imagethumb ."'
													, '". $product_rs->status ."')", 1, 2);
	
													dbAbstract::Insert("insert into attribute(`ProductID`, `option_name`, `Title`, `Price`, `option_display_preference`, `apply_sub_cat`, `Type`, `Required`, `OderingNO`, `rest_price` )  select ". $newProductID .", `option_name`, `Title`, `Price`, `option_display_preference`, `apply_sub_cat`, `Type`, `Required`, `OderingNO`, `rest_price` from attribute where ProductID=". $product_rs->prd_id ."",1);
													}//PRODUCTS
									}//CATEGORTIES
							 
						 }//MENU
						 $errMessage="Resturant Copied successfully!!!";
				}///ELSE 
		}// POST



	if($_SESSION['admin_type'] == 'admin' || $_SESSION['admin_type'] == 'reseller') {
		 $qry = "select id,name from resturants ";
		 
	 if($_SESSION['admin_type'] == 'reseller') {
		$client_ids = resellers_client( $_SESSION['owner_id'] ) ;
		  $qry .= " where owner_id IN ( $client_ids ) ";
		
		}
		 
		  $qry .= " Order by name";
			 
		  $sourceoptions ='';
		  $destinationoptions='';
         $mysqlqry=dbAbstract::Execute($qry,1);
		 
		  while($resutarnt_rs = dbAbstract::returnObject($mysqlqry,1)){
			 if($source==$resutarnt_rs->id)
			  	$source_selected="Selected"; 
			 else
				$source_selected="";
				 
			  if($destination==$resutarnt_rs->id)
			  	$destination_selected="Selected"; 
			  else 
			  	$destination_selected="";
				
			  $sourceoptions .="   <option value='". $resutarnt_rs->id ."' ".   $source_selected ." >". $resutarnt_rs->name ."</option>";
			  $destinationoptions .="   <option value='". $resutarnt_rs->id ."'  ".   $destination_selected .">". $resutarnt_rs->name ."</option>";
		}
	}
						
 ?>
 
 <script>
	(function( $ ) {
		$.widget( "ui.combobox", {
			_create: function() {
				var input,
					self = this,
					select = this.element.hide(),
					selected = select.children( ":selected" ),
					value = selected.val() ? selected.text() : "",
					wrapper = this.wrapper = $( "<span>" )
						.addClass( "ui-combobox" )
						.insertAfter( select );

				input = $( "<input>" )
					.appendTo( wrapper )
					.val( value )
					.addClass( "ui-state-default ui-combobox-input" )
					.autocomplete({
						delay: 0,
						minLength: 0,
						source: function( request, response ) {
							var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
							response( select.children( "option" ).map(function() {
								var text = $( this ).text();
								if ( this.value && ( !request.term || matcher.test(text) ) )
									return {
										label: text.replace(
											new RegExp(
												"(?![^&;]+;)(?!<[^<>]*)(" +
												$.ui.autocomplete.escapeRegex(request.term) +
												")(?![^<>]*>)(?![^&;]+;)", "gi"
											), "<strong>$1</strong>" ),
										value: text,
										option: this
									};
							}) );
						},
						select: function( event, ui ) {
							ui.item.option.selected = true;
							self._trigger( "selected", event, {
								item: ui.item.option
							});
						},
						change: function( event, ui ) {
							if ( !ui.item ) {
								var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( $(this).val() ) + "$", "i" ),
									valid = false;
								select.children( "option" ).each(function() {
									if ( $( this ).text().match( matcher ) ) {
										this.selected = valid = true;
										return false;
									}
								});
								if ( !valid ) {
									// remove invalid value, as it didn't match anything
									$( this ).val( "" );
									select.val( "" );
									input.data( "autocomplete" ).term = "";
									return false;
								}
							}
						}
					})
					.addClass( "ui-widget ui-widget-content ui-corner-left" );

				input.data( "autocomplete" )._renderItem = function( ul, item ) {
					return $( "<li></li>" )
						.data( "item.autocomplete", item )
						.append( "<a>" + item.label + "</a>" )
						.appendTo( ul );
				};

				$( "<a>" )
					.attr( "tabIndex", -1 )
					.attr( "title", "Show All Items" )
					.appendTo( wrapper )
					.button({
						icons: {
							primary: "ui-icon-triangle-1-s"
						},
						text: false
					})
					.removeClass( "ui-corner-all" )
					.addClass( "ui-corner-right ui-combobox-toggle" )
					.click(function() {
						// close if already visible
						if ( input.autocomplete( "widget" ).is( ":visible" ) ) {
							input.autocomplete( "close" );
							return;
						}

						// work around a bug (likely same cause as #5265)
						$( this ).blur();

						// pass empty string as value to search for, displaying all results
						input.autocomplete( "search", "" );
						input.focus();
					});
			},

			destroy: function() {
				this.wrapper.remove();
				this.element.show();
				$.Widget.prototype.destroy.call( this );
			}
		});
	})( jQuery );

	$(function() {
		$( "#source" ).combobox();
		$( "#destination" ).combobox();
		 
	});
	</script>
    
<div id="main_heading">
<span>Copy Restaurant</span>
</div>
<? if ($errMessage != "" ) { ?><div class="msg_done"><?=$errMessage?></div>
<? }?>
<div class="form_outer"  >
<form action="" method="post" enctype="multipart/form-data" name="">
        <table width="575" border="0" cellpadding="10" cellspacing="0">
          <tr align="left" valign="top"> 
            <td width="254">Source Restaurant:</td>
            <td>   <select name="source" id="source" style="font-size:20px;" onchange="" >
			 	     <option value="0">===Source Restaurant===</option>
                     <?=$sourceoptions?>
			      </select> 
                  </td>
          </tr>
          <tr align="left" valign="top"> 
            <td width="254">Destination Restaurant:</td>
            <td>  <select name="destination" id="destination" style="font-size:20px;" onchange="" >
			 	     <option value="0">===Destination Restaurant===</option>
                      <?=$destinationoptions?>
			      </select>   </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><input type="submit" name="submit" value="Copy Restaurant Data" onclick="return confirm('Are you sure you want to copy resturant menu ?')"></td>
          </tr>
        </table>

</form>
</div>
 
