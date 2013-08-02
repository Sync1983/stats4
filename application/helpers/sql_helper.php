<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

function makeSQLValid($sql) {
  $reg_expr = "/delete|drop|create/i";
  return preg_replace($reg_expr, "", $sql);
}

function prepareFilters($filters) {
  $result = "";
  $filter = json_decode($filters,true);
  
  foreach ($filter as $descr){      
    $result .= "`".$descr['item']."` ".$descr['operation']." ".$descr['value']." ";
    if(isset($descr['logic'])&&($descr['logic']!="-1"))
      $result .= $descr['logic']." ";
    else
      $result .= " ";
  }
  if($result!="")
    return "and (".$result.")";
  
  return "";
}

function prepareSQL($sql, $project_id, $bday,  $eday, $filter){  
  $tstamp = "stamp>=".    $bday. " and stamp<=".    $eday;    
  $rstamp = "reg_time>=". $bday. " and reg_time<=". $eday;
  
  $sql = str_replace("@[stamp_round]" , $tstamp                   , $sql);
  $sql = str_replace("@[time_range]"  , $rstamp                   , $sql);  
  $sql = str_replace("@[pid]"         , "project_id=".$project_id , $sql);  
  $sql = str_replace("@[filter]"      , prepareFilters($filter)   , $sql);
  
  return $sql;
}

function getFields($sql) {  
  $answer = array();
  $matches = array();
  $reg_exp = "/as ([y,x](\d*)_*(\w*))/i";
  preg_match_all($reg_exp, $sql, $matches,PREG_SET_ORDER);
  foreach($matches as $match){
    if((!isset($match[3])||(!$match[3])))
      $answer[$match[1]] = $match[1];
    else
      $answer[$match[1]] = $match[3];
  }
  return $answer;    
}

  /*function idToText($id) {    
    return $this->_rd->map_get($id, 'file', $this->_pid);
  }*/