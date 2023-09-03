<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Itcomplaintype extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model("It_ComplainType_model");
    }

    function index() {
        if (!$this->rbac->hasPrivilege('setup_its', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'it_support');
        $this->session->set_userdata('sub_menu', 'admin/itcomplaintype');
        $this->form_validation->set_rules('complaint_type', 'Complaint Type', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['complaint_type_list'] = $this->It_ComplainType_model->get('it_complaint_type');
            $this->load->view('layout/header');
            $this->load->view('admin/itsupport/itcomplaintypeview', $data);
            $this->load->view('layout/footer');
        } else {
            $it_complaint_type = array(
                'complaint_type' => $this->input->post('complaint_type'),
                'description' => $this->input->post('description')
            );
            $this->It_ComplainType_model->add('it_complaint_type', $it_complaint_type);
            $this->session->set_flashdata('msg', '<div class="alert alert-success"> Complaint Type added successfully</div>');
            redirect('admin/itcomplaintype');
        }
    }

    function edititcomplainttype($complainttype_id) {
        if (!$this->rbac->hasPrivilege('setup_its', 'can_edit')) {
            access_denied();
        }
        $this->form_validation->set_rules('complaint_type', 'Complaint Type', 'required');


        if ($this->form_validation->run() == FALSE) {
            $data['complaint_type_list'] = $this->It_ComplainType_model->get('it_complaint_type');
            $data['complaint_type_data'] = $this->It_ComplainType_model->get('it_complaint_type', $complainttype_id);
            
            $this->load->view('layout/header');
            $this->load->view('admin/itsupport/itcomplaintypeeditview', $data);
            $this->load->view('layout/footer');
        } else {
            
            $complaint_type = array(
                'complaint_type' => $this->input->post('complaint_type'),
                'description' => $this->input->post('description')
            );
            $this->It_ComplainType_model->update('it_complaint_type', $complainttype_id, $complaint_type);
            $this->session->set_flashdata('msg', '<div class="alert alert-success"> Complaint Type updated successfully</div>');
            redirect('admin/itcomplaintype');
        }
    }

    function delete($id) {
        if (!$this->rbac->hasPrivilege('setup_its', 'can_delete')) {
            access_denied();
        }
        $this->It_ComplainType_model->delete('it_complaint_type', $id);
        $this->session->set_flashdata('msg', '<div class="alert alert-success"> Complaint Type deleted successfully</div>');
        redirect('admin/itcomplaintype');
    }

}
