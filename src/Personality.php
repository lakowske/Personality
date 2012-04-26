<?
require_once('site_config.php');
require_once('Layout.php');


class Personality extends Layout
{

  function Personality($site_name, $op) {
    Layout::Layout($site_name);

    $HTTP_POST_VARS = $GLOBALS['HPV'];    

    if(array_key_exists('user', $_SESSION)) {
      error_log("User Logged In!");
      $u = unserialize($_SESSION['user']);
    } else {
      error_log("User not logged in.");
      $u = 0;
    }
    
    $this->add_left_col(new Template('links.tpl'));
    
    if($u && $u->admin()) {
      $this->add_left_col(new Template('admin.tpl'));
    }
    
    if(strcmp($op, 'about') == 0) {
      $this->add_cen_col(new Template('about.tpl'));
    }

    if(strcmp($op, 'resume') == 0) {
      $this->add_cen_col(new Template('resume.tpl'));
    }

    if(strcmp($op, 'code') == 0) {
       $this->show_children(1, 'code');
    }
    
    if(strcmp($op, "show_login") == 0) {
      $this->add_left_col(new Template('login.tpl'));
      $op = NULL;
    }

    //Login user.
    if(strcmp($op, 'login') == 0) {
      error_log("about to login\n");
      $username = $HTTP_POST_VARS['username'];
      $password = $HTTP_POST_VARS['password'];

      $u = new User();
      if($u->login($username, $password)) {
        error_log("$username login successful");
        $_SESSION['user'] = serialize($u);
      } else {
        error_log("$username login unsuccessful");        
        unset($_SESSION['user']);
        $u = 0;
        error_log("error logging in please try again\n");
      }
      $op = NULL;
      $this->go_home();
    }
    
    //Logout user.
    if(strcmp($op, 'logout') == 0 && $u) {
      $u->logout();
      $u = 0;
      $op = NULL;
    }
    
    if(strcmp($op, "add_entry") == 0) {
      $rcid = $_REQUEST['reply_cid'];
      $re = new Entry($rcid);
      $this->add_cen_col($re);
      $t = new Template('add_entry.tpl');
      $t->add_variable('rcid', $rcid);
      $this->add_cen_col($t);
    }

    if(strcmp($op, "post_entry") == 0) {    
      $e = new Entry();

      if($HTTP_POST_VARS['rcid'] == 1) {
        if($u && $u->admin()) {
          $e->rcid = 1;
        } else {
          $op = NULL;
          break;
        }
      } else {
        $e->rcid = $HTTP_POST_VARS['rcid'];
      }
      $e->rsid = 1;

      $e->contents = $HTTP_POST_VARS['content'];
      $e->title = $HTTP_POST_VARS['title'];
      $e->username = $HTTP_POST_VARS['username'];
      $e->type = $HTTP_POST_VARS['type'];
      
      if($u && $u->admin()) {
        $e->uid = 1;
      } else {
        $e->uid = 2;
        if(strcmp($e->username, 'seth') == 0) {
          $e->username = 'Anonymous Wimp';
        }
      }


      
      if(strcmp($HTTP_POST_VARS['submit'], "Preview") == 0) {
        $e->add_variables();
        $this->add_cen_col($e);
        $t = new Template('blank.tpl');
        $t->add_variable('html', "This is a preview of your comment, "
                         . "use the your browsers back button to revise your comment");
        $this->add_cen_col($t);

      } else {
        if($HTTP_POST_VARS['cid'] != NULL && $u && $u->admin()) {
          $old_e = new Entry($HTTP_POST_VARS['cid']);
          $old_e->contents = $e->contents;
          $old_e->title    = $e->title;
          $old_e->type     = $e->type;
          $old_e->update_entry();
        } else {
          $e->store_entry();
        }
        $op=NULL;                  
      }
    }
    
    if(strcmp($op, "edit_entry") == 0 && $u && $u->admin()) {
      $cid = $_REQUEST['cid'];
      $e = new Entry($cid);
      $edit_e = new EditEntry($cid);
      $this->add_cen_col($e);
      $this->add_cen_col($edit_e);
    }

    if($op == "delete_entry" && $u && $u->admin()) {
      $cid = $_REQUEST['cid'];
      $e = new Entry($cid);
      error_log("deleting comment");
      $e->delete_entry();
      $op = NULL;
    } else if($op == "delete_entry") {
      error_log("deleting comment");
    }

    
    if(strcmp($op, "view_children") == 0) {
      $this->show_children($_REQUEST['cid']);
    }
    
    if($op == NULL) {
      error_log("op is NULL");
      $this->show_children();
    }
    
    $this->load_variables();
  }

  function go_home() {
    echo "<meta http-equiv=\"Refresh\" CONTENT=\"0; URL=index.php\">";
  }

  function show_children($cid = 1, $type = 'comment') {
    $d = new Database();    
    $r = $d->query("select cid from comment where rcid = '$cid' "
                   . "and type = '$type' "
                   . "order by cid desc");
    while($row = $d->fetch_row()) {
      $e = new Entry($row[0]);
      $this->add_cen_col($e);      
    }
  }
}
     
