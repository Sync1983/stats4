<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chart extends CI_Controller {
  protected $_type_to_text= array(0=>'spline',1=>'bar',2=>'line',3=>'areaspline');
  
  public function ajax_load_chart() {
    $post         = $this->input->post();
    $eday         = isset($post['eday'])    ? $post['eday']     : time();
    $bday         = isset($post['bday'])    ? $post['bday']     : time() - 30*86400;
    $chart_id     = isset($post['chart_id'])? $post['chart_id'] : "chart_0_0_-1";
    $filter       = isset($post['filter'])  ? $post['filter']   : "{}";
    $chart_parts  = explode("_", $chart_id);
    $counter_id = -1;
    if(count($chart_parts)==4) {
      $counter_id = $chart_parts[3];
      $page_id  = $chart_parts[1];
      $position = $chart_parts[2];
    }
    $this->load->model('chartmodel','active_chart');
    $this->active_chart->init($counter_id,$bday,$eday,$filter);
    $answer = $this->active_chart->getData();
    
    $db = $this->statdb->db();    
    $view = $db->query("SELECT view_preset FROM page_view WHERE `page_id`='$page_id' and counter_id='".$counter_id."'");
    if(!$view)
      echo json_encode($answer);
    
    $view = $view->fetch_assoc();    
    foreach($answer['series'] as &$series)
      $series['type'] = $this->_type_to_text[$view['view_preset']];
    
    echo json_encode($answer);
  }
  
  public function ajax_change_chart_view() {
    $post         = $this->input->post();
    $chart_id     = isset($post['chart_id'])  ? $post['chart_id'] : false;
    $page_id      = isset($post['page_id'])   ? $post['page_id']  : false;
    $view         = isset($post['view'])      ? $post['view']     : -1;
    if(!$chart_id||!$page_id||($view==-1)){
      echo json_encode(array('error'=>'Data error'));
      return;
    }
    $db = $this->statdb->db();
    $result = $db->query("UPDATE page_view SET `view_preset`='$view' WHERE `page_id`=$page_id and `counter_id`=$chart_id;");
    if(!$result) {
      echo json_encode(array('error'=>'Query error '.$db->error));
      return;    
    }
    echo json_encode(array('success'=>'Ok!'));
  }
  
}
