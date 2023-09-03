<?php



if (!defined('BASEPATH'))

    exit('No direct script access allowed');



class It_Complain_Model extends CI_Model {



    public function __construct() {

        parent::__construct();

        $this->current_session = $this->setting_model->getCurrentSession();

        $this->current_session_name = $this->setting_model->getCurrentSessionName();

        $this->start_month = $this->setting_model->getStartMonth();

    }



    public function add($data) {

        $this->db->insert('it_complain', $data);

        return $query = $this->db->insert_id();

    }



    public function image_add($complaint_id, $image) {

        $array = array('id' => $complaint_id);

        $this->db->set('image', $image);

        $this->db->where($array);

        $this->db->update('it_complain');

    }



    public function complaint_list($id = null) {

        $this->db->select()->from('it_complain');

        if ($id != null) {

            $this->db->where('it_complain.id', $id);

        } else {

            $this->db->order_by('it_complain.id', "desc");

        }

        $query = $this->db->get();

        if ($id != null) {

            return $query->row_array();

        } else {

            return $query->result_array();

        }

    }
    public function complaint_list_by_user($staff_id = null) {

        $this->db->select('it_complain.*,staff.name As staff_name')->from('it_complain')
        ->join('staff', 'staff.id = it_complain.staff_id');

        if ($staff_id != null) {

            $this->db->where('it_complain.staff_id', $staff_id);
            $this->db->order_by('it_complain.id', "desc");

        } else {

            $this->db->order_by('it_complain.id', "desc");

        }

            $query = $this->db->get();
            return $query->result_array();

    }



    public function image_delete($id, $img_name) {

        $file = "./uploads/itsupport/complaints/" . $img_name;

        unlink($file);

        $this->db->where('id', $id);

        $this->db->delete('it_complain');

        $controller_name = $this->uri->segment(2);

    }



    public function compalaint_update($id, $data) {

        $this->db->where('id', $id);

        $this->db->update('it_complain', $data);

    }



    function delete($id) {

        $this->db->where('id', $id);

        $this->db->delete('it_complain');

    }



    function getComplaintType() {

        $this->db->select('*');

        $this->db->from('it_complaint_type');

        $query = $this->db->get();

        return $query->result_array();

    }



    function getComplaintSource() {



        $this->db->select('*');

        $this->db->from('it_source');

        $query = $this->db->get();

        return $query->result_array();

    }

function getcomplainReport($status= null){
        
        $data=array();
        $start_date= $this->input->get('start_date');
        $end_date=$this->input->get('end_date');

        $this->db->select()->from('it_complain'); 
        $this->db->where('it_complain.date >=', $start_date." 00:00:00");
        $this->db->where('it_complain.date <=', $end_date." 23:59:59");
       // $this->db->order_by("book_issues_fine.status", "acs");
        if ($status == null) {
        $query = $this->db->get();
        return $query->result_array();
        }
        else{
          $this->db->where('it_complain.status',$status);
          $query = $this->db->get();
          return $query->result_array();
        }
    }
function getAllcomplainReport($status= null){
        


        $this->db->select()->from('it_complain');
        //$this->db->order_by("book_issues_fine.status", "acs");

        if($status ==  null){
        $query = $this->db->get();
        return $query->result_array();
        }
        else{
       $this->db->where('it_complain.status', $status);
        $query = $this->db->get();
        return $query->result_array();
          }
    }

}

