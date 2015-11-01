<?php

/**
 * THIS IS THE CONFIGURATION FILE
 * 
 * For more info about constants please @see http://php.net/manual/en/function.define.php
 * If you want to know why we use "define" instead of "const" @see http://stackoverflow.com/q/2447791/1114320
 */
 
/******************** MAIN SETTINGS **********************
Please complete wherever marked xxxxxxxxx

/**
 * Configuration for: Base URL
 * This is the base url of our app. if you go live with your app, put your full domain name here.
 * if you are using a (differen) port, then put this in here, like http://mydomain:8888/mvc/
 * TODO: when not using subfolder, is the trailing slash important ?
 */
define('URL', 'http://127.0.0.1/');
define('EXTURL', 'xxxx');//change me here!
define('PROJECT', 'xxxx');//change me here!
/************* MYSQL DATABASE SETTINGS *****************
1. Specify Database name in $dbname
2. MySQL host (localhost or remotehost)
3. MySQL user name with ALL previleges assigned.
4. MySQL password

Note: If you use cpanel, the name will be like account_database
*************************************************************/
/* This is local database */

define ("DB_HOST", "localhost"); // set database host
define ("DB_USER", "xxxx"); // set database user //change me here!
define ("DB_PASS","xxxx"); // set database password //change me here!
define ("DB_NAME","clinic"); // set database name //change me here!
define ("DB_OPD_NAME","clinic_opd"); // set database name //change me here!
define ("DB_COMMON_NAME","clinic_common"); // set database name //change me here!

/**
 * Configuration for: Folders
 * Here you define where your folders are. Unless you have renamed them, there's no need to change this.
 */
define('LIBS', '../libs/');
// don't forget to make this folder writeable via chmod 775
// the slash at the end is VERY important!
define('AVATAR_PATH', '../public/avatars/');
define('PT_AVATAR_PATH', '../public/avatars/');
define('PT_IMAGE_PATH', '../public/ptimages/');
define('IMAGE_PATH', '../image/');
define('WALLPAPER', './rotate.php');
/**
 * Configuration for: Avatars/Gravatar support
 * Set to true if you want to use "Gravatars", a service that automatically gets avatar pictures via using
 * the email adresses of users by requesting images from the gravatar.com API.
 * Set to false to use you own, locally saved avatars.
 */
define('USE_GRAVATARS', false);

/* Registration Type (Automatic or Manual) 
 1 -> Automatic Registration (Users will receive activation code and they will be automatically approved after clicking activation link)
 0 -> Manual Approval (Users will not receive activation code and you will need to approve every user manually)
*/
$user_registration = 1;  // set 0 or 1

define("COOKIE_TIME_OUT", 10); //specify cookie timeout in days (default is 10 days)
define('SALT_LENGTH', 9); // salt for password

//define ("ADMIN_NAME", "admin"); // sp

/* Specify user levels */
define ("ADMIN_LEVEL", 5);
define ("USER_LEVEL", 2);
define ("GUEST_LEVEL", 0);


/*************** reCAPTCHA KEYS****************/
//localhost
$publickey = "xxxxxxxxx"; //change me here!
$privatekey = "xxxxxxxxxxxxx"; //change me here!
//internet site
$publickey = "xxxxxxxxxxxx"; //change me here!
$privatekey = "xxxxxxxxxxxxxxx"; //change me here!

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

/**
 * Configuration for: Email content data
 * 
 * php-login uses the PHPMailer library, please have a look here if you want to add more
 * config stuff: @see https://github.com/PHPMailer/PHPMailer
 * 
 * As email sending within your project needs some setting, you can do this here:
 * 
 * Absolute URL to password reset action, necessary for email password reset links
 * define("EMAIL_PASSWORDRESET_URL", "http://127.0.0.1/php-login/4-full-mvc-framework/login/passwordReset"); 
 * define("EMAIL_PASSWORDRESET_FROM_EMAIL", "noreply@example.com");
 * define("EMAIL_PASSWORDRESET_FROM_NAME", "My Project");
 * define("EMAIL_PASSWORDRESET_SUBJECT", "Password reset for PROJECT XY");
 * define("EMAIL_PASSWORDRESET_CONTENT", "Please click on this link to reset your password:");
 * 
 * absolute URL to verification action, necessary for email verification links
 * define("EMAIL_VERIFICATION_URL", "http://127.0.0.1/php-login/4-full-mvc-framework/login/verify/");
 * define("EMAIL_VERIFICATION_FROM_EMAIL", "noreply@example.com");
 * define("EMAIL_VERIFICATION_FROM_NAME", "My Project");
 * define("EMAIL_VERIFICATION_SUBJECT", "Account Activation for PROJECT XY");
 * define("EMAIL_VERIFICATION_CONTENT", "Please click on this link to activate your account:");
 */
define("EMAIL_PASSWORDRESET_URL", URL . "login/verifypasswordrequest");
define("EMAIL_PASSWORDRESET_FROM_EMAIL", "noreply@example.com");
define("EMAIL_PASSWORDRESET_FROM_NAME", "My Project");
define("EMAIL_PASSWORDRESET_SUBJECT", "Password reset");
define("EMAIL_PASSWORDRESET_CONTENT", "Please click on this link to reset your password: ");

define("EMAIL_VERIFICATION_URL", URL . "login/verify");
define("EMAIL_VERIFICATION_FROM_EMAIL", "noreply@example.com");
define("EMAIL_VERIFICATION_FROM_NAME", "My Project");
define("EMAIL_VERIFICATION_SUBJECT", "Account Activation for PROJECT XY");
define("EMAIL_VERIFICATION_CONTENT", "Please click on this link to activate your account: ");
define("EMAIL_UP_SUBJECT", "Account for ");

/**
 * Configuration for: Error messages and notices
 * 
 * In this project, the error messages, notices etc are alltogether called "feedback".
 */
define("FEEDBACK_PASSWORD_WRONG_3_TIMES", "You have typed in a wrong password 3 or more times already. Please wait <span id='failed-login-countdown-value'>30</span> seconds to try again.");
define("FEEDBACK_ACCOUNT_NOT_ACTIVATED_YET", "Your account is not activated yet. Please click on the confirm link in the mail. Or contact Administator for activation.");
define("FEEDBACK_PASSWORD_WRONG", "Invalid Login. Please try again with correct user email and password.  Password was wrong.");
define("FEEDBACK_USER_DOES_NOT_EXIST", "Invalid Login!  This user does not exist.");
define("FEEDBACK_USERNAME_FIELD_EMPTY", "Username field was empty.");
define("FEEDBACK_PASSWORD_FIELD_EMPTY", "Password field was empty.");
define("FEEDBACK_EMAIL_FIELD_EMPTY", "Email and passwords fields were empty.");
define("FEEDBACK_EMAIL_AND_PASSWORD_FIELDS_EMPTY", "Email field was empty.");
define("FEEDBACK_USERNAME_SAME_AS_OLD_ONE", "Sorry, that username is the same as your current one. Please choose another one.");
define("FEEDBACK_USERNAME_ALREADY_TAKEN", "Sorry, that username is already taken. Please choose another one.");
define("FEEDBACK_USERNAME_CHANGE_SUCCESSFUL", "Your username has been changed successfully.");
define("FEEDBACK_USERNAME_AND_PASSWORD_FIELD_EMPTY", "Username and password fields were empty.");
define("FEEDBACK_USERNAME_DOES_NOT_FIT_PATTERN", "Username does not fit the name sheme: only a-Z and numbers are allowed, 2 to 64 characters.");
define("FEEDBACK_EMAIL_DOES_NOT_FIT_PATTERN", "Sorry, your chosen email does not fit into the email naming pattern.");
define("FEEDBACK_EMAIL_SAME_AS_OLD_ONE", "Sorry, that email address is the same as your current one. Please choose another one.");
define("FEEDBACK_EMAIL_CHANGE_SUCCESSFUL", "Your email address has been changed successfully.");
define("FEEDBACK_CAPTCHA_WRONG", "The entered captcha security characters were wrong.");
define("FEEDBACK_PASSWORD_REPEAT_WRONG", "Password and password repeat are not the same.");
define("FEEDBACK_PASSWORD_TOO_SHORT", "Password has a minimum length of 6 characters.");
define("FEEDBACK_USERNAME_TOO_SHORT_OR_TOO_LONG", "Username cannot be shorter than 2 or longer than 64 characters.");
define("FEEDBACK_EMAIL_TOO_LONG", "Email cannot be longer than 64 characters.");
define("FEEDBACK_ACCOUNT_SUCCESSFULLY_CREATED", "Your account has been created successfully and we have sent you an email. Please click the VERIFICATION LINK within that mail.");
define("FEEDBACK_VERIFICATION_MAIL_SENDING_FAILED", "Sorry, we could not send you an verification mail. Your account has NOT been created.");
define("FEEDBACK_ACCOUNT_CREATION_FAILED", "Sorry, your registration failed. Please go back and try again.");
define("FEEDBACK_VERIFICATION_MAIL_SENDING_ERROR", "Verification mail could not be sent due to: ");
define("FEEDBACK_VERIFICATION_MAIL_SENDING_SUCCESSFUL", "A verification mail has been sent successfully.");
define("FEEDBACK_ACCOUNT_ACTIVATION_SUCCESSFUL", "Activation was successful! You can now log in.");
define("FEEDBACK_ACCOUNT_ACTIVATION_FAILED", "Sorry, no such id/verification code combination here...");
define("FEEDBACK_AVATAR_UPLOAD_SUCCESSFUL", "Avatar upload was successful.");
define("FEEDBACK_AVATAR_UPLOAD_WRONG_TYPE", "Only JPEG and PNG files are supported.");
define("FEEDBACK_AVATAR_UPLOAD_TOO_SMALL", "Avatar source file's width/height is too small. Needs to be 100x100 pixel minimum.");
define("FEEDBACK_AVATAR_UPLOAD_TOO_BIG", "Avatar source file is too big. 5 Megabyte is the maximum.");
define("FEEDBACK_AVATAR_FOLDER_NOT_WRITEABLE", "Avatar folder is not writeable. Please change this via chmod 775 or 777.");
define("FEEDBACK_PASSWORD_RESET_TOKEN_FAIL", "Could not write token to database.");
define("FEEDBACK_PASSWORD_RESET_MAIL_SENDING_ERROR", "Password reset mail could not be sent due to: ");
define("FEEDBACK_PASSWORD_RESET_MAIL_SENDING_SUCCESSFUL", "Your account password has been reset and a new password has been sent successfully to your email address.");
define("FEEDBACK_PASSWORD_RESET_LINK_EXPIRED", "Your reset link has expired. Please use the reset link within one hour.");
define("FEEDBACK_PASSWORD_RESET_COMBINATION_DOES_NOT_EXIST", "Username/Verification code combination does not exist.");
define("FEEDBACK_PASSWORD_CHANGE_SUCCESSFUL", "Password successfully changed.");
define("FEEDBACK_PASSWORD_CHANGE_FAILED", "Sorry, your password changing failed.");
define("FEEDBACK_ACCOUNT_UPGRADE_SUCCESSFUL", "Account upgrade was successful.");
define("FEEDBACK_ACCOUNT_UPGRADE_FAILED", "Account upgrade failed.");
define("FEEDBACK_ACCOUNT_DOWNGRADE_SUCCESSFUL", "Account downgrade was successful.");
define("FEEDBACK_ACCOUNT_DOWNGRADE_FAILED", "Account downgrade failed.");
define("FEEDBACK_NOTE_CREATION_FAILED", "Note creation failed.");
define("FEEDBACK_NOTE_EDITING_FAILED", "Note editing failed.");
define("FEEDBACK_NOTE_DELETION_FAILED", "Note deletion failed.");
define("FEEDBACK_COOKIE_INVALID", "Your remember-me-cookie is invalid.");
define("FEEDBACK_COOKIE_LOGIN_SUCCESSFUL", "You were successfully logged in via the remember-me-cookie.");

define("FEEDBACK_UNKNOWN_ERROR", "Unknown error occured!");
