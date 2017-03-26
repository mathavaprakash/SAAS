<?php
class Login_Database extends CI_Model
{
	function __construct() {
        parent::__construct();
    }
	
	public function login($data)
	{
		$data=$this->security->xss_clean($data);
		$condition="mail_id="  . $this->db->escape($data['user_id']) . " AND password=" .  $this->db->escape($data['password']);
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where($condition);
		$this->db->limit(1);
		$query=$this->db->get();
		if($query->num_rows()==1)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	public function validate_mail_id($data)
	{
		$data=$this->security->xss_clean($data);
		$condition="mail_id="  . $this->db->escape($data) . "";
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where($condition);
		$this->db->limit(1);
		$query=$this->db->get();
		if($query->num_rows()==1)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	public function reset_password($mail_id,$data)
	{
		//$mail_id=$this->security->xss_clean($mail_id);
		//$data=$this->security->xss_clean($data);
		//$mail_id=$this->db->escape($mail_id);
		//$this->db->set($data);
		
		$this->db->where('mail_id', $mail_id);
		$result=$this->db->update('users', $data);
		if($result)
			return TRUE;
		else
			return FALSE;
		
	}
	public function register($data)
	{
		$this->db->insert('users',$data);
			
		if($this->db->affected_rows()>0)
		{
			
			return TRUE;
		}
		else
		{
			return FALSE;
		} 
	}
	public function read_user_info($userid)
	{
		$userid=$this->security->xss_clean($userid);
		$condition="mail_id="  . $this->db->escape($userid) . "";
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
	public function profile_pic_url($mail_id)
	{
		$this->load->helper('inflector');
		$file_name=  'assets/profile_pics/' . underscore(humanize($mail_id,'.')) . '.jpg';
		$exists=file_exists($file_name);
		if($exists)
		{
			return $file_name;
		}
		else
		{
			$letter=substr($mail_id,0,1);
			$file_name=  'assets/profile_pics/letters/' . $letter . '.png';
			$exists=file_exists($file_name);
			if($exists)
			{
				return $file_name;
			}
			else
			return  'assets/profile_pics/pic.png';
		}
		
	}
	
	public function encryptor($action,$string)
	{

		$output=false;
		$encrypt_method="AES-256-CBC";
		$secret_key='madhava';
		$secret_iv='maddy94';
		$key=hash('sha512',$secret_key);
		$iv=substr(hash('sha512',$secret_iv),0,16);
		if($action=='encrypt')
		{
				$output=openssl_encrypt($string,$encrypt_method,$key,0,$iv);
				$output=base64_encode($output);

		}
		else if($action=='decrypt')
		{
				$output=openssl_decrypt(base64_decode($string),$encrypt_method,$key,0,$iv);
		}

		return $output;
	}
}
