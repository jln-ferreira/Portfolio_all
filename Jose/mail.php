<?php
if($_POST)
{
    $to_Email       = "jln-ferreira@outlook.com"; // Replace with recipient email address
    $subject        = 'Contact - Portfolio'; //Subject line for emails
    
    $host           = "smtp.live.com"; // Your SMTP server. For example, smtp.mail.yahoo.com
    $username       = "jln-ferreira@outlook.com"; //For example, your.email@yahoo.com
    $password       = "Walmart*2017"; // Your password
    $SMTPSecure     = "tls"; // For example, ssl
    $port           = 587; // For example, 465
    
    
    //check $_POST vars are set, exit if any missing
    if(!isset($_POST["name"]) || !isset($_POST["email"]) || !isset($_POST["message"]))
    {
        $output = json_encode(array('type'=>'error', 'text' => 'Input fields are empty!'));
        die($output);
    }

    //Sanitize input data using PHP filter_var().
    $user_Name        = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
    $user_Email       = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $user_Message     = filter_var($_POST["message"], FILTER_SANITIZE_STRING);
    $user_Message = str_replace("\&#39;", "'", $user_Message);
    $user_Message = str_replace("&#39;", "'", $user_Message);
    
    //additional php validation
    if(strlen($user_Name)<4) // If length is less than 4 it will throw an HTTP error.
    {
        $output = json_encode(array('type'=>'error', 'text' => 'Name is too short or empty!'));
        die($output);
    }
    if(!filter_var($user_Email, FILTER_VALIDATE_EMAIL)) //email validation
    {
        $output = json_encode(array('type'=>'error', 'text' => 'Please enter a valid email!'));
        die($output);
    }
    if(strlen($user_Message)<5) //check emtpy message
    {
        $output = json_encode(array('type'=>'error', 'text' => 'Too short message! Please enter something.'));
        die($output);
    }
    
    //proceed with PHP email.
    include("PHPMailer/PHPMailerAutoload.php"); //you have to upload class files "class.phpmailer.php" and "class.smtp.php"
 
  $mail = new PHPMailer();
   
  $mail->IsSMTP();
  $mail->SMTPAuth = true;
  
  $mail->Host = $host;
  $mail->Username = $username;
  $mail->Password = $password;
  $mail->SMTPSecure = $SMTPSecure;
  $mail->Port = $port;
  
   
  $mail->setFrom($username);
  $mail->addReplyTo($user_Email);
   
  $mail->AddAddress($to_Email);
  $mail->Subject = $subject;
  $mail->Body = $user_Message. "\r\n\n"  .'Name: '.$user_Name. "\r\n" .'Email: '.$user_Email;
  $mail->WordWrap = 200;
  $mail->IsHTML(false);
    $mail->SMTPOptions = array(
    'tls' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    ));
  if(!$mail->send()) {

    $output = json_encode(array('type'=>'error', 'text' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo));
    die($output);

  } else {
    $Success = true;
    header("Location: index.php");
  }
    
}
?>