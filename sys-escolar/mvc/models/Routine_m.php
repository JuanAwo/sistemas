<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "Classes_m.php";

class Routine_m extends MY_Model {

	protected $_table_name = 'routine';
	protected $_primary_key = 'routineID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "classesID asc";

	function __construct() {
		parent::__construct();
	}

	function get_classes() {
        $class = new Classes_m;
        return $class->get_order_by_classes();
	}

	function get_subject($id) {
		$query = $this->db->get_where('subject', array('classesID' => $id));
		return $query->result();
	}

	function get_join_all($id) {
		$this->db->select('*');
		$this->db->from('routine');
		$this->db->where(array('routine.classesID' => $id ));
		$this->db->join('teacher', 'teacher.teacherID = routine.teacherID', 'LEFT');
		$this->db->join('classes', 'classes.classesID = routine.classesID', 'LEFT');
		$this->db->join('section', 'section.sectionID = routine.sectionID', 'LEFT');
		$this->db->join('subject', 'subject.subjectID = routine.subjectID AND subject.classesID = routine.classesID', 'LEFT');
		$query = $this->db->get();
		return $query->result();
	}

	function get_join_all_wsection($id, $sectionID) {
		$this->db->select('*');
		$this->db->from('routine');
		$this->db->where(array('routine.classesID' => $id, 'routine.sectionID' => $sectionID));
		$this->db->join('teacher', 'teacher.teacherID = routine.teacherID', 'LEFT');
		$this->db->join('classes', 'classes.classesID = routine.classesID', 'LEFT');
		$this->db->join('section', 'section.sectionID = routine.sectionID', 'LEFT');
		$this->db->join('subject', 'subject.subjectID = routine.subjectID AND subject.classesID = routine.classesID', 'LEFT');

		$query = $this->db->get();
		return $query->result();
	}

	function get_routine($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_routine($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function insert_routine($array) {
		$id = parent::insert($array);
		return $id;
	}

	function update_routine($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_routine($id){
		parent::delete($id);
	}
}

/* End of file routine_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/routine_m.php */