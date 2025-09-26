<?php

//database settings
$db_host = 'localhost';
$db_user = 'root';
$db_pass = 'mysql';
$db_name = 'gmaildb';

//imap settings
$imap_server    = 'imap.gmail.com:993/imap/ssl/novalidate-cert';
$imap_username  = 'your_username@gmail.com';
$imap_password  = 'your_password';

//connecting to imap server
$imap_mail = imap_open("{".$imap_server."}INBOX", $imap_username, $imap_password) 
             or die("can't connect to imap: " . imap_last_error());

//get all inbox mails
$emails  = imap_search($imap_mail, 'ALL');

//connect to database
$db_conn = mysqli_connect($db_host, $db_user, $db_pass) 
           or die('Error connecting to mysql');
mysqli_select_db($db_conn, $db_name);

echo "<h2>Email List</h2>\n";
if ($emails) {
 //sort email from newest one
 rsort($emails);
 
 foreach ($emails as $email_number) {
  //get email overview and body
  $overview = imap_fetch_overview($imap_mail, $email_number, 0);
  $msg      = imap_fetchbody($imap_mail, $email_number, 2);
  
  //extract overview properties
  $subject  = mysqli_real_escape_string($db_conn, $overview[0]->subject);
  $from     = mysqli_real_escape_string($db_conn, $overview[0]->from);
  $date     = mysqli_real_escape_string($db_conn, $overview[0]->date);
  $to       = mysqli_real_escape_string($db_conn, $overview[0]->to);
  
  //extract message body
  $message  = mysqli_real_escape_string($db_conn, $msg);
  
  //create a unique id based on md5 of $date and $from
  $hash_id  = md5($date . $from);
  
  //check if the email already backed-up on your database
  $query    = "SELECT * FROM emails WHERE mail_id='{$hash_id}'";
  $result   = mysqli_query($db_conn, $query) 
              or die(mysqli_error($db_conn) . "<br />" . $query);
  $num_rows = mysqli_num_rows($result);
  
  //if not backed-up
  if ($num_rows < 1) {
   echo '[' . $date . '] ' . 
        'from: ' . $from . 
        '; to: ' . $to . 
        '; subject: ' . $subject . 
        "<br />\n";

   //insert email into database
   $query = "INSERT INTO" .
    " emails (mail_id, mail_date, to_addr, from_addr, subject,".
    " message, mail_type)" .
    " VALUES ('{$hash_id}', '{$date}', '{$to}',".
    " '{$from}', '{$subject}'," .
    " '{$message}', 'inbox');";
   mysqli_query($db_conn, $query) or die(mysqli_error($db_conn) 
    . "<br />" . $query);
  }
  
 }
 
}

//close database connection
mysqli_close($db_conn);

//close imap connection
imap_close($imap_mail);

?>
