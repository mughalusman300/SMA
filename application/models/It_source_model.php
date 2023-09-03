<?php



if (!defined('BASEPATH'))

    exit('No direct script access allowed');



class it_source_model extends CI_Model {



    public function __construct() {

        parent::__construct();

        $this->current_session = $this->setting_model->getCurrentSession();

        $this->current_session_name = $this->setting_model->getCurrentSessionName();

        $this->start_month = $this->setting_model->getStartMonth();

    }



    function add($source) {

        $this->db->insert('it_source', $source);

    }



    public function source_list($id = null) {

        $this->db->select()->from('it_source');

        if ($id != null) {

            $this->db->where('it_source.id', $id);

        } else {

            $this->db->order_by('it_source.id');

        }

        $query = $this->db->get();

        if ($id != null) {

            return $query->row_array();

        } else {

            return $query->result_array();

        }

    }



    public function delete($id) {

        $this->db->where('id', $id);

        $this->db->delete('it_source');

    }



    public function update($id, $data) {

        $this->db->where('id', $id);

        $this->db->update('it_source', $data);

    }



}

