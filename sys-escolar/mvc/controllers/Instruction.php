<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Instruction extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("instruction_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('instruction', $language);
    }

    public function index() {
        $this->data['instructions'] = $this->instruction_m->get_order_by_instruction();
        $this->data["subview"] = "online_exam/instruction/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'title',
                'label' => $this->lang->line("instruction_title"),
                'rules' => 'trim|required|xss_clean|max_length[128]'
            ),
            array(
                'field' => 'content',
                'label' => $this->lang->line("instruction_content"),
                'rules' => 'trim|required|xss_clean'
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
                $this->data["subview"] = "online_exam/instruction/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "title" => $this->input->post("title"),
                    "content" => $this->input->post("content"),
                );
                $this->instruction_m->insert_instruction($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("instruction/index"));
            }
        } else {
            $this->data["subview"] = "online_exam/instruction/add";
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
            $this->data['instruction'] = $this->instruction_m->get_single_instruction(array('instructionID' => $id));
            if($this->data['instruction']) {
                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "online_exam/instruction/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "title" => $this->input->post("title"),
                            "content" => $this->input->post("content")
                        );

                        $this->instruction_m->update_instruction($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("instruction/index"));
                    }
                } else {
                    $this->data["subview"] = "online_exam/instruction/edit";
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
            $this->data['instruction'] = $this->instruction_m->get_single_instruction(array('instructionID' => $id));
            if($this->data['instruction']) {
                $this->data["subview"] = "online_exam/instruction/view";
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

    public function delete() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['instruction'] = $this->instruction_m->get_single_instruction(array('instructionID' => $id));
            if($this->data['instruction']) {
                $this->instruction_m->delete_instruction($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("instruction/index"));
            } else {
                redirect(base_url("instruction/index"));
            }
        } else {
            redirect(base_url("instruction/index"));
        }
    }

    public function print_preview() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['instruction'] = $this->instruction_m->get_single_instruction(array('instructionID' => $id));
            if($this->data['instruction']) {
                $this->data['panel_title'] = $this->lang->line('panel_title');
                $this->printView($this->data, 'online_exam/instruction/print_preview');
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
            $this->data['instruction'] = $this->instruction_m->get_single_instruction(array('instructionID' => $id));
            if($this->data['instruction']) {
                $email = $this->input->post('to');
                $subject = $this->input->post('subject');
                $message = $this->input->post('message');

                $this->viewsendtomail($this->data['instruction'], 'online_exam/instruction/print_preview', $email, $subject, $message);
            } else {
                $this->data["subview"] = "error";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }

    }
}
