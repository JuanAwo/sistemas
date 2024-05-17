<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Expense extends Admin_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("expense_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('expense', $language);
	}

	public function index() {
		$this->data['expenses'] = $this->expense_m->get_expense();
		$this->data["subview"] = "expense/index";
		$this->load->view('_layout_main', $this->data);
	}

	protected function rules() {
		$rules = array(
				 array(
					'field' => 'expense',
					'label' => $this->lang->line("expense_expense"),
					'rules' => 'trim|required|xss_clean|max_length[128]'
				),
				array(
					'field' => 'date',
					'label' => $this->lang->line("expense_date"),
					'rules' => 'trim|required|max_length[10]|xss_clean|callback_date_valid'
				),
				array(
					'field' => 'amount',
					'label' => $this->lang->line("expense_amount"),
					'rules' => 'trim|required|numeric|max_length[11]|xss_clean|callback_valid_number'
				),
				array(
					'field' => 'note',
					'label' => $this->lang->line("expense_note"),
					'rules' => 'trim|max_length[200]|xss_clean'
				)
			);
		return $rules;
	}

	public function add() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/datepicker/datepicker.css',
			),
			'js' => array(
				'assets/datepicker/datepicker.js',
			)
		);
		if($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$this->data["subview"] = "expense/add";
				$this->load->view('_layout_main', $this->data);
			} else {
				$array = array(
					"expense" => $this->input->post("expense"),
					"amount" => $this->input->post("amount"),
					"note" => $this->input->post("note"),
					"create_date" => date("Y-m-d"),
					"date" => date("Y-m-d", strtotime($this->input->post("date"))),
					"expenseday" => date("d", strtotime($this->input->post("date"))),
					"expensemonth" => date("m", strtotime($this->input->post("date"))),
					"expenseyear" => date("Y", strtotime($this->input->post("date"))),
					'usertypeID' => $this->session->userdata('usertypeID'),
					'uname' => $this->session->userdata('name'),
					'userID' => $this->session->userdata('loginuserID'),
					'schoolyearID' => $this->data['siteinfos']->school_year
				);
				$this->expense_m->insert_expense($array);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("expense/index"));
			}
		} else {
			$this->data["subview"] = "expense/add";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/datepicker/datepicker.css',
			),
			'js' => array(
				'assets/datepicker/datepicker.js',
			)
		);

		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$this->data['expense'] = $this->expense_m->get_expense($id);
			if($this->data['expense']) {
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "expense/edit";
						$this->load->view('_layout_main', $this->data);
					} else {
						$array = array(
							"expense" => $this->input->post("expense"),
							"amount" => $this->input->post("amount"),
							"note" => $this->input->post("note"),
							"create_date" => date("Y-m-d"),
							"date" => date("Y-m-d", strtotime($this->input->post("date"))),
							"expenseday" => date("d", strtotime($this->input->post("date"))),
							"expensemonth" => date("m", strtotime($this->input->post("date"))),
							"expenseyear" => date("Y", strtotime($this->input->post("date"))),
							'usertypeID' => $this->session->userdata('usertypeID'),
							'uname' => $this->session->userdata('name'),
							'userID' => $this->session->userdata('loginuserID'),
						);

						$this->expense_m->update_expense($array, $id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("expense/index"));
					}
				} else {
					$this->data["subview"] = "expense/edit";
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
			$this->expense_m->delete_expense($id);
			$this->session->set_flashdata('success', $this->lang->line('menu_success'));
			redirect(base_url("expense/index"));
		} else {
			redirect(base_url("expense/index"));
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

	function valid_number() {
		if($this->input->post('amount') && $this->input->post('amount') < 0) {
			$this->form_validation->set_message("valid_number", "%s es un número invalido.");
			return FALSE;
		}
		return TRUE;
	}
}

/* End of file expense.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/expense.php */
