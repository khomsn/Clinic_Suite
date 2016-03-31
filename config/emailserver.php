<?php

/**
 * Configuration for: Email server credentials
 * 
 * Here you can define how you want to send emails.
 * If you have successfully set up a mail server on your linux server and you know
 * what you do, then you can skip this section. Otherwise please set EMAIL_USE_SMTP to true
 * and fill in your SMTP provider account data.
 * 
 * An example setup for using gmail.com [Google Mail] as email sending service, 
 * works perfectly in August 2013. Change the "xxx" to your needs.
 * Please note that there are several issues with gmail, like gmail will block your server
 * for "spam" reasons or you'll have a daily sending limit. See the readme.md for more info.
 * 
 * define("EMAIL_USE_SMTP", true);
 * define("EMAIL_SMTP_HOST", 'ssl://smtp.gmail.com');
 * define("EMAIL_SMTP_AUTH", true);
 * define("EMAIL_SMTP_USERNAME", 'xxxxxxxxxx@gmail.com');
 * define("EMAIL_SMTP_PASSWORD", 'xxxxxxxxxxxxxxxxxxxx');
 * define("EMAIL_SMTP_PORT", 465);
 * define("EMAIL_SMTP_ENCRYPTION", 'ssl');  
 * 
 * It's really recommended to use SMTP!
 * 
 */
define("EMAIL_USE_SMTP", true);//Tell PHPMailer to use SMTP
define("EMAIL_SMTP_HOST", 'smtp.gmail.com');//Set the hostname of the mail server
define("EMAIL_SMTP_AUTH", true); // leave this true until your SMTP can be used without login
define("EMAIL_SMTP_USERNAME", 'xxxx@gmail.com'); // change me Here!
define("EMAIL_SMTP_PASSWORD", 'xxxxx'); // change me Here!
define("EMAIL_SMTP_PORT", 587);//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
define("EMAIL_SMTP_ENCRYPTION", 'tls'); //Set the encryption system to use - ssl (deprecated) or tls

