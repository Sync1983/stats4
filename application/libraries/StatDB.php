<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * Description of StatsDB 
 * @author user
 */
class StatDB {
  /** @var mysqli **/
  private static $db = null;
          
  function __construct() {
    if(self::$db==null)
    {
      self::$db = new mysqli();
      self::$db->connect(DB_HOST, DB_USER, DB_PASS,DB_BASE);
      self::$db->query("SET NAMES 'utf-8'");
      self::$db->query("SET CHARSET 'utf8'");
    }
  }
  
  /**   
   * @return mysqli
   */
  function db() {
    return self::$db;
  }
}
