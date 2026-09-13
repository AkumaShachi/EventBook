<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->global['isNavbar'] = FALSE;
		$this->global['isFooter'] = FALSE;
	}

	public function login()
	{
		$this->global['pageTitle'] = 'EventBook - Login';
		$this->_render('auth/login');
	}

	public function register()
	{
		$this->global['pageTitle'] = 'EventBook - Register';
		$this->_render('auth/register');
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('login');
	}

	public function do_register()
	{
		$this->load->model('User_model');

		$data = array(
			'first_name'  => $this->input->post('firstName'),
			'last_name'   => $this->input->post('lastName'),
			'email'       => $this->input->post('email'),
			'phone'       => $this->input->post('phone'),
			'password'    => $this->input->post('password')
		);

		$result = $this->User_model->create_user($data);

		if ($result) {
			redirect('login');
		} else {
			$this->session->set_flashdata('register_error', 'Registration failed. Please try again.');
			redirect('register');
		}
	}

	public function do_login()
	{
		$this->load->model('User_model');

		$email = $this->input->post('email');
		$password = $this->input->post('password');

		$user = $this->User_model->get_user_by_email($email);

		if ($user && password_verify($password, $user->password)) {
			$this->session->set_userdata(array(
				'user_id'   => $user->id,
				'email'     => $user->email,
				'first_name'=> $user->first_name,
				'last_name' => $user->last_name,
				'phone'     => $user->phone,
				'r_id'      => $user->r_id,
				'photo'     => $user->photo,
				'institution' => $user->institution,
				'logged_in' => TRUE
			));
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode(array('success' => TRUE)));
			return;
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('success' => FALSE)));
	}
}
