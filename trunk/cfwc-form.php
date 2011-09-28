<?php
include(WP_PLUGIN_DIR . '/contact-form-with-captcha/captcha/recaptchalib.php');
// Get a key from https://www.google.com/recaptcha/admin/create

# the response from reCAPTCHA
$resp = null;
# the error code from reCAPTCHA, if any
$error = null;

# was there a reCAPTCHA response?
if ($_POST["recaptcha_response_field"]) {
        $resp = recaptcha_check_answer ($privatekey,
                                        $_SERVER["REMOTE_ADDR"],
                                        $_POST["recaptcha_challenge_field"],
                                        $_POST["recaptcha_response_field"]);

        if ($resp->is_valid) {
                
                {   
                          
                    // --- CONFIG PARAMETERS --- //
                    $email_recipient    = $cfwc_to;
                    $email_sender       = $_POST["contact_name"];
                    $email_return_to    = $_POST["contact_email"];
                    $email_content_type = "text/html; charset=us-ascii";
                    $email_client       = "PHP/" . phpversion();

                    // --- DEFINE HEADERS --- //

                    $email_header  = "From:         " . $email_sender . "\r\n";
                    $email_header .= "Reply-To:     " . $email_return_to . "\r\n";
                    $email_header .= "Return-Path:  " . $email_return_to . "\r\n";
                    $email_header .= "Content-type: " . $email_content_type . "\r\n";
                    $email_header .= "X-Mailer:     " . $email_client . "\r\n";

                    // --- SUBJECT AND CONTENTS --- //

                    $email_subject = '============= ' . $_POST["contact_subject"] . ' =============' ;
                    $email_contents = "<html>";
                    $email_contents .= "<h2>"                        . $_POST["contact_subject"] . "</h2>";
                    $email_contents .= "<br><b>Sender:</b>         "         . $email_sender;
                    $email_contents .= "<br><b>Sender Address:</b> " . $_SERVER["REMOTE_ADDR"];
                    $email_contents .= "<br><br>" . $_POST["contact_message"];    
                    $email_contents .= "</html>";
 
                    if (mail($email_recipient, $email_subject, $email_contents, $email_header))
                    {      
                        echo "<center><h2>Thank you for contacting us!</h2></center>";
                    }       
                    else 
                    {      
                        echo "<center><h2>Can't send email to Administrator. Please try later</h2></center>";      
                    } 
                }
        } 
        else 
        {
                # set the error code so that we can display it
                $error = $resp->error;
                echo "<center><h2>Incorrect Captcha!</h2></center>";
        }
}



?>

<script language="JavaScript" type="text/javascript">

function focuson() { document.cfwc_contactform.number.focus()}

function check(){
var str1 = document.getElementById("contact_email").value;
var filter=/^(.+)@(.+).(.+)$/i
if (!( filter.test( str1 ))){alert("Incorrect email address!");return false;}
if(document.getElementById("recaptcha_response_field").value=="")
   {
       alert("Please enter captcha");
       return false;
   }
}
</script>

<? echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/contact-form-with-captcha/cfwc.css" />';  ?>

<div id="cfwc_contactform">
<form action="" method="POST" name="ContactForm" onsubmit="return check();">

<table class="cfwc_table">
       <tbody>
         <tr>
             <td class="cfwc_cleanformdata cfwc_formdata">Full Name:</td>
             <td class="cfwc_cleanformdata"><input name="contact_name" class="cfwc_inputdata" type="text"></td>
         </tr>
         <tr><td class="cfwc_cleanformdata">&nbsp;&nbsp;</td></tr>
         <tr>
             <td class="cfwc_cleanformdata cfwc_formdata">E-mail:</td>
             <td class="cfwc_cleanformdata"><input id="contact_email" name="contact_email" class="cfwc_inputdata" type="text"></td>
         </tr>
         <tr><td class="cfwc_cleanformdata" >&nbsp;&nbsp;</td></tr>
         <tr>
             <td class="cfwc_cleanformdata cfwc_formdata">Subject:</td>
             <td class="cfwc_cleanformdata"><input name="contact_subject" class="cfwc_inputdata" type="text"></td>
         </tr>
         <tr><td class="cfwc_cleanformdata" >&nbsp;&nbsp;</td></tr>
         <tr>
             <td class="cfwc_cleanformdata cfwc_formdata">Message: </td>
             <td class="cfwc_cleanformdata"><a name="s" id="s"></a><textarea name="contact_message" id="contact_message" class="cfwc_textdata"></textarea></td>
         </tr>
         <tr><td class="cfwc_cleanformdata">&nbsp;&nbsp;</td></tr>       
         <tr>
            <td class="cfwc_cleanformdata"></td>
            <td class="cfwc_cleanformdata">
         <?
            if ($publickey != null)
            {
                echo recaptcha_get_html($publickey, $error);
            }
            else
            {
                echo "To use reCAPTCHA you must get an API key from <a href='https://www.google.com/recaptcha/admin/create'>https://www.google.com/recaptcha/admin/create</a> and enter it from the plugin menu";
            }
         ?>
            </td>
         </tr>
         <tr><td class="cfwc_cleanformdata">&nbsp;&nbsp;</td></tr>
         <tr>
            <td class="cfwc_cleanformdata"></td>
            <td class="cfwc_cleanformdata">
             <input name="Contact_Send" class="cfwc_inputdata" value="SendMessage" type="submit">
             <input name="SendMessage" value="1" type="hidden">
            </td>
         </tr>
     </tbody>
</table>

</form>
</div>
