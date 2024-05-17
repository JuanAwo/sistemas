<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Loginlog_m extends MY_Model {

	protected $_table_name = 'loginlog';
	protected $_primary_key = 'loginlogID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "loginlogID asc";

	function __construct() {
		parent::__construct();
	}

	function get_loginlog($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_loginlog($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_single_loginlog($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	function insert_loginlog($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_loginlog($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_loginlog($id){
		parent::delete($id);
	}
}

/* End of file holiday_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/holiday_m.php */
