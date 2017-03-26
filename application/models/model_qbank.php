<?php
class Model_QBank extends CI_Model
{
	function __construct() {
        parent::__construct();
    }
	public function add_question($data)
	{
		$this->db->insert('question_bank',$data);
		if($this->db->affected_rows()>0)
		{
			return true;
		}
		else
		{
			return FALSE;
		} 
	}
	public function get_questions($id)
	{
		$id=$this->security->xss_clean($id);
		$condition="sub_id="  . $this->db->escape($id) . "";
		$this->db->select('*');
		$this->db->from('question_bank');
		$this->db->where($condition);
		$query=$this->db->get();
		if($query->num_rows()>0)
		{
			return $query->result_array();
		}
		else
		{
			return NULL;
		}
	}
	public function get_ques($id)
	{
		$id=$this->security->xss_clean($id);
		$condition="question_id="  . $this->db->escape($id) . "";
		$this->db->select('*');
		$this->db->from('question_bank');
		$this->db->where($condition);
		$query=$this->db->get();
		if($query->num_rows()>0)
		{
			return $query->row_array();
		}
		else
		{
			return FALSE;
		}
	}
	public function delete_question($id)
	{
		$tables=array('question_bank');
		$this->db->where('question_id', $id);
		$result=$this->db->delete($tables);
		if($result)
			return TRUE;
		else
			return FALSE;
	}
	public function update_question($qid,$data)
	{
		$this->db->where('question_id', $qid);
		$result=$this->db->update("question_bank", $data);	
		if($result)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function closetags($html) 
	{
		preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
		$openedtags = $result[1];
		preg_match_all('#</([a-z]+)>#iU', $html, $result);
		$closedtags = $result[1];
		$len_opened = count($openedtags);
		if (count($closedtags) == $len_opened) {
			return $html;
		}
		$openedtags = array_reverse($openedtags);
		for ($i=0; $i < $len_opened; $i++) {
			if (!in_array($openedtags[$i], $closedtags)) {
				$html .= '</'.$openedtags[$i].'>';
			} else {
				unset($closedtags[array_search($openedtags[$i], $closedtags)]);
			}
		}
		return $html;
	}
}
