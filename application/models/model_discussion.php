<?php
class Model_Discussion extends CI_Model
{
	function __construct() {
        parent::__construct();
    }
	public function add_discussion($data)
	{
		$this->db->insert('discussions',$data);
		if($this->db->affected_rows()>0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		} 
	}
	public function add_comment($data)
	{
		$this->db->insert('comments',$data);
		if($this->db->affected_rows()>0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		} 
	}
	public function delete_discussion($id)
	{
		$tables=array('discussions','comments');
		$this->db->where('discussion_id', $id);
		$result=$this->db->delete($tables);
		if($result)
			return TRUE;
		else
			return FALSE;
	}
	public function delete_comment($id)
	{
		$this->db->where('comment_id', $id);
		$result=$this->db->delete("comments");
		if($result)
			return TRUE;
		else
			return FALSE;
	}
	
}