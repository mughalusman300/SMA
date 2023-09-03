<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Stuattendence extends Admin_Controller {

    function __construct() {
        parent::__construct();

        $this->config->load("mailsms");
        $this->load->library('mailsmsconf');
        $this->config_attendance = $this->config->item('attendence');
        $this->load->model("classteacher_model");
    }

    function index() {
        //  if(!$this->rbac->hasPrivilege('student_attendance','can_view')){
        // access_denied();
        // }
        $this->session->set_userdata('top_menu', 'Attendance');
        $this->session->set_userdata('sub_menu', 'stuattendence/index');
        $data['title'] = 'Add Fees Type';
        $data['title_list'] = 'Fees Type List';
        $class = $this->class_model->get('', $classteacher = 'yes');
        //echo "<pre>"; print_r($class); exit;


        $data['classlist'] = $class;
        //echo '<pre>'; print_r($data['classlist']); exit;
        $userdata = $this->customlib->getUserData();
        $carray = array();
        // if(($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")){
        //  $data["classlist"] =   $this->customlib->getclassteacher($userdata["id"]);


        if (!empty($data["classlist"])) {
            foreach ($data["classlist"] as $ckey => $cvalue) {

                $carray[] = $cvalue["id"];
            }
        }
        $data['class_id'] = "";
        $data['section_id'] = "";
        $data['subject_id'] = '-1';
        $data['date'] = "";
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', 'Date', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $data["searchBySubject"] = false;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/stuattendence/attendenceList', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $date = $this->input->post('date');
            //print_r($date);exit();
            $subject = $this->input->post('subjectid');

            $student_list = $this->stuattendence_model->get();
            $data['studentlist'] = $student_list;
            $data['class_id'] = $class;
            $data['section_id'] = $section;
            $data['date'] = $date;
            $search = $this->input->post('search');
            $holiday = $this->input->post('holiday');
            if ($search == "saveattendence") {



                $session_ary = $this->input->post('student_session');
                $absent_student_list = array();
                foreach ($session_ary as $key => $value) {
                    $checkForUpdate = $this->input->post('attendendence_id' . $value);
                    if ($checkForUpdate != 0) {
                        if (isset($holiday)) {
                            $arr = array(
                                'id' => $checkForUpdate,
                                'student_session_id' => $value,
                                'attendence_type_id' => 5,
                                'subject_id' =>$this->input->post('subject_id'),
                                'remark' => $this->input->post("remark" . $value),
                                'date' => date('Y-m-d', $this->customlib->datetostrtotime($date))
                            );
                        } else {
                            $arr = array(
                                'id' => $checkForUpdate,
                                'student_session_id' => $value,
                                'attendence_type_id' => $this->input->post('attendencetype' . $value),
                                'remark' => $this->input->post("remark" . $value),
                                'subject_id' =>$this->input->post('subject_id'),
                                'date' => date('Y-m-d', $this->customlib->datetostrtotime($date))
                            );
                        }
                        $insert_id = $this->stuattendence_model->add($arr);
                    } else {
                        if (isset($holiday)) {
                            $arr = array(
                                'student_session_id' => $value,
                                'attendence_type_id' => 5,
                                'subject_id' =>$this->input->post('subject_id'),
                                'remark' => $this->input->post("remark" . $value),
                                'date' => date('Y-m-d', $this->customlib->datetostrtotime($date))
                            );
                        } else {


                            $arr = array(
                                'student_session_id' => $value,
                                'attendence_type_id' => $this->input->post('attendencetype' . $value),
                                'remark' => $this->input->post("remark" . $value),
                                'subject_id' =>$this->input->post('subject_id'),
                                'date' => date('Y-m-d', $this->customlib->datetostrtotime($date))
                            );
                        }
                        $insert_id = $this->stuattendence_model->add($arr);
                        $absent_config = $this->config_attendance['absent'];
                        if ($arr['attendence_type_id'] == $absent_config) {
                            $absent_student_list[] = $value;
                        }
                    }
                }
                $absent_config = $this->config_attendance['absent'];
                if (!empty($absent_student_list)) {
                    $this->mailsmsconf->mailsms('absent_attendence', $absent_student_list, $date);
                }

                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Attendance Saved Successfully</div>');
                redirect('admin/stuattendence/index');
            }


            $attendencetypes = $this->attendencetype_model->get();
            $data['attendencetypeslist'] = $attendencetypes;


            $classData = $this->class_model->getClassData($class);


            if($classData['attendance_type'] == '0'){
                $resultlist = $this->stuattendence_model->searchAttendenceClassSection($class, $section, date('Y-m-d', $this->customlib->datetostrtotime($date)));
                $data['resultlist'] = $resultlist;

                $data['subject_id'] = '-1';

                $data["searchBySubject"] = false;
                $this->load->view('layout/header', $data);
                $this->load->view('admin/stuattendence/attendenceList', $data);
                $this->load->view('layout/footer', $data);

                
            }else{

                $resultlist = $this->stuattendence_model->searchAttendenceClassSectionSubject($class, $section, date('Y-m-d', $this->customlib->datetostrtotime($date)), $subject);
                //echo "<pre>"; print_r($resultlist);exit;
                $data['resultlist'] = $resultlist;
                $data['subject_id'] = $subject;
                $data["searchBySubject"] = true;
                $this->load->view('layout/header', $data);
                $this->load->view('admin/stuattendence/attendenceList', $data);
                $this->load->view('layout/footer', $data);
            }
        }
    }

   
    function Edit($cl_id=null , $sc_id= null, $sub_id=null, $date=null) {
        //  if(!$this->rbac->hasPrivilege('student_attendance','can_view')){
        // access_denied();
        // }
        //print_r($class_id);exit();
        $data['cl_id'] = $cl_id;
        $data['sc_id'] = $sc_id;
        $data['sub_id'] = $sub_id;
        //print_r($sub_id);exit();
        $date_v=date_create($date);
        $att_date = date_format($date_v,"d/m/Y");
        $data['att_date'] = $att_date;

        $this->session->set_userdata('top_menu', 'Attendance');
        $this->session->set_userdata('sub_menu', 'stuattendence/classmonthlyattendencereport');
        $data['title'] = 'Add Fees Type';
        $data['title_list'] = 'Fees Type List';
        $subjects = $this->class_model->getSubjectsById($sub_id);
        $data['subjects'] = $subjects;
        //print_r($subject); exit();
        if($cl_id!="" &&  $sc_id!=""){
        $classSection = $this->class_model->getClassSection($cl_id, $sc_id);
        $data['classSection'] = $classSection;
        $attendance_type =  $classSection[0]['attendance_type'];
        //print_r($attendance_type); exit();

        if($attendance_type == '0'){
            
                $data["searchBySubject"] = false;   
            }else{
                
                $data["searchBySubject"] = true;

            }
        }
       // print_r($classSection);exit();
         //echo "<pre>"; print_r($classSection); exit;
        //$class = $this->class_model->get('', $classteacher = 'yes');
        //echo "<pre>"; print_r($class); exit;
            


        //$data['classlist'] = $class;
        //echo '<pre>'; print_r($data['classlist']); exit;
        $userdata = $this->customlib->getUserData();
        $carray = array();
        // if(($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")){
        //  $data["classlist"] =   $this->customlib->getclassteacher($userdata["id"]);


        if (!empty($data["classSection"])) {
            foreach ($data["classSection"] as $ckey => $cvalue) {

                $carray[] = $cvalue["id"];
            }
        }
        $data['class_id'] = "";
        $data['section_id'] = "";
        $data['subject_id'] = '-1';
        $data['date'] = "";
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');
        //$this->form_validation->set_rules('Date', 'date', 'required');
        if ($this->form_validation->run() == FALSE) {
            //$data["searchBySubject"] = false;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/stuattendence/editAttendenceList', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class = $this->input->post('class_id');
            //$csid =$classSection[0]['id'];
            //print_r($csid);exit();
            $section = $this->input->post('section_id');
            $classSection = $this->class_model->getClassSection($class, $section);
            $data['classSection'] = $classSection;
            $date = $this->input->post('date');
            //print_r($date);exit();
            $subject = $this->input->post('subjectid');
            $subjects = $this->class_model->getSubjectsById($subject);
            $data['subjects'] = $subjects;

            $student_list = $this->stuattendence_model->get();
            $data['studentlist'] = $student_list;
            $data['class_id'] = $class;
            $data['section_id'] = $section;
            $data['date'] = $date;
            //print_r($date);exit();
            $search = $this->input->post('search');
            $holiday = $this->input->post('holiday');
            if ($search == "saveattendence") {



                $session_ary = $this->input->post('student_session');
                $absent_student_list = array();
                foreach ($session_ary as $key => $value) {
                    $checkForUpdate = $this->input->post('attendendence_id' . $value);
                    if ($checkForUpdate != 0) {
                        if (isset($holiday)) {
                            $arr = array(
                                'id' => $checkForUpdate,
                                'student_session_id' => $value,
                                'attendence_type_id' => 5,
                                'subject_id' =>$this->input->post('subject_id'),
                                'remark' => $this->input->post("remark" . $value),
                                'date' => date('Y-m-d', $this->customlib->datetostrtotime($date))
                            );
                        } else {
                            $arr = array(
                                'id' => $checkForUpdate,
                                'student_session_id' => $value,
                                'attendence_type_id' => $this->input->post('attendencetype' . $value),
                                'remark' => $this->input->post("remark" . $value),
                                'subject_id' =>$this->input->post('subject_id'),
                                'date' => date('Y-m-d', $this->customlib->datetostrtotime($date))
                            );
                        }
                        $insert_id = $this->stuattendence_model->add($arr);
                    } else {
                        if (isset($holiday)) {
                            $arr = array(
                                'student_session_id' => $value,
                                'attendence_type_id' => 5,
                                'subject_id' =>$this->input->post('subject_id'),
                                'remark' => $this->input->post("remark" . $value),
                                'date' => date('Y-m-d', $this->customlib->datetostrtotime($date))
                            );
                        } else {


                            $arr = array(
                                'student_session_id' => $value,
                                'attendence_type_id' => $this->input->post('attendencetype' . $value),
                                'remark' => $this->input->post("remark" . $value),
                                'subject_id' =>$this->input->post('subject_id'),
                                'date' => date('Y-m-d', $this->customlib->datetostrtotime($date))
                            );
                        }
                        $insert_id = $this->stuattendence_model->add($arr);
                        $absent_config = $this->config_attendance['absent'];
                        if ($arr['attendence_type_id'] == $absent_config) {
                            $absent_student_list[] = $value;
                        }
                    }
                }
                $absent_config = $this->config_attendance['absent'];
                if (!empty($absent_student_list)) {
                    $this->mailsmsconf->mailsms('absent_attendence', $absent_student_list, $date);
                }

                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Attendance Saved Successfully</div>');
                redirect('admin/stuattendence/index');
            }


            $attendencetypes = $this->attendencetype_model->get();
            $data['attendencetypeslist'] = $attendencetypes;


            $classData = $this->class_model->getClassData($class);


            if($classData['attendance_type'] == '0'){
                $resultlist = $this->stuattendence_model->searchAttendenceClassSection($class, $section, date('Y-m-d', $this->customlib->datetostrtotime($date)));
                $data['resultlist'] = $resultlist;

                $data['subject_id'] = '-1';

                $data["searchBySubject"] = false;
                $this->load->view('layout/header', $data);
                $this->load->view('admin/stuattendence/editAttendenceList', $data);
                $this->load->view('layout/footer', $data);

                
            }else{

                $resultlist = $this->stuattendence_model->searchAttendenceClassSectionSubject($class, $section, date('Y-m-d', $this->customlib->datetostrtotime($date)), $subject);
                //echo "<pre>"; print_r($resultlist);exit;
                $data['resultlist'] = $resultlist;
                $data['subject_id'] = $subject;
                $data["searchBySubject"] = true;
                $this->load->view('layout/header', $data);
                $this->load->view('admin/stuattendence/editAttendenceList', $data);
                $this->load->view('layout/footer', $data);
            }
        }
    }
    function fullattendance() {
        //  if(!$this->rbac->hasPrivilege('student_attendance','can_view')){
        // access_denied();
        // }
        $this->session->set_userdata('top_menu', 'Attendance');
        $this->session->set_userdata('sub_menu', 'stuattendence/index');
        $data['title'] = 'Add Fees Type';
        $data['title_list'] = 'Fees Type List';
        $class = $this->class_model->get('', $classteacher = 'yes');

        //echo "<pre>"; print_r($class); exit;


        $data['classlist'] = $class;
        //echo '<pre>'; print_r($data['classlist']); exit;
        $userdata = $this->customlib->getUserData();
        $carray = array();
        // if(($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")){
        //  $data["classlist"] =   $this->customlib->getclassteacher($userdata["id"]);


        if (!empty($data["classlist"])) {
            foreach ($data["classlist"] as $ckey => $cvalue) {

                $carray[] = $cvalue["id"];
            }
        }
        $data['class_id'] = "";
        $data['section_id'] = "";
        $data['subject_id'] = '-1';
        $data['date'] = "";
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $data["searchBySubject"] = false;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/stuattendence/fullAttendenceList', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $date = $this->input->post('date');
            $subject = $this->input->post('subjectid');

            $student_list = $this->stuattendence_model->get();
            $data['studentlist'] = $student_list;
            $data['class_id'] = $class;
            $data['section_id'] = $section;
            $data['date'] = $date;
            $search = $this->input->post('search');
            $holiday = $this->input->post('holiday');
            if ($search == "saveattendence") {



                $session_ary = $this->input->post('student_session');
                $absent_student_list = array();
                foreach ($session_ary as $key => $value) {
                    $checkForUpdate = $this->input->post('attendendence_id' . $value);
                    if ($checkForUpdate != 0) {
                        if (isset($holiday)) {
                            $arr = array(
                                'id' => $checkForUpdate,
                                'student_session_id' => $value,
                                'attendence_type_id' => 5,
                                'subject_id' =>$this->input->post('subject_id'),
                                'remark' => $this->input->post("remark" . $value),
                                'date' => date('Y-m-d', $this->customlib->datetostrtotime($date))
                            );
                        } else {
                            $arr = array(
                                'id' => $checkForUpdate,
                                'student_session_id' => $value,
                                'attendence_type_id' => $this->input->post('attendencetype' . $value),
                                'remark' => $this->input->post("remark" . $value),
                                'subject_id' =>$this->input->post('subject_id'),
                                'date' => date('Y-m-d', $this->customlib->datetostrtotime($date))
                            );
                        }
                        $insert_id = $this->stuattendence_model->add($arr);
                    } else {
                        if (isset($holiday)) {
                            $arr = array(
                                'student_session_id' => $value,
                                'attendence_type_id' => 5,
                                'subject_id' =>$this->input->post('subject_id'),
                                'remark' => $this->input->post("remark" . $value),
                                'date' => date('Y-m-d', $this->customlib->datetostrtotime($date))
                            );
                        } else {


                            $arr = array(
                                'student_session_id' => $value,
                                'attendence_type_id' => $this->input->post('attendencetype' . $value),
                                'remark' => $this->input->post("remark" . $value),
                                'subject_id' =>$this->input->post('subject_id'),
                                'date' => date('Y-m-d', $this->customlib->datetostrtotime($date))
                            );
                        }
                        $insert_id = $this->stuattendence_model->add($arr);
                        $absent_config = $this->config_attendance['absent'];
                        if ($arr['attendence_type_id'] == $absent_config) {
                            $absent_student_list[] = $value;
                        }
                    }
                }
                $absent_config = $this->config_attendance['absent'];
                if (!empty($absent_student_list)) {
                    $this->mailsmsconf->mailsms('absent_attendence', $absent_student_list, $date);
                }

                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Attendance Saved Successfully</div>');
                redirect('admin/stuattendence/fullattendance');
            }


            $attendencetypes = $this->attendencetype_model->get();
            $data['attendencetypeslist'] = $attendencetypes;


            $classData = $this->class_model->getClassData($class);


            if($classData['attendance_type'] == '0'){
                $resultlist = $this->stuattendence_model->searchAttendenceClassSection($class, $section, date('Y-m-d', $this->customlib->datetostrtotime($date)));

                $data['resultlist'] = $resultlist;

                $data['subject_id'] = '-1';

                $data["searchBySubject"] = false;
                $this->load->view('layout/header', $data);
                $this->load->view('admin/stuattendence/fullAttendenceList', $data);
                $this->load->view('layout/footer', $data);

                
            }else{

                $subject = -1; 
                $resultlist = $this->stuattendence_model->searchAttendenceClassSectionSubject($class, $section, date('Y-m-d', $this->customlib->datetostrtotime($date)), $subject);
                //echo "<pre>"; print_r($resultlist);exit;

                //echo "<pre>"; print_r($resultlist);exit;
                $data['resultlist'] = $resultlist;
                $data['subject_id'] = '-1';
                $data["searchBySubject"] = true;
                $this->load->view('layout/header', $data);
                $this->load->view('admin/stuattendence/fullAttendenceList', $data);
                $this->load->view('layout/footer', $data);
            }
        }
    }
   /* function attendencereport() {
        if (!$this->rbac->hasPrivilege('student_attendance', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Attendance');
        $this->session->set_userdata('sub_menu', 'stuattendence/attendenceReport');
        $data['title'] = 'Add Fees Type';
        $data['title_list'] = 'Fees Type List';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $data['class_id'] = "";
        $data['section_id'] = "";
        $data['date'] = "";
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', 'Date', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/stuattendence/attendencereport', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $date = $this->input->post('date');
            $student_list = $this->stuattendence_model->get();
            $data['studentlist'] = $student_list;
            $data['class_id'] = $class;
            $data['section_id'] = $section;
            $data['date'] = $date;
            $search = $this->input->post('search');
            if ($search == "saveattendence") {
                $session_ary = $this->input->post('student_session');
                foreach ($session_ary as $key => $value) {
                    $checkForUpdate = $this->input->post('attendendence_id' . $value);
                    if ($checkForUpdate != 0) {
                        $arr = array(
                            'id' => $checkForUpdate,
                            'student_session_id' => $value,
                            'attendence_type_id' => $this->input->post('attendencetype' . $value),
                            'date' => date('Y-m-d', $this->customlib->datetostrtotime($date))
                        );
                        $insert_id = $this->stuattendence_model->add($arr);
                    } else {
                        $arr = array(
                            'student_session_id' => $value,
                            'attendence_type_id' => $this->input->post('attendencetype' . $value),
                            'date' => date('Y-m-d', $this->customlib->datetostrtotime($date))
                        );
                        $insert_id = $this->stuattendence_model->add($arr);
                    }
                }
            }
            $attendencetypes = $this->attendencetype_model->get();
            $data['attendencetypeslist'] = $attendencetypes;
            $resultlist = $this->stuattendence_model->searchAttendenceClassSectionPrepare($class, $section, date('Y-m-d', $this->customlib->datetostrtotime($date)));
            $data['resultlist'] = $resultlist;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/stuattendence/attendencereport', $data);
            $this->load->view('layout/footer', $data);
        }
    }
*/
    function attendencereport() {
        if (!$this->rbac->hasPrivilege('student_attendance', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Attendance');
        $this->session->set_userdata('sub_menu', 'stuattendence/attendenceReport');
        $data['title'] = 'Add Fees Type';
        $data['title_list'] = 'Fees Type List';
        
        $this->form_validation->set_rules('date', 'Date', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/stuattendence/attendencereport', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $date = $this->input->post('date');
            $data['date'] = $date;
            //print_r($date);exit();
            $search = $this->input->post('search');

        
        $userdata = $this->customlib->getUserData();
        $role_id = @$userdata["role_id"];
        $id = @$userdata["id"];
        //print_r($id);exit();
        if (isset($role_id) && ($userdata["role_id"] == 2 || $userdata["role_id"] == 15 || $userdata["role_id"] == 16 || $userdata["role_id"] == 19) && ($userdata["class_teacher"] == "yes")) {
            $staff_id = @$userdata["id"];
            //print_r($id); exit();
            $resultlist = $this->stuattendence_model->searchSchoolAttendanceByStaff( $staff_id, date('Y-m-d', $this->customlib->datetostrtotime($date)));
        }
        else{
            $resultlist = $this->stuattendence_model->searchSchoolAttendance( date('Y-m-d', $this->customlib->datetostrtotime($date)));
        }
            //echo "<pre>"; print_r($resultlist); exit;

            $data['resultlist'] = $resultlist;
            //$present_resultlist = $this->stuattendence_model->searchSchoolAttendancePresent( date('Y-m-d', $this->customlib->datetostrtotime($date)));
            //echo "<pre>"; print_r($resultlist); exit;

            //$data['present_resultlist'] = $present_resultlist;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/stuattendence/attendencereport', $data);
            $this->load->view('layout/footer', $data);
        }
    }
     function classmonthlyattendencereport() {

        if (!$this->rbac->hasPrivilege('attendance_register', 'can_view')) {
            access_denied();
        }

        $data["searchBySubject"] = false;
        $this->session->set_userdata('top_menu', 'Attendance');
        $this->session->set_userdata('sub_menu', 'stuattendence/classmonthlyattendencereport');
        $attendencetypes = $this->attendencetype_model->getAttType();
        $data['attendencetypeslist'] = $attendencetypes;
        $data['title'] = 'Add Fees Type';
        $data['title_list'] = 'Fees Type List';
        $data['subject_id'] = '-1';
        $subjectId = $data['subject_id'];
        $class = $this->class_model->get('', $classteacher = 'yes');
        //echo "<pre>"; print_r($class); exit;
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();
        //      if(($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")){
        //   $data["classlist"] =   $this->customlib->getClassbyteacher($userdata["id"]);
        // }
        $data['monthlist'] = $this->customlib->getMonthDropdown();
        $data['yearlist'] = $this->stuattendence_model->attendanceYearCount();
        $data['class_id'] = "";
        $data['section_id'] = "";
        $data['date'] = "";
        $data['month_selected'] = "";
        $data['year_selected'] = "";
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');
        $this->form_validation->set_rules('month', 'Month', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/stuattendence/classattendencereport-Old', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $resultlist = array();
            $class = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $month = $this->input->post('month');
            $data["searchBySubject"] = true;
            $subjectId = $this->input->post('subjectid');
            $data['subject_id'] = $subjectId;

            $data['class_id'] = $class;
            $data['section_id'] = $section;
            $data['month_selected'] = $month;
            //print_r($month);exit();

            $studentlist = $this->student_model->searchByClassSection($class, $section);
            $session_current = $this->setting_model->getCurrentSessionName();
            $startMonth = $this->setting_model->getStartMonth();
            $centenary = substr($session_current, 0, 2); //2017-18 to 2017
            $year_first_substring = substr($session_current, 2, 2); //2017-18 to 2017
            $year_second_substring = substr($session_current, 5, 2); //2017-18 to 18
            $month_number = date("m", strtotime($month));
            $year = $this->input->post('year');
            $data['year_selected'] = $year;
            if (!empty($year)) {

                $year = $this->input->post("year");
            } else {

                if ($month_number >= $startMonth && $month_number <= 12) {
                    $year = $centenary . $year_first_substring;
                } else {
                    $year = $centenary . $year_second_substring;
                }
            }


            $num_of_days = cal_days_in_month(CAL_GREGORIAN, $month_number, $year);
            //print_r( $num_of_days);exit();
            $attr_result = array();
            $attendence_array = array();
            $student_result = array();
            $data['no_of_days'] = $num_of_days;
            $date_result = array();
            for ($i = 1; $i <= $num_of_days; $i++) {
                $att_date = $year . "-" . $month_number . "-" . sprintf("%02d", $i);
                //echo "<pre>"; print_r($att_date);exit();
                //$att_date = "2019-12-11";
                $attendence_array[] = $att_date;

                $classData = $this->class_model->getClassData($class);
                  if($classData['attendance_type'] == '0'){

                        $subjectId= '-1';
                }
                $res = $this->stuattendence_model->searchAttendenceReport($class, $section, $att_date, $subjectId);
                //echo "<pre>"; print_r($res);exit();

                $student_result = $res;
                $s = array();
                foreach ($res as $result_k => $result_v) {
                    $s[$result_v['student_session_id']] = $result_v;
                }
                $date_result[$att_date] = $s;

            }

             //echo "<pre>"; print_r($date_result[$att_date]);exit();

            if (!empty($res)){
            foreach ($res as $result_k => $result_v) {


                $classData = $this->class_model->getClassData($class);
                  if($classData['attendance_type'] == '0'){

                        $subjectId= '-1';
                }
                $date = $year . "-" . $month;
                $newdate = date('Y-m-d', strtotime($date));
                $monthAttendance[] = $this->classMonthAttendance($newdate, 1, $result_v['student_session_id'] , $subjectId);
            }

            //print_r($subjectId);exit();
            $data['monthAttendance'] = $monthAttendance;
            }
            //echo "<pre>";print_r($monthAttendance);exit();
            $data['resultlist'] = $date_result;
            //echo"<pre>"; print_r($date_result);exit;
            $data['attendence_array'] = $attendence_array;
            //echo"<pre>"; print_r($attendence_array);exit;
            $data['student_array'] = $student_result;
            //echo"<pre>"; print_r($student_result);exit;    
            if($classData['attendance_type'] == '0'){

                $data['subject_id'] = '-1';

                $data["searchBySubject"] = false;
                $this->load->view('layout/header', $data);
                $this->load->view('admin/stuattendence/classattendencereport-Old', $data);
                $this->load->view('layout/footer', $data);

                
            }else{

                //echo "<pre>"; print_r($resultlist);exit;
                $data['subject_id'] = $subjectId;
                $data["searchBySubject"] = true;
                $this->load->view('layout/header', $data);
                $this->load->view('admin/stuattendence/classattendencereport-Old', $data);
                $this->load->view('layout/footer', $data);
            }        
            
        }
    }

    function classMonthAttendance($st_month, $no_of_months, $student_id, $subjectId) {

        $record = array();

        $r = array();
        $month = date('m', strtotime($st_month));
        $year = date('Y', strtotime($st_month));

        foreach ($this->config_attendance as $att_key => $att_value) {

            $s = $this->stuattendence_model->count_attendance_obj($month, $year, $student_id, $att_value, $subjectId);


            $attendance_key = $att_key;


            $r[$attendance_key] = $s;
        }

        $record[$student_id] = $r;

        return $record;
    }


  function classattendencereport() {

        if (!$this->rbac->hasPrivilege('student_attendance_report', 'can_view')) {
            access_denied();
        }

        $data["searchBySubject"] = false;
        $this->session->set_userdata('top_menu', 'Attendance');
        $this->session->set_userdata('sub_menu', 'stuattendence/classattendencereport');
        $attendencetypes = $this->attendencetype_model->getAttType();
        $data['attendencetypeslist'] = $attendencetypes;
        //echo "<pre>"; print_r($attendencetypes); exit;
        $data['title'] = 'Add Fees Type';
        $data['title_list'] = 'Fees Type List';
        $data['subject_id'] = '-1';
        $subjectId = $data['subject_id'];
        $class = $this->class_model->get('', $classteacher = 'yes');
        //echo "<pre>"; print_r($class); exit;
        $data['classlist'] = $class;
        $userdata = $this->customlib->getUserData();
        //      if(($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")){
        //   $data["classlist"] =   $this->customlib->getClassbyteacher($userdata["id"]);
        // }
        //$data['monthlist'] = $this->customlib->getMonthDropdown();
        $data['yearlist'] = $this->stuattendence_model->attendanceYearCount();
        $data['class_id'] = "";
        $data['section_id'] = "";
        $data['date'] = "";
        $data['month_selected'] = "";
        $data['year_selected'] = "";
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');
        $this->form_validation->set_rules('start_date', 'Start Date', 'trim|required|xss_clean');
        $this->form_validation->set_rules('end_date', 'End Date', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/stuattendence/classattendencereport', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $resultlist = array();
            $class = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            
            //print_r($month); exit();
            $startdate= $this->input->post('start_date');
            $data['start_date']=$startdate;

            $enddate=$this->input->post('end_date');
            $data['end_date']= $enddate;


            //$startdate = "2019-11-01";
            $timstamp1 =strtotime($startdate);
            //$enddate = "2019-12-15";
            $timstamp2 =strtotime($enddate);
            $fri    = array();
            $sat    = array();
            $oneDay = 60*60*24;
            $countfri = 0;
            $countsat = 0;

            for($i = $timstamp1; $i <= $timstamp2; $i += $oneDay) {
                $day = date('N', $i);

                // If friday
                if($day == 5 ) {
                    // Save friday in format YYYY-MM-DD, if you need just timestamp
                    // save only $i
                    $fri[] = date('Y-m-d', $i);
                    // Since we know it is friday, we can simply skip 
                    // next 6 days so we get right to next friday
                    $i += 6 * $oneDay;
                    $countfri++;
                }
            }
            
            for($i = $timstamp1; $i <= $timstamp2; $i += $oneDay) {
                $day = date('N', $i);

                // If Saturday
                if($day == 6 ) {
                    // Save Saturday in format YYYY-MM-DD, if you need just timestamp
                    // save only $i
                    $sat[] = date('Y-m-d', $i);
                    // Since we know it is Saturday, we can simply skip 
                    // next 6 days so we get right to next Saturday
                    $i += 6 * $oneDay;
                    $countsat++;
                }
            }
            $total_off_days = $countfri + $countsat;
            $data['total_off_days'] = $total_off_days;
            //echo"<pre>"; print_r($total_off_days);exit;
            //echo"<pre>"; print_r($countfri);exit;
            $date1=date_create($startdate);
            $date2=date_create($enddate);
            $diff=date_diff($date1,$date2);
            $total_days = $diff->format("%a");
            $total_working_days = $total_days - $total_off_days + 1;
            $data['total_working_days'] = $total_working_days;
            //echo"<pre>"; print_r($total_working_days);exit;
            $subjectId = $this->input->post('subjectid');
            $data['subject_id'] = $subjectId;

            $data['class_id'] = $class;
            $data['section_id'] = $section;
            //$data['month_selected'] = $month;

            $studentlist = $this->student_model->searchByClassSection($class, $section);
            //echo "<pre>"; print_r($studentlist); exit();
            //$session_current = $this->setting_model->getCurrentSessionName();
            //echo "<pre>"; print_r($session_current); exit();            
            //$startMonth = $this->setting_model->getStartMonth();
            //echo "<pre>"; print_r($startMonth); exit();
            //$centenary = substr($session_current, 0, 2); //2019-20 to 20
            //echo "<pre>"; print_r($centenary); exit();
            //$year_first_substring = substr($session_current, 2, 2); //2019-20 to 19
           // echo "<pre>"; print_r($year_first_substring); exit();
            //$year_second_substring = substr($session_current, 5, 2); //2019-20 to 20
            //$month_number = date("m", strtotime($month));
            //echo "<pre>"; print_r($month_number); exit();
            //$year = $this->input->post('year');
            //$data['year_selected'] = $year;
            


            $attr_result = array();
            $attendence_array = array();
            $student_result = array();
            $date_result = array();
            for ($i = $timstamp1; $i <= $timstamp2; $i += $oneDay) {
                $att_date = date("Y-m-d", $i);
                //echo "<pre>"; print_r($att_date); exit();
                $attendence_array[] = $att_date;

                $res = $this->stuattendence_model->searchAttendenceReport($class, $section, $att_date, $subjectId);
                //echo "<pre>"; print_r($res); exit();
                $student_result = $res;
                $s = array();
                foreach ($res as $result_k => $result_v) {
                    $s[$result_v['student_session_id']] = $result_v;
                }
                $date_result[$att_date] = $s;
            }

            if (!empty($res)){
            foreach ($res as $result_k => $result_v) {


                $classData = $this->class_model->getClassData($class);
                  if($classData['attendance_type'] == '0'){

                        $subjectId= '-1';
                }
                $monthAttendance[] = $this->monthAttendance($startdate, $enddate, $result_v['student_session_id'], $subjectId);
                //$startdate = "2019-11-01";
                //$enddate = "2019-12-15";
            }
            $data['monthAttendance'] = $monthAttendance;
           }

            //echo "<pre>"; print_r($monthAttendance); exit();
            $data['resultlist'] = $date_result;
            //echo "<pre>"; print_r($date_result); exit();
            $data['attendence_array'] = $attendence_array;
            //echo "<pre>"; print_r($attendence_array); exit();
            $data['student_array'] = $student_result;
            //echo "<pre>"; print_r($student_result); exit();
            $classData = $this->class_model->getClassData($class);


            if($classData['attendance_type'] == '0'){

                $data['subject_id'] = '-1';
                $data["searchBySubject"] = false;
                $this->load->view('layout/header', $data);
                $this->load->view('admin/stuattendence/classattendencereport', $data);
                $this->load->view('layout/footer', $data);

                
            }else{

                //echo "<pre>"; print_r($resultlist);exit;
                $data['subject_id'] = $subjectId;
                $data["searchBySubject"] = true;
                $this->load->view('layout/header', $data);
                $this->load->view('admin/stuattendence/classattendencereport', $data);
                $this->load->view('layout/footer', $data);
            }

        }
    }

    function monthAttendance($startdate, $enddate, $student_id, $subjectId) {

        $record = array();

        $r = array();
        

        foreach ($this->config_attendance as $att_key => $att_value) {

            $s = $this->stuattendence_model->count_attendance_total_obj($startdate , $enddate, $student_id, $att_value, $subjectId);


            $attendance_key = $att_key;


            $r[$attendance_key] = $s;
        }

        $record[$student_id] = $r;
        //echo "<pre>"; print_r($record); exit();

        return $record;
    }

}
?>