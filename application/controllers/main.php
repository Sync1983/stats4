<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {
  
  public function show() {        
    $data = array();
    $data['base_url'] = base_url();
    $this->load->view('_include/header',$data);
    $this->load->view('_include/empty_menu');
    
    $user = getActiveUser();
    
    if((!$user)||(!$user->is_valid())) {    
      
      $this->load->view('login/display');
      $this->load->view('_include/footer');
      return;
    }      
    
    $data['projects'] = $user->projects();
    $data['isAdmin'] = $user->is_admin();    
    $this->load->view('main/display',$data);    
    $this->load->view('_include/footer');
  }
  
  
}

