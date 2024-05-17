<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Studentgroup extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("studentgroup_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('studentgroup', $language);
    }

    public function index() {
        $this->data['studentgroups'] = $this->studentgroup_m->get_order_by_studentgroup();
        $this->data["subview"] = "/studentgroup/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'group',
                'label' => $this->lang->line("studentgroup_group"),
                'rules' => 'trim|required|xss_clean|max_length[50]|callback_unique_group'
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
                $this->data["subview"] = "/studentgroup/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "group" => $this->input->post("group"),
                );
                
                $this->studentgroup_m->insert_studentgroup($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("studentgroup/index"));
            }
        } else {
            $this->data["subview"] = "/studentgroup/add";
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
            $this->data['studentgroup'] = $this->studentgroup_m->get_single_studentgroup(array('studentgroupID' => $id));
            if($this->data['studentgroup']) {
                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "/studentgroup/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "group" => $this->input->post("group")
                        );

                        $this->studentgroup_m->update_studentgroup($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("studentgroup/index"));
                    }
                } else {
                    $this->data["subview"] = "/studentgroup/edit";
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
            $this->data['studentgroup'] = $this->studentgroup_m->get_single_studentgroup(array('studentgroupID' => $id));
            if($this->data['studentgroup']) {
                $this->studentgroup_m->delete_studentgroup($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("studentgroup/index"));
            } else {
                redirect(base_url("studentgroup/index"));
            }
        } else {
            redirect(base_url("studentgroup/index"));
        }
    }

    public function unique_group() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $group = $this->studentgroup_m->get_order_by_studentgroup(array("group" => $this->input->post("group"), "studentgroupID !=" => $id));
            if(count($group)) {
                $this->form_validation->set_message("unique_group", "%s ya existe.");
                return FALSE;
            }
            return TRUE;
        } else {
            $group = $this->studentgroup_m->get_order_by_studentgroup(array("group" => $this->input->post("group")));

            if(count($group)) {
                $this->form_validation->set_message("unique_group", "%s ya existe.");
                return FALSE;
            }
            return TRUE;
        }   
    }
}
