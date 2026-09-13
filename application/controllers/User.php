<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->global['isNavbar'] = FALSE;
		$this->global['isFooter'] = FALSE;
	}

	public function edit_profile_modal()
	{
		$s = $this->_session_user_data();

		$this->load->view('modal/edit_profile_modal', array(
			'firstName'       => $s['firstName'],
			'lastName'        => $s['lastName'],
			'userEmail'       => $s['userEmail'],
			'userPhone'       => $s['userPhone'],
			'userRole'        => $s['userRole'],
			'userInstitution' => $s['userInstitution']
		));
	}

	public function save_profile()
	{
		$this->load->model('User_model');

		$user_id = $this->session->userdata('user_id');
		if (!$user_id) {
			echo 'error';
			return;
		}

		$first_name = $this->input->post('firstName');
		$last_name  = $this->input->post('lastName');
		$email      = $this->input->post('email');
		$phone      = $this->input->post('phone');
		$new_password = $this->input->post('newPassword');

		$r_id = intval($this->input->post('r_id'));
		if ($r_id < 1 || $r_id > 3) {
			$r_id = 1;
		}

		$institution = $this->session->userdata('institution');

		$data = array(
			'first_name' => $first_name,
			'last_name'  => $last_name,
			'email'      => $email,
			'phone'      => $phone,
			'institution' => $institution,
			'r_id'       => $r_id
		);

		$comfirmed_email = $this->input->post('comfirmed_email');
		$comfirmed_phone = $this->input->post('comfirmed_phone');
		if ($comfirmed_email === $email && $comfirmed_email !== '') {
			$data['comfirmed_email'] = $comfirmed_email;
		}
		if ($comfirmed_phone === $phone && $comfirmed_phone !== '') {
			$data['comfirmed_phone'] = $comfirmed_phone;
		}

		if (!empty($new_password)) {
			$data['password'] = password_hash($new_password, PASSWORD_DEFAULT);
		}

		if (!empty($_FILES['institution']['name'])) {
			$this->load->library('upload');
			$dir = 'assets/institution/';
			if (!is_dir($dir)) {
				mkdir($dir, 0777, true);
			}
			$this->upload->initialize(array(
				'upload_path'   => './' . $dir,
				'allowed_types' => 'jpg|jpeg|png|gif|webp',
				'max_size'      => 2048,
				'file_name'     => 'inst_' . $user_id . '_' . time()
			));
			if ($this->upload->do_upload('institution')) {
				$up = $this->upload->data();
				$data['institution'] = $dir . $up['file_name'];
			}
		}

		$this->User_model->update_user($user_id, $data);

		$this->session->set_userdata(array(
			'first_name'  => $first_name,
			'last_name'   => $last_name,
			'email'       => $email,
			'phone'       => $phone,
			'institution' => !empty($data['institution']) ? $data['institution'] : $this->session->userdata('institution'),
			'r_id'        => $r_id,
			'photo'       => $this->session->userdata('photo')
		));

		echo 'success';
	}
}