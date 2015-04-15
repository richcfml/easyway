<?php	
/**
 * Social Share Button main setting page.
 *
 * @author 		ParaTheme
 * @version     1.0
 */
	if ( ! defined('ABSPATH')) exit;

	if(empty($_POST['ssb_hidden']))
		{
			$ssb_share_content_display = get_option( 'ssb_share_content_display' );	
			$ssb_share_content_themes = get_option( 'ssb_share_content_themes' );
			$ssb_share_content_position = get_option( 'ssb_share_content_position' );
			$ssb_share_content_icon_margin = get_option( 'ssb_share_content_icon_margin' );
			$ssb_share_filter_posttype = get_option( 'ssb_share_filter_posttype' );			
			$ssb_share_target_tab = get_option( 'ssb_share_target_tab' );
			$ssb_social_sites = get_option( 'ssb_social_sites' );			
			
			
		}
	else
		{
	
		if($_POST['ssb_hidden'] == 'Y') {
			//Form data sent

			
			$ssb_share_content_display = $_POST['ssb_share_content_display'];
			update_option('ssb_share_content_display', $ssb_share_content_display);			

			$ssb_share_content_themes = $_POST['ssb_share_content_themes'];
			update_option('ssb_share_content_themes', $ssb_share_content_themes);
			
			$ssb_share_content_position = $_POST['ssb_share_content_position'];
			update_option('ssb_share_content_position', $ssb_share_content_position);
			
			$ssb_share_content_icon_margin = $_POST['ssb_share_content_icon_margin'];
			update_option('ssb_share_content_icon_margin', $ssb_share_content_icon_margin);
			

			if(!empty($_POST['ssb_share_filter_posttype']))
				{
					$ssb_share_filter_posttype = $_POST['ssb_share_filter_posttype'];
				}
			else
				{
					$ssb_share_filter_posttype = "";
				}
				
			update_option('ssb_share_filter_posttype', $ssb_share_filter_posttype);			
			
			$ssb_share_target_tab = $_POST['ssb_share_target_tab'];
			update_option('ssb_share_target_tab', $ssb_share_target_tab);	
			
			$ssb_social_sites = stripslashes_deep($_POST['ssb_social_sites']);
			update_option('ssb_social_sites', $ssb_social_sites);			
			
					

			?>
			<div class="updated"><p><strong><?php _e('Changes Saved.' ); ?></strong></p></div>

			<?php
		} 
	}
	
	
	


	
	
	
	
?>





<div class="wrap">

	<div id="icon-tools" class="icon32"><br></div><?php echo "<h2>".ssb_plugin_name." Settings</h2>";?>
		<form  method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<input type="hidden" name="ssb_hidden" value="Y">
        <?php settings_fields( 'ssb_plugin_options' );
				do_settings_sections( 'ssb_plugin_options' );
			
		?>
       
	<br />
	<div class="ssb-settings">
        <ul class="tab-nav">
            <li nav="1" class="nav1 active">SSB Options</li>
            <li nav="2" class="nav2">SSB Style</li>
            <li nav="3" class="nav3">SSB Content</li>
        
        </ul>

        <ul class="box">
            <li style="display: block;" class="box1 tab-box active">
                <div class="option-box">
                    <p class="option-title">Display On Content.</p>
                    <p class="option-info">Choose 'yes' to display share buttons on content and choose 'No' to hide share buttons on post.</p>
                    
                    <select name="ssb_share_content_display">
                        <option value="yes" <?php  if($ssb_share_content_display=='yes') echo "selected"; ?>>Yes</option>
                        <option value="no" <?php  if($ssb_share_content_display=='no') echo "selected"; ?>>No</option>
                       
                    </select>
                </div>
				
				<div class="option-box">
                    <p class="option-title">Open new tab when click share buttons.</p>
                    <p class="option-info">When someone try to click share buttons it will open new tab to share link on social media sites.</p>
					<select name="ssb_share_target_tab">
                    	<option value="new" <?php  if($ssb_share_target_tab=='new') echo "selected"; ?>>New</option>
                        <option value="same" <?php  if($ssb_share_target_tab=='same') echo "selected"; ?>>Same</option>
					</select>
                </div>          
				  





				


				<div class="option-box">
                    <p class="option-title">Need Help ?</p>
                    <p class="option-info">Please report any issue via support forum <a href="http://wordpress.org/support/plugin/social-share-button">wordpress.org &raquo; support</a>.</p>
                </div>



				<div class="option-box">
                    <p class="option-title">Display only these buttons.</p>
                    <p class="option-info"></p>
                    
                    <label><input type="checkbox" name="ssb_social_sites[fb]" value="fb" <?php if($ssb_social_sites['fb']=="fb") echo "checked";?> />Facebook</label><br />
                    <label><input type="checkbox" name="ssb_social_sites[twitter]" value="twitter" <?php if($ssb_social_sites['twitter']=="twitter") echo "checked";?> />Twitter </label><br />
                    <label><input type="checkbox" name="ssb_social_sites[gplus]" value="gplus" <?php if($ssb_social_sites['gplus']=="gplus") echo "checked";?> />Google+ </label><br />
                    <label><input type="checkbox" name="ssb_social_sites[linkedin]" value="linkedin" <?php if($ssb_social_sites['linkedin']=="linkedin") echo "checked";?> />Linkedin</label><br />
                    <label><input type="checkbox" name="ssb_social_sites[pinterest]" value="pinterest" <?php if($ssb_social_sites['pinterest']=="pinterest") echo "checked";?> />Pinterest</label><br />
                    <label><input type="checkbox" name="ssb_social_sites[reddit]" value="reddit" <?php if($ssb_social_sites['reddit']=="reddit") echo "checked";?> />Reddit</label><br />
                    
                    
                    
                    
                    
                    
                    
                    
                </div>





            </li>
            <li class="box2 tab-box">
				<div class="option-box">
                    <p class="option-title">Select Buttons Themes.</p>
                    <p class="option-info">Select different style themes to display different style buttons on post.</p>
<style type="text/css">

.ssb_share_content_themes input[type="radio"] {
  display: none;
}

.ssb_share_content_themes label {
  cursor: pointer;
  display: inline-block;
  margin: 10px;
  
}

.ssb_share_content_themes span {
  border: 3px solid rgb(199, 199, 199);
}

.ssb_share_content_themes span.selected {
  border: 3px solid #FF511C;
}



span.ssb_share_content_themes_defualt 
	{
  background: url("<?php echo ssb_plugin_url; ?>/css/admin-defualt.png") repeat scroll 0 0 rgba(0, 0, 0, 0);
  display: block;
  height: 100px;
  width: 100px;
	
	}
span.ssb_share_content_themes_flat {
  background: url("<?php echo ssb_plugin_url; ?>/css/admin-flat.png") repeat scroll 0 0 rgba(0, 0, 0, 0);
  display: block;
  height: 100px;
  width: 100px;
  
}
	
span.ssb_share_content_themes_round 
	{
  background: url("<?php echo ssb_plugin_url; ?>css/admin-round.png") repeat scroll 0 0 rgba(0, 0, 0, 0);
  display: block;
  height: 100px;
  width: 100px;
	}
	
	
span.ssb_share_content_themes_wide 
	{
  background: url("<?php echo ssb_plugin_url; ?>css/admin-wide.png") repeat scroll 0 0 rgba(0, 0, 0, 0);
  display: block;
  height: 100px;
  width: 100px;
	}	
	
	
span.ssb_share_content_themes_bodyname 
	{
  background: url("<?php echo ssb_plugin_url; ?>css/admin-bodyname.png") repeat scroll 0 0 rgba(0, 0, 0, 0);
  display: block;
  height: 100px;
  width: 100px;
	}
span.ssb_share_content_themes_packslide 
	{
  background: url("<?php echo ssb_plugin_url; ?>css/admin-packslide.png") repeat scroll 0 0 rgba(0, 0, 0, 0);
  display: block;
  height: 100px;
  width: 100px;
	}	
	
span.ssb_share_content_themes_hexa
	{
  background: url("<?php echo ssb_plugin_url; ?>css/admin-hexa.png") repeat scroll 0 0 rgba(0, 0, 0, 0);
  display: block;
  height: 100px;
  width: 100px;
	}	
	
span.ssb_share_content_themes_hover_left
	{
  background: url("<?php echo ssb_plugin_url; ?>css/admin-hover-left.png") repeat scroll 0 0 rgba(0, 0, 0, 0);
  display: block;
  height: 100px;
  width: 100px;
	}	
	
	
span.ssb_share_content_themes_hover_right 
	{
  background: url("<?php echo ssb_plugin_url; ?>css/admin-hover-right.png") repeat scroll 0 0 rgba(0, 0, 0, 0);
  display: block;
  height: 100px;
  width: 100px;
	}	
	
</style>


<script>
jQuery(document).ready(function(jQuery)
	{
					jQuery(".ssb_share_content_themes span").click(function(){
						jQuery('.selected').removeClass('selected');
						jQuery(this).addClass('selected');
						
						})

					
	})

</script>







        <div class="ssb_share_content_themes">
        	<label ><input type="radio" name="ssb_share_content_themes"  value="defualt" <?php  if($ssb_share_content_themes=='defualt') echo "checked"; ?>/><span title="Defualt" class="ssb_share_content_themes_defualt <?php  if($ssb_share_content_themes=='defualt') echo "selected"; ?>"></span></label>
            
        	<label ><input type="radio" name="ssb_share_content_themes"  value="flat" <?php  if($ssb_share_content_themes=='flat') echo "checked"; ?>/><span title="Flat" class="ssb_share_content_themes_flat <?php  if($ssb_share_content_themes=='flat') echo "selected"; ?>"></span></label>
            
           <label ><input type="radio" name="ssb_share_content_themes"  value="round" <?php  if($ssb_share_content_themes=='round') echo "checked"; ?>/><span title="Round" class="ssb_share_content_themes_round <?php  if($ssb_share_content_themes=='round') echo "selected"; ?>" ></span></label>
           
           <label ><input type="radio" name="ssb_share_content_themes"  value="wide" <?php  if($ssb_share_content_themes=='wide') echo "checked"; ?>/><span title="Wide" class="ssb_share_content_themes_wide <?php  if($ssb_share_content_themes=='wide') echo "selected"; ?>"></span></label>
            
           <label > <input type="radio" name="ssb_share_content_themes"  value="bodyname" <?php  if($ssb_share_content_themes=='bodyname') echo "checked"; ?>/><span title="Body Name" class="ssb_share_content_themes_bodyname <?php  if($ssb_share_content_themes=='bodyname') echo "selected"; ?>"></span></label>
            
             <label ><input type="radio" name="ssb_share_content_themes"  value="packslide" <?php  if($ssb_share_content_themes=='packslide') echo "checked"; ?>><span title="Pack Slide"  class="ssb_share_content_themes_packslide <?php  if($ssb_share_content_themes=='packslide') echo "selected"; ?>"></span></label>
             
            <label ><input type="radio" name="ssb_share_content_themes"  value="hexa" <?php  if($ssb_share_content_themes=='hexa') echo "checked"; ?>/><span title="Hexa" class="ssb_share_content_themes_hexa <?php  if($ssb_share_content_themes=='hexa') echo "selected"; ?>"></span></label>
            
            <label > <input type="radio" name="ssb_share_content_themes"  value="hover-left" <?php  if($ssb_share_content_themes=='hover-left') echo "checked"; ?>><span title="Hover Left" class="ssb_share_content_themes_hover_left <?php  if($ssb_share_content_themes=='hover-left') echo "selected"; ?>"></span></label>
                 
             <label ><input type="radio" name="ssb_share_content_themes"   value="hover-right" <?php  if($ssb_share_content_themes=='hover-right') echo "checked"; ?>/><span title="Hover Right" class="ssb_share_content_themes_hover_right <?php  if($ssb_share_content_themes=='hover-right') echo "selected"; ?>"></span></label>                   
           			</div>
                </div>
                
                
				<div class="option-box">
                    <p class="option-title">Buttons Margin.</p>
                    <p class="option-info">Margin for buttons.</p>
					<input name="ssb_share_content_icon_margin" size="5" type="text" value="<?php  if(!empty($ssb_share_content_icon_margin)) echo $ssb_share_content_icon_margin; else echo "0"; ?>" />px 
                </div>
                
                
				<div class="option-box">
                    <p class="option-title">Social Share Position.</p>
                    <p class="option-info">Display share button top or bottom of content.</p>
                    <select name="ssb_share_content_position">
                        <option value="top" <?php  if($ssb_share_content_position=='top') echo "selected"; ?>>Top</option>
                        <option value="bottom" <?php  if($ssb_share_content_position=='bottom') echo "selected"; ?>>Bottom</option>
                    </select>
                </div> 
                
                
                
            </li>

            <li class="box3 tab-box">
				<div class="option-box">
                    <p class="option-title">Display share button On These Post Type Only.</p>
                    <p class="option-info">By choosing post type you can filter display share button only these post type.</p>
                    
                    <?php

					$post_types = get_post_types( '', 'names' ); 
					
					foreach ( $post_types as $post_type ) {
					
					   echo '<label for="ssb_share_filter_posttype['.$post_type.']"><input type="checkbox" name="ssb_share_filter_posttype['.$post_type.']" id="ssb_share_filter_posttype['.$post_type.']"  value ="1" '; 
					   if ( isset( $ssb_share_filter_posttype[$post_type] ) ) echo "checked"; 
					   echo' >'. $post_type.'</label><br />' ;
					}
					
					?>
                </div>
            </li>

</ul>







<p class="submit">
                    <input class="button button-primary" type="submit" name="Submit" value="<?php _e('Save Changes' ) ?>" />
                </p>
		</form>
	</div>

</div>
