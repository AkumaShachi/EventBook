<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	protected $global = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');

		$this->global = array(
			'isNavbar'  => FALSE,
			'isFooter'  => FALSE,
			'pageTitle' => 'EventBook',
			'bodyClass' => '',
			'activeNav' => 'browse'
		);
	}

	protected function _render($view, $data = array())
	{
		$data = $data + $this->_session_user_data();

		$data['pageTitle']  = isset($data['pageTitle'])  ? $data['pageTitle']  : 'EventBook';
		$data['isNavbar']   = isset($data['isNavbar'])   ? $data['isNavbar']   : FALSE;
		$data['bodyClass']  = isset($data['bodyClass'])  ? $data['bodyClass']  : '';
		$data['activeNav']  = isset($data['activeNav'])  ? $data['activeNav']  : 'browse';
		$data['initial']    = isset($data['initial'])    ? $data['initial']    : 'U';
		$data['userName']   = isset($data['userName'])   ? $data['userName']   : 'User';
		$data['userEmail']  = isset($data['userEmail'])  ? $data['userEmail']  : '';
		$data['userRole']   = isset($data['userRole'])   ? $data['userRole']   : 0;

		$this->load->view('includes/header', $this->global + $data);
		$this->load->view($view, $data);
		$this->load->view('includes/footer', $this->global + $data);
	}

	protected function _session_user_data($user = NULL)
	{
		if ($user !== NULL) {
			$firstName = $user->first_name;
			$lastName  = $user->last_name;
			$email     = $user->email;
			$phone     = $user->phone;
		} else {
			$firstName = $this->session->userdata('first_name') ?: '';
			$lastName  = $this->session->userdata('last_name') ?: '';
			$email     = $this->session->userdata('email') ?: '';
			$phone     = $this->session->userdata('phone') ?: '';
		}

		$userName = $firstName ?: ($email ?: 'User');

		$role = (int) $this->session->userdata('r_id');
		if ($role < 0 || $role > 4) {
			$role = 1;
		}

		return array(
			'userName'        => $userName,
			'initial'         => strtoupper(mb_substr($userName, 0, 1)),
			'firstName'       => $firstName,
			'lastName'        => $lastName,
			'userEmail'       => $email,
			'userPhone'       => $phone,
			'userRole'        => $role,
			'userPhoto'       => $this->session->userdata('photo') ?: '',
			'userInstitution' => $this->session->userdata('institution') ?: ''
		);
	}

	protected function _json($data)
	{
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
	}

	protected function _slugify($str)
	{
		$str = strtolower(trim((string) $str));
		$str = preg_replace('/[^a-z0-9]+/', '-', $str);
		$str = trim($str, '-');
		return $str !== '' ? $str : 'event';
	}
}