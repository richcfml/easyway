<?php











function ssb_ajax_form()
	{	
		$ssb_site = $_POST['ssb_site'];
		$post_id = $_POST['post_id'];
		
		$ssb_post_sites = get_post_meta( $post_id, 'ssb_post_sites', true );

	
		$ssb_post_sites['fb'] = $ssb_post_sites['fb'];
		$ssb_post_sites['gplus'] = $ssb_post_sites['gplus'];
		$ssb_post_sites['twitter'] = $ssb_post_sites['twitter'];
		$ssb_post_sites['linkedin'] = $ssb_post_sites['linkedin'];
		$ssb_post_sites['pinterest'] = $ssb_post_sites['pinterest'];
		$ssb_post_sites['reddit'] = $ssb_post_sites['reddit'];


		if($ssb_site=="fb")
			{
			$ssb_post_sites['fb'] = $ssb_post_sites['fb']+1;
			}
		elseif($ssb_site=="gplus")
			{
			$ssb_post_sites['gplus'] = $ssb_post_sites['gplus']+1;
			}
		elseif($ssb_site=="twitter")
			{
			$ssb_post_sites['twitter'] = $ssb_post_sites['twitter']+1;
			}
		elseif($ssb_site=="linkedin")
			{
			$ssb_post_sites['linkedin'] = $ssb_post_sites['linkedin']+1;
			}
		elseif($ssb_site=="pinterest")
			{
			$ssb_post_sites['pinterest'] = $ssb_post_sites['pinterest']+1;
			}
		elseif($ssb_site=="reddit")
			{
			$ssb_post_sites['reddit'] = $ssb_post_sites['reddit']+1;
			}

		// trace stats
		update_post_meta( $post_id, 'ssb_post_sites', $ssb_post_sites );
		die();
	}



add_action('wp_ajax_ssb_ajax_form', 'ssb_ajax_form');
add_action('wp_ajax_nopriv_ssb_ajax_form', 'ssb_ajax_form');




function ssb_display($content)
	{
		$ssb_share_content_position = get_option( 'ssb_share_content_position' );
		$ssb_share_content_display = get_option( 'ssb_share_content_display' );
		$ssb_share_filter_posttype = get_option( 'ssb_share_filter_posttype' );
		
		
		
		

		if($ssb_share_filter_posttype==NULL)
			{
				$type ="none";
			}
		else
			{
				$type = "";
			foreach ( $ssb_share_filter_posttype as  $post_type => $post_type_value )
				{
			
				$type .= $post_type.",";
				}
			}
		
		
		if(is_singular(explode(',',$type)))
			{
				
				$content_new = "";
				if($ssb_share_content_position=='top')
					{
						$content_new.=ssb_share_icons();
						$content_new .=$content;
					}
				elseif($ssb_share_content_position=='bottom')
					{	
						$content_new .=$content;
						$content_new.=ssb_share_icons();
						
					}
			
				if($ssb_share_content_display=='yes')
					{
						return $content_new;
					}
				else
					{
						return $content;
					}
			
			}	
		else
			{
				return $content;
			}
	
	
	}

add_filter('the_content', 'ssb_display');



function ssb_share_icons()
	{	
		$ssb_share_content_themes = get_option( 'ssb_share_content_themes' );
		$ssb_share_content_icon_margin = get_option( 'ssb_share_content_icon_margin' );		

		$ssb_share_icons = "";
		$ssb_share_icons.="<div class='ssb-share ".$ssb_share_content_themes."' post_id='".get_the_ID()."' >";
		$ssb_share_icons.= ssb_share_body();
		$ssb_share_icons.="</div>";	
		

		return $ssb_share_icons;
	}



function ssb_share_get_title()
	{
		global $post;
		$title = get_the_title( $post->ID );
		
		return $title;	
	}


function ssb_share_get_url()
	{
		global $post;
		$permalink = get_permalink( $post->ID );
		
		return $permalink;	
	}



function ssb_share_get_image()
	{	global $post;
		if ( has_post_thumbnail())
			{
				$post_thumbnail_id = wp_get_attachment_image_src(get_post_thumbnail_id( $post->ID ));
				$post_thumbnail_id = $post_thumbnail_id[0];
		 	}
		else
			{
				$post_thumbnail_id ="";
			}
		 
	return $post_thumbnail_id;	
	}




























function ssb_dark_color($input_color)
	{
		if(empty($input_color))
			{
				return "";
			}
		else
			{
				$input = $input_color;
			  
				$col = Array(
					hexdec(substr($input,1,2)),
					hexdec(substr($input,3,2)),
					hexdec(substr($input,5,2))
				);
				$darker = Array(
					$col[0]/2,
					$col[1]/2,
					$col[2]/2
				);
		
				return "#".sprintf("%02X%02X%02X", $darker[0], $darker[1], $darker[2]);
			}

		
		
	}
	
	
	

	