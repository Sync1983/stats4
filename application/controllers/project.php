<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project extends CI_Controller {

	public function show() {
    $get = $this->input->get();    
    $tab = isset($get['tab'])?$get['tab']:false;
    $user = getActiveUser();
    $projects = $user->projects();
    
    $data = array();
    $data['base_url'] = base_url();    
    $this->load->view('_include/header',$data);
    
    $active_project = null;
    foreach ($projects as $project)
      if($project['id']==$get['id'])
        $active_project = $project;
      
    if(!$active_project) {    // Access to deprecated project
      $this->load->view('login/display');
      $this->load->view('_include/footer');
      return;
    }
    
    $db = $this->statdb->db();
    //load filters for project
    $filters = array();
    $db_filters = $db->query("SELECT name,data FROM filter WHERE project_id=".$active_project['id']);
    if($db_filters)
      $filters = $db_filters->fetch_all(MYSQLI_ASSOC);
    $charts = array();
    $db_charts = $db->query("SELECT id,name FROM logger_chart WHERE project_id=".$active_project['id']);
    if($db_charts)
      $charts = $db_charts->fetch_all(MYSQLI_ASSOC);
    $users = array();
    if($user->is_admin()) {
      $db_users = $db->query("SELECT id,login,name FROM member");
      if($db_users)
        $users = $db_users->fetch_all(MYSQLI_ASSOC);
    }
    //Construct top panel
    $this->load->view('_include/top_menu',array('filters'=>$filters,'charts'=>$charts,'isAdmin'=>$user->is_admin(),'users'=>$users));
    // load tabs for project
    $result = $db->query("SELECT id,name FROM pager WHERE project_id=".$active_project['id']." ORDER BY `id`");
    $pages = array();
    if($result)
      $pages = $result->fetch_all(MYSQLI_ASSOC);
    
    $tabs = array_values($pages);
    $default_tab = $tabs[0]['id'];
    // Construct main view
    $content = array();
    $content['title']           = $active_project['title'];
    $content['pages']           = $pages;
    $content['active_tab']      = $tab?$tab:$default_tab;
    $content['active_project']  = $active_project['id'];
    $this->load->view('project/display',$content);    
    
    $this->load->view('_include/footer');
	}
  
  public function ajax_load_tab() {
    $post         = $this->input->post();
    $project_id   = isset($post['project_id'])?$post['project_id']:-1;
    $page_id      = isset($post['page_id'])?$post['page_id']:-1;    
    $user         = getActiveUser();
    
    $db = $this->statdb->db();
    $charts = array();
    $result = $db->query("SELECT * FROM page_view WHERE page_id=$page_id");    
    if($result)
      $charts = $result->fetch_all(MYSQL_ASSOC);
    $answer = array();
    $answer['html'] = $this->load->view('project/load_tab',array('charts'=>$charts),true);    
    echo json_encode($answer);
  }
  
  public function ajax_add_tab() {
    $post         = $this->input->post();
    $project_id   = isset($post['project_id'])?$post['project_id']:false;
    $name         = isset($post['name'])?$post['name']:false;
    if(!$name||!$project_id) {
      echo json_encode(array('error'=>"Data error"));
      return;
    }
    $db = $this->statdb->db();    
    $user = getActiveUser();
    if(!$user->is_valid_project($project_id)) {
      echo json_encode(array('error'=>"Depricated"));
      return;
    }
    $result = $db->query("INSERT INTO pager (`project_id`,`member_id`,`name`) VALUES ('$project_id','".$user->get('id')."','$name');");   
    if(!$result) {
      echo json_encode(array('error'=>"Query error: ".$db->error));
      return;
    }
    echo json_encode(array('success'=>"Ok!"));    
  }
  
  public function ajax_remove_tab() {
    $post         = $this->input->post();
    $project_id   = isset($post['project_id'])?$post['project_id']:false;
    $page_id      = isset($post['page_id'])   ?$post['page_id']   :false;
    
    if(!$page_id||!$project_id) {
      echo json_encode(array('error'=>"Data error"));
      return;
    }
    $db = $this->statdb->db();    
    $user = getActiveUser();
    if(!$user->is_valid_project($project_id)) {
      echo json_encode(array('error'=>"Depricated"));
      return;
    }
    $result = $db->query("DELETE FROM pager WHERE project_id='$project_id' and id='$page_id';");   
    if(!$result) {
      echo json_encode(array('error'=>"Query error: ".$db->error));
      return;
    }
    echo json_encode(array('success'=>"Ok!"));    
  }
  
  public function ajax_rename_tab() {
    $post         = $this->input->post();
    $project_id   = isset($post['project_id'])?$post['project_id']:false;
    $page_id      = isset($post['page_id'])   ?$post['page_id']   :false;
    $name         = isset($post['name'])      ?$post['name']      :false;
    
    if(!$page_id||!$project_id||!$name) {
      echo json_encode(array('error'=>"Data error"));
      return;
    }
    $db = $this->statdb->db();    
    $user = getActiveUser();
    if(!$user->is_valid_project($project_id)) {
      echo json_encode(array('error'=>"Depricated"));
      return;
    }
    $result = $db->query("UPDATE pager SET name='$name' WHERE project_id='$project_id' and id='$page_id';");   
    if(!$result) {
      echo json_encode(array('error'=>"Query error: ".$db->error));
      return;
    }
    echo json_encode(array('success'=>"Ok!"));    
  }
  
  function ajax_append_chart(){
    $post         = $this->input->post();
    $project_id   = isset($post['project_id'])?$post['project_id']:false;
    $page_id      = isset($post['page_id'])   ?$post['page_id']   :false;
    $chart_id     = isset($post['chart_id'])  ?$post['chart_id']  :false;
    
    if(!$page_id||!$project_id||!$chart_id) {
      echo json_encode(array('error'=>"Data error"));
      return;
    }
    $db = $this->statdb->db();    
    $user = getActiveUser();
    if(!$user->is_valid_project($project_id)) {
      echo json_encode(array('error'=>"Depricated"));
      return;
    }
    $sql = "UPDATE IGNORE page_view SET `position`=".
      "( SELECT @chart_pos := @chart_pos + 1 FROM ".
      "( SELECT( @chart_pos := -1) as s ) ".
      " as new_pos )".
      "WHERE `page_id`=$page_id ORDER BY `position`";
    $renumerate = $db->query($sql);
    if(!$renumerate) {
      echo json_encode(array('error'=>"Renumerate query error: ".$db->error));
      return;
    }
    $sql = "INSERT INTO page_view VALUES ('$page_id',@chart_pos + 1,'1','$chart_id','0')";
    $insert = $db->query($sql);
    if(!$insert){
      echo json_encode(array('error'=>"Renumerate query error: ".$db->error));
      return;
    }
    echo json_encode(array('success'=>'Ok!'));
  }
  
}
