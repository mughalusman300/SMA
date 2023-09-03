<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Feetype_model extends CI_Model {

    public function __construct() {
        parent::__construct();
		$this->current_session = $this->setting_model->getCurrentSession();
    }

    public function getlist($id = null) {
        $this->db->select()->from('feetype');
        $this->db->where('is_system', 0);
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }
    
    public function get($id = null) {
        $this->db->select()->from('feetype');
        $this->db->where('is_system', 0);
        $this->db->where('is_active', 'yes');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove($id) {
        $this->db->where('id', $id);
        $this->db->where('is_system', 0);
        //$this->db->delete('feetype');
        $this->db->update('feetype', array("is_active"=>'no'));
    }
    
    public function check($id) {
        $this->db->where('feetype_id', $id);
        $this->db->where('session_id', $this->current_session);
        $this->db->where('is_active', 'yes');
        //$this->db->delete('fee_groups');
        $query = $this->db->get('fee_groups_feetype');

        if ($query->num_rows() > 0) {

            return TRUE;

        } else {

            return FALSE;

        }
        
       
    }
    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function active($id) {
        $this->db->where('id', $id);
        $this->db->where('is_system', 0);
        //$this->db->delete('feetype');
        $this->db->update('feetype', array("is_active"=>'yes'));
    }

    /**
     * This function will take the post data passed from the controller
     * If id is present, then it will do an update
     * else an insert. One function doing both add and edit.
     * @param $data
     */
    public function add($data) {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('feetype', $data);
        } else {
            $this->db->insert('feetype', $data);
            return $this->db->insert_id();
        }
    }

    public function check_exists($str) {
        $name = $this->security->xss_clean($str);
        $id = $this->input->post('id');
        if (!isset($id)) {
            $id = 0;
        }

        if ($this->check_data_exists($name, $id)) {
            $this->form_validation->set_message('check_exists', 'Record already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function check_data_exists($name, $id) {
        $this->db->where('type', $name);
        $this->db->where('id !=', $id);

        $query = $this->db->get('feetype');
        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function checkFeetypeByName($name) {
        $this->db->where('type', $name);


        $query = $this->db->get('feetype');
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
public function getTaxable($id) {        $sql = "SELECT feetype.* FROM `fee_groups_feetype` INNER JOIN `feetype` on `feetype`.id=fee_groups_feetype.feetype_id  WHERE `fee_groups_feetype`.`id`=".$id;        $query = $this->db->query($sql);        $result = $query->row();        return $result;    }
}
