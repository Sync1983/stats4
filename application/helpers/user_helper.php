<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * Description of StatsUser 
 * @author user
 */

//if(session_status()==PHP_SESSION_NONE)
try{
  session_start();
}catch (ErrorException $E) { 
}

  /**   
   * @return User
   */
  function getActiveUser(){    
    if(isset($_SESSION['hash'])&&($_SESSION['hash']!==''))
      return new User($_SESSION['hash']);
    else
      return _tryLoadFromCoockies();
  }

  function _tryLoadFromCoockies() {    
    if(isset($_COOKIE[self::COOKIE_MEMBER_HASH])) {
      $hash = $_COOKIE[self::COOKIE_MEMBER_HASH];
      $_SESSION['hash'] = $hash;
      if($hash=='')
        return false;
      return new User($hash);  
    } else
      return false;
  }
