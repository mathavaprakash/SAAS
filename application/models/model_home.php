<?php
class Model_Home extends CI_Model
{
	function __construct() {
        parent::__construct();
    }
	public function category()
	{
		$sql=$this->db->query("Select * from category");
		return $sql->result_array();
	}
	public function id_to_cat($id)
	{
		$sql=$this->db->query("Select * from category where category_id=$id");
		$row = $sql->row_array();
		if(isset($row))
		{
			return $row['title'];
		}
		else
		{
			return NULL;
		}
	}
	public function my_chapter($id)
	{
		$id=$this->security->xss_clean($id);
		$condition="mail_id="  . $this->db->escape($id) . " ORDER BY `sub_id` DESC";
		$this->db->select('*');
		$this->db->from('sub_category');
		$this->db->where($condition);
		$query=$this->db->get();
		if($query->num_rows()>0)
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
		
	}
	public function sub_category($id)
	{
		$sql=$this->db->query("Select * from sub_category where category_id=$id and status=1 ORDER BY `sub_id` DESC");
		return $sql->result_array();
	}
	public function sub_category1($id)
	{
		$sql=$this->db->query("Select * from sub_category where category_id=$id ORDER BY `sub_id` DESC LIMIT 6");
		return $sql->result_array();
	}
	public function pending_chapter()
	{
		$sql=$this->db->query("Select * from sub_category where status=0 ORDER BY `sub_id` DESC");
		return $sql->result_array();
	}
	public function sub_category2()
	{
		$sql=$this->db->query("Select * from sub_category  where status=1 ORDER BY `sub_id` DESC LIMIT 3");
		return $sql->result_array();
	}
	public function content($sub_id)
	{
		
		$id=$this->security->xss_clean($sub_id);
		$condition="sub_id="  . $this->db->escape($id) . "";
		$this->db->select('*');
		$this->db->from('sub_category');
		$this->db->where($condition);
		$this->db->limit(1);
		$query=$this->db->get();
		if($query->num_rows()==1)
		{
			return $query->row_array();
		}
		else
		{
			return FALSE;
		}
	}
	public function add_category($data)
	{
		$this->db->insert('category',$data);
		if($this->db->affected_rows()>0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		} 
	}
	public function add_sub_category($data)
	{
		$this->db->insert('sub_category',$data);
		if($this->db->affected_rows()==1)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		} 
	}
	public function edit_sub_category($id,$data)
	{
		$this->db->where('sub_id', $id);
		$result=$this->db->update("sub_category", $data);
		if($result)
			return TRUE;
		else
			return FALSE;
	}
	
	public function delete_sub_category($id)
	{
		$this->db->where('sub_id', $id);
		$result=$this->db->delete("sub_category");
		if($result)
			return TRUE;
		else
			return FALSE;
	}
	public function delete_category($id)
	{
		$tables=array('category','sub_category');
		$this->db->where('category_id', $id);
		$result=$this->db->delete($tables);
		if($result)
			return TRUE;
		else
			return FALSE;
	}
	public function get_days_ago($date)
	{
		$now=now();
		$st=strtotime($date);
		$diff=$now-$st;
		if($diff>0 && $diff<=60)
		{
			$str="$diff Secs Ago";
		}
		elseif($diff>60 && $diff<=3600)
		{
			$aa=round($diff/60);
			$str=($aa>1) ? "$aa Mins Ago" : "$aa Min Ago";
		}
		elseif($diff>3600 && $diff<=86400)
		{
			$aa=round($diff/3600);
			$str=($aa>1) ? "$aa Hours Ago" : "$aa Hour Ago";
		}
		elseif($diff>86400 && $diff<=2592000)
		{
			$aa=round($diff/86400);
			$str=($aa>1) ? "$aa Days Ago" : "$aa Day Ago";
		}
		elseif($diff>2592000 && $diff<=31104000)
		{
			$aa=round($diff/2592000);
			$str=($aa>1) ? "$aa Months Ago" : "$aa Month Ago";
		}
		elseif($diff>31104000)
		{
			$aa=round($diff/31104000);
			$str=($aa>1) ? "$aa Years Ago" : "$aa Year Ago";
		}
		else
		{
			$str='';
		}			
		return $str;
	}
	public function update_user_table($id,$data)
	{
		$this->db->where('mail_id', $id);
		$result=$this->db->update("users", $data);
		if($result)
			return TRUE;
		else
			return FALSE;
	}
	
	public function marks($mail_id)
	{
		//$arr1=[0];
		$sql=$this->db->query("Select * from test where status=2");
		$ms=$sql->result_array();
		$arr=array();
		$arr[]=0;
		if(isset($ms))
		{
			foreach($ms as $mark)
			{
				if($mark['mail_id']==$mail_id)
				{
					$quest=$this->model_quiz->get_test_details($mark['test_id']);
					
					$nos=$quest['no_of_questions'];
					
					if($nos!=0) 
					$arr[]=$mark['marks']/$nos;
				}
				
			}
		
				
		}
		return $arr;

	}
	public function dates($mail_id,$lbls)
	{
		//$arr1=[0];
		
		$category=array();
		$category= $this->model_home->category();
		$sql=$this->db->query("Select * from test where status=2");
		$ms=$sql->result_array();
		$darr=array();
		$darr[]="";
		if(isset($ms))
		{
			foreach($ms as $mark)
			{
				if($mark['mail_id']==$mail_id)
				{
					//$arr[]=$mark['marks'];
					$dt=strtotime($mark['start_date']);
					$dat=date('d-m-y',$dt);
					
					$tid=$mark['test_id'];
					$sql1=$this->db->query("Select * from online_test where test_id=$tid");
					$ids=$sql1->result_array();
					if(isset($ids))
					{
						foreach($category as $cat1)
						{
							$ca=$cat1['category_id'];
							foreach($ids as $cat)
							{
								if($cat['category_id']==$ca)
								{
								$c=$lbls[$ca-1]." - ".$cat['title'];
								$darr[]=$dat." ".$c;
								}
							}	
						}
					}
					
					
					
				}
				
			}
				
		}
		return $darr;

	}
	public function get_discussions($sub_id)
	{
		//if($this->uri->segment(1)=='admin_home')  $sub_id=$this->uri->segment(3);
		//else $sub_id=$this->uri->segment(3);
		$sql=$this->db->query("Select * from discussions where sub_id=$sub_id and state=1 ORDER BY `creation_date` DESC");
		return $sql->result_array();
	}
	public function get_comments($did)
	{
		$sql=$this->db->query("Select * from comments where discussion_id=$did and state=1");
		return $sql->result_array();
	}
}
