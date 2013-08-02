<?php

/**
 * Description of User 
 * @author user
 */
class User extends CI_Model{
  
  const USERS_KEY = "active_users";
  
  protected $data;
  protected $_is_valid = false;  
  
  function __construct($hash=null) {
    parent::__construct();    
    if(!$hash)
      return;
    $rd = $this->statredis->redis();
    if(!$rd->hexists(self::USERS_KEY,$hash))
      return;
    $this->data = json_decode($rd->hget(self::USERS_KEY,$hash),true);    
    $this->_is_valid = true;
  }
  
  public function get($name) {
    if(!isset($this->data[$name]))
      throw new Exception("Undefined field $name");
    return $this->data[$name];
  }
  
  public function is_admin() {    
    return isset($this->data['is_admin'])?$this->data['is_admin']:false;
  }
  
  public function projects() {    
    $ids = $this->data['projects_ids'];
    $ids = $ids ? implode(",",unpack('V*', $ids)) : "";    
    if(!$this->is_admin()) {
      $result = $this->statdb->db()->query("SELECT id,title,api_key FROM project where id in ($ids)");
    } else
      $result = $this->statdb->db()->query("SELECT id,title,api_key FROM project");
    
    if(!$result)
      return array();
    
    $ids = $result->fetch_all(MYSQLI_ASSOC);
    
    return $ids;
  }
  
  public function is_valid_project($project_id) {
    foreach($this->projects() as $project)
      if($project['id']==$project_id)
        return true;
    return false;
  }

  public function is_valid() {
    return $this->_is_valid;
  }
}
