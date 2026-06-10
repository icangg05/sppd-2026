<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends MY_Controller {
	function __construct()
	{
		parent::__construct();
		// error_reporting(0);
		$this->load->model('setting/m_log');
		$this->load->library('ion_auth');
		
		
	}
	
	public function index()
	{
		
		$this->data['page_title'] = 'Login';
		$this->load->library('form_validation');
		$this->form_validation->set_rules('identity', 'Identity', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('remember','Remember me','integer');
		if($this->form_validation->run()===TRUE)
		{
			$remember = (bool) $this->input->post('remember');
			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
			{
				//save log
				$log['kode_log_action'] = "51";
				$log['action'] = "LOGIN";
				$log['kode_log_action_table'] = "0";
				$log['action_table'] = "";
				$this->m_log->create($log);
				//redirect
				redirect('beranda', 'refresh');
			}
			else
			{
				$this->session->set_flashdata('notif', 'Nama user atau password salah!');
				redirect('login', 'refresh');
			}
		}
		else
		{
			$this->load->helper('form');
			$this->load->view('login','admin_master');
		}
	}
	public function loginx()
	{
		
		$this->data['page_title'] = 'Login';
		$this->load->library('form_validation');
		$this->form_validation->set_rules('identity', 'Identity', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('remember','Remember me','integer');
		if($this->form_validation->run()===TRUE)
		{
			$remember = (bool) $this->input->post('remember');
			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
			{
				//save log
				$log['kode_log_action'] = "51";
				$log['action'] = "LOGIN";
				$log['kode_log_action_table'] = "0";
				$log['action_table'] = "";
				$this->m_log->create($log);
				//redirect
				redirect('beranda', 'refresh');
			}
			else
			{
				$this->session->set_flashdata('notif', 'Nama user atau password salah!');
				redirect('login', 'refresh');
			}
		}
		else
		{
			$this->load->helper('form');
			$this->load->view('login','admin_master');
		}
	}
	
	public function logout()
	{
		//save log
		// $log['kode_log_action'] = "52";
		// $log['action'] = "LOGOUT";
		// $log['kode_log_action_table'] = "0";
		// $log['action_table'] = "";
		// $this->m_log->create($log);
		//redirect
		$this->ion_auth->logout();
		redirect('login', 'refresh');
	}
	
}
