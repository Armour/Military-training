<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Military extends CI_controller
	{
		public function __construct()
		{
			parent::__construct();		
			$this->load->model('military_model');
			$this->load->helper('url');
			$this->load->helper('form');
			$this->load->library('parser');
		}

		public function index()
		{	
			$num = $this->military_model->get_essay_number();
			for ($i=0; $i<$num; $i++)
		    	$this->data['essay'][$i] = $this->military_model->get_essay_in_short($i+1);
			//var_dump($this->data);
			$this->load->view('templates/header');
			$this->parser->parse('military/index',$this->data);
			$this->load->view('templates/footer');
		}
		
		public function register()
		{
			if (!isset($_SESSION)) session_start();
		
			$_SESSION["name"] = '';
			
			$this->load->library('form_validation');
		
			$this->form_validation->set_rules('name','用户名','required');
			$this->form_validation->set_rules('psw','密码','required');
			$this->form_validation->set_rules('email','邮箱','required');
			
			if ($this->form_validation->run() == FALSE)
			{
				$this->load->view('military/register');
			}
			else
			{
				if ($this->military_model->find_user() == 1)
				{
					echo "<script language='JavaScript'> alert('用户名已经存在');</script>";
					$this->load->view('military/register');
				}
				else if ($_SESSION["checkcode"] != $_POST["code"])
				{
					echo "<script language='JavaScript'> alert('验证码不正确');</script>";
					$this->load->view('military/register');
				}
				else
				{
					$this->military_model->add_new_user();
					?>
						<h3><a href="login"> 注册成功!单击进入登录界面  </a></h3>
					<?php
				}
			}
		}
		
		public function login()
		{
			if (!isset($_SESSION)) session_start();
		
			$_SESSION["name"] = '';
		
			$this->load->library('form_validation');
			$this->load->helper('url');
			$this->load->helper('form');
		
			$this->form_validation->set_rules('name','用户名','required');
			$this->form_validation->set_rules('psw','密码','required');
			
			if ($this->form_validation->run() == FALSE)
			{
				$this->load->view('templates/header');
				$this->load->view('military/login');
				$this->load->view('templates/header');
			}
			else
			if ($_SESSION["checkcode"] != $_POST["code"])
			{
				echo "<script language='JavaScript'> alert('验证码不正确');</script>";
				$this->load->view('templates/header');
				$this->load->view('military/login');
				$this->load->view('templates/header');
			}
			else
			if ($this->military_model->find_user() == 0)
			{
				echo "<script type='text/javascript'>  alert('此用户名不存在');</script>";
				$this->load->view('templates/header');
				$this->load->view('military/login');
				$this->load->view('templates/header');
			}
			else
			{
				$psw = hashit($_POST["name"],$_POST["psw"]);
				
				if ($this->military_model->check_user() == 1)
				{
					$_SESSION['name'] = $_POST["name"];
					$data = $this->military_model->get_essay_title();
					$this->load->view('templates/header');
					$this->load->view('military/index',$data);
					$this->load->view('templates/header');
					redirect('military/index','refresh');
				}
				else
				{
					$_SESSION['name'] = '';
					echo "<script type='text/javascript'> alert('密码错误');</script>";
					$this->load->view('templates/header');
					$this->load->view('military/login');
					$this->load->view('templates/header');
				}
			}
		}

		public function add_essay()
		{
			if (@($_SESSION['name']==''))
			{
				echo "<script type='text/javascript'> alert('未登录，没有权限');</script>";
				$this->load->view('templates/header');
				$this->load->view('military/login');
				$this->load->view('templates/footer');
			}	else 
			{
				if (@($_POST['title'] != '' && $_POST['essay']!=''))
				{
					$this->military_model->add_new_essay();
					
					$this->load->view('templates/header');
					$this->load->view('military/success');
					$this->load->view('templates/footer');
				}	else
				{
					$this->load->view('templates/header');
					$this->load->view('military/essay');
					$this->load->view('templates/footer');
				}
			}
		}
		
		public function show_essay($mark = 0)
		{
			if (! is_numeric($mark)) $mark = 0;
			if ($mark!=0)
			{
				$this->data['essay'] = $this->military_model->get_essay($mark);
				
				$this->load->view('templates/header');
				$this->parser->parse('military/show',$this->data);
				$this->load->view('templates/footer');
			}
		}
	}
	
	include_once "function.php";
?>