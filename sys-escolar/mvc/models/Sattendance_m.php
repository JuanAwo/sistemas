<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//sattendance_m.php
//asistencia estudiante (model)
//edit sattendance 
//controllers
//model
//views
class Sattendance_m extends MY_Model {

	protected $_table_name = 'attendance';
	protected $_primary_key = 'attendanceID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "monthyear asc";

	function __construct() {
		parent::__construct();
	}

	function get_attendance($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_attendance($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function insert_attendance($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_attendance($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	function update_attendance_classes($array, $id) {
		$this->db->update($this->_table_name, $array, $id);
		return $id;
	}

	public function delete_attendance($id){
		parent::delete($id);
	}


	function update_sub_attendance_classes_new($colname, $status, $schoolyearID, $classesID, $monthyear, $sectionID) {

		$this->db->query("UPDATE $this->_table_name SET $colname='".$status."' WHERE schoolyearID = $schoolyearID AND classesID = $classesID AND monthyear = '".$monthyear."' AND sectionID = $sectionID AND ($colname !='L' OR $colname IS NULL)");
		return TRUE;
	}

	

}

/* End of file attendance_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/attendance_m.php */