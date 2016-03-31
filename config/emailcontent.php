<?php

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

