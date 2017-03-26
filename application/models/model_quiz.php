<?php
class Model_Quiz extends CI_Model
{
	function __construct() {
        parent::__construct();
    }
	public function get_online_test()
	{
		$sql=$this->db->query("Select * from online_test where active=1 ORDER BY `end_date` DESC");
		$row=$sql->result_array();
		if(isset($row))
		{
			return $row;
		}
		else
		{
			return FALSE;
		}
		/*$condition="test_id="  . $this->db->escape($test_id) . " and mail_id="  . $this->db->escape($mail_id) . "";
		$this->db->select('*');
		$this->db->from('online_test');
		$this->db->where($condition);
		$this->db->limit(4);
		$query=$this->db->get();
		if($query->num_rows()>0)
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}*/
		
	}
	public function sub_category($cid)
	{
		$sql=$this->db->query("Select * from online_test where category_id=$cid and active=1 ORDER BY `end_date` DESC");
		$row=$sql->result_array();
		
		if(isset($row))
		{
			return $row;
		}
		else
		{
			return FALSE;
		}
	}
	public function attended_students($id)
	{
		$sql=$this->db->query("Select * from test where test_id=$id ORDER BY `ticket_id` DESC");
		$row=$sql->result_array();
		
		if(isset($row))
		{
			return $row;
		}
		else
		{
			return FALSE;
		}
	}
	public function get_temp_test_id($time_stamp)
	{
		$sql=$this->db->query("Select * from online_test where create_time=$time_stamp");
		$row=$sql->row_array();
		
		if(isset($row))
		{
			return $row;
		}
		else
		{
			return FALSE;
		}
	}
	public function my_test($id)
	{
		$id=$this->security->xss_clean($id);
		$condition="mail_id="  . $this->db->escape($id) . " ORDER BY `test_id` DESC";
		$this->db->select('*');
		$this->db->from('online_test');
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
	public function get_table()
	{
		$sql=$this->db->query("Select * from online_test ORDER BY `end_date` DESC");
		$new_array=array();
		$table_not=array();
		$array=array();
		$table_all=array();
		$table_active=array();
		$table_pending=array();
		$table_finish=array();
		$table_close=array();
		
		$sno=0;
		foreach ($sql->result_array() as $row)
		{
			$sno =$sno + 1;
			$id=$row['category_id'];
			$category=$this->id_to_cat($id);
			$test_id=$row['test_id'];
			$url= site_url() . '/quiz/detail_view/' . $test_id;
			$btn='<div class="btn-group">
					<a class="btn btn-primary" href="' . $url . '"><i class="fa fa-cogs fa-lg" aria-hidden="true"></i></a>
					</div>';
			$array=array(
				//'sno'=>$sno,
				'title'=>$row['title'],
				'category'=>$category,
				'mail_id'=>$row['mail_id'],
				'start_date'=>$this->cdate($row['start_date']),
				'end_date'=>$this->cdate($row['end_date']),
				'action'=>$btn
			);
			$status=$this->get_test_status($test_id);
			if($status=='Active')
			{
				$table_active[$sno]=$array;
			}		
			elseif($status=='Pending')
			{
				$table_pending[$sno]=$array;
			}		
			elseif($status=='Finished')
			{
				$table_finish[$sno]=$array;
			}
			elseif($status=='Closed')
			{
				$table_close[$sno]=$array;
			}
			elseif($status=='Incomplete')
			{
				$table_not[$sno]=$array;
			}
			$table_all[$sno]=$array;
		}
		$new_array=array(
			'Active'=>$table_active,			
			'Pending'=>$table_pending,			
			'Finished'=>$table_finish,
			'Closed'=>$table_close,
			'All'=>$table_all,
			'Incomplete'=>$table_not
		);
		return $new_array;
		
	}
	public function get_user_table($mail_id)
	{
		$sql=$this->db->query("Select * from online_test ORDER BY `end_date` DESC");
		$new_array=array();
		$array=array();
		$table_all=array();
		$table_active=array();
		$table_finish=array();		
		$sno=0;
		foreach ($sql->result_array() as $row)
		{
			$sno =$sno + 1;
			$id=$row['category_id'];
			$category=$this->id_to_cat($id);
			$test_id=$row['test_id'];
			//$status=$this->get_user_test_status($test_id,$mail_id);
			
			$test_status=$this->get_test_status($test_id);
			$url= site_url() . '/online_test/detail_view/' . $test_id;
			$btn='<div class="btn-group">
					<a class="btn btn-primary" href="' . $url . '"><i class="fa fa-cogs fa-lg" aria-hidden="true"></i></a>
					</div>';
			$array=array(
				//'sno'=>$sno,
				'title'=>$row['title'],
				'category'=>$category,
				'start_date'=>$this->cdate($row['start_date']),
				'end_date'=>$this->cdate($row['end_date']),
				'action'=>$btn
			);
			if(( $test_status=='Ready' OR $test_status=='Active') OR ($test_status=='Finished'  OR $test_status=='Closed'))
			{
				$table_all[$sno]=$array;		
			}
			if( $test_status=='Ready' OR $test_status=='Active')
			{
				$table_active[$sno]=$array;		
			}
			if($test_status=='Finished' OR $test_status=='Closed' )
			{
				$table_finish[$sno]=$array;	
			}	
			
		}
		$new_array=array(
			'Active'=>$table_active,			
			'Finished'=>$table_finish,
			'All'=>$table_all
		);
		return $new_array;
		
	}
	public function check_attended($test_id,$mail_id)
	{
		$mail_id=$this->security->xss_clean($mail_id);
		$test_id=$this->security->xss_clean($test_id);
		$condition="test_id="  . $this->db->escape($test_id) . " and mail_id="  . $this->db->escape($mail_id) . "";
		$this->db->select('*');
		$this->db->from('test');
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
	public function get_test_status($test_id)
	{
		$test=$this->get_test_details($test_id);
		$active=$test['active'];		
		$status="";
		if(isset($test))
		{
			
			$now=now();
			$st=strtotime($test['start_date']);
			$end=strtotime($test['end_date']);
			if($active==1)
			{
				if(($st<$now) && ($end>$now))
				{
					$status='Active';
				}
				elseif($end<$now)
				{
					$status='Finished';
				}
				elseif($st>$now)
				{
					$status='Ready';
				}
				else
				{
					$status='Error';
				}
			}
			elseif($active==2)		
			{
				$status='Incomplete';
			}
			elseif($active==3)		
			{
				$status='Closed';
			}
			elseif($active==10)		
			{
				$status='Pending';
			}
			else
			{
				$status='Error';
			}				
			return $status;
		}
		else
		{
			return 'Error';
		}
		
	}
	public function get_user_test_status($test_id,$mail_id)
	{
		$test_status=$this->get_test_status($test_id);
			$attend=$this->check_attended($test_id,$mail_id);
			$status="";
			if(( $test_status=='Ready' OR $test_status=='Active') OR ($test_status=='Finished'  OR $test_status=='Closed'))
			{
				$status='taball';		
			}
			if( $test_status=='Ready' OR ($test_status=='Active' && $attend==FALSE))
			{
				$status='tab1';		
			}
			if($test_status=='Active' && $attend)
			{
				$status='Taken';		
			}
			if($test_status=='Finished' OR $test_status=='Closed' )
			{
				$status='tab2';		
			}		
			return $status;
		
		
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
	public function get_category()
	{
		$sql=$this->db->query("Select * from category");
		$new_array=array();
		foreach ($sql->result_array() as $row)
		{
			$new_array[$row['category_id']]=$row['title'];
				
		}
		return $new_array;
	}
	public function get_test_details($id)
	{
		$id=$this->security->xss_clean($id);
		$condition="test_id="  . $this->db->escape($id) . "";
		$this->db->select('*');
		$this->db->from('online_test');
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
	public function get_ticket_details($id)
	{
		$ticket_id=$this->security->xss_clean($id);
		$condition="ticket_id="  . $this->db->escape($ticket_id) . "";
		$this->db->select('*');
		$this->db->from('test');
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
	public function get_ticket_id($test_id,$mail_id)
	{
		$test_id=$this->security->xss_clean($test_id);
		$mail_id=$this->security->xss_clean($mail_id);
		$condition="test_id="  . $this->db->escape($test_id) . " and mail_id="  . $this->db->escape($mail_id) . "";
		$this->db->select('*');
		$this->db->from('test');
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
	public function get_questions($id)
	{
		$id=$this->security->xss_clean($id);
		$condition="test_id="  . $this->db->escape($id) . "ORDER BY RAND()";
		$this->db->select('*');
		$this->db->from('questions');
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
	public function cdate($date)
	{
		return (date("M d Y, h:i:s A",strtotime($date)));
	}
	public function new_test($data)
	{
		$this->db->insert('online_test',$data);
		if($this->db->affected_rows()>0)
		{
			return true;
		}
		else
		{
			return FALSE;
		} 
	}
	public function update_test($id,$data)
	{
		$this->db->where('test_id', $id);
		$result=$this->db->update("online_test", $data);	
		if($result)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	public function delete_test($id)
	{
		$tables=array('online_test','questions');
		$this->db->where('test_id', $id);
		$result=$this->db->delete($tables);
		if($result)
			return TRUE;
		else
			return FALSE;
	}
	public function delete_questions($id)
	{
		$tables=array('questions');
		$this->db->where('test_id', $id);
		$result=$this->db->delete($tables);
		if($result)
			return TRUE;
		else
			return FALSE;
	}
	public function validate_question($test_id)
	{
		$test_id=$this->security->xss_clean($test_id);
		$condition="test_id="  . $this->db->escape($test_id) . "";
		$this->db->select('*');
		$this->db->from('questions');
		$this->db->where($condition);
		$query=$this->db->get();
		$ques_count=$query->num_rows();	
		$test=$this->get_test_details($test_id);				
		if($ques_count==$test['no_of_questions'])
		{
			$data=array('active'=>1);
			
		}
		else
		{
			$data=array('active'=>2);
			$res=$this->delete_questions($test_id);
		}
		$result=$this->update_test($test_id,$data);
	}	
	public function new_questions($data)
	{
		$this->db->insert('questions',$data);
		if($this->db->affected_rows()>0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		} 
	}
	public function update_questions($id,$data)
	{
		$this->db->where('question_id', $id);
		$this->db->update("questions", $data);		
	}
	public function take_test($data)
	{
		$this->db->insert('test',$data);
		if($this->db->affected_rows()==1)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		} 
	}
	public function submit_test($ticket_id,$data)
	{
		$this->db->where('ticket_id', $ticket_id);
		if($this->db->update("test", $data))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	public function test_rank($test_id)
	{
		$sql=$this->db->query("SELECT * FROM `test` where `test_id`=$test_id and status=2 ORDER BY `test`.`marks` DESC");
		$rank_list = $sql->result_array();
		if(isset($rank_list))
		{
			$prev=0;
			$sno=0;
			$my_rank=array();
			foreach($rank_list as $rank)
			{
				if($prev!=$rank['marks'])
				{
					$sno=$sno+1;
				}
				$my_rank[]=array(
					'mail_id'=>$rank['mail_id'],
					'marks'=>$rank['marks'],
					'rank'=>$sno
				);
				
				$prev=$rank['marks'];
			}
			return $my_rank;
		}
		else
		{
			return NULL;
		}
		
	}
	public function get_my_rank($test_id,$mail_id)
	{
		$sql=$this->db->query("SELECT * FROM `test` where `test_id`=$test_id and status=2 ORDER BY `test`.`marks` DESC");
		$rank_list = $sql->result_array();
		$num=$sql->num_rows();
		if(isset($rank_list))
		{
			$prev=0;
			$sno=0;
			
			foreach($rank_list as $rank)
			{
				if($rank['mail_id']==$mail_id)
				{
					if($prev!=$rank['marks'])
					{
						$sno=$sno+1;
					}
					$my_rank=array(
						'takers'=>$num,
						'rank'=>$sno
					);
					return $my_rank;
				}
				if($prev!=$rank['marks'])
				{
					$sno=$sno+1;
				}
				$prev=$rank['marks'];
			}
			$my_rank=array(
				'takers'=>$num,
				'rank'=>0
			);
			return $my_rank;
		}
		else
		{
			$my_rank=array(
				'takers'=>0,
				'rank'=>0
			);
			return $my_rank;
		}
		
	}
	public function get_overall_rank($mail_id)
	{
		$sql=$this->db->query("SELECT * FROM `users` where `active`=1 ORDER BY `users`.`points` DESC");
		$rank_list = $sql->result_array();
		$num=$sql->num_rows();
		if(isset($rank_list))
		{
			$prev=0;
			$sno=0 ;
			
			foreach($rank_list as $rank)
			{
				if($rank['mail_id']==$mail_id)
				{
					if($prev!=$rank['points'])
					{
						$sno=$sno+1;
					}
					$my_rank=array(
						'total'=>$num,
						'rank'=>$sno,
						'star'=>$this->get_star($sno,$num)
						
					);
					return $my_rank;
				}
				if($prev!=$rank['points'])
				{
					$sno=$sno+1;
				}
				$prev=$rank['points'];
			}
			$my_rank=array(
				'total'=>$num,
				'rank'=>0,
				'star'=>0
			);
			return $my_rank;
		}
		else
		{
			$my_rank=array(
				'total'=>0,
				'rank'=>0,
				'star'=>0
			);
			return $my_rank;
		}
	}
	public function get_star($rank,$total)
	{
		$avg=round(($rank/$total) * 100);
		if($avg>=0 && $avg<20)
			$star=5;
		elseif($avg>=20 && $avg<40)
			$star=4;
		elseif($avg>=40 && $avg<60)
			$star=3;
		elseif($avg>=60 && $avg<80)
			$star=2;
		elseif($avg>=80 && $avg<90)
			$star=1;
		else
			$star=0;
		return $star;
	}
	public function add_points($mail_id,$points)
	{
		
		$condition="mail_id="  . $this->db->escape($mail_id) . "";
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where($condition);
		$this->db->limit(1);
		$query=$this->db->get();
		if($query->num_rows()==1)
		{
			$row=$query->row_array();
			$prev_points=$row['points'];
			$total=$prev_points+$points;
			$data=array('points'=>$total);
			if($this->model_home->update_user_table($mail_id,$data))
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}
	public function get_student_details($mail_id)
	{
		$condition="mail_id="  . $this->db->escape($mail_id) . "";
		$this->db->select('*');
		$this->db->from('users');
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
	public function get_rank_list()
	{
		$sql=$this->db->query("SELECT * FROM `users` where `active`=1 ORDER BY `users`.`points` DESC");
		$rank_list = $sql->result_array();
		if(isset($rank_list))
		{
			$prev=0;
			$sno=0;
			$my_rank=array();
			foreach($rank_list as $rank)
			{
				if($prev!=$rank['points'])
				{
					$sno=$sno+1;
				}
				$my_rank[]=array(
					'mail_id'=>$rank['mail_id'],
					'marks'=>$rank['points'],
					'rank'=>$sno
				);
				
				$prev=$rank['points'];
			}
			return $my_rank;
		}
		else
		{
			return NULL;
		}
	}
	public function sort_multi_array($array)
	{
		$count=count($array);
		if (is_array($array) && $count > 0) 
		{
			for ($i=0;$i<$count-1;$i++)
			{
				for($j=$i+1;$j<$count;$j++)
				{
					$mr1=$array[$i]['points'];
					$mr2=$array[$j]['points'];
					if($mr1<$mr2)
					{
						$temp=$array[$i];
						$array[$i]=$array[$j];
						$array[$j]=$temp;
					}
				}
            }
        }
		return $array;
    }
    
	public function get_cat_rank_list($cid)
	{
		$sub_category=$this->model_quiz->sub_category($cid);
		if($sub_category==FALSE)
		{
			return FALSE;
		}
		$stud=array();
		foreach($sub_category as $test)
		{
			$test_marks=$this->test_rank($test['test_id']);
			if(!isset($test_marks))
			{
				return FALSE;
			}
			foreach($test_marks as $marks)
			{
				$mid=$marks['mail_id'];
				if(!isset($stud[$mid]))
				{
					//$stud[$mid]=array('mail_id'=>$mid,'points'=>0);
					$stud[$mid]=array('points'=>0);
				}
				$stud[$mid]['points']= $stud[$mid]['points'] + $marks['marks'];
			}
			
		}
		if(!isset($stud))
		{
			return FALSE;
		}
			$prev=0;
			$sno=0;
			//array_multisort($stud['points'], SORT_DESC, $stud);
			arsort($stud);
			//$stud=$this->sort_multi_array($stud);
			$my_rank=array();
			foreach($stud as $key=>$rank)
			{
				if($prev!=$rank['points'])
				{
					$sno=$sno+1;
				}
				$my_rank[]=array(
					'mail_id'=>$key,
					'marks'=>$rank['points'],
					'rank'=>$sno
				);
				
				$prev=$rank['points'];
			}
			return $my_rank;
		
		
	}
	public function get_marks($test_id,$mail_id)
	{
		$sql=$this->db->query("SELECT * FROM `test` where `test_id`=$test_id and status=2 ORDER BY `test`.`marks` DESC");
		$rank_list = $sql->result_array();
		$num=$sql->num_rows();
		$marks=array();
		$c=0;
		//$marks=[1,20];
		if(isset($rank_list))
		{
			$m=0;
			$fm=0;
			foreach($rank_list as $rank)
			{
				$c=$c+1;
				if($rank['mail_id']==$mail_id)
				{
					$m=$rank['marks'];
				}
				if($c==1) $fm=$rank['marks'];
			}
			$marks=array(
				'my'=>$m,
				'top'=>$fm
			);
			return $marks;
		}
		else
		{
			$marks=array(
				'my'=>$m,
				'top'=>$fm
			);
			return $marks;
		}
		
	}
}
