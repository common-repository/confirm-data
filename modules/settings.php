<?php 

add_action('admin_menu', 'cdp_item_menu');

function cdp_item_menu() {


add_options_page(  __('Confirm Data Plugin', 'wwp'), __('Confirm Data Plugin', 'wwp'), 'edit_published_posts', 'cdp_config', 'cdp_config');
}

function cdp_config(){

?>
<div class="wrap tw-bs">
<h2><?php _e('Confirm Data Plugin Settings', 'wcc'); ?></h2>
<?php if(  wp_verify_nonce($_POST['_wpnonce']) ): ?>
<div id="message" class="updated" ><p><?php _e('Settings saved successfully', 'wcc'); ?></p></div>  
<?php 
$config = get_option('wec_options'); 

foreach( $_POST as $key=>$value ){
	if( $key == 'css' ){
		include_once(dirname(__FILE__).'/inc/csstidy/class.csstidy.php');
			$css_code = $value;
			$css = new csstidy();
			$css->set_cfg('remove_last_;',TRUE);
			$css->parse($css_code);
			$options[$key] = $css->print->plain();

		//$options[$key] = sanitize_text_field( $value );
	}else{
		$options[$key] = sanitize_text_field( $value );
	}
	
	
}
update_option('wec_options', $options );


else:  ?>

<?php //exit; ?>

<?php endif; ?> 
<form class="form-horizontal" method="post" action="" enctype="multipart/form-data" >
<?php wp_nonce_field();  
$config = get_option('wec_options'); 

//var_dump( $config );

?>  
<fieldset>
	<div id="backup" class="postbox">
	 	<p class="headerhndle" id="about-sidebar"><?php _e( 'Settings' ); ?></p>
		<div class="inside">
		<div class="control-group">  
		    <label class="control-label" for="multiSelect"><?php _e( 'Is enabled' ); ?></label>  
			    <div class="controls">  
					<select name="is_active">  
						<option value="yes" <?php if( $config['is_active'] == 'yes' ) echo ' selected '; ?> ><?php _e( 'Yes' ); ?></option>  
						<option value="no" <?php if( $config['is_active'] == 'no' ) echo ' selected '; ?> ><?php _e( 'No' ); ?></option>    
					</select>	
			    </div>  
		    </div>

		<div class="control-group">  
		 	<label class="control-label" for="input01"><?php _e( 'Check email address every X days' ); ?></label>  
		    <div class="controls">  
		    	<input type="text" class="input-mini" name="each_x_days" value="<?php if( $config['each_x_days'] != '' ) echo  esc_html( $config['each_x_days'] ); else echo  '30'; ?>">  
		    </div>             
		</div> 

		<div class="control-group">  
		    <label class="control-label" for="input01"><?php _e( 'Check email address every X logins' ); ?></label>  
		    <div class="controls">  
		    	<input type="text" class="input-mini" name="each_x_logins" value="<?php if( $config['each_x_logins'] != '' ) echo  esc_html( $config['each_x_logins'] ); else echo  '10'; ?>" >  
		    </div>  
	    </div>  

	<!-- Custom CSS -->
	  <div class="control-group">  
        <label class="control-label" for="textarea"><?php _e( 'Custom CSS' ); ?></label>  
        <div class="controls">  
          <textarea class="input-xlarge w_100" name="css" rows="10"><?php echo  esc_html( stripslashes( $config['css'] ) ); ?></textarea>  
        </div>  
      </div> 
	<!-- End Custom CSS -->

	<!-- Save Button -->
      <div>  
        <button type="submit" class="button-primary"><?php _e( 'Save Settings' ); ?></button>  
      </div>
    <!-- End Save Button -->  

		</div>
	</div>

    </fieldset>  
</form>
</div>

<?php 
}
?>