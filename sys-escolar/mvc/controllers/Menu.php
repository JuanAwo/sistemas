<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends Admin_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("menu_m");
		redirect('dashboard/index');

	}

	public function index() {
		$this->data['menus'] = $this->menu_m->get_order_by_menu();
		$this->data["subview"] = "menu/index";
		$this->load->view('_layout_main', $this->data);
	}

	protected function rules() {
		$rules = array(
				array(
					'field' => 'menuName',
					'label' => 'Menu Name',
					'rules' => 'trim|required|xss_clean|max_length[120]'
				),
				array(
					'field' => 'parentID',
					'label' => 'Parent',
					'rules' => 'trim|numeric|max_length[11]|xss_clean'
				),
				array(
					'field' => 'link',
					'label' => 'Link',
					'rules' => 'trim|required|xss_clean'
				),
				array(
					'field' => 'icon',
					'label' => 'Icon',
					'rules' => 'trim|xss_clean'
				),
				array(
					'field' => 'status',
					'label' => 'Status',
					'rules' => 'trim|numeric|xss_clean'
				),
				array(
					'field' => 'priority',
					'label' => 'Priority',
					'rules' => 'trim|numeric|max_length[200]|xss_clean'
				),
                array(
					'field' => 'pullRight',
					'label' => 'Pull Right',
					'rules' => 'trim|max_length[200]|xss_clean'
				)
			);
		return $rules;
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

        $this->data['menus'] = $this->menu_m->get_order_by_menu();

		if($_POST) {

			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$this->data["subview"] = "menu/add";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->menu_m->insert_menu(array_filter($this->input->post()));
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("menu/index"));
			}
		} else {
			$this->data["subview"] = "menu/add";
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
		
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$this->data['menu'] = $this->menu_m->get_menu($id);
            // dd($this->data['menu']);
            $this->data['menus'] = $this->menu_m->get_order_by_menu();
			if($this->data['menu']) {
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "menu/edit";
						$this->load->view('_layout_main', $this->data);
					} else {
                        $postData = $this->input->post();
                        $postData['status'] = (int) $postData['status'];
//                        dd($postData);
						$this->menu_m->update_menu($postData, $id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("menu/index"));
					}
				} else {
					$this->data["subview"] = "menu/edit";
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
			$this->menu_m->delete_menu($id);
			$this->session->set_flashdata('success', $this->lang->line('menu_success'));
			redirect(base_url("menu/index"));
		} else {
			redirect(base_url("menu/index"));
		}
	}

    public function menuList()
    {
        $menus = json_decode(json_encode(pluck($this->menu_m->get_order_by_menu(), 'obj', 'menuID')), true);
        dd($this->menuTrees($menus));
    }


    public function menuTrees($dataset) {
    	$tree = array();
    	foreach ($dataset as $id=>&$node) {
    		if ($node['parentID'] == 0) {
    			$tree[$id]=&$node;
    		} else {
    			if (!isset($dataset[$node['parentID']]['child']))
    				$dataset[$node['parentID']]['child'] = array();
    			$dataset[$node['parentID']]['child'][$id] = &$node;
    		}
    	}
    	return $tree;
    }
}
