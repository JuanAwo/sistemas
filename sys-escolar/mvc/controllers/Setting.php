<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Setting extends Admin_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("setting_m");
		$this->load->model("markpercentage_m");
		$this->load->model("schoolyear_m");
		$this->load->model("idmanager_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('setting', $language);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'sname',
				'label' => $this->lang->line("setting_school_name"),
				'rules' => 'trim|required|xss_clean|max_length[128]'
			),
			array(
				'field' => 'phone',
				'label' => $this->lang->line("setting_school_phone"),
				'rules' => 'trim|required|xss_clean|max_length[25]'
			),
			array(
				'field' => 'email',
				'label' => $this->lang->line("setting_school_email"),
				'rules' => 'trim|required|valid_email|max_length[40]|xss_clean'
			),
			array(
				'field' => 'automation',
				'label' => $this->lang->line("setting_school_day"),
				'rules' => 'trim|max_length[5]|xss_clean|callback_unique_day '
			),
			array(
				'field' => 'auto_invoice_generate',
				'label' => $this->lang->line("setting_school_auto_invoice_generate"),
				'rules' => 'trim|required|max_length[5]|xss_clean'
			),
			array(
				'field' => 'currency_code',
				'label' => $this->lang->line("setting_school_currency_code"),
				'rules' => 'trim|required|max_length[11]|xss_clean'
			),
			array(
				'field' => 'currency_symbol',
				'label' => $this->lang->line("setting_school_currency_symbol"),
				'rules' => 'trim|required|max_length[3]|xss_clean'
			),
			array(
				'field' => 'footer',
				'label' => $this->lang->line("setting_school_footer"),
				'rules' => 'trim|required|max_length[200]|xss_clean'
			),
			array(
				'field' => 'address',
				'label' => $this->lang->line("setting_school_address"),
				'rules' => 'trim|required|max_length[200]|xss_clean'
			),
			array(
				'field' => 'language',
				'label' => $this->lang->line("setting_school_lang"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'attendance',
				'label' => $this->lang->line("setting_school_attendance"),
				'rules' => 'trim|required|xss_clean|callback_unique_attendance'
			),
			array(
				'field' => 'school_year',
				'label' => $this->lang->line("setting_school_default_school_year"),
				'rules' => 'trim|required|xss_clean|callback_unique_schoolyear'
			),
			array(
				'field' => 'note',
				'label' => $this->lang->line("setting_school_note"),
				'rules' => 'trim|required|max_length[5]|xss_clean'
			),
			array(
				'field' => 'photo',
				'label' => $this->lang->line("setting_school_photo"),
				'rules' => 'trim|max_length[200]|xss_clean|callback_photoupload'
			),
		);

		$markpercentages = $this->markpercentage_m->get_markpercentage();
		if(count($markpercentages)) {
		    foreach ($markpercentages as $key => $markpercentage) {
		        $rules[] = array(
	        		'field' => 'mark_'. str_replace(' ', '',$markpercentage->markpercentageID),
	        		'label' => $markpercentage->markpercentagetype,
	        		'rules' => 'traim|xss_clean|max_lenght[5]'

	        	);
		    }
		}
		return $rules;
	}

	public function photoupload() {
		$setting = $this->setting_m->get_setting(1);	
		$new_file = "site.png";
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
			if(count($setting)) {
				$this->upload_data['file'] = array('file_name' => $setting->photo);
				return TRUE;
			} else {
				$this->upload_data['file'] = array('file_name' => $new_file);
			return TRUE;
			}
		}
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
		
		$usertype = $this->session->userdata("usertype");
		$this->data['setting'] = $this->setting_m->get_setting(1);

		$this->data['settingarray'] = $this->setting_m->get_setting_array();
		$this->data['markpercentages'] = $this->markpercentage_m->get_markpercentage();
		
		$this->data['schoolyears'] = $this->schoolyear_m->get_order_by_schoolyear(array('schooltype' => $this->data['setting']->school_type));

		$this->data['idmanagers'] = $this->idmanager_m->get_order_by_idmanager(array('schooltype' => $this->data['setting']->school_type));

		if($this->data['setting']) {
			if($_POST) {

				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == TRUE) {

					$this->data["subview"] = "setting/index";
					$this->load->view('_layout_main', $this->data);
					echo "te falta";
				} else {

					$array = array();
					for($i=0; $i<count($rules); $i++) {
						if($this->input->post($rules[$i]['field']) == FALSE) {
							$array[$rules[$i]['field']] = 0;
						} else {
							$array[$rules[$i]['field']] = $this->input->post($rules[$i]['field']);
						}
					}

					if(!count($this->get_setting_mark_percentage($array))) {
						$array['mark_1'] = 1;
					}
					if(isset($array['language'])) {
						$this->session->set_userdata('lang',$array['language']);
					}
					if(isset($array['school_year'])) {
						$this->session->set_userdata('defaultschoolyearID',$array['school_year']);
					}
					$array['photo'] = $this->upload_data['file']['file_name'];
					$this->setting_m->insertorupdate($array);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("setting/index"));
				}/**/
			} else {
				$this->data["subview"] = "setting/index";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}

	}

	function get_setting_mark_percentage($inputArrays) {
		$markpercentagesSettings = $this->setting_m->get_markpercentage();
		$array = array();
		if(count($markpercentagesSettings)) {
			foreach ($markpercentagesSettings as $key => $markpercentagesSetting) { 
				if(array_key_exists($markpercentagesSetting->fieldoption, $inputArrays)) {
					if($inputArrays[$markpercentagesSetting->fieldoption] !=0) {
						$array[] = $inputArrays[$markpercentagesSetting->fieldoption];
					}
				}

			}
		}
		return $array;
	}

	public function unique_day() {
		$day = $this->input->post('automation');
		if((int)$day) {
			if($day < 0 || $day > 28) {
				$this->form_validation->set_message("unique_day", "%s ya existe.");
				return FALSE;
			}
			return TRUE;
		} else {
			$this->form_validation->set_message("unique_day", "%s ya existe.");
			return FALSE;
		}
	}
	
	public function unique_attendance() {
		if($this->input->post('attendance') === "0") {
			$this->form_validation->set_message("unique_attendance", "%s es requerido");
			 	return FALSE;
		}
		return TRUE;
	}

	function unique_schoolyear() {
		if($this->input->post('school_year') === "0") {
			$this->form_validation->set_message("unique_schoolyear", "%s es requerido");
			 	return FALSE;
		}
		return TRUE;
	}


}

/* End of file setting.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/setting.php */
