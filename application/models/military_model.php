<?php 
	class Military_model extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}
		
		function add_new_essay()
		{
			$data = array
			(
				'title' => $this->input->post('title',TRUE),
				'essay' => $this->input->post('essay',TRUE),
				'author' => $this->input->post('author',TRUE),
				'time' => $this->input->post('time',TRUE),
			);
			$this->db->insert('military_essay',$data);
		}
		
		function add_new_user()
		{
			$data = array
			(
					'name'     =>$this->input->post('name',TRUE),
					'psw'      =>$this->input->post('psw',TRUE),
					'email'    =>$this->input->post('email',TRUE),
			);
			$this->db->insert('military_user',$data);
		}
		
		function get_essay_number()
		{
			$query = $this->db->get('lol_essay');
			return $query->num_rows();
		}
		
		function get_essay_in_short($slug = 0)
		{
			if (!is_numeric($slug)) $slug = 0;
			if ($slug!=0)
			{
				$array = array('id'=>$slug);
				$query = $this->db->select('title,author,time')->get_where('military_essay',$array);	
				if ($query->num_row()>0)
				{
					$data = $query->row_array();
					return $data;
				}	
			}
		}
		
		function get_essay($slug = 0)
		{
			if (!is_numeric($slug)) $slug = 0;
			if ($slug!=0)
			{
				$array = array('id'=>$slug);
				$query = $this->db->get_where('military_essay',$array);
				if ($query->num_row()>0)
				{
					$data = $query->row_array();
					return $data;
				}
			}
		}
		
		function find_user()
		{
			$array = array('name'=>$this->input->post('name',TRUE));
			$query = $this->db->get_where('military_user',$array);
			if ($query->num_rows() > 0)
				return 1;
			else
				return 0;
		}
		
		function check_user()
		{
			$query = $this->db->get_where('military_user',array('name'=>$this->input->post('name',TRUE),'psw'=>$this->input->post('psw',TRUE)));
			if ($query->num_rows() > 0)
				return 1;
			else
				return 0;
		}
	}
?>