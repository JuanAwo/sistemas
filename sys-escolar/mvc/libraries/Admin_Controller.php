<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @property document_m $document_m
 * @property email_m $email_m
 * @property error_m $error_m
 */
class Admin_Controller extends MY_Controller {

	function __construct () {
		parent::__construct();

		$this->load->model("signin_m");
		$this->load->model("permission_m");
		$this->load->model("message_m");
		$this->load->model("site_m");
		$this->load->model("schoolyear_m");
		$this->data["siteinfos"] = $this->site_m->get_site(1);
		$this->data['topbarschoolyears'] = $this->schoolyear_m->get_order_by_schoolyear(array('schooltype' => $this->data["siteinfos"]->school_type));
		$this->load->library("session");
		$this->load->helper('language');
		$this->load->helper('date');
		$this->load->helper('form');
		$this->load->helper('traffic');
		$this->load->library('form_validation');
		$this->load->model('classes_m');
		$this->load->model("menu_m");


		$this->data["menuclasss"] = $this->classes_m->get_order_by_classes();
		$this->load->model("notice_m");
		$this->load->model("alert_m");
		$this->data['all'] = array();
		$this->data['alert'] = array();
		$notices = $this->notice_m->get_order_by_notice(array('schoolyearID' => $this->session->userdata('defaultschoolyearID')));
		$i = 0;
		if(count($notices) >0) {
			foreach ($notices as $notice) {
				$this->data['all'][] = $this->alert_m->get_order_by_alert(array("noticeID" => $notice->noticeID, "username" => $this->session->userdata("username")));
				if(count($this->data['all'][$i]) == 0) {
					$this->data['alert'][] = $notice;
				}
				$i++;
			}
		}
		$this->data['alert'];

		$this->data['sitesettings'] = array(
			'schooltype' => 'classbase'
		);

		$this->data['allcountry'] = $this->getAllCountry();
		$this->data['allbloodgroup'] = $this->getAllBloodgroup();

		$email = $this->session->userdata('email');
		$usertype = $this->session->userdata('usertype');
		$this->data['unread'] = $this->message_m->get_order_by_message(array('email' => $email, 'receiverType' => $usertype, 'to_status' => 0, 'read_status' => 0));
		

		$language = $this->session->userdata('lang');
		$this->lang->load('topbar_menu', $language);

		$exception_uris = array(
			"signin/index",
			"signin/signout"
		);

		if(in_array(uri_string(), $exception_uris) == FALSE) {
			if($this->signin_m->loggedin() == FALSE) {
				redirect(base_url("signin/index"));
			}
		}



		$module = $this->uri->segment(1);
		$action = $this->uri->segment(2);
		$permission = '';

		if($action == 'index' || $action == false) {
			$permission = $module;
		} else {
			$permission = $module.'_'.$action;
		}

		$permissionset = array();
		$userdata = $this->session->userdata;

		if($this->session->userdata('usertypeID') == 1 && $this->session->userdata('loginuserID') == 1) {
			if(isset($userdata['loginuserID']) && !isset($userdata['get_permission'])) {

				$allmodules = $this->permission_m->get_permission();

				if(count($allmodules)) {
					foreach ($allmodules as $key => $allmodule) {
						$permissionset['master_permission_set'][trim($allmodule->name)] = $allmodule->active;
						if($allmodule->name == 'report') {
							$permissionset['master_permission_set'][trim($allmodule->name)] = $allmodule->active;
							$permissionset['master_permission_set']['report/studentreport'] = $allmodule->active;
							$permissionset['master_permission_set']['report/classreport'] = $allmodule->active;
							$permissionset['master_permission_set']['report/attendancereport'] = $allmodule->active;
							$permissionset['master_permission_set']['report/certificate'] = $allmodule->active;
						}
					}
					$permissionset['master_permission_set']['take_exam'] = 'yes';
					$data = ['get_permission' => TRUE];
					$this->session->set_userdata($data);
					$this->session->set_userdata($permissionset);
				}
			}
		} else {
			if(isset($userdata['loginuserID']) && !isset($userdata['get_permission'])) {
				if(!$this->session->userdata($permission)) {
					$user_permission = $this->permission_m->get_modules_with_permission($userdata['usertypeID']);

					foreach ($user_permission as $value) {
						$permissionset['master_permission_set'][$value->name] = $value->active;
						if($value->name == 'report') {
							$permissionset['master_permission_set'][$value->name] = $value->active;
							$permissionset['master_permission_set']['report/studentreport'] = $value->active;
							$permissionset['master_permission_set']['report/classreport'] = $value->active;
							$permissionset['master_permission_set']['report/attendancereport'] = $value->active;
							$permissionset['master_permission_set']['report/certificate'] = $value->active;
						}
					}

					if($userdata['usertypeID'] == 3) {
						$permissionset['master_permission_set']['take_exam'] = 'yes';
					}
					
					$data = ['get_permission' => TRUE];
					$this->session->set_userdata($data);
					$this->session->set_userdata($permissionset);
				}
			}
		}


		$sessionPermission = $this->session->userdata('master_permission_set');

		$dbMenus	= $this->menuTree(json_decode(json_encode(pluck($this->menu_m->get_order_by_menu(['status' => 1]), 'obj', 'menuID')), true) , $sessionPermission);
		$this->data["dbMenus"] = $dbMenus;

		if((isset($sessionPermission[$permission]) && $sessionPermission[$permission] == "no") ) {
			if($permission == 'dashboard' && $sessionPermission[$permission] == "no") {
				$url = 'exceptionpage/index';
				if(in_array('yes', $sessionPermission)) {
			    	if($sessionPermission["dashboard"] == 'no') {
			    		foreach ($sessionPermission as $key => $value) {
			    			if($value == 'yes') {
			    				$url = $key;
			    				break;
			    			}
			    		}
			    	}
		    	} else {
		    		redirect(base_url('exceptionpage/index'));
		    	}
		    	redirect(base_url($url));
			} else {
				redirect(base_url('exceptionpage/error'));
			}
		}
	}



	public function usercreatemail($email=NULL, $username=NULL, $password=NULL) {
		$this->load->library('email');
		$this->email->set_mailtype("html");
		$this->data["siteinfos"] = $this->site_m->get_site(1);
	    if($email) {
	        $this->email->from($this->data['siteinfos']->email, $this->data['siteinfos']->sname);
	        $this->email->to($email);
	        $this->email->subject($this->data['siteinfos']->sname);
	        $url = base_url();
	        $message = "<h2>Welcome to ".$this->data['siteinfos']->sname."</h2>
	        <p>Please log-in to this website and change the password as soon as possible </p>
	        <p>Website : ".$url."</p>
	        <p>Username: ".$username."</p>
	        <p>Password: ".$password."</p>
	        <br>
	        <p>Once again, thank you for choosing ".$this->data['siteinfos']->sname."</p>
	        <p>Best Wishes,</p>
	        <p>The ".$this->data['siteinfos']->sname." Team</p>";
	        $this->email->message($message);
	        $this->email->send();
	    }
	}

	public function viewsendtomail($data=NULL, $viewpath= NULL, $email=NULL, $subject=NULL, $message=NULL, $pagesize = 'a4', $pagetype='portrait') {
		$this->load->library('email');
		$this->load->library('mhtml2pdf');
	    $this->mhtml2pdf->folder('uploads/report/');
	    $rand = rand(1, 99999999999999999999999) . date('y-m-d h:i:s');
	    $sharand = hash('sha512', $rand);

	    $this->mhtml2pdf->filename($sharand);
	    $this->data['panel_title'] = $this->lang->line('panel_title');
		$html = $this->load->view($viewpath, $this->data, true);
		$this->mhtml2pdf->html($html);

		if($path = $this->mhtml2pdf->create('save')) {
			$this->email->set_mailtype("html");
			$this->email->from($this->data["siteinfos"]->email, $this->data['siteinfos']->sname);
			$this->email->to($email);
			$this->email->subject($subject);
			$this->email->message($message);
			$this->email->attach($path);
			if($this->email->send()) {
				$this->session->set_flashdata('success', $this->lang->line('mail_success'));
			} else {
				$this->session->set_flashdata('error', $this->lang->line('mail_error'));
			}
		}
	}

	function printview($data=NULL, $viewpath= NULL, $mode = 'view', $pagesize = 'a4', $pagetype='portrait') {
		$this->data['panel_title'] = $this->lang->line('panel_title');
		$html = $this->load->view($viewpath, $this->data, true);

		$this->load->library('mhtml2pdf');

		$this->mhtml2pdf->folder('uploads/report/');
		$this->mhtml2pdf->filename('Report');

		$this->mhtml2pdf->html($html);
		return $this->mhtml2pdf->create($mode);
	}

	public function getAllCountry() {
		$country = array(
			"AF" => "Afghanistan",
			"AL" => "Albania",
			"DZ" => "Algeria",
			"AS" => "American Samoa",
			"AD" => "Andorra",
			"AO" => "Angola",
			"AI" => "Anguilla",
			"AQ" => "Antarctica",
			"AG" => "Antigua and Barbuda",
			"AR" => "Argentina",
			"AM" => "Armenia",
			"AW" => "Aruba",
			"AU" => "Australia",
			"AT" => "Austria",
			"AZ" => "Azerbaijan",
			"BS" => "Bahamas",
			"BH" => "Bahrain",
			"BD" => "Bangladesh",
			"BB" => "Barbados",
			"BY" => "Belarus",
			"BE" => "Belgium",
			"BZ" => "Belize",
			"BJ" => "Benin",
			"BM" => "Bermuda",
			"BT" => "Bhutan",
			"BO" => "Bolivia",
			"BA" => "Bosnia and Herzegovina",
			"BW" => "Botswana",
			"BV" => "Bouvet Island",
			"BR" => "Brazil",
			"BQ" => "British Antarctic Territory",
			"IO" => "British Indian Ocean Territory",
			"VG" => "British Virgin Islands",
			"BN" => "Brunei",
			"BG" => "Bulgaria",
			"BF" => "Burkina Faso",
			"BI" => "Burundi",
			"KH" => "Cambodia",
			"CM" => "Cameroon",
			"CA" => "Canada",
			"CT" => "Canton and Enderbury Islands",
			"CV" => "Cape Verde",
			"KY" => "Cayman Islands",
			"CF" => "Central African Republic",
			"TD" => "Chad",
			"CL" => "Chile",
			"CN" => "China",
			"CX" => "Christmas Island",
			"CC" => "Cocos [Keeling] Islands",
			"CO" => "Colombia",
			"KM" => "Comoros",
			"CG" => "Congo - Brazzaville",
			"CD" => "Congo - Kinshasa",
			"CK" => "Cook Islands",
			"CR" => "Costa Rica",
			"HR" => "Croatia",
			"CU" => "Cuba",
			"CY" => "Cyprus",
			"CZ" => "Czech Republic",
			"CI" => "Côte d’Ivoire",
			"DK" => "Denmark",
			"DJ" => "Djibouti",
			"DM" => "Dominica",
			"DO" => "Dominican Republic",
			"NQ" => "Dronning Maud Land",
			"DD" => "East Germany",
			"EC" => "Ecuador",
			"EG" => "Egypt",
			"SV" => "El Salvador",
			"GQ" => "Equatorial Guinea",
			"ER" => "Eritrea",
			"EE" => "Estonia",
			"ET" => "Ethiopia",
			"FK" => "Falkland Islands",
			"FO" => "Faroe Islands",
			"FJ" => "Fiji",
			"FI" => "Finland",
			"FR" => "France",
			"GF" => "French Guiana",
			"PF" => "French Polynesia",
			"TF" => "French Southern Territories",
			"FQ" => "French Southern and Antarctic Territories",
			"GA" => "Gabon",
			"GM" => "Gambia",
			"GE" => "Georgia",
			"DE" => "Germany",
			"GH" => "Ghana",
			"GI" => "Gibraltar",
			"GR" => "Greece",
			"GL" => "Greenland",
			"GD" => "Grenada",
			"GP" => "Guadeloupe",
			"GU" => "Guam",
			"GT" => "Guatemala",
			"GG" => "Guernsey",
			"GN" => "Guinea",
			"GW" => "Guinea-Bissau",
			"GY" => "Guyana",
			"HT" => "Haiti",
			"HM" => "Heard Island and McDonald Islands",
			"HN" => "Honduras",
			"HK" => "Hong Kong SAR China",
			"HU" => "Hungary",
			"IS" => "Iceland",
			"IN" => "India",
			"ID" => "Indonesia",
			"IR" => "Iran",
			"IQ" => "Iraq",
			"IE" => "Ireland",
			"IM" => "Isle of Man",
			"IL" => "Israel",
			"IT" => "Italy",
			"JM" => "Jamaica",
			"JP" => "Japan",
			"JE" => "Jersey",
			"JT" => "Johnston Island",
			"JO" => "Jordan",
			"KZ" => "Kazakhstan",
			"KE" => "Kenya",
			"KI" => "Kiribati",
			"KW" => "Kuwait",
			"KG" => "Kyrgyzstan",
			"LA" => "Laos",
			"LV" => "Latvia",
			"LB" => "Lebanon",
			"LS" => "Lesotho",
			"LR" => "Liberia",
			"LY" => "Libya",
			"LI" => "Liechtenstein",
			"LT" => "Lithuania",
			"LU" => "Luxembourg",
			"MO" => "Macau SAR China",
			"MK" => "Macedonia",
			"MG" => "Madagascar",
			"MW" => "Malawi",
			"MY" => "Malaysia",
			"MV" => "Maldives",
			"ML" => "Mali",
			"MT" => "Malta",
			"MH" => "Marshall Islands",
			"MQ" => "Martinique",
			"MR" => "Mauritania",
			"MU" => "Mauritius",
			"YT" => "Mayotte",
			"FX" => "Metropolitan France",
			"MX" => "Mexico",
			"FM" => "Micronesia",
			"MI" => "Midway Islands",
			"MD" => "Moldova",
			"MC" => "Monaco",
			"MN" => "Mongolia",
			"ME" => "Montenegro",
			"MS" => "Montserrat",
			"MA" => "Morocco",
			"MZ" => "Mozambique",
			"MM" => "Myanmar [Burma]",
			"NA" => "Namibia",
			"NR" => "Nauru",
			"NP" => "Nepal",
			"NL" => "Netherlands",
			"AN" => "Netherlands Antilles",
			"NT" => "Neutral Zone",
			"NC" => "New Caledonia",
			"NZ" => "New Zealand",
			"NI" => "Nicaragua",
			"NE" => "Niger",
			"NG" => "Nigeria",
			"NU" => "Niue",
			"NF" => "Norfolk Island",
			"KP" => "North Korea",
			"VD" => "North Vietnam",
			"MP" => "Northern Mariana Islands",
			"NO" => "Norway",
			"OM" => "Oman",
			"PC" => "Pacific Islands Trust Territory",
			"PK" => "Pakistan",
			"PW" => "Palau",
			"PS" => "Palestinian Territories",
			"PA" => "Panama",
			"PZ" => "Panama Canal Zone",
			"PG" => "Papua New Guinea",
			"PY" => "Paraguay",
			"YD" => "People's Democratic Republic of Yemen",
			"PE" => "Peru",
			"PH" => "Philippines",
			"PN" => "Pitcairn Islands",
			"PL" => "Poland",
			"PT" => "Portugal",
			"PR" => "Puerto Rico",
			"QA" => "Qatar",
			"RO" => "Romania",
			"RU" => "Russia",
			"RW" => "Rwanda",
			"RE" => "Réunion",
			"BL" => "Saint Barthélemy",
			"SH" => "Saint Helena",
			"KN" => "Saint Kitts and Nevis",
			"LC" => "Saint Lucia",
			"MF" => "Saint Martin",
			"PM" => "Saint Pierre and Miquelon",
			"VC" => "Saint Vincent and the Grenadines",
			"WS" => "Samoa",
			"SM" => "San Marino",
			"SA" => "Saudi Arabia",
			"SN" => "Senegal",
			"RS" => "Serbia",
			"CS" => "Serbia and Montenegro",
			"SC" => "Seychelles",
			"SL" => "Sierra Leone",
			"SG" => "Singapore",
			"SK" => "Slovakia",
			"SI" => "Slovenia",
			"SB" => "Solomon Islands",
			"SO" => "Somalia",
			"ZA" => "South Africa",
			"GS" => "South Georgia and the South Sandwich Islands",
			"KR" => "South Korea",
			"ES" => "Spain",
			"LK" => "Sri Lanka",
			"SD" => "Sudan",
			"SR" => "Suriname",
			"SJ" => "Svalbard and Jan Mayen",
			"SZ" => "Swaziland",
			"SE" => "Sweden",
			"CH" => "Switzerland",
			"SY" => "Syria",
			"ST" => "São Tomé and Príncipe",
			"TW" => "Taiwan",
			"TJ" => "Tajikistan",
			"TZ" => "Tanzania",
			"TH" => "Thailand",
			"TL" => "Timor-Leste",
			"TG" => "Togo",
			"TK" => "Tokelau",
			"TO" => "Tonga",
			"TT" => "Trinidad and Tobago",
			"TN" => "Tunisia",
			"TR" => "Turkey",
			"TM" => "Turkmenistan",
			"TC" => "Turks and Caicos Islands",
			"TV" => "Tuvalu",
			"UM" => "U.S. Minor Outlying Islands",
			"PU" => "U.S. Miscellaneous Pacific Islands",
			"VI" => "U.S. Virgin Islands",
			"UG" => "Uganda",
			"UA" => "Ukraine",
			"SU" => "Union of Soviet Socialist Republics",
			"AE" => "United Arab Emirates",
			"GB" => "United Kingdom",
			"US" => "United States",
			"ZZ" => "Unknown or Invalid Region",
			"UY" => "Uruguay",
			"UZ" => "Uzbekistan",
			"VU" => "Vanuatu",
			"VA" => "Vatican City",
			"VE" => "Venezuela",
			"VN" => "Vietnam",
			"WK" => "Wake Island",
			"WF" => "Wallis and Futuna",
			"EH" => "Western Sahara",
			"YE" => "Yemen",
			"ZM" => "Zambia",
			"ZW" => "Zimbabwe",
			"AX" => "Åland Islands",
			);
		return $country;
	}

	function getAllBloodgroup() {
		$bloodgroup = array(
			'A+' => 'A+',
            'A-' => 'A-',
            'B+' => 'B+',
            'B-' => 'B-',
            'O+' => 'O+',
            'O-' => 'O-',
            'AB+' => 'AB+',
            'AB-' => 'AB-'
        );
        return $bloodgroup;
	}

	public function menuTree($dataset, $sessionPermission) {
    	$tree = array();
    	foreach ($dataset as $id=>&$node) {
			// echo $node['menuName']." => ";
			// echo (isset($sessionPermission[$node['link']]) && $sessionPermission[$node['link']] != "no");
			// echo "<br>";
			if($node['link'] == '#' || (isset($sessionPermission[$node['link']]) && $sessionPermission[$node['link']] != "no") ) {
	    		if ($node['parentID'] == 0) {
	    			$tree[$id]=&$node;
	    		} else {
					if (!isset($dataset[$node['parentID']]['child']))
						$dataset[$node['parentID']]['child'] = array();

					$dataset[$node['parentID']]['child'][$id] = &$node;
	    		}
			}
    	}
    	return $tree;
    }
}

