<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Itsource extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        //  $this->load->library('customlib');
        $this->load->model("it_source_model");
        // $this->load->model("Complaint_model");
    }

    function index() {
        if (!$this->rbac->hasPrivilege('setup_its', 'can_view')) {
            access_denied();
        }
        $this->form_validation->set_rules('source', 'Source', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['source_list'] = $this->it_source_model->source_list();
            // print_r($data);
            $this->load->view('layout/header');
            $this->load->view('admin/itsupport/itsourceview', $data);
            $this->load->view('layout/footer');
        } else {
            // print_r($_POST);
            $source = array(
                'source' => $this->input->post('source'),
                'description' => $this->input->post('description')
            );
            $this->it_source_model->add($source);
            $this->session->set_flashdata('msg', '<div class="alert alert-success"> Source added successfully</div>');
            redirect('admin/itsource');
        }
    }

    function edit($source_id) {
        if (!$this->rbac->hasPrivilege('setup_its', 'can_edit')) {
            access_denied();
        }
        $this->form_validation->set_rules('source', 'Source', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['source_list'] = $this->it_source_model->source_list();
            $data['source_data'] = $this->it_source_model->source_list($source_id);
            $this->load->view('layout/header');
            $this->load->view('admin/itsupport/itsourceeditview', $data);
            $this->load->view('layout/footer');
        } else {
            // print_r($_POST);
            $source = array(
                'source' => $this->input->post('source'),
                'description' => $this->input->post('description')
            );
            $this->it_source_model->update($source_id, $source);
            $this->session->set_flashdata('msg', '<div class="alert alert-success"> Source updated successfully</div>');
            redirect('admin/itsource');
        }
    }

    function delete($id) {
        if (!$this->rbac->hasPrivilege('setup_its', 'can_delete')) {
            access_denied();
        }
        $this->it_source_model->delete($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-success"> Source deleted successfully</div>');
        redirect('admin/itsource');
    }

}
