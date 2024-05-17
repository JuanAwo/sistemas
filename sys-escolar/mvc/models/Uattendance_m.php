<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Uattendance_m extends MY_Model {

	protected $_table_name = 'uattendance';
	protected $_primary_key = 'uattendanceID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "monthyear asc";

	function __construct() {
		parent::__construct();
	}

	function get_uattendance($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_uattendance($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function insert_uattendance($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_uattendance($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_uattendance($id){
		parent::delete($id);
	}


	function update_uattendance_new($colname, $status, $schoolyearID, $monthyear) {
		$this->db->query("UPDATE $this->_table_name SET $colname='".$status."' WHERE schoolyearID = $schoolyearID AND monthyear = '".$monthyear."' AND ($colname ='L' OR $colname = 'A' OR $colname = 'P' OR $colname IS NULL)");
		return TRUE;
	}

	

}

/* End of file attendance_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/attendance_m.php */