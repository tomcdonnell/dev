<?php
 $_toEmailAddress  = 'tomcdonnell@gmail.com';
 $_ccEmailAddress  = '';
 $_bccEmailAddress = '';

 $_subject  = $_POST['subject'    ];
 $_comments = $_POST['message'    ];
 $phone     = $_POST['phoneNumber'];
 $name      = $_POST['name'       ];
 $_from     = "\"$name\" <{$_POST['email']}>";
 
 if ($_POST['cc_me'] == 'on')
 {
    $_ccEmailAddress = $_from;
 }

 $message  = "\n\nName: $name\n";
 $message .= "Email: $_from\n";
 $message .= "Phone Number: $phone\n";
 $message .= "\nMessage: $_comments\n\n";

 $_headers = '';
 //$_headers .= "MIME-Version: 1.0\r\n";
 //$_headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
 $_headers .= "From: $_from\r\n";
 //$_headers .= "To: ".$_toEmailAddress."\r\n";

 if (!empty($_bccEmailAddress)) {$_headers .= "BCC: $_bccEmailAddress\r\n";}
 if (!empty($_ccEmailAddress )) {$_headers .= "CC: $_ccEmailAddress\r\n"  ;}

 $_headers .= "Reply-To: $_from\r\n";
 $_headers .= "X-Priority: 1\r\n";
 $_headers .= "X-MSMail-Priority: High\r\n";
 $_headers .= "X-Mailer: Hero Network Email System";

 $mailresponse = mail
 (
    "$_toEmailAddress",
    "$_subject",
    "$message",
    $_headers
 );
?>
