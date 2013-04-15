<?php
/*
Plugin Name: Contact Form With Captcha
Plugin URI: http://www.teqlog.com/
Description: Creates a contact form with captcha. For more details you can visit plugin page <a href="http://www.teqlog.com/wordpress-contact-form-with-captcha-plugin.html"><strong>CFWC Plugin home page</strong></a>.
Version: 1.5.9
Date: 26 Feb 2012
Author: Teqlog
Author URI: http://www.teqlog.com/

    Copyright 2011  Teqlog  (email : teknocrat.com@gmail.com)

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

	add_options_page(
                       'Contact Form With Captcha Plugin Page', 
                       'Contact Form With Captcha', 
                       'manage_options', 
                       'contact-form-with-captcha', 
                       'plugin_options_page'
                      );

}

// display the admin options page
function plugin_options_page() {
?>

<div>
	<h2>Contact Form With Captcha Plugin Menu</h2>
	Options relating to the Contact Form With Captcha Plugin Plugin.
	<form action="options.php" method="post">
            <?php settings_fields('cfwc_options_group'); ?>
            <?php do_settings_sections('contact-form-with-captcha'); ?>
	<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
	</form>
</div>

<?php 
}

// add the admin settings and such

add_action('admin_init', 'plugin_admin_init');

function plugin_admin_init(){

	register_setting( 'cfwc_options_group', 'cfwc_private_key_value', 'plugin_options_validate' );
	register_setting( 'cfwc_options_group', 'cfwc_public_key_value',  'plugin_options_validate' );
	register_setting( 'cfwc_options_group', 'cfwc_to_value',          'plugin_options_validate' );
	register_setting( 'cfwc_options_group', 'cfwc_full_name_value',   'plugin_options_validate' );
	register_setting( 'cfwc_options_group', 'cfwc_e_mail_value',      'plugin_options_validate' );
	register_setting( 'cfwc_options_group', 'cfwc_subj_value',        'plugin_options_validate' );
	register_setting( 'cfwc_options_group', 'cfwc_message_value',     'plugin_options_validate' );
	register_setting( 'cfwc_options_group', 'cfwc_button_value',      'plugin_options_validate' );
	register_setting( 'cfwc_options_group', 'cfwc_subject_value',     'plugin_options_validate' );
	register_setting( 'cfwc_options_group', 'cfwc_subject_prefix_value',     'plugin_options_validate' );
	register_setting( 'cfwc_options_group', 'cfwc_subject_suffix_value',     'plugin_options_validate' );
      register_setting( 'cfwc_options_group', 'cfwc_credit_value' );
      register_setting( 'cfwc_options_group', 'cfwc_captcha_theme_value' );
      register_setting( 'cfwc_options_group', 'cfwc_form_theme_value' );



	add_settings_section('plugin_main', 'Main Settings', 'plugin_section_text', 'contact-form-with-captcha');

	add_settings_field('cfwc_private_key_field_id', 'Specify your private key',  'cfwc_private_key_field_callback', 'contact-form-with-captcha', 'plugin_main');
	add_settings_field('cfwc_public_key_field_id',  'Specify your public key',   'cfwc_public_key_field_callback',  'contact-form-with-captcha', 'plugin_main');
	add_settings_field('cfwc_to_field_id',          'Specify your email address','cfwc_to_field_callback',          'contact-form-with-captcha', 'plugin_main');
      add_settings_field('cfwc_full_name_field_id',   'Specify Full Name Label (Optional)'   ,'cfwc_full_name_field_callback',   'contact-form-with-captcha', 'plugin_main');
      add_settings_field('cfwc_e_mail_field_id',      'Specify E-Mail Label (Optional)'      ,'cfwc_e_mail_field_callback',      'contact-form-with-captcha', 'plugin_main');
      add_settings_field('cfwc_subj_field_id',        'Specify Subject Label (Optional)'     ,'cfwc_subj_field_callback',        'contact-form-with-captcha', 'plugin_main');
      add_settings_field('cfwc_message_field_id',     'Specify Message Label (Optional)'     ,'cfwc_message_field_callback',     'contact-form-with-captcha', 'plugin_main');
      add_settings_field('cfwc_button_field_id',      'Specify Button Label (Optional)'      ,'cfwc_button_field_callback',      'contact-form-with-captcha', 'plugin_main');      
      add_settings_field('cfwc_subject_field_id',     'Specify predefined subject for drop down menu (Use : between different options)','cfwc_subject_field_callback', 'contact-form-with-captcha', 'plugin_main');
      add_settings_field('cfwc_subject_prefix_field_id','Specify a subject prefix (Optional)','cfwc_subject_prefix_field_callback', 'contact-form-with-captcha', 'plugin_main');
      add_settings_field('cfwc_subject_suffix_field_id','Specify a subject suffix (Optional)','cfwc_subject_suffix_field_callback', 'contact-form-with-captcha', 'plugin_main');      
      add_settings_field('cfwc_credit_field_id',      'Do not give credit to developer (Please consider <b>NOT</b> checking this box)' ,'cfwc_credit_field_callback', 'contact-form-with-captcha', 'plugin_main');
      add_settings_field('cfwc_captcha_theme_field_id','Pick a reCaptcha theme' ,'cfwc_captcha_theme_field_callback', 'contact-form-with-captcha', 'plugin_main');
      add_settings_field('cfwc_form_theme_field_id',  'Pick a form theme'       ,'cfwc_form_theme_field_callback',    'contact-form-with-captcha', 'plugin_main');


}

function plugin_section_text() {

	echo '<p>Specify your captcha key (Get a key from https://www.google.com/recaptcha/admin/create)</p>';

} 

function cfwc_to_field_callback() {

	$options = get_option('cfwc_to_value');
	echo "<input id='cfwc_to_field_id' name='cfwc_to_value[text_string]' size='50' type='text' value='{$options['text_string']}' />";

}

function cfwc_private_key_field_callback() {

	$options = get_option('cfwc_private_key_value');
	echo "<input id='cfwc_private_key_field_id' name='cfwc_private_key_value[text_string]' size='50' type='text' value='{$options['text_string']}' />";

}

function cfwc_public_key_field_callback()  {

      $options = get_option('cfwc_public_key_value');
      echo "<input id='cfwc_public_key_field_id' name='cfwc_public_key_value[text_string]' size='50' type='text' value='{$options['text_string']}' />";
}

function cfwc_subject_field_callback()  {

      $options = get_option('cfwc_subject_value');
      echo "<input id='cfwc_subject_field_id' name='cfwc_subject_value[text_string]' size='100' type='text' value='{$options['text_string']}' />";
}

function cfwc_subject_prefix_field_callback()  {

      $options = get_option('cfwc_subject_prefix_value');
      echo "<input id='cfwc_subject_prefix_field_id' name='cfwc_subject_prefix_value[text_string]' size='50' type='text' value='{$options['text_string']}' />";
}

function cfwc_subject_suffix_field_callback()  {

      $options = get_option('cfwc_subject_suffix_value');
      echo "<input id='cfwc_subject_suffix_field_id' name='cfwc_subject_suffix_value[text_string]' size='50' type='text' value='{$options['text_string']}' />";
}


function cfwc_full_name_field_callback()  {

      $options = get_option('cfwc_full_name_value');
      echo "<input id='cfwc_full_name_field_id' name='cfwc_full_name_value[text_string]' size='50' type='text' value='{$options['text_string']}' />";
}

function cfwc_e_mail_field_callback()  {

      $options = get_option('cfwc_e_mail_value');
      echo "<input id='cfwc_e_mail_field_id' name='cfwc_e_mail_value[text_string]' size='50' type='text' value='{$options['text_string']}' />";
}

function cfwc_subj_field_callback()  {

      $options = get_option('cfwc_subj_value');
      echo "<input id='cfwc_subj_field_id' name='cfwc_subj_value[text_string]' size='50' type='text' value='{$options['text_string']}' />";
}

function cfwc_message_field_callback()  {

      $options = get_option('cfwc_message_value');
      echo "<input id='cfwc_message_field_id' name='cfwc_message_value[text_string]' size='50' type='text' value='{$options['text_string']}' />";
}
function cfwc_button_field_callback()  {

      $options = get_option('cfwc_button_value');
      echo "<input id='cfwc_button_field_id' name='cfwc_button_value[text_string]' size='50' type='text' value='{$options['text_string']}' />";
}

function cfwc_credit_field_callback()  {

      $options = get_option('cfwc_credit_value');
      echo "<input id='cfwc_credit_field_id' name='cfwc_credit_value[boolean]' type='checkbox' value='true' " ; if ("true" == $options['boolean']) {echo "checked='checked'"; } echo "/>";
}
function cfwc_captcha_theme_field_callback()  {

      $options = get_option('cfwc_captcha_theme_value');
      echo "<input id='cfwc_captcha_theme_field_id' name='cfwc_captcha_theme_value[text_string]' type='radio' value='red' " ; if ("red" == $options['text_string']) {echo "checked='checked'"; } echo "/>Red<br>";
      echo "<input id='cfwc_captcha_theme_field_id' name='cfwc_captcha_theme_value[text_string]' type='radio' value='white' " ; if ("white" == $options['text_string']) {echo "checked='checked'"; } echo "/>white<br>";
      echo "<input id='cfwc_captcha_theme_field_id' name='cfwc_captcha_theme_value[text_string]' type='radio' value='blackglass' " ; if ("blackglass" == $options['text_string']) {echo "checked='checked'"; } echo "/>Blackglass<br>";
      echo "<input id='cfwc_captcha_theme_field_id' name='cfwc_captcha_theme_value[text_string]' type='radio' value='clean' " ; if ("clean" == $options['text_string']) {echo "checked='checked'"; } echo "/>Clean<br>";

}
function cfwc_form_theme_field_callback()  {

      $options = get_option('cfwc_form_theme_value');
      echo "<input id='cfwc_form_theme_field_id' name='cfwc_form_theme_value[text_string]' type='radio' value='parallel' " ; if ("parallel" == $options['text_string']) {echo "checked='checked'"; } echo "/>Parallel<br>";
      echo "<input id='cfwc_form_theme_field_id' name='cfwc_form_theme_value[text_string]' type='radio' value='stacked' " ; if ("stacked" == $options['text_string']) {echo "checked='checked'"; } echo "/>Stacked<br>";

}



// validate our options
function plugin_options_validate($input) {

	$newinput['text_string'] = trim($input['text_string']);
//	if(!preg_match('/^[a-z0-9]{32}$/i', $newinput['text_string'])) {
//		$newinput['text_string'] = '';
//	}
	return $newinput;
}

// [cfwc publickey="abc" privatekey="def"]

add_shortcode( 'cfwc', 'cfwc_func' );
function cfwc_func( $atts ) {
	extract( shortcode_atts( array(
		'publickey' => 'something',
		'privatekey' => 'something else',
	), $atts ) );
      
      ob_start();
      $privatekey     = get_option('cfwc_private_key_value');
      $publickey      = get_option('cfwc_public_key_value');
      $cfwc_to        = get_option('cfwc_to_value');
      $cfwc_full_name = get_option('cfwc_full_name_value');
      $cfwc_e_mail    = get_option('cfwc_e_mail_value');
      $cfwc_subj      = get_option('cfwc_subj_value');
      $cfwc_message   = get_option('cfwc_message_value');
      $cfwc_button    = get_option('cfwc_button_value');
      $cfwc_subject   = get_option('cfwc_subject_value');
      $cfwc_subject_prefix = get_option('cfwc_subject_prefix_value');
      $cfwc_subject_suffix = get_option('cfwc_subject_suffix_value');
      $cfwc_credit    = get_option('cfwc_credit_value');
      $cfwc_captcha_theme    = get_option('cfwc_captcha_theme_value');
      $cfwc_form_theme    = get_option('cfwc_form_theme_value');


      $privatekey     = $privatekey['text_string'] ;
      $publickey      = $publickey['text_string'] ;
      $cfwc_to        = $cfwc_to['text_string'];
      $cfwc_full_name = $cfwc_full_name['text_string'];
      $cfwc_e_mail    = $cfwc_e_mail['text_string'];
      $cfwc_subj      = $cfwc_subj['text_string'];
      $cfwc_message   = $cfwc_message['text_string'];
      $cfwc_button    = $cfwc_button['text_string'];
      $cfwc_subject   = $cfwc_subject['text_string'];
      $cfwc_subject_prefix   = $cfwc_subject_prefix['text_string'];
      $cfwc_subject_suffix   = $cfwc_subject_suffix['text_string'];
      $cfwc_credit    = $cfwc_credit['boolean'];
      $cfwc_captcha_theme   = $cfwc_captcha_theme['text_string'];
      $cfwc_form_theme   = $cfwc_form_theme['text_string'];



      include(WP_PLUGIN_DIR . '/contact-form-with-captcha/cfwc-form.php');
    
      $output_string=ob_get_contents();
      ob_end_clean();

      return $output_string;
}
?>
