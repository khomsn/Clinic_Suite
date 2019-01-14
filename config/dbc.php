<?php
/*************** PHP LOGIN SCRIPT *********************
(c) Khomsn 2560. All Rights Reserved
***********************************************************/
require 'config.php';

require 'connection.php';

/**** PAGE PROTECT CODE  ********************************
This code protects pages to only logged in users. If users have not logged in then it will redirect to login page.
If you want to add a new page and want to login protect, COPY this from this to END marker.
Remember this code must be placed on very top of any html or php page.
********************************************************/
//echo "1";
function page_protect() 
{
    session_start();

    global $link,$linkopd,$linkcm; 
//echo "2";
    /* Secure against Session Hijacking by checking user agent */
    if (isset($_SESSION['HTTP_USER_AGENT']))
    {//echo "3";
        if ($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT']))
        {
            logout();
            exit;
        }
    }

    // before we allow sessions, we need to check authentication key - ckey and ctime stored in database

    /* If session not set, check for cookies set by Remember me */
    if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_name']) ) 
    {
        if(isset($_COOKIE['user_id']) && isset($_COOKIE['user_key']))
        {
            /* we double check cookie expiry time against stored in database */

            $cookie_user_id  = filter($_COOKIE['user_id']);
            $rs_ctime = mysqli_query($link, "select `ckey`,`ctime` from `users` where `id` ='$cookie_user_id'") or die(mysqli_error($link));
            list($ckey,$ctime) = mysqli_fetch_row($rs_ctime);
            // coookie expiry
            if( (time() - $ctime) > 60*60*24*COOKIE_TIME_OUT) 
            {
                logout();
            }
            /* Security check with untrusted cookies - dont trust value stored in cookie. 		
            /* We also do authentication check of the `ckey` stored in cookie matches that stored in database during login*/

            if( !empty($ckey) && is_numeric($_COOKIE['user_id']) && isUserID($_COOKIE['user_name']) && $_COOKIE['user_key'] == sha1($ckey)  ) 
            {
                session_regenerate_id(); //against session fixation attacks.
            
                $_SESSION['user_id'] = $_COOKIE['user_id'];
                $_SESSION['user_name'] = $_COOKIE['user_name'];
                /* query user level from database instead of storing in cookies */	
                list($user_level) = mysqli_fetch_row(mysqli_query($link, "select user_level from users where id='$_SESSION[user_id]'"));
                list($accode) = mysqli_fetch_row(mysqli_query($link, "select user_level from users where id='$_SESSION[user_id]'"));

                $_SESSION['user_level'] = $user_level;
                $_SESSION['user_accode'] = $accode;
                $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);         
            }
            else
            {
                logout();
            }
        }
        else
        {
            header("Location: ../../login/login.php");
            exit();
        }
    }
}

function filter($data) 
{
  $data = trim(htmlentities(strip_tags($data), ENT_QUOTES, 'UTF-8')); // ( Mod by MixMan)

  if (get_magic_quotes_gpc())  $data = stripslashes($data);
  
  /* Decode HTML entities */
  $data = html_entity_decode( $data, ENT_QUOTES, "UTF-8" ); // ( Mod by MixMan)

  return $data;
}

function mysqlescape($data) 
{
  $data = mysqli_real_escape_string($link, $data); 
  return $data;
}

function EncodeURL($url)
{
    $new = strtolower(ereg_replace(' ','_',$url));
    return($new);
}

function DecodeURL($url)
{
    $new = ucwords(ereg_replace('_',' ',$url));
    return($new);
}

function ChopStr($str, $len) 
{
    if (strlen($str) < $len)
        return $str;

    $str = substr($str,0,$len);
    if ($spc_pos = strrpos($str," "))
            $str = substr($str,0,$spc_pos);

    return $str . "...";
}

function isEmail($email)
{
  return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? TRUE : FALSE;
}

function isUserID($username)
{
    if (preg_match('/^[a-z\d_]{5,20}$/i', $username)) 
    {
        return true;
    }
    else
    {
        return false;
    }
}

function isURL($url) 
{
    if (preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $url))
    {
        return true;
    }
    else
    {
        return false;
    }
} 

function checkPwd($x,$y) 
{
    if(empty($x) || empty($y) ) { return false; }
    if (strlen($x) < 4 || strlen($y) < 4) { return false; }

    if (strcmp($x,$y) != 0) { return false;}
    
    return true;
}

function GenPwd($length = 7)
{
  $password = "";
  $possible = "0123456789bcdfghjkmnpqrstvwxyz"; //no vowels
  
  $i = 0; 
    
  while ($i < $length)
  { 
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
    
    if (!strstr($password, $char)) 
    { 
      $password .= $char;
      $i++;
    }
  }
  return $password;
}

function GenKey($length = 7)
{
  $password = "";
  $possible = "0123456789abcdefghijkmnopqrstuvwxyz"; 
  
  $i = 0; 
    
  while ($i < $length)
  { 
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
    
    if (!strstr($password, $char)) 
    { 
      $password .= $char;
      $i++;
    }

  }
  return $password;
}


function logout()
{
    global $link,$linkcm,$linkopd;
    
    session_start();

    if(isset($_SESSION['user_id']) || isset($_COOKIE['user_id']))
    {
        mysqli_query($link, "update `users` set `ckey`= '' where `id`='$_SESSION[user_id]' OR  `id` = '$_COOKIE[user_id]'") or die(mysqli_error($link));
    }
    
    /************ Delete the sessions****************/
    unset($_SESSION['user_id']);
    unset($_SESSION['user_name']);
    unset($_SESSION['user_level']);
    unset($_SESSION['user_accode']);
    unset($_SESSION['HTTP_USER_AGENT']);
    session_unset();
    session_destroy(); 

    /* Delete the cookies*******************/
    setcookie("user_id", '', time()-60*60*24*COOKIE_TIME_OUT, "/");
    setcookie("user_name", '', time()-60*60*24*COOKIE_TIME_OUT, "/");
    setcookie("user_key", '', time()-60*60*24*COOKIE_TIME_OUT, "/");

    header("Location: ../../login/login.php");
}

// Password and salt generation
function PwdHash($pwd, $salt = null)
{
    if ($salt === null)
    {
        $salt = substr(md5(uniqid(rand(), true)), 0, SALT_LENGTH);
    }
    else
    {
        $salt = substr($salt, 0, SALT_LENGTH);
    }
    return $salt . sha1($pwd . $salt);
}

function checkAdmin()
{

    if($_SESSION['user_level'] == ADMIN_LEVEL)
    {
        return 1;
    }
    else
    {
        return 0 ;
    }

}

?>
