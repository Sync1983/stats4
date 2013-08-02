<?php

/**
 * Description of User 
 * @author user
 */
class ChartModel extends CI_Model{
  
  const MAP_PREFIX = "logger_map::";
  const CACHE_PREFIX = "cache::";
  
  protected $_id;
  protected $_bday;
  protected $_eday;
  protected $_filter;
  protected $_key;
  protected $_cache = null;
  
  public function init($id,$bday,$eday,$filter) {
    parent::__construct();    
    $this->_id      = $id;
    $this->_bday    = $bday;
    $this->_eday    = $eday;
    $this->_filter  = $filter;
    $key = md5($id.$bday.$eday.$filter);
    $this->_key = $key;
    $rd = $this->statredis->redis();
    if($rd->exists(self::CACHE_PREFIX.$key))
      $this->_cache = $rd->get(self::CACHE_PREFIX.$key);
  }


  public function getData() {
    //if($this->_cache)
    //  return json_decode ($this->_cache);
    
    $this->load->helper('sql_helper');
    $db = $this->statdb->db();
    $chart = $db->query("SELECT project_id,name,query FROM logger_chart WHERE id=".$this->_id);
    if((!$chart)||($chart->num_rows!=1))
      return array();
    $chart  = $chart->fetch_assoc();
    $querys = explode(";", $chart['query']);
    $chart_name = $chart['name'];
    $project_id = $chart['project_id'];
    
    $data = array();
    foreach($querys as $sql)
      $this->_groupResult($this->_makeQuery($sql,$project_id,$db),$data);
    
    $series = array();
    foreach($data as $name=>$values){
      $tmp = array();
      $tmp['name'] = $name;
      $tmp['data'] = $values;      
      $series[] = $tmp;
    }
    
    $answer = array(
        'name'   => $chart_name,
        'series' => $series,
    );
    $rd = $this->statredis->redis();    
    $this->_cache = json_encode($answer);
    $rd->set(self::CACHE_PREFIX.$this->_key,  $this->_cache);
    return $answer;
  }
  
  private function _makeQuery($sql,$project_id,$db){
    if(!$sql||($sql==''))
      return array(array(),array(),$project_id);
    $sql    = makeSQLValid($sql);    
    $sql    = prepareSQL  ($sql, $project_id, $this->_bday,  $this->_eday, $this->_filter);    
    $fields = getFields   ($sql);    
    $result = $db->query  ($sql);
    if(!$result)
      return array(array(),array(),$project_id);
    $db_result = $result->fetch_all(MYSQLI_ASSOC);
    $result->free();
    return array($fields,$db_result,$project_id);
  }
  
  private function _groupResult($inputData,&$data) {
    list($fields,$result,$project_id) = $inputData; 
    if(!$result||(count($result)==0))
      return;    
    foreach ($result as &$row){
        $x = FALSE;
        if(isset($row['x'])){
          $x = $row['x'];
          if($x>1000000)    //Hoock for js time
            $x*=1000;
          unset($row['x']);
        } elseif (isset($row['x_id'])) {
          $x = $this->statredis->getProtoById($row['x_id'], $project_id,'file');
          unset($row['x_id']);
        }
        
        if(!$x)
          continue;        
        
        foreach ($row as $field=>$value){
          $field_name = $fields[$field];          
          $data[$field_name][] = array($x,$value*1);          
        }        
      }          
  }
  
}
