<?php
/*
Plugin Name: Confirm Data
Description: Keep your user data up to date by prompting the user to confirm their email address or enter a new email address every X amount of logins or X amount of days.
Version: 1.0.7
Author: Unihost
Author URI: http://www.bootmin.com/
Stable tag: 1.0.7
*/
#include('modules/functions.php');
#include('modules/shortcodes.php');
include('modules/settings.php');
#include('modules/meta_box.php');
#include('modules/widgets.php');
include('modules/hooks.php');
#include('modules/cpt.php');
include('modules/scripts.php');
include('modules/ajax.php');

register_activation_hook( __FILE__, 'cdp_activate' );
function cdp_activate() {
	$config = get_option('wec_options'); 
	update_option('wec_time', time());
	$config['css'] = '
		.email_container{
			position:fixed;
			top:0px;
			left:0px;
			width:100%;
			background:#feffdb;
			z-index:100000;
			text-align: center;
			padding: 10px;
			color:#333333
		}
		.control_block{
			display:inline;
			margin-left:20px;
		}
		.email_block{
	    display: inline-block;
		}
		.ajax_loader, .email_bl_cont{
			display:none;
		}

		#new_email{
			margin: 0px;
 		}

		@media screen and (max-width: 420px){
			.control_block {
				display: block;
				margin-left: 0px;
			}
			.email_block {
				display: inline;
			}
		}
	';
	update_option('wec_options', $config );
}

?>