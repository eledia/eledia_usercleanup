<?php
class block_eledia_usercleanup extends block_base {

    function init() {
        $this->title   = get_string('title', 'block_eledia_usercleanup');
        $this->version = 2012021400;//Format yyyymmddvv
        $this->cron = 1;
    }

    function applicable_formats() {
        return array('site'=>true);
    }
    
    function get_content() {
        global $USER, $CFG, $COURSE;
        if ($this->content !== NULL) {
            return $this->content;
        }
	
        $this->content =  new object();
        $this->content->text = '';
        $this->content->footer = '';
    
        if(has_capability('moodle/site:config', get_context_instance(CONTEXT_SYSTEM))) {

            $this->content->text .= '<ul>';
            $this->content->text .= '<li>';
            $this->content->text .= '<a href="'.$CFG->wwwroot.'/blocks/eledia_usercleanup/config_usercleanup.php" >';
            $this->content->text .= get_string('el_header', 'block_eledia_usercleanup');
            $this->content->text .= '</a>';
            $this->content->text .= '</li>';
            $this->content->text .= '</ul>';
        }
        return $this->content;
    }

    function cron() {
        global $CFG, $DB;
        error_reporting(E_ALL);
        
        require_once("$CFG->dirroot/local/eledialib/lib.php");

        if(!isset($CFG->eledia_cleanup_active)){
            set_config('eledia_cleanup_active', '0');
        }
        if($CFG->eledia_cleanup_active){
            if(!isset($CFG->eledia_deleteinactiveuserinterval)){
                set_config('eledia_deleteinactiveuserinterval', '7');
            }
            if(!isset($CFG->eledia_informinactiveuserafter)){
                set_config('eledia_informinactiveuserafter', '120');
            }
            if(!isset($CFG->eledia_deleteinactiveuserafter)){
                set_config('eledia_deleteinactiveuserafter', '120');
            }

            if(isset($CFG->eledia_usercleanuplastrun)){//check if the script was called befor & set timediff between now and last run
                $lastrundiff = time()-$CFG->eledia_usercleanuplastrun;
                echo"\nlast usercleanup plugin was ".date('H:i:s d.m.Y', $CFG->eledia_usercleanuplastrun);
            }else{
                $lastrundiff = ((int)($CFG->eledia_deleteinactiveuserinterval)*24*60*60)+1;
                echo"\nfirst run of usercleanup plugin";

            }
            $today = time();

            if($lastrundiff > ((int)($CFG->eledia_deleteinactiveuserinterval)*24*60*60)){//check if interval is over
                $informexpired = time() - (((int)$CFG->eledia_informinactiveuserafter)*24*60*60);
                $eledia = new eledia_lib();

                //get inactice user
                $not_to_check_user = $CFG->siteguest;
                $admins = $eledia->get_site_admins();//get_users_by_global_role('admin');
                foreach ($admins as $adm) {
                    $not_to_check_user .= ', '.$adm->id;
                }                
                $sql = "deleted = 0
                        AND confirmed = 1 AND id NOT IN($not_to_check_user)
                        AND ((lastaccess < $informexpired AND lastaccess > 0)
                            OR (lastaccess = 0 AND firstaccess < $informexpired AND firstaccess > 0)
                            OR (auth = 'manual' AND firstaccess = 0 AND lastaccess = 0  AND timemodified > 0 AND timemodified < $informexpired))";
                $informuserlist = $DB->get_records_select('user', $sql, null,'','id, email, lang, firstname, lastname, currentlogin, username, lastaccess');
                echo "\ninactive user found: ".count($informuserlist);
                
                //get user which already get mails
                $informeduser = $DB->get_records('eledia_usercleanup');
                if($informeduser){
                    //remove user which are active from table
                    foreach ($informeduser as $iuser) {
                        if(!array_key_exists($iuser->user, $informuserlist)){
                            $DB->delete_records('eledia_usercleanup', array('user' => $iuser->user));
                            echo "\n $iuser->user active again, deletet from user cleanup table";
                        }
                    }
                }else{
                    $informeduser = Array();
                    echo"\ninformuserlist leer";
                }

                //setting index to user id
                $informeduser2 = array();
                foreach ($informeduser as $key => $infuser) {
                    $informeduser2[$infuser->user] = $infuser;
                }
                $informeduser = $informeduser2;

                //mail->
                echo "\nuser to mail timestamp $informexpired";
                if($informuserlist){
                    foreach ($informuserlist as $informuser) {
                        if(!array_key_exists($informuser->id, $informeduser)){//no mail when already send one

                            $user =new object();
                            $user->lang        = $informuser->lang;
                            $user->email        = $informuser->email;
                            $user->mailformat = 1;  // Always send HTML version as well

                            $site = get_site();
                            $supportuser = generate_email_supportuser();

                            $data = new object();
                            $data->userinactivedays = $CFG->eledia_informinactiveuserafter;
                            $data->eledia_deleteinactiveuserafter = $CFG->eledia_deleteinactiveuserafter;
                            $data->firstname = $informuser->firstname;
                            $data->lastname = $informuser->lastname;
                            $data->sitename = format_string($site->fullname);
                            $data->admin = generate_email_signoff();
                            $data->link = $CFG->wwwroot .'/index.php';

                            $subject = get_string('email_subject', 'block_eledia_usercleanup', $data);

                            $message     = get_string('email_message', 'block_eledia_usercleanup', $data);
                            $messagehtml = text_to_html(get_string('email_message', 'block_eledia_usercleanup', $data), false, false, true);

                            echo "\ntry mail to user $informuser->username and mail: $informuser->email";
                            email_to_user($user, $supportuser, $subject, $message, $messagehtml);

                            //save mailed user
                            $saveuserinfo = new object();
                            $saveuserinfo->user = $informuser->id;
                            $saveuserinfo->mailedto = $informuser->email;
                            $saveuserinfo->timestamp = time();
                            $DB->insert_record('eledia_usercleanup', $saveuserinfo);
                        }
                    }
                }

               //delete users->
               $deleteexpired = ((int)$CFG->eledia_deleteinactiveuserafter)*24*60*60;
               $deleteuserids = $DB->get_records_select('eledia_usercleanup', "(timestamp + $deleteexpired) < $today AND user > 2", null,'','user');

               if($deleteuserids){
                    $deleteuserstring = false;
                    foreach ($deleteuserids as $u) {
                        if(!$deleteuserstring){
                            $deleteuserstring = "($u->user";
                        }else{
                            $deleteuserstring .= ", $u->user";
                        }
                    }
                    $deleteuserstring .= ')';

                    $deleteuserlist = $DB->get_records_select('user', "id IN $deleteuserstring AND deleted = 0 AND confirmed = 1");
               }

                if(isset($deleteuserlist)){
                    foreach ($deleteuserlist as $deleteuser) {
                        delete_user($deleteuser);
                        $DB->delete_records('eledia_usercleanup', array('user' => $deleteuser->id));
                        echo "\ndeleting inactive user $deleteuser->username";
                    }
                }
                set_config('eledia_usercleanuplastrun', time());//set lastrun in config table
                echo "\nsetlastrunto: ".time()."\n";
            }
        }
    }
}
