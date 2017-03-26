<?php
class Model_Progress extends CI_Model
{
	function __construct() {
        parent::__construct();
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
	
	public function strength($mail_id)
	{
		$category=array();
		$category= $this->model_home->category();
		$sql=$this->db->query("Select * from test where status=2");
		$ms=$sql->result_array();
		$arr=array();
		foreach($category as $cc)
		{
			$arr[$cc['category_id']-1]=0;
		}
		if(isset($ms))
		{
			foreach($ms as $test)
			{
				if($test['mail_id']==$mail_id)
				{
					$tid=$test['test_id'];
					$m=$test['marks'];
					$sql1=$this->db->query("Select * from online_test where test_id=$tid");
					$ids=$sql1->result_array();
					if(isset($ids))
					{
						foreach($category as $cat1)
						{
							$c=$cat1['category_id'];
							foreach($ids as $cat)
							{
								if($cat['category_id']==$c)
								{
								$arr[$c-1]=$arr[$c-1]+$m;
								}	
							}
							
						}	
					}	
				}	
			}
			
		}
		return $arr;
		
	}
	/*	
	public function strength($mail_id)
	{
		$category=array();
		$category= $this->model_home->category();
		$sql=$this->db->query("Select * from test where mail_id=$mail_id and status=2");
		$ms=$sql->result_array();
		$arr=array();
		$count=array();
		$tot=0;
		$i=0;
		foreach($category as $cc)
		{
			$arr[$i]=0;
			$count[$i]=0;
			$i++;
		}
		$arr[$i]=0;
		$count[$i]=0;
		if(isset($ms))
		{
			foreach($ms as $test)
			{
				$tid=$test['test_id'];
				$sql1=$this->db->query("Select * from online_test where test_id=$tid");
				$catt=$sql1->row_array();
				$indx=$catt['category_id'];
				$arr[$indx]=$arr[$indx]+$test['marks'];
				$count[$indx]=$count[$indx]+1;
			}
		}
		foreach($category as $catt)
		{
			$arr[$catt['category_id']]=$arr[$catt['category_id']]/$count[$cc['category_id']];
		
		}
		return $arr;
		
	} */

	public function cat_prog($mail_id,$c)
	{
		$sql=$this->db->query("Select * from test where status=2");
		$ms=$sql->result_array();
		
$ap=array();
$ap[]=0;
		
		if(isset($ms))
		{
			foreach($ms as $test)
			{
				if($test['mail_id']==$mail_id)
				{
					$tid=$test['test_id'];
					$m=$test['marks'];
					$sql1=$this->db->query("Select * from online_test where test_id=$tid");
					$ids=$sql1->result_array();
					if(isset($ids))
					{
						foreach($ids as $cat)
						{
							if($cat['category_id']==$c)
							{
								$quest=$this->model_quiz->get_test_details($cat['test_id']);
					$nos=$quest['no_of_questions'];
					if($nos!=0) 
							$ap[]=$m/$nos;
							}
							
						}
					}
					
				}
				
			}
				
		}
	
		return $ap;
	}
	public function cat_prog_dates($mail_id,$c)
	{
		$sql=$this->db->query("Select * from test where status=2");
		$ms=$sql->result_array();
		
$ap=array();
$ap[]=0;
		
		if(isset($ms))
		{
			foreach($ms as $test)
			{
				if($test['mail_id']==$mail_id)
				{
					$dt=strtotime($test['start_date']);
					$dat=date('d-m-y',$dt);
					
					$tid=$test['test_id'];
					
					$sql1=$this->db->query("Select * from online_test where test_id=$tid");
					$ids=$sql1->result_array();
					if(isset($ids))
					{
						foreach($ids as $cat)
						{
							if($cat['category_id']==$c)
							{
							$ap[]=$dat." ".$cat['title'];
							}
							
						}
					}
					
				}
				
			}
				
		}
	
		return $ap;
		
		

	}
	public function weeks($mail_id)
	{
	$sql=$this->db->query("Select * from test where status=2 ORDER BY `end_date` ASC");
	$res=$sql->result_array();
	$dates=array();
	//$dates[]="";
	foreach($res as $r)
		{
			if($r['mail_id']==$mail_id)
				$dates[]=$r['end_date'];
		}
		$startd=$dates[0];
		$lastd=$dates[sizeof($dates)-1];
		$weeks[]=array();
		$dt=date('y-m-d',strtotime($startd));
		$weeks[]=$dt;
		while(strtotime($dt)<=strtotime($lastd))
		{
		$dt=date('y-m-d',strtotime($dt.' + 6 days'));
		$weeks[]=$dt;
		}
	return $weeks;
	}
	public function weekwise($mail_id,$st,$end)
	{
	$sql=$this->db->query("Select * from test where status=2 and end_date ORDER BY `end_date` ASC");
	$ms=$sql->result_array();
	$st=date('y-m-d',strtotime($st)) ;
	$end=date('y-m-d',strtotime($end)) ;	
	
		$arr=array();
		$arr[]=0;
		if(isset($ms))
		{
			foreach($ms as $mark)
			{
				$sd=date('y-m-d',strtotime($mark['start_date'])) ; 
				$ed=date('y-m-d',strtotime($mark['end_date'])) ; 
				
				if($sd>=$st && $ed<=$end){
				if($mark['mail_id']==$mail_id)
				{
					$quest=$this->model_quiz->get_test_details($mark['test_id']);
					$nos=$quest['no_of_questions'];
					if($nos!=0) 
					$arr[]=$mark['marks']/$nos;
				}
				}
				
			}
		//$arr[]=0;
				
		}
		return $arr;
	}
	public function weekwise_lbl($mail_id,$st,$end,$lbls)
	{
	$category=array();
		$category= $this->model_home->category();
		$sql=$this->db->query("Select * from test where status=2");
		$ms=$sql->result_array();
		$st=date('y-m-d',strtotime($st)) ;
	$end=date('y-m-d',strtotime($end)) ;	
	
		$darr=array();
		$darr[]="";
		if(isset($ms))
		{
			foreach($ms as $mark)
			{
				$sd=date('y-m-d',strtotime($mark['start_date'])) ; 
				$ed=date('y-m-d',strtotime($mark['end_date'])) ; 
				
				if($sd>=$st && $ed<=$end){
				
				if($mark['mail_id']==$mail_id)
				{
					//$arr[]=$mark['marks'];
					$dt=strtotime($mark['start_date']);
					$dat=date('d-m-y',$dt);
					
					$tid=$mark['test_id'];
					$sql1=$this->db->query("Select * from online_test where test_id=$tid");
					$ids=$sql1->result_array();
					$c='';
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
								}
							}	
						}
					}
					
					$darr[]=$dat." ".$c;
					
				}}
				
			}
				
		}
		return $darr;
	}
}
