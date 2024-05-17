<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Teacher extends Admin_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("teacher_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('teacher', $language);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'name',
				'label' => $this->lang->line("teacher_name"),
				'rules' => 'trim|required|xss_clean|max_length[60]'
			),
			array(
				'field' => 'designation',
				'label' => $this->lang->line("teacher_designation"),
				'rules' => 'trim|required|max_length[128]|xss_clean'
			),
			array(
				'field' => 'dob',
				'label' => $this->lang->line("teacher_dob"),
				'rules' => 'trim|required|max_length[10]|callback_date_valid|xss_clean'
			),
			array(
				'field' => 'sex',
				'label' => $this->lang->line("teacher_sex"),
				'rules' => 'trim|required|max_length[10]|xss_clean'
			),
			array(
				'field' => 'email',
				'label' => $this->lang->line("teacher_email"),
				'rules' => 'trim|required|max_length[40]|valid_email|xss_clean|callback_unique_email'
			),
			array(
				'field' => 'phone',
				'label' => $this->lang->line("teacher_phone"),
				'rules' => 'trim|min_length[5]|max_length[25]|xss_clean'
			),
			array(
				'field' => 'address',
				'label' => $this->lang->line("teacher_address"),
				'rules' => 'trim|max_length[200]|xss_clean'
			),
			array(
				'field' => 'jod',
				'label' => $this->lang->line("teacher_jod"),
				'rules' => 'trim|required|max_length[10]|callback_date_valid|xss_clean'
			),
			array(
				'field' => 'photo',
				'label' => $this->lang->line("teacher_photo"),
				'rules' => 'trim|max_length[200]|xss_clean|callback_photoupload'
			),
			array(
				'field' => 'username',
				'label' => $this->lang->line("teacher_username"),
				'rules' => 'trim|required|min_length[4]|max_length[40]|xss_clean|callback_lol_username'
			),
			array(
				'field' => 'password',
				'label' => $this->lang->line("teacher_password"),
				'rules' => 'trim|required|min_length[4]|max_length[40]|xss_clean'
			),
            array(
				'field' => 'dni',
				'label' => $this->lang->line("teacher_dni"),
				'rules' => 'trim|required|max_length[30]|numeric|callback_unique_dni|xss_clean'
			)
		);
		return $rules;
	}

	public function photoupload() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$user = array();
		if((int)$id) {
			$user = $this->teacher_m->get_teacher($id);
		}

		$new_file = "defualt.png";
		if($_FILES["photo"]['name'] !="") {
			$file_name = $_FILES["photo"]['name'];
			$random = rand(1, 10000000000000000);
	    	$makeRandom = hash('sha512', $random.$this->input->post('username') . config_item("encryption_key"));
			$file_name_rename = $makeRandom;
            $explode = explode('.', $file_name);
            if(count($explode) >= 2) {
	            $new_file = $file_name_rename.'.'.$explode[1];
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
				$this->form_validation->set_message("photoupload", "Archivo no permitido");
	     		return FALSE;
			}
		} else {
			if(count($user)) {
				$this->upload_data['file'] = array('file_name' => $user->photo);
				return TRUE;
			} else {
				$this->upload_data['file'] = array('file_name' => $new_file);
			return TRUE;
			}
		}
	}

	public function index() {

		$this->data['teachers'] = $this->teacher_m->get_teacher();
		$this->data["subview"] = "teacher/index";
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
		if($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$this->data['form_validation'] = validation_errors();
				$this->data["subview"] = "teacher/add";
				$this->load->view('_layout_main', $this->data);
			} else {
				$array = array();
				$array['name'] = $this->input->post("name");
				$array['designation'] = $this->input->post("designation");
				$array["dob"] = date("Y-m-d", strtotime($this->input->post("dob")));
				$array["sex"] = $this->input->post("sex");
				$array['email'] = $this->input->post("email");
				$array['phone'] = $this->input->post("phone");
				$array['address'] = $this->input->post("address");
				$array['jod'] = date("Y-m-d", strtotime($this->input->post("jod")));
				$array['username'] = $this->input->post("username");
				$array['password'] = $this->teacher_m->hash($this->input->post("password"));
				$array['usertypeID'] = 2;
				$array["create_date"] = date("Y-m-d h:i:s");
				$array["modify_date"] = date("Y-m-d h:i:s");
				$array["create_userID"] = $this->session->userdata('loginuserID');
				$array["create_username"] = $this->session->userdata('username');
				$array["create_usertype"] = $this->session->userdata('usertype');
				$array["active"] = 1;
				$array['photo'] = $this->upload_data['file']['file_name'];
                $array["dni"] = $this->input->post("dni"); 

				// For Email
				$this->usercreatemail($this->input->post('email'), $this->input->post('username'), $this->input->post('password'));

				//$this->usercreateid($this->input->post('dni'));

				$this->teacher_m->insert_teacher($array);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("teacher/index"));

			}
		} else {
			$this->data["subview"] = "teacher/add";
			$this->load->view('_layout_main', $this->data);
		}

	}

	public function edit() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/datepicker/datepicker.css'
			),
			'js' => array(
				'assets/datepicker/datepicker.js'
			)
		);
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$this->data['teacher'] = $this->teacher_m->get_teacher($id);
			if($this->data['teacher']) {
				if($_POST) {
					$rules = $this->rules();
					unset($rules[10]);
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "teacher/edit";
						$this->load->view('_layout_main', $this->data);
					} else {
						$array = array();
						$array['name'] = $this->input->post("name");
						$array['designation'] = $this->input->post("designation");
						$array["dob"] = date("Y-m-d", strtotime($this->input->post("dob")));
						$array["sex"] = $this->input->post("sex");
						$array['email'] = $this->input->post("email");
						$array['phone'] = $this->input->post("phone");
						$array['address'] = $this->input->post("address");
						$array['jod'] = date("Y-m-d", strtotime($this->input->post("jod")));
						$array['username'] = $this->input->post('username');
						$array["modify_date"] = date("Y-m-d h:i:s");
						$array['photo'] = $this->upload_data['file']['file_name'];
                        $array['dni'] = $this->input->post("dni");
						$this->teacher_m->update_teacher($array, $id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("teacher/index"));

					}
				} else {
					$this->data["subview"] = "teacher/edit";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}


	public function delete() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$this->data['teacher'] = $this->teacher_m->get_teacher($id);
			if($this->data['teacher']) {
				if($this->data['teacher']->photo != 'defualt.png') {
                    if(file_exists(FCPATH.'uploads/images/'.$this->data['teacher']->photo)) {
						unlink(FCPATH.'uploads/images/'.$this->data['teacher']->photo);
                    }
				}
				$this->teacher_m->delete_teacher($id);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("teacher/index"));
			} else {
				redirect(base_url("teacher/index"));
			}
		} else {
			redirect(base_url("teacher/index"));
		}
	}

	public function view() {
		$usertype = $this->session->userdata('usertype');
		if ($usertype) {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if ((int)$id) {
				$this->data['teacher'] = $this->teacher_m->get_teacher($id);
				if($this->data['teacher']) {
					$this->data["subview"] = "teacher/view";
					$this->load->view('_layout_main', $this->data);
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function lol_username() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$teacher_info = $this->teacher_m->get_teacher($id);
			$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
			$array = array();
			$i = 0;
			foreach ($tables as $table) {
				$user = $this->teacher_m->get_username($table, array("username" => $this->input->post('username'), "username !=" => $teacher_info->username));
				if(count($user)) {
					$this->form_validation->set_message("lol_username", "%s ya existe.");
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
		} else {
			$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
			$array = array();
			$i = 0;
			foreach ($tables as $table) {
				$user = $this->teacher_m->get_username($table, array("username" => $this->input->post('username')));
				if(count($user)) {
					$this->form_validation->set_message("lol_username", "%s ya existe.");
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

	public function date_valid($date) {
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
		if(permissionChecker('teacher_view')) {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if ((int)$id) {
				$this->data["teacher"] = $this->teacher_m->get_teacher($id);
				if($this->data["teacher"]) {
					$this->data['panel_title'] = $this->lang->line('panel_title');
					$this->printview($this->data, 'teacher/print_preview');
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}

			} else {
				$this->data["subview"] = "error";
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

			$this->data["teacher"] = $this->teacher_m->get_teacher($id);
			if($this->data["teacher"]) {
				$email = $this->input->post('to');
				$subject = $this->input->post('subject');
				$message = $this->input->post('message');

				$this->viewsendtomail($this->data, 'teacher/print_preview', $email, $subject, $message);

			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function unique_email() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$teacher_info = $this->teacher_m->get_single_teacher(array('teacherID' => $id));
			$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
			$array = array();
			$i = 0;
			foreach ($tables as $table) {
				$user = $this->teacher_m->get_username($table, array("email" => $this->input->post('email'), 'username !=' => $teacher_info->username));
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
		} else {
			$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
			$array = array();
			$i = 0;
			foreach ($tables as $table) {
				$user = $this->teacher_m->get_username($table, array("email" => $this->input->post('email')));
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
    
	//busqueda de dni en la base de datos
    public function unique_dni() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$teacher_info = $this->teacher_m->get_teacher($id);
			$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
			$array = array();
			$i = 0;
			foreach ($tables as $table) {
				$user = $this->teacher_m->get_username($table, array("dni" => $this->input->post('dni'), "username !=" => $teacher_info->username));
				if(count($user)) {
					$this->form_validation->set_message("unique_dni", "%s ya existe.");
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
		} else {
			$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
			$array = array();
			$i = 0;
			foreach ($tables as $table) {
				$user = $this->teacher_m->get_username($table, array("dni" => $this->input->post('dni')));
				if(count($user)) {
					$this->form_validation->set_message("unique_dni", "%s ya existe.");
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



	function active() {
		if(permissionChecker('teacher_edit')) {
			$id = $this->input->post('id');
			$status = $this->input->post('status');
			if($id != '' && $status != '') {
				if((int)$id) {
					if($status == 'chacked') {
						$this->teacher_m->update_teacher(array('active' => 1), $id);
						echo 'Success';
					} elseif($status == 'unchacked') {
						$this->teacher_m->update_teacher(array('active' => 0), $id);
						echo 'Success';
					} else {
						echo "Error";
					}
				} else {
					echo "Error";
				}
			} else {
				echo "Error";
			}
		} else {
			echo "Error";
		}
	}
}

/* End of file teacher.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/teacher.php */
