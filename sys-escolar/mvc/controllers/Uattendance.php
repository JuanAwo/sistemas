<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Uattendance extends Admin_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("usertype_m");
		$this->load->model("user_m");
		$this->load->model('uattendance_m');
		
		$language = $this->session->userdata('lang');
		$this->lang->load('uattendance', $language);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'date',
				'label' => $this->lang->line("attendance_date"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_date_valid|callback_valid_future_date'
			)
		);
		return $rules;
	}

	public function index() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/select2/select2.js'
			)
		);

		
		$this->data['users'] = $this->user_m->get_user_by_usertype();
		$this->data["subview"] = "uattendance/index";
		$this->load->view('_layout_main', $this->data);
	}

	public function add() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/datepicker/datepicker.css'
			),
			'js' => array(
				'assets/datepicker/datepicker.js'
			)
		);

		$this->data['date'] = date("d-m-Y");
		$schoolyearID = $this->data['siteinfos']->school_year;
		$this->data['users'] = array();
		$this->data['dateinfo'] = array();

		if($_POST) {
			$rules = $this->rules();

			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$this->data["subview"] = "uattendance/add";
				$this->load->view('_layout_main', $this->data);
			} else {
				$date = $this->input->post("date");
				$this->data['date'] = $date;
				$explode_date = explode("-", $date);
				$monthyear = $explode_date[1]."-".$explode_date[2];

				$last_day = cal_days_in_month(CAL_GREGORIAN, $explode_date[1], $explode_date[2]);
				if($last_day >= $explode_date[1]) {
					$users = $this->user_m->get_user_by_usertype();
					if(count($users)) {
						foreach ($users as $user) {
							$userID = $user->userID;
							$uattendance_monthyear = $this->uattendance_m->get_order_by_uattendance(array("userID" => $userID, "monthyear" => $monthyear));
							if(!count($uattendance_monthyear)) {
								$array = array(
									'schoolyearID' => $schoolyearID,
									"userID" => $userID,
									"usertypeID" => $user->usertypeID,
									"monthyear" => $monthyear
								);
								$this->uattendance_m->insert_uattendance($array);
							}
						}

						$this->data['dateinfo']['day'] = date('l', strtotime($date));
						$this->data['dateinfo']['date'] = date('jS F Y', strtotime($date));
						$this->data['users'] = $users;
						$this->data['uattendances'] = $this->uattendance_m->get_uattendance();
						$this->data['monthyear'] = $monthyear;
						$this->data['day'] = $explode_date[0];
					}
					$this->data["subview"] = "uattendance/add";
					$this->load->view('_layout_main', $this->data);
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}

			}
		} else {
			$this->data["subview"] = "uattendance/add";
			$this->load->view('_layout_main', $this->data);
		}

	}

	function singl_add() {
		$id = $this->input->post('id');
		$day = $this->input->post('day');
		$method = $this->input->post('method');
		$status = $this->input->post('status');

		if((int)$id && (int)$day && $method && $status) {
			$aday = "a".abs($day);

			if($status == "checked") {
				if($method == 'late') {
					$status = 'L';
				} else {
					$status = "P";
				}
			} elseif($status == "unchecked") {
				$status = "A";
			}

			if((int)$day && (int)$id) {
				$attendance_row = $this->uattendance_m->get_uattendance($id);
				if($attendance_row) {
					$this->uattendance_m->update_uattendance(array($aday => $status), $id);
					echo $this->lang->line('menu_success');
				}
			}

		}
	}

	function all_add() {
		$day = $this->input->post('day');
		$monthyear = $this->input->post('monthyear');
		$status = $this->input->post('status');
		$schoolyearID = $this->data['siteinfos']->school_year;
		$method = $this->input->post('method');

		if($status == "checked") {
			if($method == 'late') {
				$status = 'L';
			} else {
				$status = "P";
			}
		} elseif($status == "unchecked") {
			$status = "A";
		}

		if((int)$day) {
			$this->uattendance_m->update_uattendance_new("a".abs($day), $status, $schoolyearID, $monthyear);
			echo $this->lang->line('menu_success');
		}
	}

	public function view() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if ((int)$id) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$this->data["user"] = $this->user_m->get_user($id);
			if($this->data["user"]) {
				$this->data['usertypes'] = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID' );
				$this->data['uattendances'] = $this->uattendance_m->get_order_by_uattendance(array("userID" => $id, 'schoolyearID' => $schoolyearID));
				$this->data["subview"] = "uattendance/view";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}
	

	function date_valid($date) {
   		if(strlen($date) <10) {
			$this->form_validation->set_message("date_valid", "%s no es valido el formato dd-mm-yyyy");
	     	return FALSE;
		} else {
	   		$arr = explode("-", $date);
	        $dd = $arr[0];
	        $mm = $arr[1];
	        $yyyy = $arr[2];
	      	if(checkdate($mm, $dd, $yyyy)) {
	      		return TRUE;
	      	} else {
	      		$this->form_validation->set_message("date_valid", "%s no es valido el formato dd-mm-yyyy");
	     		return FALSE;
	      	}
	    }
	}

	public function print_preview() {
		if(permissionChecker('uattendance_view')) {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if ((int)$id) {
				$this->data["user"] = $this->user_m->get_single_user(array('userID' => $id));
				if($this->data["user"]) {
					$this->data['usertypes'] = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID' );
				    $this->data['panel_title'] = $this->lang->line('panel_title');
					$this->data['uattendances'] = $this->uattendance_m->get_order_by_uattendance(array("userID" => $id));
					$this->printView($this->data, 'uattendance/print_preview');
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "errorpermission";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "errorpermission";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function send_mail() {
		$id = $this->input->post('id');
		if ((int)$id) {
			$this->data["user"] = $this->user_m->get_user($id);
			if($this->data["user"]) {
				$this->data['usertypes'] = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID' );
				$this->data['uattendances'] = $this->uattendance_m->get_order_by_uattendance(array("userID" => $id));
				$email = $this->input->post('to');
				$subject = $this->input->post('subject');
				$message = $this->input->post('message');

				$this->viewsendtomail($this->data, 'uattendance/print_preview', $email, $subject, $message);
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	function valid_future_date($date) {
		$presentdate = date('Y-m-d');
		$date = date("Y-m-d", strtotime($date));
		if($date > $presentdate) {
			return FALSE;
		}
		return TRUE;
	}
}

/* End of file class.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/sattendance.php */
