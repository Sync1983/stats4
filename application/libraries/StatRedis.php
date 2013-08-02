<?php

 if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * Description of Redis 
 * @author user
 */
class StatRedis {
  const MAP_PREFIX = "logger_map::";
  
  /** @var Redis **/
  private static $rd = null;
          
  function __construct() {
    if(self::$rd==null)
    {
      self::$rd = new Redis();
      self::$rd->connect(REDIS_HOST, REDIS_PORT);            
    }
  }
  
  function redis() {
    return self::$rd;
  }
  
  public function getProtoById($id,$project_id,$field){
    if(!self::$rd->hexists(self::MAP_PREFIX.$project_id."::".$id,$field))
      return $id;
    return self::$rd->hget(self::MAP_PREFIX.$project_id."::".$id,$field);
  }
  
}
