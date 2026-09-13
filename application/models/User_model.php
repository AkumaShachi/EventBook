<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function create_user($data)
	{
		$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
		$data['r_id'] = 1;
		return $this->db->insert('users', $data);
	}

	public function get_user_by_email($email)
	{
		$query = $this->db->get_where('users', array('email' => $email), 1);
		return $query->row();
	}

	public function get_user_by_id($id)
	{
		$query = $this->db->get_where('users', array('id' => $id), 1);
		return $query->row();
	}

	public function update_user($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('users', $data);
	}
}