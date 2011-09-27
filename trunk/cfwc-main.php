<?php
/*
Plugin Name: Contact Form With Captcha (CFWC)
Plugin URI: http://www.teknocrat.com/
Description: Creates a contact form with captcha (CFWC)
Version: 1.00
Date: 01 Sep 2011
Author: Tecknocrat
Author URI: http://www.teknocrat.com/

    Copyright 2011  Teknocrat  (email : teknocrat.com@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

// add the admin options page

add_action('admin_menu', 'plugin_admin_add_page');

function plugin_admin_add_page() {

	add_options_page('Contact Form With Captcha Plugin Page', 'Contact Form With Captcha', 'manage_options', 'contact-form-with-captcha', 'plugin_options_page');

}

// display the admin options page
function plugin_options_page() {
?>

<div>
	<h2>Contact Form With Captcha Plugin Menu</h2>
	Options relating to the Contact Form With Captcha Plugin Plugin.
	<form action="options.php" method="post">
		<?php settings_fields('key_value'); ?>
            <?php settings_fields('public_key_value'); ?>
            <?php settings_fields('cfwc_to_value'); ?>

		<?php do_settings_sections('contact-form-with-captcha'); ?>
	<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
	</form>
</div>

<?php 
}

// add the admin settings and such

add_action('admin_init', 'plugin_admin_init');

function plugin_admin_init(){

	register_setting( 'key_value', 'key_value', 'plugin_options_validate' );
	register_setting( 'public_key_value', 'public_key_value', 'plugin_options_validate' );
	register_setting( 'cfwc_to_value', 'cfwc_to_value', 'plugin_options_validate' );

	add_settings_section('plugin_main', 'Main Settings', 'plugin_section_text', 'contact-form-with-captcha');

	add_settings_field('private_key_field_id', 'Specify your private key',  'private_key_field_callback', 'contact-form-with-captcha', 'plugin_main');
	add_settings_field('public_key_field_id',  'Specify your public key',   'public_key_field_callback',  'contact-form-with-captcha', 'plugin_main');
	add_settings_field('cfwc_to_field_id',     'Specify your email address','cfwc_to_field_callback',     'contact-form-with-captcha', 'plugin_main');



}

function plugin_section_text() {

	echo '<p>Specify your captcha key (Get a key from https://www.google.com/recaptcha/admin/create)</p>';

} 

function cfwc_to_field_callback() {

	$options = get_option('cfwc_to_value');
	echo "<input id='cfwc_to_field_id' name='cfwc_to_value[text_string]' size='40' type='text' value='{$options['text_string']}' />";

}

function private_key_field_callback() {

	$options = get_option('key_value');
	echo "<input id='private_key_field_id' name='key_value[text_string]' size='40' type='text' value='{$options['text_string']}' />";

}

function public_key_field_callback()  {

      $options = get_option('public_key_value');
      echo "<input id='public_key_field_id' name='public_key_value[text_string]' size='40' type='text' value='{$options['text_string']}' />";
}

// validate our options
function plugin_options_validate($input) {

	$newinput['text_string'] = trim($input['text_string']);
//	if(!preg_match('/^[a-z0-9]{32}$/i', $newinput['text_string'])) {
//		$newinput['text_string'] = '';
//	}
	return $newinput;
}

// [cfcw publickey="abc" privatekey="def"]

add_shortcode( 'cfwc', 'cfwc_func' );
function cfwc_func( $atts ) {
	extract( shortcode_atts( array(
		'publickey' => 'something',
		'privatekey' => 'something else',
	), $atts ) );
      
	$privatekey = get_option('key_value');
      $publickey  = get_option('public_key_value');
      $cfwc_to    = get_option('cfwc_to_value');
      $privatekey = $privatekey['text_string'] ;
      $publickey  = $publickey['text_string'] ;
      $cfwc_to    = $cfwc_to['text_string'];

      include(WP_PLUGIN_DIR . '/contact-form-with-captcha/cfwc-form.php');
}
?>
