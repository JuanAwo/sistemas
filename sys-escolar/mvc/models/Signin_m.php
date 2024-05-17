<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class signin_m extends MY_Model {
	function __construct() {
		parent::__construct();
		$this->load->model("setting_m");
		$this->load->model('usertype_m');
		$this->load->model('loginlog_m');
	}

	public function signin() {
		$returnArray = array(
			'return' => FALSE,
			'message' => ''
		);
		$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');

		$settings = $this->setting_m->get_setting(1);
		$lang = $settings->language;
		$defaultschoolyearID = $settings->school_year;
		$array = array();
		$i = 0;
		$username = $this->input->post('username');
		$password = $this->hash($this->input->post('password'));
		$userdata = '';
		foreach ($tables as $table) {
			$user = $this->db->get_where($table, array("username" => $username, "password" => $password));
			$alluserdata = $user->row();
			if(count($alluserdata)) {
				$userdata = $alluserdata;
				$array['permition'][$i] = 'yes';
				$array['usercolname'] = $table.'ID';
			} else {
				$array['permition'][$i] = 'no';
			}
			$i++;
		}

	/*	if(isset($settings->captcha_status) && $settings->captcha_status == 0) {
			$captchaResponse = $this->recaptcha->verifyResponse($this->input->post('g-recaptcha-response'));
		} else {
			$captchaResponse = array('success' => TRUE);
		}
*/
		/*if($captchaResponse['success'] == TRUE) {*/
			if(in_array('yes', $array['permition'])) {
				$usertype = $this->usertype_m->get_usertype($userdata->usertypeID);
				if(count($usertype)) {
					if($userdata->active == 1) {
						$data = array(
							"loginuserID" => isset($userdata->$array['usercolname']) && !is_null($userdata->$array['usercolname']) ? $userdata->$array['usercolname'] : 0,
							"name" => $userdata->name,
							"email" => $userdata->email,
							"usertypeID" => $userdata->usertypeID,
							'usertype' => $usertype->usertype,
							"username" => $userdata->username,
							"photo" => $userdata->photo,
							"lang" => $lang,
							"defaultschoolyearID" => $defaultschoolyearID,
							"loggedin" => TRUE
						);
						$browser = $this->getBrowser();

						$getPreviusData = $this->loginlog_m->get_single_loginlog(array('userID' => $userdata->$array['usercolname'], 'usertypeID' => $userdata->usertypeID, 'ip' => $this->getUserIP(), 'browser' => $browser['name'], 'logout' => NULL));

						if(count($getPreviusData)) {
							$lgoinLogUpdateArray = array(
								'logout' => ($getPreviusData->login+(60*5))
							);
							$this->loginlog_m->update_loginlog($lgoinLogUpdateArray, $getPreviusData->loginlogID);
						}

						
						$lgoinLog = array(
							'ip' => $this->getUserIP(),
							'browser' => $browser['name'],
							'operatingsystem' => $browser['platform'],
							'login' => strtotime(date('Ymdhis')),
							'usertypeID' => $userdata->usertypeID,
							'userID' => isset($userdata->$array['usercolname']) && !is_null($userdata->$array['usercolname']) ? $userdata->$array['usercolname'] : 0
						);

						$this->loginlog_m->insert_loginlog($lgoinLog);
						$this->session->set_userdata($data);

						$returnArray = array( 'return' => TRUE, 'message' => 'Bien');
					} else {
						$returnArray = array( 'return' => FALSE, 'message' => 'Esta cuenta esta bloqueada.');
					}
				} else {
					$returnArray = array( 'return' => FALSE, 'message' => 'Esta cuenta no esta registrada.');
				}
			} else {
				$returnArray = array( 'return' => FALSE, 'message' => 'Datos incorrectos.');
			}
		/*} else {
			$returnArray = array( 'return' => FALSE, 'message' => $captchaResponse['error-codes'][0]);
		}*/

		return $returnArray;
	}

	function getUserIP() {
	    $client  = @$_SERVER['HTTP_CLIENT_IP'];
	    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	    $remote  = $_SERVER['REMOTE_ADDR'];

	    if(filter_var($client, FILTER_VALIDATE_IP))
	    {
	        $ip = $client;
	    }
	    elseif(filter_var($forward, FILTER_VALIDATE_IP))
	    {
	        $ip = $forward;
	    }
	    else
	    {
	        $ip = ($remote == "::1" ? "127.0.0.1" : $remote) ;
	    }

	    return $ip;
	}

	
	public function getBrowser() {
	    $u_agent = $_SERVER['HTTP_USER_AGENT'];
	    $bname = 'Unknown';
	    $platform = 'Unknown';
	    $version= "";

	    //First get the platform?
	    if (preg_match('/linux/i', $u_agent)) {
	        $platform = 'linux';
	    }
	    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
	        $platform = 'mac';
	    }
	    elseif (preg_match('/windows|win32/i', $u_agent)) {
	        $platform = 'windows';
	    }

	    // Next get the name of the useragent yes seperately and for good reason
	    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
	    {
	        $bname = 'Internet Explorer';
	        $ub = "MSIE";
	    }
	    elseif(preg_match('/Firefox/i',$u_agent))
	    {
	        $bname = 'Mozilla Firefox';
	        $ub = "Firefox";
	    }
	    elseif(preg_match('/Chrome/i',$u_agent))
	    {
	        $bname = 'Google Chrome';
	        $ub = "Chrome";
	    }
	    elseif(preg_match('/Safari/i',$u_agent))
	    {
	        $bname = 'Apple Safari';
	        $ub = "Safari";
	    }
	    elseif(preg_match('/Opera/i',$u_agent))
	    {
	        $bname = 'Opera';
	        $ub = "Opera";
	    }
	    elseif(preg_match('/Netscape/i',$u_agent))
	    {
	        $bname = 'Netscape';
	        $ub = "Netscape";
	    }

	    // finally get the correct version number
	    $known = array('Version', $ub, 'other');
	    $pattern = '#(?<browser>' . join('|', $known) .
	    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
	    if (!preg_match_all($pattern, $u_agent, $matches)) {
	        // we have no matching number just continue
	    }

	    // see how many we have
	    $i = count($matches['browser']);
	    if ($i != 1) {
	        //we will have two since we are not using 'other' argument yet
	        //see if version is before or after the name
	        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
	            $version= $matches['version'][0];
	        }
	        else {
	            $version= $matches['version'][1];
	        }
	    }
	    else {
	        $version= $matches['version'][0];
	    }

	    // check if we have a number
	    if ($version==null || $version=="") {$version="?";}

	    return array(
	        'userAgent' => $u_agent,
	        'name'      => $bname,
	        'version'   => $version,
	        'platform'  => $platform,
	        'pattern'    => $pattern
	    );
	}

	function change_password() {

		$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');

		$username = $this->session->userdata("username");
		$old_password = $this->hash($this->input->post('old_password'));
		$new_password = $this->hash($this->input->post('new_password'));
		$getOrginalData = '';
		$getOrginalTable = '';
		foreach ($tables as $key => $table) {
			$user = $this->db->get_where($table, array("username" => $username, "password" => $old_password));
			$alluserdata = $user->row();
			if(count($alluserdata)) {
				$getOrginalData = $alluserdata;
				$getOrginalTable = $table;	
			} 
		}

		if(isset($getOrginalData->password) && ( $getOrginalData->password == $old_password)) {
			$array = array(
				"password" => $new_password
			);
			$this->db->where(array("username" => $username, "password" => $old_password));
			$this->db->update($getOrginalTable, $array);
			$this->session->set_flashdata('success', $this->lang->line('menu_success'));
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function signout() {
		$browser = $this->getBrowser();
		$getPreviusData = $this->loginlog_m->get_single_loginlog(array('userID' => $this->session->userdata('loginuserID'), 'usertypeID' => $this->session->userdata('usertypeID'), 'ip' => $this->getUserIP(), 'browser' => $browser['name'], 'logout' => NULL));

		if(count($getPreviusData)) {
			$lgoinLogUpdateArray = array(
				'logout' => strtotime(date('Ymdhis'))
			);
			$this->loginlog_m->update_loginlog($lgoinLogUpdateArray, $getPreviusData->loginlogID);
		}

		$this->session->sess_destroy();
	}

	public function loggedin() {
		return (bool) $this->session->userdata("loggedin");
	}
}
