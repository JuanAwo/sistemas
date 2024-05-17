<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Exam_type extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("exam_type_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('exam_type', $language);
    }

    public function index() {
        $this->data['exam_types'] = $this->exam_type_m->get_order_by_exam_type();
        $this->data["subview"] = "online_exam/exam_type/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'title',
                'label' => $this->lang->line("exam_type_title"),
                'rules' => 'trim|required|xss_clean|max_length[128]'
            ),
            array(
                'field' => 'typeNumber',
                'label' => $this->lang->line("exam_type_typeNumber"),
                'rules' => 'trim|required|xss_clean|numeric'
            ),
            array(
                'field' => 'status',
                'label' => $this->lang->line("exam_type_status"),
                'rules' => 'trim|required|xss_clean|numeric'
            )
        );
        return $rules;
    }

    public function add() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/datepicker.css',
                'assets/editor/jquery-te-1.4.0.css'
            ),
            'js' => array(
                'assets/editor/jquery-te-1.4.0.min.js',
                'assets/datepicker/datepicker.js'
            )
        );
        if($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['form_validation'] = validation_errors();
                $this->data["subview"] = "online_exam/exam_type/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "title" => $this->input->post("title"),
                    "examTypeNumber" => $this->input->post("typeNumber"),
                    "status" => $this->input->post("status"),
                );
                $this->exam_type_m->insert_exam_type($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("exam_type/index"));
            }
        } else {
            $this->data["subview"] = "online_exam/exam_type/add";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/datepicker.css',
                'assets/editor/jquery-te-1.4.0.css'
            ),
            'js' => array(
                'assets/editor/jquery-te-1.4.0.min.js',
                'assets/datepicker/datepicker.js'
            )
        );
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['exam_type'] = $this->exam_type_m->get_single_exam_type(array('onlineExamTypeID' => $id));
            if($this->data['exam_type']) {
                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "online_exam/exam_type/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "title" => $this->input->post("title"),
                            "examTypeNumber" => $this->input->post("typeNumber"),
                            "status" => $this->input->post("status"),
                        );
                        $this->exam_type_m->update_exam_type($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("exam_type/index"));
                    }
                } else {
                    $this->data["subview"] = "online_exam/exam_type/edit";
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
            $this->data['exam_type'] = $this->exam_type_m->get_single_exam_type(array('onlineExamTypeID' => $id));
            if($this->data['exam_type']) {
                $this->exam_type_m->delete_exam_type($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("exam_type/index"));
            } else {
                redirect(base_url("exam_type/index"));
            }
        } else {
            redirect(base_url("exam_type/index"));
        }
    }
}
