<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends Admin_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('usertype_m');
		$this->load->model('section_m');
		$this->load->model("student_m");
		$this->load->model("parents_m");
		$this->load->model("teacher_m");
		$this->load->model("user_m");
		$this->load->model("systemadmin_m");
		$this->load->model('studentrelation_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('profile', $language);
	}

	public function index() {
		$usertypeID = $this->session->userdata("usertypeID");
		$username = $this->session->userdata('username');
		if($usertypeID == 1) {
			$this->data['user'] = $this->systemadmin_m->get_single_systemadmin(array('username' => $username));
		} elseif($usertypeID == 2) {
			$this->data['user'] = $this->teacher_m->get_single_teacher(array('username' => $username));
		} elseif($usertypeID == 3) {
			$this->data['user'] = $this->student_m->get_single_student(array('username' => $username));
			$this->data['section'] = pluck($this->section_m->get_section(), 'section', 'sectionID'); 
			$this->data['classes'] = pluck($this->classes_m->get_classes(), 'classes', 'classesID');
		} elseif($usertypeID == 4) {
			$this->data['user'] = $this->parents_m->get_single_parents(array("username" => $username));
		} else {
			$this->data['user'] = $this->user_m->get_single_user(array("username" => $username));
		}
		
		$this->data['usertypeID'] =$usertypeID;
		$this->data['usertype'] = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');
		if($this->data['user']) {
			$this->data["subview"] = "profile/index";
			$this->load->view('_layout_main', $this->data);
		} else {
			$this->data['subview'] ='error';
			$this->load->view('_layout_main', $this->data);
		}
		
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'name',
				'label' => $this->lang->line("profile_name"),
				'rules' => 'trim|required|xss_clean|max_length[60]'
			),
			array(
				'field' => 'dob',
				'label' => $this->lang->line("profile_dob"),
				'rules' => 'trim|max_length[10]|callback_date_valid|xss_clean'
			),
			array(
				'field' => 'sex',
				'label' => $this->lang->line("profile_sex"),
				'rules' => 'trim|required|max_length[10]|xss_clean'
			),
			array(
				'field' => 'phone',
				'label' => $this->lang->line("profile_phone"),
				'rules' => 'trim|max_length[25]|min_length[5]|xss_clean'
			),
			array(
				'field' => 'address',
				'label' => $this->lang->line("profile_address"),
				'rules' => 'trim|max_length[200]|xss_clean'
			),
			array(
				'field' => 'photo',
				'label' => $this->lang->line("profile_photo"),
				'rules' => 'trim|max_length[200]|xss_clean|callback_photoupload'
			),
			
			array(
				'field' => 'status',
				'label' => $this->lang->line("profile_status"),
				'rules' => 'trim|max_length[25]|xss_clean'
			),
			array(
				'field' => 'bloodgroup',
				'label' => $this->lang->line("profile_bloodgroup"),
				'rules' => 'trim|max_length[5]|xss_clean'
			),
			array(
				'field' => 'state',
				'label' => $this->lang->line("profile_state"),
				'rules' => 'trim|max_length[128]|xss_clean'
			),
			array(
				'field' => 'country',
				'label' => $this->lang->line("profile_country"),
				'rules' => 'trim|max_length[128]|xss_clean'
			),


			array(
				'field' => 'email',
				'label' => $this->lang->line("profile_email"),
				'rules' => 'trim|max_length[40]|valid_email|xss_clean|callback_unique_email'
			),
			array(
				'field' => 'email',
				'label' => $this->lang->line("profile_email"),
				'rules' => 'trim|required|max_length[40]|valid_email|xss_clean|callback_unique_email'
			),
			
			array(
				'field' => 'designation', 
				'label' => $this->lang->line("profile_designation"),
				'rules' => 'trim|required|max_length[128]|xss_clean'
			),
			array(
				'field' => 'father_name',
				'label' => $this->lang->line("profile_father_name"), 
				'rules' => 'trim|xss_clean|max_length[60]'
			),
			array(
				'field' => 'mother_name', 
				'label' => $this->lang->line("profile_mother_name"), 
				'rules' => 'trim|xss_clean|max_length[60]'
			),
			array(
				'field' => 'father_profession', 
				'label' => $this->lang->line("profile_father_name"), 
				'rules' => 'trim|xss_clean|max_length[40]'
			),
			array(
				'field' => 'mother_profession', 
				'label' => $this->lang->line("profile_mother_name"), 
				'rules' => 'trim|xss_clean|max_length[40]'
			),
		);
		return $rules;
	}

	public function photoupload() {
		$passUserData = array();
		$username = $this->session->userdata('username');
		if($username) {
			$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
			$array = array();
			$i = 0;
			foreach ($tables as $table) {
				$user = $this->student_m->get_single_username($table, array('username' => $username ));
				if(count($user)) {
					$this->form_validation->set_message("unique_email", "%s ya existe.");
					$passUserData = $user;
				}
			}
		}

		$new_file = "defualt.png";
		if($_FILES["photo"]['name'] !="") {
			$file_name = $_FILES["photo"]['name'];
			$random = rand(1, 10000000000000000);
	    	$makeRandom = hash('sha512', $random.rand(1, 10000000000000000) . config_item("encryption_key"));
			$file_name_rename = $makeRandom;
            $explode = explode('.', $file_name);
            if(count($explode) >= 2) {
	            $new_file = $file_name_rename.'.'.end($explode);
				$config['upload_path'] = "./uploads/images";
				$config['allowed_types'] = "gif|jpg|png";
				$config['file_name'] = $new_file;
				$config['max_size'] = '1024';
				$config['max_width'] = '3000';
				$config['max_height'] = '3000';
				$this->load->library('upload', $config);
				if(!$this->upload->do_upload("photo")) {
					$this->form_validation->set_message("photoupload", $this->upload->display_errors());
	     			return FALSE;
				} else {
					$this->upload_data['file'] =  $this->upload->data();
					return TRUE;
				}
			} else {
				$this->form_validation->set_message("photoupload", "Archivo no valido.");
	     		return FALSE;
			}
		} else {
			if(count($passUserData)) {
				$this->upload_data['file'] = array('file_name' => $passUserData->photo);
				return TRUE;
			} else {
				$this->upload_data['file'] = array('file_name' => $new_file);
				return TRUE;
			}
		}
	}

	public function edit() {
		// dump($this->session->userdata);
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/datepicker/datepicker.css',
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/datepicker/datepicker.js',
				'assets/select2/select2.js'
			)
		);

		$tableArray = array('1' => 'systemadmin', '2' => 'teacher', '3' => 'student', '4' => 'parents');
		if(!isset($tableArray[$this->session->userdata('usertypeID')])) {
			$tableArray[$this->session->userdata('usertypeID')] = 'user';
		}

		$rules = array();
		$usertypeID = $this->session->userdata('usertypeID');
		$username = $this->session->userdata('username');
		$this->data['usertypeID'] = $usertypeID;
		if($usertypeID == 1) {
			$rules = $this->rules();
			unset($rules[7], $rules[8], $rules[9], $rules[10], $rules[12], $rules[13], $rules[14], $rules[15], $rules[16]);
			$this->data['user'] = $this->systemadmin_m->get_single_systemadmin(array('username' => $username));
		} elseif($usertypeID == 2) {
			$rules = $this->rules();
			unset($rules[7], $rules[8], $rules[9], $rules[10], $rules[12], $rules[13], $rules[14], $rules[15], $rules[16]);
			$this->data['user'] = $this->teacher_m->get_single_teacher(array('username' => $username));
		} elseif($usertypeID == 3) {
			$rules = $this->rules();
			unset($rules[11], $rules[12], $rules[13], $rules[14], $rules[15], $rules[16]);
			$this->data['user'] = $this->student_m->get_single_student(array('username' => $username));
		} elseif($usertypeID == 4) {
			$rules = $this->rules();
			unset($rules[1], $rules[2], $rules[6], $rules[7], $rules[8], $rules[9], $rules[11], $rules[12]);
			$this->data['user'] = $this->parents_m->get_single_parents(array('username' => $username));
		} else {
			$rules = $this->rules();
			unset($rules[7], $rules[8], $rules[9], $rules[10], $rules[12], $rules[13], $rules[14], $rules[15], $rules[16]);
			$this->data['user'] = $this->user_m->get_single_user(array('username' => $username));
		}


		if($_POST) {
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$this->data["subview"] = "profile/edit";
				$this->load->view('_layout_main', $this->data);
			} else {
				$array = array();
				foreach ($rules as $rulekey => $rule) {
					if($rule['field'] == 'dob') {
						if($this->input->post($rule['field'])) {
							$array[$rule['field']] = date("Y-m-d", strtotime($this->input->post($rule['field'])));	
						}
					} else {
						$array[$rule['field']] = $this->input->post($rule['field']);
					}
				}


				if($usertypeID == 3) {
					$getRelationTableStudent = $this->studentrelation_m->get_single_studentrelation(array('srstudentID' => $this->data['user']->studentID));
					if(count($getRelationTableStudent)) {
						$this->student_m->profileRelationUpdate('studentrelation', array('srname' => $this->input->post('name')), $this->data['user']->studentID);
					}
				}

				$array['photo'] = $this->upload_data['file']['file_name'];
				
				$this->session->set_userdata(array('name' => $this->input->post('name'), 'email' => $this->input->post('email'), 'photo' => $array['photo']));

				$this->student_m->profileUpdate($tableArray[$usertypeID], $array, $username);
				redirect(base_url('profile/index'));
			}
		} else {
			$this->data['subview'] = 'profile/edit';
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function date_valid($date) {
		if($date) {
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
		return TRUE;
	}

	public function unique_email() {
		if($this->input->post('email')) {
			$username = $this->session->userdata('username');
			if($username) {
				$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
				$array = array();
				$i = 0;
				foreach ($tables as $table) {
					$user = $this->student_m->get_username($table, array("email" => $this->input->post('email'), 'username !=' => $username ));
					if(count($user)) {
						$this->form_validation->set_message("unique_email", "%s ya existe.");
						$array['permition'][$i] = 'no';
					} else {
						$array['permition'][$i] = 'yes';
					}
					$i++;
				}
				if(in_array('no', $array['permition'])) {
					return FALSE;
				} else {
					return TRUE;
				}
			}
		}
		return TRUE;
	}

	public function print_preview() {
		$usertypeID = $this->session->userdata("usertypeID");
		$username = $this->session->userdata('username');
		if($usertypeID == 1) {
			$this->data['user'] = $this->systemadmin_m->get_single_systemadmin(array('username' => $username));
		} elseif($usertypeID == 2) {
			$this->data['user'] = $this->teacher_m->get_single_teacher(array('username' => $username));
		} elseif($usertypeID == 3) {
			$this->data['user'] = $this->student_m->get_single_student(array('username' => $username));
			$this->data['section'] = pluck($this->section_m->get_section(), 'section', 'sectionID'); 
			$this->data['classes'] = pluck($this->classes_m->get_classes(), 'classes', 'classesID');
		} elseif($usertypeID == 4) {
			$this->data['user'] = $this->parents_m->get_single_parents(array("username" => $username));
		} else {
			$this->data['user'] = $this->user_m->get_single_user(array("username" => $username));
		}
		
		$this->data['usertypeID'] =$usertypeID;
		$this->data['usertype'] = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');
		if($this->data['user']) {
			$this->printview($this->data, 'profile/print_preview');
		} else {
			$this->data['subview'] ='error';
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function send_mail() {
		$usertypeID = $this->session->userdata("usertypeID");
		$username = $this->session->userdata('username');
		if($usertypeID == 1) {
			$this->data['user'] = $this->systemadmin_m->get_single_systemadmin(array('username' => $username));
		} elseif($usertypeID == 2) {
			$this->data['user'] = $this->teacher_m->get_single_teacher(array('username' => $username));
		} elseif($usertypeID == 3) {
			$this->data['user'] = $this->student_m->get_single_student(array('username' => $username));
			$this->data['section'] = pluck($this->section_m->get_section(), 'section', 'sectionID'); 
			$this->data['classes'] = pluck($this->classes_m->get_classes(), 'classes', 'classesID');
		} elseif($usertypeID == 4) {
			$this->data['user'] = $this->parents_m->get_single_parents(array("username" => $username));
		} else {
			$this->data['user'] = $this->user_m->get_single_user(array("username" => $username));
		}
		
		$this->data['usertypeID'] =$usertypeID;
		$this->data['usertype'] = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');
		if($this->data['user']) {
			$email = $this->input->post('to');
			$subject = $this->input->post('subject');
			$message = $this->input->post('message');
			$this->viewsendtomail($this->data, 'profile/print_preview', $email, $subject, $message);
		} else {
			$this->data['subview'] ='error';
			$this->load->view('_layout_main', $this->data);
		}

	}
}

/* End of file profile.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/profile.php */
