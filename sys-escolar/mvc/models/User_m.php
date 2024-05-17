<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class user_m extends MY_Model {

	protected $_table_name = 'user';
	protected $_primary_key = 'userID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "usertypeID";

	function __construct() {
		parent::__construct();
	}

	function get_username($table, $data=NULL) {
		$query = $this->db->get_where($table, $data);
		return $query->result();
	}

	function get_username_row($table, $data=NULL) {
		$query = $this->db->get_where($table, $data);
		return $query->row();
	}

	function get_user_by_usertype($userID = null) {
		$this->db->select('*');
		$this->db->from('user');
		$this->db->join('usertype', 'usertype.usertypeID = user.usertypeID', 'LEFT');
		if($userID) {
			$this->db->where(array('userID' => $userID));
			$query = $this->db->get();
			return $query->row();
		} else {
			$query = $this->db->get();
			return $query->result();
		}
	}


	function get_user($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_user($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_single_user($array) {
		$query = parent::get_single($array);
		return $query;
	}

	function insert_user($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_user($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	function delete_user($id){
		parent::delete($id);
	}

	function hash($string) {
		return parent::hash($string);
	}	
}

/* End of file user_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/user_m.php */