<?php

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
define("FEEDBACK_PT_IMAGE_FOLDER_NOT_WRITEABLE", "PT_IMAGE folder is not writeable. Please change this via chmod 775 or 777.");
define("FEEDBACK_AVATAR_NOT_UPLOAD", "Avatar source is not set or cannot upload or not readable.");
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
