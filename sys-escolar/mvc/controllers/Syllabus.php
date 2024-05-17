<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Syllabus extends Admin_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("syllabus_m");
		$this->load->model("parents_m");
		$this->load->model("student_m");
		$this->load->model('student_info_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('syllabus', $language);	
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'title', 
				'label' => $this->lang->line("syllabus_title"), 
				'rules' => 'trim|required|xss_clean|max_length[128]'
			), 
			array(
				'field' => 'description', 
				'label' => $this->lang->line("syllabus_description"),
				'rules' => 'trim|required|xss_clean'
			), 
			array(
				'field' => 'classesID', 
				'label' => $this->lang->line("syllabus_classes"),
				'rules' => 'trim|required|numeric|max_length[11]|xss_clean|callback_unique_classes'
			),
			array(
				'field' => 'file', 
				'label' => $this->lang->line("syllabus_file"), 
				'rules' => 'trim|max_length[512]|xss_clean|callback_fileupload'
			)
		);
		return $rules;
	}

	public function fileupload() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$syllabus = array();
		if((int)$id) {
			$syllabus = $this->syllabus_m->get_syllabus($id);	
		}
		
		$new_file = "";
		$original_file_name = '';
		if($_FILES["file"]['name'] !="") {
			$file_name = $_FILES["file"]['name'];
			$original_file_name = $file_name;
			$random = rand(1, 10000000000000000);
	    	$makeRandom = hash('sha512', $random.$this->input->post('title') . config_item("encryption_key"));
			$file_name_rename = $makeRandom;
            $explode = explode('.', $file_name);
            if(count($explode) >= 2) {
	            $new_file = $file_name_rename.'.'.end($explode);
				$config['upload_path'] = "./uploads/images";
				$config['allowed_types'] = "gif|jpg|png|jpeg|pdf|doc|xml|docx|GIF|JPG|PNG|JPEG|PDF|DOC|XML|DOCX|xls|xlsx|txt|ppt|csv";
				$config['file_name'] = $new_file;
				$config['max_size'] = '100024';
				$config['max_width'] = '3000';
				$config['max_height'] = '3000';
				$this->load->library('upload', $config);
				if(!$this->upload->do_upload("file")) {
					$this->form_validation->set_message("fileupload", $this->upload->display_errors());
	     			return FALSE;
				} else {
					$this->upload_data['file'] =  $this->upload->data();
					$this->upload_data['file']['original_file_name'] = $original_file_name;
					return TRUE;
				}
			} else {
				$this->form_validation->set_message("fileupload", "Archivo no permitido.");
	     		return FALSE;
			}
		} else {
			if(count($syllabus)) {
				$this->upload_data['file'] = array('file_name' => $syllabus->file);
				$this->upload_data['file']['original_file_name'] = $syllabus->originalfile;
				return TRUE;
			} else {
				if($new_file == '') {
					$this->form_validation->set_message("fileupload", "%s es requerido.");
					return FALSE;
				} else {
					$this->upload_data['file'] = array('file_name' => $new_file);
					$this->upload_data['file']['original_file_name'] = $original_file_name;
					return TRUE;
				}
			}
		}
	}

	public function index() {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 3) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css'
				),
				'js' => array(
					'assets/select2/select2.js'
				)
			);
			
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$student = $this->student_info_m->get_student_info();
			if(count($student)) {
				$this->data['syllabuss'] = $this->syllabus_m->get_order_by_syllabus(array('classesID' => $student->classesID, 'schoolyearID' => $schoolyearID));
				$this->data['set'] = $student->classesID;
				$this->data["subview"] = "syllabus/index_parents";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} elseif($usertypeID == 4) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css'
				),
				'js' => array(
					'assets/select2/select2.js'
				)
			);
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$username = $this->session->userdata("username");
			$parent = $this->parents_m->get_single_parents(array('username' => $username));
			$this->data['students'] = $this->student_m->get_order_by_student(array('parentID' => $parent->parentsID, 'schoolyearID' => $schoolyearID));
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$checkstudent = $this->student_m->get_single_student(array('studentID' => $id));
				if(count($checkstudent)) {
					$classesID = $checkstudent->classesID;
					$this->data['set'] = $id;
					$this->data['syllabuss'] = $this->syllabus_m->get_order_by_syllabus(array('classesID' => $classesID, 'schoolyearID' => $schoolyearID));
					$this->data["subview"] = "syllabus/index_parents";
					$this->load->view('_layout_main', $this->data);
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data['set'] = $id;
				$this->data['syllabuss'] = array();
				$this->data["subview"] = "syllabus/index_parents";
				$this->load->view('_layout_main', $this->data);
			}

		} else {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css'
				),
				'js' => array(
					'assets/select2/select2.js'
				)
			);

			$id = htmlentities(escapeString($this->uri->segment(3)));
			$this->data['classes'] = $this->classes_m->get_classes();
			if((int)$id) {
				$this->data['set'] = $id;
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$this->data['syllabuss'] = $this->syllabus_m->get_order_by_syllabus(array('schoolyearID' => $schoolyearID, 'classesID' => $id));
				$this->data["subview"] = "syllabus/index";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data['set'] = 0;
				$this->data['syllabuss'] = array(); 
				$this->data["subview"] = "syllabus/index";
				$this->load->view('_layout_main', $this->data);
			}
		}
		
	}

	public function add() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/select2/select2.js'
			)
		);

		$this->data['classes'] = $this->classes_m->get_classes();
		if($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) { 
				$this->data["subview"] = "syllabus/add";
				$this->load->view('_layout_main', $this->data);			
			} else {
				$array = array(
					"title" => $this->input->post("title"),
					"description" => $this->input->post("description"),
					"date" => date('Y-m-d'),
					"usertypeID" => $this->session->userdata('usertypeID'),
					"userID" => $this->session->userdata('loginuserID'),
					"classesID" => $this->input->post("classesID"),
					"schoolyearID" => $this->data['siteinfos']->school_year,
				);

				$array['originalfile'] = $this->upload_data['file']['original_file_name'];
				$array['file'] = $this->upload_data['file']['file_name'];

				$this->syllabus_m->insert_syllabus($array);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("syllabus/index"));
			}
		} else {
			$this->data["subview"] = "syllabus/add";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/select2/select2.js'
			)
		);
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$url = htmlentities(escapeString($this->uri->segment(4)));
		if((int)$id && (int)$url) {
			$this->data['classes'] = $this->classes_m->get_classes();
			$this->data['syllabus'] = $this->syllabus_m->get_single_syllabus(array('syllabusID' => $id, 'schoolyearID' => $schoolyearID));
			if(count($this->data['syllabus'])) {
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "syllabus/edit";
						$this->load->view('_layout_main', $this->data);			
					} else {
						$array = array(
							"title" => $this->input->post("title"),
							"description" => $this->input->post("description"),
							"date" => date('Y-m-d'),
							"usertypeID" => $this->session->userdata('usertypeID'),
							"userID" => $this->session->userdata('loginuserID'),
							"classesID" => $this->input->post("classesID")
						);

						$array['originalfile'] = $this->upload_data['file']['original_file_name'];
						$array['file'] = $this->upload_data['file']['file_name'];
						$this->syllabus_m->update_syllabus($array, $id);	
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("syllabus/index/$url"));
					}
				} else {
					$this->data["subview"] = "syllabus/edit";
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
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$url = htmlentities(escapeString($this->uri->segment(4)));
		if((int)$id && (int)$url) {
			$syllabus = $this->syllabus_m->get_single_syllabus(array('syllabusID' => $id, 'schoolyearID' => $schoolyearID));
			if(count($syllabus)) {
				if(config_item('demo') == FALSE) {
					if(file_exists(FCPATH.'uploads/images/'.$syllabus->file)) {
						unlink(FCPATH.'uploads/images/'.$syllabus->file);
					}
				}
				$this->syllabus_m->delete_syllabus($id);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("syllabus/index/$url"));
			} else {
				redirect(base_url("syllabus/index"));	
			}
		} else {
			redirect(base_url("syllabus/index"));
		}
	}

	function unique_classes() {
		if($this->input->post('classesID') == 0) {
			$this->form_validation->set_message("unique_classes", "%s es requerido.");
	     	return FALSE;
		}
		return TRUE;
	}

	public function student_list() {
		$classID = $this->input->post('id');
		if((int)$classID) {
			$string = base_url("syllabus/index/$classID");
			echo $string;
		} else {
			redirect(base_url("syllabus/index"));
		}
	}

	public function download() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$syllabus = $this->syllabus_m->get_single_syllabus(array('syllabusID' => $id, 'schoolyearID' => $schoolyearID));
			$file = realpath('uploads/images/'.$syllabus->file);
			$originalname = $syllabus->originalfile;
		    if (file_exists($file)) {
		    	header('Content-Description: File Transfer');
			    header('Content-Type: application/octet-stream');
			    header('Content-Disposition: attachment; filename="'.basename($originalname).'"');
			    header('Expires: 0');
			    header('Cache-Control: must-revalidate');
			    header('Pragma: public');
			    header('Content-Length: ' . filesize($file));
			    readfile($file);
			    exit;
		    } else {
		    	redirect(base_url('syllabus/index'));
		    }
		} else {
			redirect(base_url('syllabus/index'));
		}
		
	}
	
}

/* End of file class.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/class.php */