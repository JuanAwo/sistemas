<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu_m extends MY_Model {

	protected $_table_name = 'menu';
	protected $_primary_key = 'menuID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "priority desc";

	function __construct() {
		parent::__construct();
	}

	function get_menu($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_menu($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function insert_menu($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_menu($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_menu($id){
		parent::delete($id);
	}
}

/* End of file menu_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/menu_m.php */
