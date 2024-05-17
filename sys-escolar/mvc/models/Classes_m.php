<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Classes_m extends MY_Model {

	protected $_table_name = 'classes';
	protected $_primary_key = 'classesID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "classes_numeric asc";

	function __construct() {
		parent::__construct();
	}

	function get_join_classes() {
		$this->db->select('*');
		$this->db->from('classes');
		$this->db->join('teacher', 'classes.teacherID = teacher.teacherID', 'LEFT');

		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 2) {
			$teacherID = $this->session->userdata('loginuserID');
			$this->db->where(['classes.teacherID' => $teacherID]);
		} 

		$this->db->order_by('classes_numeric asc');
		$query = $this->db->get();
		return $query->result();
	}

	function get_teacher() {
		$this->db->select('*')->from('teacher');
		$query = $this->db->get();
		return $query->result();
	}

	function get_classes($id=NULL, $signal=false) {
	    $newArray = [];
	    if(!is_null($id)) {
	        $newArray['classesID'] = $id;
        }
		$newArray = $this->showTeacherClass($newArray);
		$query = $this->get_order_by_classes($newArray);
		if($signal == true || !is_null($id)) {
		    return count($query) ? $query[0] : NULL;
        }
		return $query;
	}

	function get_single_classes($array=NULL) {
		$array = $this->showTeacherClass($array);
		$query = parent::get_single($array);
		return $query;
	}

	function get_order_by_classes($array=NULL) {
		$array = $this->showTeacherClass($array);
		$query = parent::get_order_by($array);
		return $query;
	}

	function insert_classes($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_classes($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_classes($id){
		parent::delete($id);
	}

	function get_order_by_numeric_classes() {
		$this->db->select('*')->from('classes')->order_by('classes_numeric asc');
		$query = $this->db->get();
		return $query->result();
	}


	public function showTeacherClass($array) {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 2) {
			$teacherID = $this->session->userdata('loginuserID');
			$array['teacherID'] = $teacherID;
		} 

		return $array;
	}
}

/* End of file classes_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/classes_m.php */