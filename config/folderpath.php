<?php

/**
 * Configuration for: Folders
 * Here you define where your folders are. Unless you have renamed them, there's no need to change this.
 */
 
//define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'].'/');
define('ROOT_PATH', '../../../');
define('CONFIG_PATH', ROOT_PATH.'config/');
define('DOCFORM_PATH', ROOT_PATH.'docform/');
define('LIBS_PATH', ROOT_PATH.'libs/');
define('LOGIN_PATH', ROOT_PATH.'login/');
define('MAIN_PATH', ROOT_PATH.'main/');
define('UTILITY_PATH', ROOT_PATH.'utility/');
define('PUBLIC_PATH', ROOT_PATH.'public/');

//This must be set to relative path to use with jscript and css style.
define('JSCSS_PATH', '../jscss/');

define('WALLPAPER', CONFIG_PATH.'rotate.php');


// don't forget to make this folder writeable via chmod 775
// the slash at the end is VERY important!

define('IMAGE_PATH', '../image/');
define('WALLPAPER_PATH', '../image/wallpaper/');
define('DRUGIMAGE_PATH', '../image/drug/');

define('AVATAR_PATH', '../public/avatars/');
define('PT_AVATAR_PATH', '../public/avatars/');
define('PT_IMAGE_PATH', '../public/ptimages/');
