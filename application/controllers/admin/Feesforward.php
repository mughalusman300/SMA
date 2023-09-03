<?php



if (!defined('BASEPATH'))

    exit('No direct script access allowed');



class Feesforward extends Admin_Controller

{



    protected $balance_group;

    protected $balance_type;

    protected $setting_result;



    function __construct()

    {

        parent::__construct();

        $this->load->config('ci-blog');

        $this->balance_group = $this->config->item('ci_balance_group');

        $this->balance_type = $this->config->item('ci_balance_type');

        $this->setting_result = $this->setting_model->get();

        $this->load->library('encoding_lib');

    }



    function index()

    {

        if (!$this->rbac->hasPrivilege('fees_carry_forward', 'can_view')) {

            access_denied();

        }

        $this->session->set_userdata('top_menu', 'Fees Collection');

        $this->session->set_userdata('sub_menu', 'feesforward/index');

        $data['title'] = 'Add Feesforward';

        $data['title_list'] = 'Recent FeeType';

        $class = $this->class_model->get();

        $data['classlist'] = $class;

        $action = $this->input->post('action');

        $class_id = $this->input->post('class_id');

        $section_id = $this->input->post('section_id');

        if ($this->input->server('REQUEST_METHOD') == "POST") {

            $setting_result = $this->setting_model->get();

            $current_session = $setting_result[0]['session_id'];

            $data['current_session'] = $current_session;

            $pre_session = $this->session_model->getPreSession($current_session);

            $data['pre_session'] = $pre_session;

            //=========date==============

            $fees_due_days = $this->setting_result[0]['fee_due_days'];

            if ($fees_due_days > 0 && $fees_due_days != "") {

                $due_date = date('Y-m-d', strtotime('+' . $fees_due_days . ' day'));

                $data['due_date_formated'] = date($this->setting_result[0]['date_format'], $this->customlib->dateyyyymmddTodateformat($due_date));

            } else {

                $due_date = date('Y-m-d');

                $data['due_date_formated'] = date($this->setting_result[0]['date_format'], $this->customlib->dateyyyymmddTodateformat($due_date));

            }

            //========================

            if ($action == 'search') {

                $this->form_validation->set_rules('class_id', 'Class', 'required');

                $this->form_validation->set_rules('section_id', 'Section', 'required');

                if ($this->form_validation->run() == TRUE) {

                    $student_Array = json_decode($this->findPreviousBalanceFees($pre_session->id, $class_id, $section_id, $current_session));



                    $data['student_due_fee'] = $student_Array->student_Array;

                    $data['is_update'] = $student_Array->is_update;

                }

            } else if ($action == 'fee_submit') {

                $student_Array = json_decode($this->findPreviousBalanceFees($pre_session->id, $class_id, $section_id, $current_session));



                $data['student_due_fee'] = $student_Array->student_Array;

                $data['is_update'] = $student_Array->is_update;

                $this->form_validation->set_rules('due_date', 'Date', 'required');

                $counter = $this->input->post('student_counter');

                if ($this->form_validation->run() == TRUE) {



                    $due_date = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('due_date')));

                    $student_data = array();

                    foreach ($counter as $count_key => $count_value) {

                        $student_array = array();

                        $student_array['student_session_id'] = $this->input->post('student_sesion[' . $count_value . ']');

                        $student_array['amount'] = $this->input->post('amount[' . $count_value . ']');

                        $student_array['is_system'] = 1;

                        $student_array['fee_session_group_id'] = 0;

                        $student_data[] = $student_array;

                    }



                    $student_due_fee = $this->studentfeemaster_model->addPreviousBal($student_data, $due_date);



                    $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">

                        Fee alloted successfully</div>');

                    redirect('admin/feesforward');

                }

            }

        }





        $this->load->view('layout/header', $data);

        $this->load->view('admin/feesforward/index', $data);

        $this->load->view('layout/footer', $data);

    }



    public function bulkFeesUpdate()

    {

        $setting_result = $this->setting_model->get();

        $current_session = $setting_result[0]['session_id']; //14

        $data['current_session'] = $current_session; //14

        $pre_session = $this->session_model->getPreSession($current_session); // id = 13

        $data['pre_session'] = $pre_session;

        //=========date==============

        $fees_due_days = $this->setting_result[0]['fee_due_days'];


//echo $fees_due_days;
//die;
        if ($fees_due_days > 0 && $fees_due_days != "") {

            $due_date = date('Y-m-d', strtotime('+' . $fees_due_days . ' day'));

            $data['due_date_formated'] = date($this->setting_result[0]['date_format'], $this->customlib->dateyyyymmddTodateformat($due_date));

        } else {

            $due_date = date('Y-m-d');

            $data['due_date_formated'] = date($this->setting_result[0]['date_format'], $this->customlib->dateyyyymmddTodateformat($due_date));

        }

        //========================

        $class_id = $this->input->post('class_id'); //done

        $section_id = $this->input->post('section_id'); //done



//        if ($class_id == null || $section_id == null) {
//
  ///          $this->session->set_flashdata('err_msg', '<div class="alert alert-danger text-left"> Class or Section is Missing</div>');
//
  //          redirect('admin/feesforward');
//
  //      }









        $fields = array('admission_no', 'amount');



        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {

            $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

            if ($ext == 'csv') {

                $file = $_FILES['file']['tmp_name'];

                $this->load->library('CSVReader');

                $result = $this->csvreader->parse_file($file);

                $student_data = array();

                $fees_data = array();



                if (!empty($result)) {

$student_admission_number = array();
					for ($i = 1; $i <= count($result); $i++) {
						if(trim($result[$i]['admission_no']) != '')
							$student_admission_number[] = $result[$i]['admission_no']; 
					}
					
					if(empty($student_admission_number)){
						$this->session->set_flashdata('err_msg', '<div class="alert alert-danger text-left"> File is empty</div>');
	                    redirect('admin/feesforward');
						die;
					}
					$studentAArrayA = $this->findPreviousBalanceFees($pre_session->id, $class_id, $section_id, $current_session, $student_admission_number);
					$student_Array = json_decode($studentAArrayA);
					$student_due_fee = $student_Array->student_Array;
	

                for ($i = 1; $i <= count($result); $i++) {

                    $n=0;





                        foreach ($result[$i] as $key => $value) {



                            $student_data[$i][$fields[$n]] = $this->encoding_lib->toUTF8($result[$i][$key]);

                            $n++;

                        }



                            $student_array = array();



                        foreach ($student_due_fee as $due_fee_key => $due_fee_value) {





                            if($due_fee_value->admission_no == $student_data[$i]['admission_no']){



                                $student_array['student_session_id'] = $due_fee_value->student_session_id;

                                $student_array['amount'] = $student_data[$i]['amount'];

                                $student_array['is_system'] = 1;

                                $student_array['fee_session_group_id'] = 0;

                                $fees_data[] = $student_array;



                            }



                        }

                    }



                    $date = $data['due_date_formated'];

                    $due_date = date('Y-m-d', $this->customlib->datetostrtotime($date));

                    $update_fees = $this->studentfeemaster_model->addPreviousBal($fees_data, $due_date);

                    $this->session->set_flashdata('msg', '<div class="alert alert-success text-left"> Fee alloted successfully</div>');

                    redirect('admin/feesforward');



                }else{

                    $this->session->set_flashdata('err_msg', '<div class="alert alert-danger text-left"> File is empty</div>');

                    redirect('admin/feesforward');

                }

            }else{

                $this->session->set_flashdata('err_msg', '<div class="alert alert-danger text-left"> Please select a CSV file</div>');

                redirect('admin/feesforward');

            }

        } else {

            $this->session->set_flashdata('err_msg', '<div class="alert alert-danger text-left"> CSV file not selected or is empty</div>');

            redirect('admin/feesforward');

        }



    }



    public function sampleFeeUpdate(){

        $this->load->helper('download');

        $filepath = "./backend/import/fees_update_sample_file.csv";

        $data = file_get_contents($filepath);

        $name = 'fees_update_sample_file.csv';



        force_download($name, $data);

    }



    public function findPreviousBalanceFees($session_id, $class_id, $section_id, $current_session, $student_admission_number = array()) {
        $studentlist = $this->student_model->getPreviousSessionStudent($session_id, $class_id, $section_id, $student_admission_number);
        $is_update = false;
        $student_Array = array();
        if (!empty($studentlist)) {

            $student_comma_seprate = array();
			
            foreach ($studentlist as $student_list_key => $student_list_value) {

                $obj = new stdClass();

                $obj->name = $student_list_value->firstname . " " . $student_list_value->lastname;

                $obj->admission_no = $student_list_value->admission_no;

                $obj->roll_no = $student_list_value->roll_no;

                $obj->father_name = $student_list_value->father_name;

                $obj->student_session_id = $student_list_value->current_student_session_id;

                $obj->student_previous_session_id = $student_list_value->previous_student_session_id;
				if(trim($student_list_value->admission_date)!='')
					$obj->admission_date = date($this->setting_result[0]['date_format'], $this->customlib->dateyyyymmddTodateformat($student_list_value->admission_date));
				else 
					$obj->admission_date = date($this->setting_result[0]['date_format']);



                $student_Array[] = $obj;

                $student_comma_seprate[] = $student_list_value->current_student_session_id;

            }



            $student_session_array = "(" . implode(",", $student_comma_seprate) . ")";

            $record_exists = $this->studentfeemaster_model->getBalanceMasterRecord($this->balance_group, $student_session_array);



            if (!empty($record_exists)) {

                $is_update = true;

                foreach ($student_Array as $stkey => $eachstudent) {
                    $eachstudent->balance = $this->findValueExists($record_exists, $eachstudent->student_session_id);
                }
				

            } else {


                foreach ($student_Array as $stkey => $eachstudent) {
                    //==========================
                    $student_total_fees = array();
                    if ($eachstudent->student_previous_session_id != "") {
                        $student_total_fees = $this->studentfeemaster_model->getPreviousStudentFees($eachstudent->student_previous_session_id);
                    }

                    if (!empty($student_total_fees)) {

                        $totalfee = 0;

                        $deposit = 0;

                        $discount = 0;

                        $balance = 0;

                        foreach ($student_total_fees as $student_total_fees_key => $student_total_fees_value) {

                            if (!empty($student_total_fees_value->fees)) {

                                foreach ($student_total_fees_value->fees as $each_fee_key => $each_fee_value) {

                                    $totalfee = $totalfee + $each_fee_value->amount;



                                    $amount_detail = json_decode($each_fee_value->amount_detail);

                                    if ($amount_detail != null) {

                                        foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {

                                            $deposit = $deposit + $amount_detail_value->amount;

                                            $discount = $discount + $amount_detail_value->amount_discount;

                                        }

                                    }

                                }

                            }

                        }



                        $eachstudent->balance = $totalfee - ($deposit + $discount);

                    } else {

                        $eachstudent->balance = "0";

                    }

                    //===================

                }

            }

        }
		$studentArrayJson = array('student_Array' => $student_Array, 'is_update' => $is_update);
		
        return json_encode($studentArrayJson);
    }



    function findValueExists($array, $find) {

        $amount = 0;

        foreach ($array as $x => $x_value) {

            if ($x_value->student_session_id == $find)

                return $x_value->amount;

        }

        return $amount;

    }



}

