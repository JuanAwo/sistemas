<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Idmanager_m extends MY_Model {

	protected $_table_name = 'idmanager';
	protected $_primary_key = 'idmanagerID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "idmanagerID asc";

	function __construct() {
		parent::__construct();
	}

	function get_idmanager($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_idmanager($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function insert_idmanager($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_idmanager($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_idmanager($id){
		parent::delete($id);
	}
}

/* End of file idmanager_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/idmanager_m.php */