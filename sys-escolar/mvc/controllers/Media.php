<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Media extends Admin_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("media_m");
		$this->load->model("media_category_m");
		$this->load->model("classes_m");
		$this->load->model("student_m");
		$this->load->model("media_share_m");
		$this->load->model('usertype_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('media', $language);
		$this->load->helper("file");
	}


	function userTableCall($usertypeID, $userID) {
	    $this->load->model('systemadmin_m');
	    $this->load->model('teacher_m');
	    $this->load->model('student_m');
	    $this->load->model('parents_m');
	    $this->load->model('user_m');

	    $findUserName = ''; 
	    if($usertypeID == 1) {
	       $user = $this->db->get_where('systemadmin', array("usertypeID" => $usertypeID, 'systemadminID' => $userID));
	        $alluserdata = $user->row();
	        if(count($alluserdata)) {
	            $findUserName = $alluserdata->name;
	        }
	        return $findUserName;
	    } elseif($usertypeID == 2) {
	        $user = $this->db->get_where('teacher', array("usertypeID" => $usertypeID, 'teacherID' => $userID));
	        $alluserdata = $user->row();
	        if(count($alluserdata)) {
	            $findUserName = $alluserdata->name;
	        }
	        return $findUserName;
	    } elseif($usertypeID == 3) {
	        $user = $this->db->get_where('student', array("usertypeID" => $usertypeID, 'studentID' => $userID));
	        $alluserdata = $user->row();
	        if(count($alluserdata)) {
	            $findUserName = $alluserdata->name;
	        }
	        return $findUserName;
	    } elseif($usertypeID == 4) {
	        $user = $this->db->get_where('parents', array("usertypeID" => $usertypeID, 'parentsID' => $userID));
	        $alluserdata = $user->row();
	        if(count($alluserdata)) {
	            $findUserName = $alluserdata->name;
	        }
	        return $findUserName;
	    } else {
	        $user = $this->db->get_where('user', array("usertypeID" => $usertypeID, 'userID' => $userID));
	        $alluserdata = $user->row();
	        if(count($alluserdata)) {
	            $findUserName = $alluserdata->name;
	        }
	        return $findUserName;
	    }
	}

	public function index() {
		$allUserTypes = $this->usertype_m->get_usertype();
		$this->data['allusertype'] = pluck($allUserTypes, 'usertype', 'usertypeID');
		$this->data['usertypeID'] = $this->session->userdata('usertypeID');
		$this->data['userID'] = $this->session->userdata('loginuserID');
		$share_table = $this->media_share_m->get_media_share();
		
		if($this->data['usertypeID'] == 1) {
			$this->data['folders'] = $this->media_category_m->get_media_category();
			$this->data['files'] = $this->media_m->get_order_by_media(array('mcategoryID'=>0));
		} else {
			$this->data['folders'] = $this->media_category_m->get_order_by_mcategory(array('userID'=> $this->data['userID'], 'usertypeID'=>$this->data['usertypeID']));
			$this->data['files'] = $this->media_m->get_order_by_media(array('userID'=> $this->data['userID'], 'usertypeID'=>$this->data['usertypeID'], 'mcategoryID'=>0));
		}
		
		foreach ($share_table as $key => $item) {
			if ($item->public) {
				if (!$item->file_or_folder) {
					array_push($this->data['files'], $this->media_m->get_media($item->item_id));
				} else {
					array_push($this->data['folders'], $this->media_category_m->get_media_category($item->item_id));
				}
			} else {
				$classID = 0;
				if ($this->data['usertypeID'] == 3) {
					$student = $this->student_m->get_student($this->data['userID']);
					$classID = $student->classesID;

					if($item->classesID == $classID) {
						if (!$item->file_or_folder) {
							array_push($this->data['files'], $this->media_m->get_media($item->item_id));
						} else {
							array_push($this->data['folders'], $this->media_category_m->get_media_category($item->item_id));
						}
					}
				}
			}
		}

		foreach ($this->data['files'] as $key => $share) {
			$query = $this->userTableCall($share->usertypeID, $share->userID);
			$this->data['files'][$key] = (object) array_merge( (array)$share, array( 'shared_by' => $query));
		}

		foreach ($this->data['folders'] as $key => $share_folder) {
			$query = $this->userTableCall($share_folder->usertypeID, $share_folder->userID);
			$this->data['folders'][$key] = (object) array_merge( (array)$share_folder, array( 'shared_by' => $query));
		}

		$this->data['folders'] = array_map("unserialize", array_unique(array_map("serialize", $this->data['folders'])));
		$this->data['files'] = array_map("unserialize", array_unique(array_map("serialize", $this->data['files'])));

		$this->data["subview"] = "media/index";
		$this->load->view('_layout_main', $this->data);
	}

	public function create_folder() {
		if(permissionChecker('media_add')) {
			$array = array();
			$array['userID'] = $this->session->userdata('loginuserID');
			$array['usertypeID'] = $this->session->userdata('usertypeID');
			$array['folder_name'] = $this->input->post('folder_name');
			$this->form_validation->set_rules('folder_name', 'Folder name', 'required|trim|xss_clean|max_length[128]');
			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error', 'Some error occurred!');
			} else {
				if($this->media_category_m->insert_mcategory($array)) {
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				} else {
					$this->session->set_flashdata('error', 'Some error occurred!');
				}
			}
		} else {
			$this->session->set_flashdata('error', 'Permission Deny');
		}
	}

	public function view() {
		$usertypeID = $this->session->userdata('usertypeID');
		$userID = $this->session->userdata('loginuserID');
		$allUserTypes = $this->usertype_m->get_usertype();
		$this->data['allusertype'] = pluck($allUserTypes, 'usertype', 'usertypeID');
		$this->data['usertypeID'] = $this->session->userdata('usertypeID');
		$this->data['userID'] = $userID;

		$folderID = htmlentities(escapeString($this->uri->segment(3)));
		$folder_info = $this->media_category_m->get_media_category($folderID);
		$this->data['f'] = $this->media_category_m->get_media_category($folderID);
		if ((int)$folderID) {
			$this->data['files'] = $this->media_m->get_order_by_media(array("mcategoryID"=>$folderID));
			if(isset($_POST['upload_file'])) {
				if (($folder_info->userID == $userID && $folder_info->usertypeID == $usertypeID) || $usertypeID == 1) {
					if(isset($_FILES['file']['name'])=="") {
						$this->session->set_flashdata('error', 'File not found');
						redirect(base_url('media/view/'.$folderID),'refresh');
					} else {
						$array = array();
						$array['userID'] = $userID;
						$array['usertypeID'] = $usertypeID;
						$array['mcategoryID'] = $folderID;
						$file_name = $_FILES["file"]['name'];
						$file_name_display = $_FILES["file"]['name'];
						$file_name_rename = rand(1, 100000000000);
			            $explode = explode('.', $file_name);
			            if(count($explode) >= 2) {
				            $new_file = $file_name_rename.'.'.end($explode);
							$config['upload_path'] = "./uploads/media";
							$config['allowed_types'] = "gif|jpg|png|jpeg|pdf|doc|xml|docx|GIF|JPG|PNG|JPEG|PDF|DOC|XML|DOCX|xls|xlsx|txt|ppt|csv";
							$config['file_name'] = $new_file;
							$config['max_size'] = '1024';
							$config['max_width'] = '3000';
							$config['max_height'] = '3000';
							$array['file_name'] = $new_file;
							$array['file_name_display'] = $file_name_display;
							$this->load->library('upload', $config);
							if(!$this->upload->do_upload("file")) {
								$this->data["attachment_error"] = $this->upload->display_errors();
								$this->session->set_flashdata('error', $this->data["attachment_error"]);
								redirect(base_url("media/view/$folderID"));
							} else {
								$data = array("upload_data" => $this->upload->data());
								$this->media_m->insert_media($array);
								$this->session->set_flashdata('success', $this->lang->line('menu_success'));
								redirect(base_url("media/view/$folderID"));
							}
						} else {
							$this->data["attachment_error"] = "Invalid file";
							$this->session->set_flashdata('error', 'invalid file format! please upload only gif|jpg|png|pdf|docx|doc|csv|txt|ppt|xls|xlsx files');
							redirect(base_url("media/view/$folderID"));
						}
					}
				} else {
					$this->session->set_flashdata('error', 'You are not authorized to upload files in this folder!');
					redirect(base_url('media/view/'.$folderID),'refresh');
				}
			}
			
			foreach ($this->data['files'] as $key => $share) {
				$query = $this->userTableCall($share->usertypeID, $share->userID);
				$this->data['files'][$key] = (object) array_merge( (array)$share, array( 'shared_by' => $query));
			}
			$this->data["subview"] = "media/view";
			$this->load->view('_layout_main', $this->data);
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	protected function rules() {
		$rules = array(
				 array(
					'field' => 'file',
					'label' => $this->lang->line("file"),
					'rules' => 'trim|required|xss_clean|max_length[128]'
				)
			);
		return $rules;
	}

	public function add() {
		if(permissionChecker('media_add')) {
			$usertypeID = $this->session->userdata('usertypeID');
			$userID = $this->session->userdata('loginuserID');
			if($_FILES['file']['name']=="") {
				$this->session->set_flashdata('error', 'Please select file!');
				redirect(base_url('media/index'));
			} else {
				$array = array();
				$array['userID'] = $userID;
				$array['usertypeID'] = $usertypeID;
				$file_name = $_FILES["file"]['name'];
				$file_name_display = $_FILES["file"]['name'];
				$file_name_rename = rand(1, 100000000000);
	            $explode = explode('.', $file_name);
	            if(count($explode) >= 2) {
		            $new_file = $file_name_rename.'.'.end($explode);
					$config['upload_path'] = "./uploads/media";
					$config['allowed_types'] = "gif|jpg|png|jpeg|pdf|doc|xml|docx|GIF|JPG|PNG|JPEG|PDF|DOC|XML|DOCX|xls|xlsx|txt|ppt|csv";
					$config['file_name'] = $new_file;
					$config['max_size'] = '1024';
					$config['max_width'] = '3000';
					$config['max_height'] = '3000';
					$array['file_name'] = $new_file;
					$array['file_name_display'] = $file_name_display;
					$this->load->library('upload', $config);
					if(!$this->upload->do_upload("file")) {
						$this->data["attachment_error"] = $this->upload->display_errors();
						$this->session->set_flashdata('error', $this->data["attachment_error"]);
						redirect(base_url("media/index"));
					} else {
						$data = array("upload_data" => $this->upload->data());
						$this->media_m->insert_media($array);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("media/index"));
					}
			 	} else {
					$this->data["attachment_error"] = "Invalid file";
					$this->session->set_flashdata('error', 'invalid file format! please upload only gif|jpg|png|pdf|docx|doc|csv|txt|ppt|xls|xlsx files');
					redirect(base_url("media/index"));
				}
			}
		} else {
			$this->data["subview"] = "errorpermission";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function media_share() {
		if ($this->input->post('share_with') == "0") {
			$this->session->set_flashdata('error', 'Please select share with!');
			redirect($_SERVER['HTTP_REFERER']);
		} else {
			$array = array();
			$media_info = $this->input->post('media_info');
			$array['classesID']	= $this->input->post('classesID');
			if (strpos($media_info,'folder') !== false) {
			    $folderID = explode("folder",$this->input->post('media_info'));
			    $array['file_or_folder'] = 1;
			    $array['item_id'] = $folderID['1'];
				$is_shared_media = $this->media_share_m->get_single(array('file_or_folder'=> 1,'item_id' => $array['item_id']));
			} else {
				$array['file_or_folder'] = 0;
				$array['item_id'] = $this->input->post('media_info');
				$is_shared_media = $this->media_share_m->get_single(array('file_or_folder'=> 0,'item_id' => $array['item_id']));
			}

			if ($this->input->post('share_with')=="public") {
				$array['public']	= 1;
			} else {
				$array['public']	= 0;
			}

			if($array['classesID'] == '') {
				$array['classesID'] = 0;
			}

			if (count($is_shared_media)) {
				if ($this->media_share_m->update_media_share($array, $is_shared_media->shareID)) {
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect($_SERVER['HTTP_REFERER']);
				} else {
					$this->session->set_flashdata('error', 'error occured!');
					redirect($_SERVER['HTTP_REFERER']);
				}
			} else {
				if ($this->media_share_m->insert_media_share($array)) {
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect($_SERVER['HTTP_REFERER']);
				} else {
					$this->session->set_flashdata('error', 'error occured!');
					redirect($_SERVER['HTTP_REFERER']);
				}
			}
		}
	}

	public function deletef() {
		if(permissionChecker('media_delete')) {
			$usertypeID = $this->session->userdata('usertypeID');
			$userID = $this->session->userdata('loginuserID');
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if($id) {
				$all_files = $this->media_m->get_order_by_media(array("mcategoryID"=>$id));	
				if (count($all_files)) {
					foreach ($all_files as $file) {
						if(($usertypeID == $file->usertypeID && $userID == $file->userID) || ($usertypeID == 1)) {
							$path = "uploads/media/".$file->file_name;
							if(config_item('demo') == FALSE) {
								if(unlink($path)) {
									$this->media_m->delete_media($file->mediaID);
									$this->media_share_m->delete_share_file($file->mediaID);
								}
							} else {
								$this->media_m->delete_media($file->mediaID);
								$this->media_share_m->delete_share_file($file->mediaID);
							}
						}
					}
				}
			
				$mediaCategory = $this->media_category_m->get_media_category($id);
				if(count($mediaCategory)) {
					if(($usertypeID == $mediaCategory->usertypeID && $userID == $mediaCategory->userID) || ($usertypeID == 1)) {
						$this->media_category_m->delete_mcategory($id);
						$this->media_share_m->delete_share_folder($id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("media/index"));
					} else {
						$this->session->set_flashdata('error', 'Access Deined');
						redirect(base_url("media/index"));
					}
				} else {

				}
			} else {
				redirect(base_url("media/index"));
			}
		} else {
			$this->data["subview"] = "errorpermission";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function delete() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$usertypeID = $this->session->userdata('usertypeID');
		$userID = $this->session->userdata('loginuserID');
		if($id) {
			$file = $this->media_m->get_media($id);
			if(($usertypeID == $file->usertypeID && $userID == $file->userID) || ($usertypeID == 1)) {
				$path = "uploads/media/".$file->file_name;
				if(config_item('demo') == FALSE) {
					if (unlink($path)) {
						$this->media_share_m->delete_share_file($id);
						$this->media_m->delete_media($id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					}
				} else {
					$this->media_share_m->delete_share_file($id);
					$this->media_m->delete_media($id);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				}
				redirect($_SERVER['HTTP_REFERER']);
			} else {
				redirect($_SERVER['HTTP_REFERER']);
			}
		} else {
			redirect($_SERVER['HTTP_REFERER']);
		}
	}

	function classcall() {
		$allclass = $this->classes_m->get_classes();
		echo "<option value='0'>", $this->lang->line("all_class"),"</option>";
		foreach ($allclass as $value) {
			echo "<option value=\"$value->classesID\">",$value->classes,"</option>";
		}
	}
}

/* End of file media.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/media.php */
