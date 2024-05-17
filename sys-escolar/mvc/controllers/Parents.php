<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parents extends Admin_Controller {

	public function __construct () {
		parent::__construct();
		$this->load->model("parents_m");
		$this->load->model("student_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('parents', $language);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'name',
				'label' => $this->lang->line("parents_guargian_name"),
				'rules' => 'trim|required|xss_clean|max_length[60]'
			),
			array(
				'field' => 'father_name',
				'label' => $this->lang->line("parents_father_name"),
				'rules' => 'trim|xss_clean|max_length[60]'
			),
			array(
				'field' => 'mother_name',
				'label' => $this->lang->line("parents_mother_name"),
				'rules' => 'trim|xss_clean|max_length[60]'
			),
			array(
				'field' => 'father_profession',
				'label' => $this->lang->line("parents_father_name"),
				'rules' => 'trim|xss_clean|max_length[40]'
			),
			array(
				'field' => 'mother_profession',
				'label' => $this->lang->line("parents_mother_name"),
				'rules' => 'trim|xss_clean|max_length[40]'
			),
			array(
				'field' => 'email',
				'label' => $this->lang->line("parents_email"),
				'rules' => 'trim|max_length[40]|valid_email|xss_clean|callback_unique_email'
			),
			array(
				'field' => 'phone',
				'label' => $this->lang->line("parents_phone"),
				'rules' => 'trim|min_length[5]|max_length[25]|xss_clean'
			),
			array(
				'field' => 'address',
				'label' => $this->lang->line("parents_address"),
				'rules' => 'trim|max_length[200]|xss_clean'
			),
			array(
				'field' => 'photo',
				'label' => $this->lang->line("parents_photo"),
				'rules' => 'trim|max_length[200]|xss_clean|callback_photoupload'
			),
			array(
				'field' => 'username',
				'label' => $this->lang->line("parents_username"),
				'rules' => 'trim|required|min_length[4]|max_length[40]|xss_clean|callback_lol_username'
			),
			array(
				'field' => 'password',
				'label' => $this->lang->line("parents_password"),
				'rules' => 'trim|required|min_length[4]|max_length[40]|xss_clean'
			),
			array(
				'field' => 'dni',
				'label' => $this->lang->line("parents_dni"),
				'rules' => 'trim|required|max_length[30]|numeric|callback_unique_dni|xss_clean'
			)
		);
		return $rules;
	}

	public function photoupload() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$user = array();
		if((int)$id) {
			$user = $this->parents_m->get_parents($id);
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
		$this->data['parents'] = $this->parents_m->get_parents();
		$this->data["subview"] = "parents/index";
		$this->load->view('_layout_main', $this->data);
	}

	public function add() {
		if($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$this->data['form_validation'] = validation_errors();
				$this->data["subview"] = "parents/add";
				$this->load->view('_layout_main', $this->data);
			} else {
				$array = array();
				for($i=0; $i<count($rules)-1; $i++) {
					$array[$rules[$i]['field']] = $this->input->post($rules[$i]['field']);
				}
				$array['name'] = $this->input->post("name");
				$array['password'] = $this->student_m->hash($this->input->post("password"));
				$array['usertypeID'] = 4;
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

				$this->parents_m->insert_parents($array);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("parents/index"));

			}
		} else {
			$this->data["subview"] = "parents/add";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if ((int)$id) {
			$this->data['parents'] = $this->parents_m->get_parents($id);
			if($this->data['parents']) {
				if($_POST) {
					$rules = $this->rules();
					unset($rules[10]);
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "parents/edit";
						$this->load->view('_layout_main', $this->data);
					} else {

						$array = array();
						for($i=0; $i<count($rules)-1; $i++) {
							$array[$rules[$i]['field']] = $this->input->post($rules[$i]['field']);
						}
						//$array['name'] = $this->input->post("name");
						$array["modify_date"] = date("Y-m-d h:i:s");
						$array['photo'] = $this->upload_data['file']['file_name'];
						$array["dni"] = $this->input->post("dni"); 

						$this->parents_m->update_parents($array, $id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("parents/index"));

					}
				} else {
					$this->data["subview"] = "parents/edit";
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


	public function view() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if ((int)$id) {
			$this->data['parents'] = $this->parents_m->get_parents($id);

			if($this->data['parents']) {
				$this->data["subview"] = "parents/view";
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

	public function print_preview() {
		if(permissionChecker('parents_view')) {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			$url = htmlentities(escapeString($this->uri->segment(4)));
			if((int)$id) {
				$this->data['parents'] = $this->parents_m->get_parents($id);
				if($this->data['parents']) {
					$this->data['panel_title'] = $this->lang->line('panel_title');
					$this->printView($this->data, 'parents/print_preview');
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
			$this->data['parents'] = $this->parents_m->get_parents($id);
			if($this->data['parents']) {

				$email = $this->input->post('to');
				$subject = $this->input->post('subject');
				$message = $this->input->post('message');

				$this->viewsendtomail($this->data, 'parents/print_preview', $email, $subject, $message);

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
		if ((int)$id) {
			$this->data['parents'] = $this->parents_m->get_parents($id);
			if($this->data['parents']) {
				if($this->data['parents']->photo != 'defualt.png') {
					unlink(FCPATH.'uploads/images/'.$this->data['parents']->photo);
				}
				$this->parents_m->delete_parents($id);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("parents/index"));
			} else {
				redirect(base_url("parents/index"));
			}
		} else {
			redirect(base_url("parents/index"));
		}
	}

	public function unique_email() {
		if($this->input->post('email')) {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$parents = $this->parents_m->get_single_parents(array('parentsID' => $id));
				$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
				$array = array();
				$i = 0;
				foreach ($tables as $table) {
					$user = $this->parents_m->get_username($table, array("email" => $this->input->post('email'), 'username !=' => $parents->username ));
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
					$user = $this->student_m->get_username($table, array("email" => $this->input->post('email')));
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
	}



public function unique_dni() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$parents_info = $this->parents_m->get_parents($id);
			$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
			$array = array();
			$i = 0;
			foreach ($tables as $table) {
				$user = $this->parents_m->get_username($table, array("dni" => $this->input->post('dni'), "username !=" => $parents_info->username));
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
				$user = $this->parents_m->get_username($table, array("dni" => $this->input->post('dni')));
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

	
	public function lol_username() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$parents_info = $this->parents_m->get_single_parents(array('parentsID' => $id));
			$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
			$array = array();
			$i = 0;
			foreach ($tables as $table) {
				$user = $this->student_m->get_username($table, array("username" => $this->input->post('username'), "username !=" => $parents_info->username ));
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
				$user = $this->student_m->get_username($table, array("username" => $this->input->post('username')));
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

	function active() {
		if(permissionChecker('parents_edit')) {
			$id = $this->input->post('id');
			$status = $this->input->post('status');
			if($id != '' && $status != '') {
				if((int)$id) {
					if($status == 'chacked') {
						$this->parents_m->update_parents(array('active' => 1), $id);
						echo 'Success';
					} elseif($status == 'unchacked') {
						$this->parents_m->update_parents(array('active' => 0), $id);
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

/* End of file parents.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/parents.php */
