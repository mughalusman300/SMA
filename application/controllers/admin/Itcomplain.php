<?php



if (!defined('BASEPATH'))

    exit('No direct script access allowed');



class Itcomplain extends Admin_Controller {



    function __construct() {

        parent::__construct();

        $this->load->library('form_validation');

        //  $this->load->library('customlib');

        $this->load->model("It_Complain_Model");

        // $this->load->model("It_Complain_Model");

    }



    public function index() {

        if (!$this->rbac->hasPrivilege('maintenance_task', 'can_view')) {

            access_denied();

        }



        $this->session->set_userdata('top_menu', 'it_support');

        $this->session->set_userdata('sub_menu', 'admin/itcomplain');

        $this->form_validation->set_rules('name', 'Complaint By', 'required');

        //$this->form_validation->set_message('check_default', 'The Complaint field is required');



        if ($this->form_validation->run() == FALSE) {

            $data['complaint_list'] = $this->It_Complain_Model->complaint_list();

            $data['complaint_type'] = $this->It_Complain_Model->getComplaintType();

            $data['complaintsource'] = $this->It_Complain_Model->getComplaintSource();

            $this->load->view('layout/header');

            $this->load->view('admin/itsupport/itcomplainview', $data);

            $this->load->view('layout/footer');

        } else {

            $complaint = array(

                'complaint_type' => $this->input->post('complaint'),

                'source' => $this->input->post('source'),
                'staff_id' => $this->input->post('staff_id'),

                'name' => $this->input->post('name'),

                'contact' => $this->input->post('contact'),

                'date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),

                'description' => $this->input->post('description'),

                'action_taken' => $this->input->post('action_taken'),

                'assigned' => $this->input->post('assigned'),

                'note' => $this->input->post('note'),
                'status' => "Registered"

            );



            //print_r($complaint);

            $complaint_id = $this->It_Complain_Model->add($complaint);

            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {

                $fileInfo = pathinfo($_FILES["file"]["name"]);

                $img_name = 'id' . $complaint_id . '.' . $fileInfo['extension'];

                move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/itsupport/complaints/" . $img_name);

                $this->It_Complain_Model->image_add($complaint_id, $img_name);

            }



            $this->session->set_flashdata('msg', '<div class="alert alert-success"> Complaint added successfully</div>');

            redirect('admin/itcomplain');

        }

    }


public function itComplainByUser() {

        if (!$this->rbac->hasPrivilege('maintenance_request', 'can_view')) {

            access_denied();

        }


        
        $this->session->set_userdata('top_menu', 'it_support');

        $this->session->set_userdata('sub_menu', 'admin/itcomplain/itComplainByUser');

        $this->form_validation->set_rules('complaint', 'Complaint', 'required');

        //$this->form_validation->set_message('check_default', 'The Complaint field is required');


         $staff_id=$this->customlib->getUserData()['id'];
        if ($this->form_validation->run() == FALSE) {

            $complaint_list = $this->It_Complain_Model->complaint_list_by_user($staff_id);
            $data['complaint_list']=$complaint_list;
            $data['complaint_type'] = $this->It_Complain_Model->getComplaintType();

            $data['complaintsource'] = $this->It_Complain_Model->getComplaintSource();

            $this->load->view('layout/header');

            $this->load->view('admin/itsupport/itcomplainbyuserview', $data);

            $this->load->view('layout/footer');

        } else {

            $complaint = array(

                'complaint_type' => $this->input->post('complaint'),

                'source' => $this->input->post('source'),
                'staff_id' => $this->input->post('staff_id'),

                'name' =>$this->input->post('staff_name'),

                'contact' => $this->input->post('contact'),

                'date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),

                'description' => $this->input->post('description'),

                'status' => "Registered"

            );



            //print_r($complaint);

            $complaint_id = $this->It_Complain_Model->add($complaint);

            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {

                $fileInfo = pathinfo($_FILES["file"]["name"]);

                $img_name = 'id' . $complaint_id . '.' . $fileInfo['extension'];

                move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/itsupport/complaints/" . $img_name);

                $this->It_Complain_Model->image_add($complaint_id, $img_name);

            }



            $this->session->set_flashdata('msg', '<div class="alert alert-success"> Complaint added successfully</div>');

            redirect('admin/itcomplain/itComplainByUser');

        }

    }
    function edit($id) {

        if (!$this->rbac->hasPrivilege('maintenance_task', 'can_edit')) {

            access_denied();

        }

        $this->form_validation->set_rules('name', 'Complaint By', 'required');   
         $status=$this->input->post('status');
         $completion_date=$this->input->post('completion_date');
         //echo "<pre>"; print($completion_date); exit;
        if ($status== "Completed"){
             $completion_date=date("Y-m-d");
             //echo "<pre>"; print($completion_date); exit;
        }
        else{
          $completion_date="0000-00-00";
        }
        // echo "<pre>"; print_r($status); exit;
        if ($this->form_validation->run() == FALSE) {

            $data['complaint_list'] = $this->It_Complain_Model->complaint_list();

            $data['complaint_data'] = $this->It_Complain_Model->complaint_list($id);

            $data['complaint_type'] = $this->It_Complain_Model->getComplaintType();

            $data['complaintsource'] = $this->It_Complain_Model->getComplaintSource();

            $this->load->view('layout/header');

            $this->load->view('admin/itsupport/itcomplaineditview', $data);

            $this->load->view('layout/footer');

        } else {

            $complaint = array(

                'complaint_type' => $this->input->post('complaint'),

                'source' => $this->input->post('source'),

                'name' => $this->input->post('name'),

                'contact' => $this->input->post('contact'),

                'date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),

                'description' => $this->input->post('description'),

                'action_taken' => $this->input->post('action_taken'),

                'assigned' => $this->input->post('assigned'),

                'note' => $this->input->post('note'),
                'status'=>$this->input->post('status'),
                'completion_date'=>$completion_date

            );



            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {

                $fileInfo = pathinfo($_FILES["file"]["name"]);

                $img_name = 'id' . $id . '.' . $fileInfo['extension'];

                move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/itsupport/complaints/" . $img_name);

                $this->It_Complain_Model->image_add($id, $img_name);

            }

            $this->It_Complain_Model->compalaint_update($id, $complaint);

            $this->session->set_flashdata('msg', '<div class="alert alert-success"> Complaint updated successfully</div>');

            redirect('admin/itcomplain');

        }

    }



    function details($id) {

        if (!$this->rbac->hasPrivilege('maintenance_task', 'can_view')) {

            access_denied();

        }

        //echo $id;

        $data['complaint_data'] = $this->It_Complain_Model->complaint_list($id);

        $this->load->view('admin/itsupport/itcomplainmodalview', $data);

    }



    public function delete($id) {

        if (!$this->rbac->hasPrivilege('maintenance_task', 'can_delete')) {

            access_denied();

        }

        //echo $id;

        $this->It_Complain_Model->delete($id);

        $this->session->set_flashdata('msg', '<div class="alert alert-success"> Complaint deleted successfully</div>');



        redirect('admin/itcomplain');

    }



    function download($image) {

        $this->load->helper('download');

        $filepath = "./uploads/itsupport/complaints/" . $image;

        $data = file_get_contents($filepath);

        $name = $image;

        force_download($name, $data);

    }



    function imagedelete($id, $image) {

        if (!$this->rbac->hasPrivilege('maintenance_task', 'can_delete')) {

            access_denied();

        }

        $this->It_Complain_Model->image_delete($id, $image);

        $this->session->set_flashdata('msg', '<div class="alert alert-success"> Complaint deleted successfully</div>');

        redirect('admin/itcomplain');

    }



    public function check_default($post_string) {

        return $post_string == "" ? FALSE : TRUE;

    }
    public function itreports(){

        if (!$this->rbac->hasPrivilege('its_report', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'it_support');

        $this->session->set_userdata('sub_menu', 'admin/itcomplain/itreports');
        $status= $this->input->get('status');
        $data['status']=$status;
        $data['title'] = "ITS Report"; 
        $data['start_date']= $this->input->get('start_date');
        $data['end_date']=$this->input->get('end_date'); 
        if($data['start_date']!="" && $data['end_date']!="")
        { 
        $complainlist = $this->It_Complain_Model->getcomplainReport();
        $data["complainlist"]= $complainlist;
        }
        if($data['start_date']!="" && $data['end_date']!="" && $data['status']!="")
        { 
        $complainlist = $this->It_Complain_Model->getcomplainReport($status);
        $data["complainlist"]= $complainlist;
        }
        if(isset($_GET['submit'])){
        if($data['start_date']=="" && $data['end_date']=="" && $data['status']=="")
        {    
        $complainlist = $this->It_Complain_Model->getAllcomplainReport();
        $data['complainlist'] = $complainlist;
        }
        }
        if($data['start_date']=="" && $data['end_date']=="" && $data['status']!="")
        {    
        $complainlist = $this->It_Complain_Model->getAllcomplainReport($status);
        //echo '<pre>'; print_r(  $complainlist); exit;
        $data['complainlist'] = $complainlist;
         //echo '<pre>'; print_r(  $complainlist); exit;
        }
        $this->load->view('layout/header');
        $this->load->view('admin/itsupport/itcomplainreport.php',$data);
        $this->load->view('layout/footer');
    }



}

