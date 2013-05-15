<?php
if(!is_callable('recaptcha_check_answer')) require_once(WP_PLUGIN_DIR . '/contact-form-with-captcha/captcha/recaptchalib.php');
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
                    $_POST = str_replace("\\","",$_POST);
      
                    // --- CONFIG PARAMETERS --- //
                    $email_recipient    = $cfwc_to;
                    $email_sender       = $_POST["contact_name"];
                    $email_return_to    = $_POST["contact_email"];
                    $email_content_type = "text/html; charset=UTF-8";
                    $email_client       = "PHP/" . phpversion();

                    // --- SUBJECT --- //
                    $email_subject = $cfwc_subject_prefix . ' ' . $_POST["contact_subject"] . ' ' . $cfwc_subject_suffix ;


                    // --- DEFINE HEADERS --- //
                    $email_header  = "From:         =?UTF-8?B?".base64_encode($email_sender)."?=" . " <" . $email_return_to . ">" . "\r\n";
                    //$email_header .= "Subject:      =?UTF-8?B?".base64_encode($email_subject)."?=" . "\r\n";
                    $email_header .= "Reply-To:     " . $email_return_to . "\r\n";
                    $email_header .= "Return-Path:  " . $email_return_to . "\r\n";
                    $email_header .= "Content-type: " . $email_content_type . "\r\n";
                    $email_header .= "X-Mailer:     " . $email_client . "\r\n";

                    // --- CONTENTS --- //
                    
                    $email_contents = "<html>";
                    $email_contents .= "<h2>"                                . $_POST["contact_subject"] . "</h2>";
                    $email_contents .= "<br><b>Sender Name:</b>         "    . $email_sender;
                    $email_contents .= "<br><b>Sender Email:</b>         "   . $email_return_to;
                    $email_contents .= '<br><b>Sender IP Address:</b> ' . $_SERVER["REMOTE_ADDR"] . ' <strong>(<a href="http://www.teqlog.com/find-my-ip-address.html">Find location for this IP</a></strong>)';
                    $email_contents .= "<br><br>" . $_POST["contact_message"];    
                    $email_contents .= "</html>";
 
                    if (mail($email_recipient, $email_subject, $email_contents, $email_header, '-f'.$email_return_to))
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

<script type="text/javascript">
 var RecaptchaOptions = {
    theme : '<?php echo $cfwc_captcha_theme; ?>'
 };
 </script>

<?php echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/contact-form-with-captcha/cfwc.css" />';  ?>

<div id="cfwc_contactform">
<!-- Contact form with Captcha - For more details visit http://www.teqlog.com/wordpress-contact-form-with-captcha-plugin.html -->
<form action="" method="POST" name="ContactForm" onsubmit="return check();">

<table>
       <tbody>
         <tr>
             <td>
             <?php 
                 if ($cfwc_full_name != null)
                 {
                     echo $cfwc_full_name ;
                 }
                 else
                 {
                     echo "Full Name:"; 
                 }
             ?>
             <?php if ($cfwc_form_theme == "stacked") {echo "<br>";} else {echo "</td><td>";} ?>
             <input name="contact_name" type="text" value="<?php if(isset($_POST['contact_name']) && !$resp->is_valid ) echo $_POST['contact_name']; ?>"/>
             </td>
         </tr>
         <tr/><tr/><tr/><tr/>
         <tr>
             <td>
             <?php 
                 if ($cfwc_e_mail != null)
                 {
                     echo $cfwc_e_mail ;
                 }
                 else
                 {
                     echo "E Mail:";
                 }
             ?>
             <?php if ($cfwc_form_theme == "stacked") {echo "<br>";} else {echo "</td><td>";} ?>
             <input id="contact_email" name="contact_email" type="text" value="<?php if(isset($_POST['contact_email']) && !$resp->is_valid ) echo $_POST['contact_email']; ?>"/></td>
         </tr>
         <tr/><tr/><tr/><tr/>
         <tr>
             <td>
             <?php 
                 if ($cfwc_subj != null)
                 {
                     echo $cfwc_subj ;
                 }
                 else
                 {
                     echo "Subject:"; 
                 }
             ?>
             <?php if ($cfwc_form_theme == "stacked") {echo "<br>";} else {echo "</td><td>";} ?>
             <?php
                 if ($cfwc_subject == null)
                 {
                     echo '<input name="contact_subject" class="cfwc_inputdata" type="text" value="'; if(isset($_POST['contact_subject']) && !$resp->is_valid ) echo $_POST['contact_subject']; echo '"/>';
                 }
                 else
                 {
                     $subject_tok = explode(":",$cfwc_subject);
                     echo '<select name="contact_subject">';
                     foreach ($subject_tok as $v) 
                     {
                         echo '<option value="' . $v . '">' . $v . '</option>';
                     }
                     echo '</select>';
                 }
             ?>
             </td>
         </tr>
         <tr/><tr/><tr/><tr/>
         <tr>
             <td>
             <?php 
                 if ($cfwc_message != null)
                 {
                     echo $cfwc_message ;
                 }
                 else
                 {
                     echo "Message:"; 
                 }
             ?>
             <?php if ($cfwc_form_theme == "stacked") {echo "<br>";} else {echo "</td><td>";} ?>
             <textarea name="contact_message" id="contact_message" ><?php if(isset($_POST['contact_message']) && !$resp->is_valid ) echo $_POST['contact_message']; ?></textarea></td>
         </tr>
         <tr/><tr/><tr/><tr/>
         <tr>
            <td>
            <?php if ($cfwc_form_theme == "stacked") {echo "<br>";} else {echo "</td><td>";} ?>
         <?php
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
         <tr/><tr/><tr/><tr/>
         <tr>
            <td>
             <?php if ($cfwc_form_theme == "stacked") {echo "<br>";} else {echo "</td><td>";} ?>
             <input name="Contact_Send" value="<?php if ($cfwc_button != null){ echo $cfwc_button ; } else { echo "Send Message";} ?> " type="submit">            
             <input name="SendMessage"  value="1" type="hidden">
            </td>
         </tr>
         <tr>
            <td>
             <?php 
              /*if ($cfwc_credit != "true")
              echo '<p class="credit">Powered by <a href="http://www.teqlog.com">Technology blog</a></p>';
              else
              {
                  echo '<div id="cimg"><a title="Technology Blog" href="http://www.teqlog.com/"><img src="' ; echo WP_PLUGIN_URL; echo '/contact-form-with-captcha/1.gif" alt="Technology Blog" /></a></div>';
              }*/
            ?>
            </td>
         </tr>
     </tbody>
</table>

</form>
<!-- Contact form with Captcha - For more details visit http://www.teqlog.com/wordpress-contact-form-with-captcha-plugin.html -->
</div>
