<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Systemadmin extends Admin_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("systemadmin_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('systemadmin', $language);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'name',
				'label' => $this->lang->line("systemadmin_name"),
				'rules' => 'trim|required|xss_clean|max_length[60]'
			),
			array(
				'field' => 'dob',
				'label' => $this->lang->line("systemadmin_dob"),
				'rules' => 'trim|required|max_length[10]|callback_date_valid|xss_clean'
			),
			array(
				'field' => 'sex',
				'label' => $this->lang->line("systemadmin_sex"),
				'rules' => 'trim|max_length[10]|xss_clean'
			),
			array(
				'field' => 'email',
				'label' => $this->lang->line("systemadmin_email"),
				'rules' => 'trim|required|max_length[40]|valid_email|xss_clean|callback_unique_email'
			),
			array(
				'field' => 'phone',
				'label' => $this->lang->line("systemadmin_phone"),
				'rules' => 'trim|min_length[5]|max_length[25]|xss_clean'
			),
			array(
				'field' => 'address',
				'label' => $this->lang->line("systemadmin_address"),
				'rules' => 'trim|max_length[200]|xss_clean'
			),
			array(
				'field' => 'jod',
				'label' => $this->lang->line("systemadmin_jod"),
				'rules' => 'trim|required|max_length[10]|callback_date_valid|xss_clean'
			),
			array(
				'field' => 'photo',
				'label' => $this->lang->line("systemadmin_photo"),
				'rules' => 'trim|max_length[200]|xss_clean|callback_photoupload'
			),
			array(
				'field' => 'username',
				'label' => $this->lang->line("systemadmin_username"),
				'rules' => 'trim|required|min_length[4]|max_length[40]|xss_clean|callback_lol_username'
			),
			array(
				'field' => 'password',
				'label' => $this->lang->line("systemadmin_password"),
				'rules' => 'trim|required|min_length[4]|max_length[40]|min_length[4]|xss_clean'
			),
			array(
				'field' => 'dni',
				'label' => $this->lang->line("systemadmin_dni"),
				'rules' => 'trim|required|max_length[30]|numeric|callback_unique_dni|xss_clean'
			)
		);
		return $rules;
	}

	public function photoupload() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$user = array();
		if((int)$id) {
			$user = $this->systemadmin_m->get_systemadmin($id);
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
				$this->form_validation->set_message("photoupload", "Archivo no permitido.");
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
		$this->data['systemadmins'] = $this->systemadmin_m->get_systemadmin_by_usertype();
		$this->data["subview"] = "systemadmin/index";
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
				$this->data["subview"] = "systemadmin/add";
				$this->load->view('_layout_main', $this->data);
			} else {
				$array = array();
				$array["dni"] = $this->input->post("dni");
				$array["name"] = $this->input->post("name");
				$array["dob"] = date("Y-m-d", strtotime($this->input->post("dob")));
				$array["sex"] = $this->input->post("sex");
				$array["email"] = $this->input->post("email");
				$array["phone"] = $this->input->post("phone");
				$array["address"] = $this->input->post("address");
				$array["jod"] = date("Y-m-d", strtotime($this->input->post("jod")));
				$array["username"] = $this->input->post("username");
				$array['password'] = $this->systemadmin_m->hash($this->input->post("password"));
				$array["usertypeID"] = 1;
				$array["create_date"] = date("Y-m-d h:i:s");
				$array["modify_date"] = date("Y-m-d h:i:s");
				$array["create_userID"] = $this->session->userdata('loginuserID');
				$array["create_username"] = $this->session->userdata('username');
				$array["create_usertype"] = $this->session->userdata('usertype');
				$array["active"] = 1;
				$array['photo'] = $this->upload_data['file']['file_name'];

				// For Email
				$this->usercreatemail($this->input->post('email'), $this->input->post('username'), $this->input->post('password'));

				$this->systemadmin_m->insert_systemadmin($array);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("systemadmin/index"));

			}
		} else {
			$this->data["subview"] = "systemadmin/add";
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
		$usertype = $this->session->userdata("usertype");
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if ((int)$id) {
			$this->data['systemadmin'] = $this->systemadmin_m->get_systemadmin($id);
			if($id != 1 && $this->session->userdata('loginuserID') != $this->data['systemadmin']->systemadminID) {

				if($this->data['systemadmin']) {
					if($_POST) {
						$rules = $this->rules();
						unset($rules[9]);
						$this->form_validation->set_rules($rules);
						if ($this->form_validation->run() == FALSE) {
							$this->data["subview"] = "systemadmin/edit";
							$this->load->view('_layout_main', $this->data);
						} else {
							$array = array();
							$array["dni"] = $this->input->post("dni");
							$array["name"] = $this->input->post("name");
							$array["dob"] = date("Y-m-d", strtotime($this->input->post("dob")));
							$array["sex"] = $this->input->post("sex");
							$array["email"] = $this->input->post("email");
							$array["phone"] = $this->input->post("phone");
							$array["address"] = $this->input->post("address");
							$array["jod"] = date("Y-m-d", strtotime($this->input->post("jod")));
							$array["modify_date"] = date("Y-m-d h:i:s");
							$array['username'] = $this->input->post('username');
							$array['photo'] = $this->upload_data['file']['file_name'];

							$this->systemadmin_m->update_systemadmin($array, $id);
							$this->session->set_flashdata('success', $this->lang->line('menu_success'));
							redirect(base_url("systemadmin/index"));

						}
					} else {
						$this->data["subview"] = "systemadmin/edit";
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
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function delete() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$this->data['systemadmin'] = $this->systemadmin_m->get_systemadmin($id);
			if($id != 1 && $this->session->userdata('loginuserID') != $this->data['systemadmin']->systemadminID) {
				if($this->data['systemadmin']) {
					if($this->data['systemadmin']->photo != 'defualt.png') {
						unlink(FCPATH.'uploads/images/'.$this->data['systemadmin']->photo);
					}
					$this->systemadmin_m->delete_systemadmin($id);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("systemadmin/index"));
				} else {
					redirect(base_url("systemadmin/index"));
				}
			} else {
				redirect(base_url("systemadmin/index"));
			}
		} else {
			redirect(base_url("systemadmin/index"));
		}
	}

	public function view() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$this->data["systemadmin"] = $this->systemadmin_m->get_systemadmin_by_usertype($id);
			if($id != 1 && $this->session->userdata('loginuserID') != $this->data['systemadmin']->systemadminID) {
				if($this->data["systemadmin"]) {
					$this->data["subview"] = "systemadmin/view";
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

	public function print_preview() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if ((int)$id) {
			$this->data['systemadmin'] = $this->systemadmin_m->get_systemadmin_by_usertype($id);
			if($id != 1 && $this->session->userdata('loginuserID') != $this->data['systemadmin']->systemadminID) {
				if($this->data['systemadmin']) {
					$this->data['panel_title'] = $this->lang->line('panel_title');
					$this->printView($this->data, 'systemadmin/print_preview');
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

	public function send_mail() {
		$id = $this->input->post('id');
		if ((int)$id) {
			$this->data['systemadmin'] = $this->systemadmin_m->get_systemadmin_by_usertype($id);
			if($this->data["systemadmin"]) {
				$this->data['panel_title'] = $this->lang->line('panel_title');
				if($path = $this->printView($this->data, 'systemadmin/print_preview', 'save')) {
					$this->load->library('email');
					$this->email->set_mailtype("html");
					$this->email->from($this->data["siteinfos"]->email, $this->data['siteinfos']->sname);
					$this->email->to($this->input->post('to'));
					$this->email->subject($this->input->post('subject'));
					$this->email->message($this->input->post('message'));
					$this->email->attach($path);
					if($this->email->send()) {
						$this->session->set_flashdata('success', $this->lang->line('mail_success'));
					} else {
						$this->session->set_flashdata('error', $this->lang->line('mail_error'));
					}
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
			$systemadmin_info = $this->systemadmin_m->get_single_systemadmin(array('systemadminID' => $id));
			$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
			$array = array();
			$i = 0;
			foreach ($tables as $table) {
				$user = $this->systemadmin_m->get_username($table, array("username" => $this->input->post('username'), "username !=" => $systemadmin_info->username));
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
				$user = $this->systemadmin_m->get_username($table, array("username" => $this->input->post('username')));
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

	public function unique_dni() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$systemadmin_info = $this->systemadmin_m->get_systemadmin($id);
			$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
			$array = array();
			$i = 0;
			foreach ($tables as $table) {
				$user = $this->systemadmin_m->get_username($table, array("dni" => $this->input->post('dni'), "username !=" => $systemadmin_info->username));
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
				$user = $this->systemadmin_m->get_username($table, array("dni" => $this->input->post('dni')));
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

	public function date_valid($date) {
		if(strlen($date) <10) {
			$this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
	     	return FALSE;
		} else {
	   		$arr = explode("-", $date);
	        $dd = $arr[0];
	        $mm = $arr[1];
	        $yyyy = $arr[2];
	      	if(checkdate($mm, $dd, $yyyy)) {
	      		return TRUE;
	      	} else {
	      		$this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
	     		return FALSE;
	      	}
	    }
	}

	public function unique_email() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$systemadmin_info = $this->systemadmin_m->get_single_systemadmin(array('systemadminID' => $id));
			$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
			$array = array();
			$i = 0;
			foreach ($tables as $table) {
				$user = $this->systemadmin_m->get_username($table, array("email" => $this->input->post('email'), 'username !=' => $systemadmin_info->username ));
				if(count($user)) {
					$this->form_validation->set_message("unique_email", "%s already exists");
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
				$user = $this->systemadmin_m->get_username($table, array("email" => $this->input->post('email')));
				if(count($user)) {
					$this->form_validation->set_message("unique_email", "%s already exists");
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
		if(permissionChecker('systemadmin_edit')) {
			$id = $this->input->post('id');
			$this->data['systemadmin'] = $this->systemadmin_m->get_systemadmin($id);
			if($id != 1 && $this->session->userdata('loginuserID') != $this->data['systemadmin']->systemadminID) {

				$status = $this->input->post('status');
				if($id != '' && $status != '') {
					if((int)$id) {
						if($status == 'chacked') {
							$this->systemadmin_m->update_systemadmin(array('active' => 1), $id);
							echo 'Success';
						} elseif($status == 'unchacked') {
							$this->systemadmin_m->update_systemadmin(array('active' => 0), $id);
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
		} else {
			echo "Error";
		}
	}
}

/* End of file user.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/user.php */
