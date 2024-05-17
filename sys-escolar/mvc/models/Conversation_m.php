<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class conversation_m extends MY_Model {

	protected $_table_name = 'conversations';
	protected $_primary_key = 'id';
	protected $_primary_filter = 'intval';
	protected $_order_by = "id ASC";

	function __construct() {
		parent::__construct();
	}

	function get_conversation($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_my_conversations() {
		$userID = $this->session->userdata("loginuserID");
		$usertypeID = $this->session->userdata("usertypeID");
		$this->db->distinct();
		$this->db->select('*');
        $this->db->from('conversation_user a'); 
        $this->db->join('conversations b', 'a.conversation_id=b.id', 'left');
        $this->db->join('conversation_msg c', 'a.conversation_id=c.conversation_id', 'left');
        $this->db->where('a.user_id',$userID);
        $this->db->where('a.usertypeID',$usertypeID);
        $this->db->where('a.trash',0);
        $this->db->where('c.start',1);
        $this->db->where('b.draft',0);
        $this->db->order_by('b.id','desc');
        $this->db->group_by('b.id');
        $query = $this->db->get(); 
        if($query->num_rows() != 0) {
            return $query->result_array();
        } else {
            return false;
        }
	}

	public function get_my_conversations_draft() {
		$userID = $this->session->userdata("loginuserID");
		$usertypeID = $this->session->userdata("usertypeID");
		$this->db->select('*');
        $this->db->from('conversation_user a'); 
        $this->db->join('conversations b', 'a.conversation_id=b.id', 'left');
        $this->db->join('conversation_msg c', 'a.conversation_id=c.conversation_id', 'left');
        $this->db->where('a.user_id',$userID);
        $this->db->where('a.usertypeID',$usertypeID);
        $this->db->where('a.trash',0);
        $this->db->where('a.is_sender',1);
        $this->db->where('c.start',1);
        $this->db->where('b.draft',1);
        $this->db->order_by('b.id','desc');
        $query = $this->db->get(); 
        if($query->num_rows() != 0) {
            return $query->result_array();
        } else {
            return false;
        }
	}

	public function get_my_conversations_sent() {
		$userID = $this->session->userdata("loginuserID");
		$usertypeID = $this->session->userdata("usertypeID");
		$this->db->select('*');
        $this->db->from('conversation_user a'); 
        $this->db->join('conversations b', 'a.conversation_id=b.id', 'left');
        $this->db->join('conversation_msg c', 'a.conversation_id=c.conversation_id', 'left');
        $this->db->where('a.user_id',$userID);
        $this->db->where('a.usertypeID',$usertypeID);
        $this->db->where('a.trash',0);
        $this->db->where('a.is_sender',1);
        $this->db->where('c.start',1);
        $this->db->where('b.draft',0);
        $this->db->order_by('b.id','desc');
        $query = $this->db->get(); 
        if($query->num_rows() != 0) {
            return $query->result_array();
        } else {
            return false;
        }
	}

	public function get_my_conversations_trash() {
		$userID = $this->session->userdata("loginuserID");
		$usertypeID = $this->session->userdata("usertypeID");
		$this->db->select('*');
        $this->db->from('conversation_user a'); 
        $this->db->join('conversations b', 'a.conversation_id=b.id', 'left');
        $this->db->join('conversation_msg c', 'a.conversation_id=c.conversation_id', 'left');
        $this->db->where('a.user_id',$userID);
        $this->db->where('a.usertypeID',$usertypeID);
        $this->db->where('a.trash',1);
        $this->db->where('c.start',1);
        $this->db->where('b.draft',0);
        $this->db->order_by('b.id','desc');
        $query = $this->db->get(); 
        if($query->num_rows() != 0) {
            return $query->result_array();
        }
        else {
            return false;
        }
	}

	public function get_conversation_msg_by_id($id=null) {
		$this->db->order_by("msg_id", "asc"); 
		$query = $this->db->get_where('conversation_msg', array('conversation_id' => $id));
		return $query->result();
	}

	public function get_student_by_class($studentID) {
		$query = $this->db->get_where('student', array('studentID' => $studentID));
		return $query->result();	
	}

	function get_recivers($single=FALSE, $array=NULL) {
		if ($array) {
			$query = $this->db->get_where($single, $array);
		} else {
			$query = $this->db->get($single);
		}
		return $query->result();
	}

	function get_order_by_conversation($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function insert_conversation($array) {
		$insetID = parent::insert($array);
		return $insetID;
	}
	function insert_conversation_user($array) {
		$this->db->insert("conversation_user", $array);
		return true;
	}
	function insert_conversation_msg($array) {
		$this->db->insert("conversation_msg", $array);
		return true;
	}

	function update_conversation($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_conversation($id){
		parent::delete($id);
	}

	function get_order_by_student_class($classesID) {
		$query = $this->db->query("SELECT * FROM student WHERE classesID = $classesID order by roll asc");
		return $query->result();
	}

	function get_all_student() {
		$query = $this->db->query("SELECT * FROM student order by studentID asc");
		return $query->result();
	}
	
	public function user_Check($conv_id, $user_id, $usertypeID) {
		$query = $this->db->get_where('conversation_user', array('conversation_id' => $conv_id, 'user_id' => $user_id, 'usertypeID' => $usertypeID));
		return $query->row();
	}

	public function trash_conversation($data, $id) {
		$usertypeID = $this->session->userdata("usertypeID");
		$username = $this->session->userdata("username");
		$userID = $this->session->userdata("loginuserID");
		$query = $this->db->get_where('conversation_user', array('conversation_id' => $id, 'user_id' => $userID, 'usertypeID' => $usertypeID));
		if (count($query->row())==1) {
			$this->db->where('conversation_id', $id);
			$this->db->where('user_id', $userID);
			$this->db->where('usertypeID', $usertypeID);
			$this->db->update('conversation_user', $data); 
			return true;
		} else {
			return false;
		}
	}

	function get_usertype_by_permission() {
		$this->db->select('*');
		$this->db->from('permission_relationships');
		$this->db->join('permissions', 'permissions.permissionID = permission_relationships.permission_id', 'LEFT');
		$this->db->join('usertype', 'usertype.usertypeID = permission_relationships.usertype_id', 'LEFT');
		$this->db->where(array('permissions.name' => 'conversation'));
		$query = $this->db->get();
		return $query->result();
	}
}

/* End of file conversation_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/conversation_m.php */
