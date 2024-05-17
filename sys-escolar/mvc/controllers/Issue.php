<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Issue extends Admin_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("lmember_m");
		$this->load->model("book_m");
		$this->load->model("issue_m");
		$this->load->model("student_m");
		$this->load->model("parents_m");
		$this->load->model('invoice_m');
		
		$language = $this->session->userdata('lang');
		$this->lang->load('issue', $language);	
	}

	public function index() {
		$usertypeID = $this->session->userdata("usertypeID");
		if($usertypeID == 3) {
			$username = $this->session->userdata("username");			
			$student = $this->student_m->get_single_student(array("username" => $username));
			if($student->library === '1') {
				$lmember = $this->lmember_m->get_single_lmember(array('studentID' => $student->studentID));
				$lID = $lmember->lID;
				$this->data['libraryID'] = $lID;

				$this->data['issues'] = $this->issue_m->get_issue_with_books(array("lID" => $lID));
				$this->data["subview"] = "issue/index";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data["subview"] = "issue/message";
				$this->load->view('_layout_main', $this->data);
			}
		} elseif($usertypeID == 4) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css',
				),
				'js' => array(
					'assets/select2/select2.js'
				)
			);

			$username = $this->session->userdata("username");
			$parent = $this->parents_m->get_single_parents(array('username' => $username));
			$this->data['students'] = $this->student_m->get_order_by_student(array('parentID' => $parent->parentsID));
			$id = htmlentities(escapeString($this->uri->segment(3)));

			if((int)$id) {
				$checkstudent = $this->student_m->get_single_student(array('studentID' => $id));
				if(count($checkstudent)) {
					$classesID = $checkstudent->classesID;
					$this->data['set'] = $id;

					if($checkstudent->library === '1') {
						$lmember = $this->lmember_m->get_single_lmember(array('studentID' => $checkstudent->studentID));
						$lID = $lmember->lID;
						$this->data['libraryID'] = $lID;

						$this->data['issues'] = $this->issue_m->get_issue_with_books(array("lID" => $lID));
						$this->data["subview"] = "issue/index_parent";
						$this->load->view('_layout_main', $this->data);
					} else {
						$this->data["subview"] = "issue/message_parent";
						$this->load->view('_layout_main', $this->data);
					}
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data['issues'] = array();
				$this->data["subview"] = "issue/search_parent";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$ulID = htmlentities(escapeString($this->uri->segment(3)));
			$lID = htmlentities(escapeString($this->input->post("lid")));
			if($lID != "" || !empty($lID)) {
				$this->data['issues'] = $this->issue_m->get_issue_with_books(array("lID" => $lID));
				$this->data['libraryID'] = $lID;
				$this->data["subview"] = "issue/index";
				$this->load->view('_layout_main', $this->data);
			} elseif($ulID) {

				$this->data['issues'] = $this->issue_m->get_issue_with_books(array("lID" => $ulID));
				$this->data['libraryID'] = $ulID;
				$this->data["subview"] = "issue/index";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data["subview"] = "issue/search";
				$this->load->view('_layout_main', $this->data);
			}
		}
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'lid', 
				'label' => $this->lang->line("issue_lid"), 
				'rules' => 'trim|required|xss_clean|max_length[40]|callback_unique_lID'
			), 
			array(
				'field' => 'book', 
				'label' => $this->lang->line("issue_book"),
				'rules' => 'trim|required|xss_clean|max_length[60]|callback_unique_book_call|callback_unique_quantity|callback_unique_book'
			), 
			array(
				'field' => 'author', 
				'label' => $this->lang->line("issue_author"),
				'rules' => 'trim|required|xss_clean'
			), 
			array(
				'field' => 'subject_code', 
				'label' => $this->lang->line("issue_subject_code"),
				'rules' => 'trim|required|xss_clean'
			), 
			array(
				'field' => 'serial_no', 
				'label' => $this->lang->line("issue_serial_no"),
				'rules' => 'trim|required|xss_clean|max_length[40]'
			),
			array(
				'field' => 'due_date', 
				'label' => $this->lang->line("issue_due_date"),
				'rules' => 'trim|required|xss_clean|max_length[10]|callback_date_valid|callback_wrong_date'
			),
			array(
				'field' => 'note', 
				'label' => $this->lang->line("issue_note"), 
				'rules' => 'trim|max_length[200]|xss_clean'
			)
		);
		return $rules;
	}

	public function add() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css',
				'assets/datepicker/datepicker.css'
			),
			'js' => array(
				'assets/select2/select2.js',
				'assets/datepicker/datepicker.js'
			)
		);
		$this->data['books'] = $this->book_m->get_book();
		if($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$this->data["subview"] = "issue/add";
				$this->load->view('_layout_main', $this->data);			
			} else {
				$array = array(
					"lID" => $this->input->post("lid"),
					"bookID" => $this->input->post("book"),
					"serial_no" => $this->input->post("serial_no"),
					"issue_date" => date("Y-m-d"),
					"due_date" => date("Y-m-d", strtotime($this->input->post("due_date"))),
					"note" => $this->input->post("note")
				);

				$quantity = $this->book_m->get_single_book(array("bookID" => $this->input->post("book")));
				$all_due_quantity = ($quantity->due_quantity)+1;

				$this->book_m->update_book(array("due_quantity" => $all_due_quantity), $this->input->post("book"));
				$this->issue_m->insert_issue($array);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("issue/index"));
			}
		} else {
			$this->data["subview"] = "issue/add";
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
		$lID = htmlentities(escapeString($this->uri->segment(4)));
		$this->data['books'] = $this->book_m->get_book();
		if((int)$id && $lID) {
			$this->data['issue'] = $this->issue_m->get_issue_with_books(array('issueID' => $id), TRUE);
			$dbGet_bookID = $this->data['issue']->bookID;
			$this->data['bookinfo'] = $this->book_m->get_book($dbGet_bookID);

			if($this->data['issue']) {
				$this->data['set'] = $lID;
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "issue/edit";
						$this->load->view('_layout_main', $this->data);			
					} else {
						$array = array(
							"lID" => $this->input->post("lid"),
							"serial_no" => $this->input->post("serial_no"),
							"due_date" => date("Y-m-d", strtotime($this->input->post("due_date"))),
							"note" => $this->input->post("note")
						);

						$this->issue_m->update_issue($array, $id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						if($this->session->userdata('usertypeID') == 4) {
							$lmember = $this->lmember_m->get_single_lmember(array('lID' => $this->data['issue']->lID));
							redirect(base_url("issue/index/$lmember->studentID"));
						} else {
							redirect(base_url("issue/index/$lID"));
						}
					}
				} else {
					$this->data["subview"] = "issue/edit";
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
		if((int)$id) {
			$this->data['book'] = $this->issue_m->get_issue_with_books(array('issueID' => $id), TRUE);
			if($this->data['book']) {
				$this->data["subview"] = "issue/view";
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

	public function returnbook() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$lID = htmlentities(escapeString($this->uri->segment(4)));
		if((int)$id && $lID) {
			$date = date("Y-m-d");
			$issue = $this->issue_m->get_issue($id);
			if($issue) {
				$dbGet_bookID = $issue->bookID;
				$book = $this->book_m->get_book($dbGet_bookID);
				$due_quantity = ($book->due_quantity-1);

				$this->book_m->update_book(array("due_quantity" => $due_quantity), $dbGet_bookID);
				$this->issue_m->update_issue(array("return_date" => $date), $id);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				if($this->session->userdata('usertypeID') == 4) {
					$lmember = $this->lmember_m->get_single_lmember(array('lID' => $issue->lID));
					redirect(base_url("issue/index/$lmember->studentID"));
				} else {
					redirect(base_url("issue/index/$lID"));
				}
			} else {
				redirect(base_url("issue/index/$lID"));
			}
		} else {
			redirect(base_url("issue/index/$lID"));
		}
	}

	public function unique_quantity() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$bookID = $this->input->post("book");
		$author = $this->input->post("author");

		if($id) {
			if((int)$bookID) {
				$bookandauthor = $this->issue_m->get_single_issue(array("bookID" => $bookID, "issueID" => $id));

				if(count($bookandauthor)) {
					return TRUE;
				}
			} else {
				$this->form_validation->set_message("unique_quantity", "%s no esta disponible.");
				return FALSE;
			}

		} else {
			if((int)$bookID) {
				$bookandauthor = $this->book_m->get_single_book(array("bookID" => $bookID));

				if(count($bookandauthor)) {
					if($bookandauthor->due_quantity >= $bookandauthor->quantity) {
						$this->form_validation->set_message("unique_quantity", "%s no esta disponible.");
						return FALSE;
					}
					return TRUE;
				}
			} else {
				$this->form_validation->set_message("unique_quantity", "%s no esta disponible.");
				return FALSE;
			}
		}	
	}

	function unique_lID() {
		$lID = $this->lmember_m->get_single_lmember(array("lID" => $this->input->post("lid")));
		if(!count($lID)) {
			$this->form_validation->set_message("unique_lID", "%s  es incorrecto");
			return FALSE;	
		}
		return TRUE;
	}

	function unique_book() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if($id) {
			$book = $this->issue_m->get_single_issue(array("bookID" => $this->input->post("book"), "return_date" => NULL, "issueID" => $id));
			if(count($book)) {
				return TRUE;
			} else {
				$this->form_validation->set_message("unique_book", "%s ya existe.");
				return FALSE;
			}
		} else {
			$book = $this->issue_m->get_single_issue(array("bookID" => $this->input->post("book"), "return_date" => NULL, "lID" => $this->input->post("lid")));
			if(count($book)) {
				$this->form_validation->set_message("unique_book", "%s ya existe.");
				return FALSE;	
			}
			return TRUE;
		}
	}

	function unique_book_call() {
		if($this->input->post('book') === '0') {
			$this->form_validation->set_message("unique_book_call", "%s es requerido.");
	     	return FALSE;
		}
		return TRUE;
	}

	function wrong_date() {
		$due_date = date("Y-m-d", strtotime($this->input->post("due_date")));
		$date = date("Y-m-d");
		if($due_date < $date) {
			$this->form_validation->set_message("wrong_date", "%s es menor de la fecha actual.");
	     	return FALSE;
		} else {
			return TRUE;
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

	function bookIDcall() {
		$bookID = $this->input->post('bookID');
		if($bookID) {
			$bookinfo = $this->book_m->get_book($bookID);
			$author = $bookinfo->author;
			$subject_code = $bookinfo->subject_code;
			$json = array("author" => $author, "subject_code" => $subject_code);
			header("Content-Type: application/json", true);
			echo json_encode($json);
			exit;
		}
	}

	function match_bookauthor() {
		$bookID = $this->input->post("book");
		$author = $this->input->post("author");

		if((int)$bookID && $bookID != "") {
			$bookandauthor = $this->book_m->get_single_book(array("bookID" => $bookID));
			if($bookandauthor) {
				if($bookandauthor->author == $author) {
					return TRUE;
				} else {
					$this->form_validation->set_message("match_bookauthor", "%s los datos del autor no coinciden.");
					return FALSE;
				}
			} else {
				$this->form_validation->set_message("match_bookauthor", "%s los datos del autor no coinciden.");
				return FALSE;
			}
		} else {
			$this->form_validation->set_message("match_bookauthor", "%s los datos del autor no coinciden.");
			return FALSE;
		}
	}

	function valid_number () {
		if($this->input->post('fine') && $this->input->post('fine') < 0) {
			$this->form_validation->set_message("valid_number", "%s es un número invalido.");
			return FALSE;
		}
		return TRUE;
	}

	public function student_list() {
		$studentID = $this->input->post('id');
		if((int)$studentID) {
			$string = base_url("issue/index/$studentID");
			echo $string;
		} else {
			redirect(base_url("issue/index"));
		}
	}

	public function add_invoice() {
		$libraryID = $this->input->post('libraryID');
		$amount = $this->input->post('amount');
		$librarymember = $this->issue_m->get_student_by_libraryID_with_studenallinfo($libraryID);

		if(count($librarymember)) {
			$array = array(
				'schoolyearID' => $this->data['siteinfos']->school_year,
				'classesID' => $librarymember->classesID,
				'studentID' => $librarymember->studentID,
				'feetype' => $this->lang->line('issue_bookfine'),
				'amount' => $amount,
				'discount' => 0,
				'paidstatus' => 0,
				'userID' => $this->session->userdata('loginuserID'),
				'usertypeID' => $this->session->userdata('usertypeID'),
				'uname' => $this->session->userdata('name'),
				'date' => date('Y-m-d'),
				'create_date' => date('Y-m-d'),
				'day' => date('d'),
				'month' => date('m'),
				'year' => date('Y'),
				'deleted_at' => 1	
			);
			$this->invoice_m->insert_invoice($array);
			$this->session->set_flashdata('success', $this->lang->line('menu_success'));
			if($this->session->userdata('usertypeID') == 4) {
				echo base_url("issue/index/".$librarymember->studentID);
			} else {
				echo base_url("issue/index/".$libraryID);
			}
		}
	}
}

/* End of file issue.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/issue.php */