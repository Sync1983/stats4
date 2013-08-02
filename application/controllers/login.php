<?php

/**
 * Description of login 
 * @author user
 */
class login extends CI_Controller{
  
  const COOKIE_MEMBER_HASH = 'member_hash';
  
  public function ajax_login() {    
    $post = $this->input->post();
    $name = $post['login'];
    $pass = $post['password'];
    $remember = isset($post['remember'])?true:false;
    $hash = $this->_cryptPassword($pass);
    $db = $this->statdb->db();
    $sql = "SELECT * FROM member where login='$name' and hashed_password='$hash'";    
    /** @var mysqli_result */
    $answer = $db->query($sql);
    if($answer->num_rows!=1) {
      $err = array('error'=>'Wrong username or password!');
      $this->output->set_output(json_encode($err));
      return;
    }
    if($remember)
      setcookie (self::COOKIE_MEMBER_HASH,$hash,time()+30*86400);    //Active 30 days
    $rd = $this->statredis->redis();
    $rd->hset(User::USERS_KEY,$hash,  json_encode($answer->fetch_assoc()));    
    $_SESSION['hash'] = $hash;
    $this->output->set_output(json_encode(array('redirect'=>'/')));
  }
  
  public function logout() {    
    session_start();
    setcookie(self::COOKIE_MEMBER_HASH,'',time());    //Active 30 days
    $hash = $_SESSION['hash'];
    $_SESSION['hash'] = null;
    unset($_SESSION['hash']);
    $rd = $this->statredis->redis();
    $rd->hdel(User::USERS_KEY,$hash);
    $this->load->helper('url');    
    redirect("/");    
  }

  private function _cryptPassword($password){
    $sailt1 = "s(*&&n3яч"; $sailt2 = "LrfЛцЗ";
    return substr(md5($password . $sailt1), 5) . substr(sha1($password . $sailt2), 3, 20);
  }
  
}
