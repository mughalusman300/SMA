<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Stuattendence_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->current_date = $this->setting_model->getDateYmd();
    }

    public function get($id = null) {
        $this->db->select()->from('student_attendences');
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

    public function add($data) {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('student_attendences', $data);
        } else {
            $this->db->insert('student_attendences', $data);
        }
    }


    public function searchAttendenceClassSectionSubject($class_id, $section_id, $date, $subject_id){

        $sql = "select student_sessions.attendence_id,students.firstname,student_sessions.date,student_sessions.remark,student_sessions.subject_id,students.roll_no,students.admission_no,students.lastname,student_sessions.attendence_type_id,student_sessions.id as student_session_id, attendence_type.type as `att_type`,attendence_type.key_value as `key` from students ,(SELECT student_session.id,student_session.student_id ,IFNULL(student_attendences.date, 'xxx') as date,student_attendences.remark,student_attendences.subject_id, IFNULL(student_attendences.id, 0) as attendence_id,student_attendences.attendence_type_id FROM `student_session` LEFT JOIN student_attendences ON student_attendences.student_session_id=student_session.id  and student_attendences.date=" . $this->db->escape($date) . " and student_attendences.subject_id=".$this->db->escape($subject_id)." where  student_session.session_id=" . $this->db->escape($this->current_session) . " and student_session.class_id=" . $this->db->escape($class_id) ." and student_session.section_id=" . $this->db->escape($section_id) . ") as student_sessions   LEFT JOIN attendence_type ON attendence_type.id=student_sessions.attendence_type_id where student_sessions.student_id = students.id and students.is_active = 'yes'";
        $query = $this->db->query($sql);

        return $query->result_array();

    }




    public function searchAttendenceClassSection($class_id, $section_id, $date) {

        $sql = "select student_sessions.attendence_id,students.firstname,student_sessions.date,student_sessions.remark,students.roll_no,students.admission_no,students.lastname,student_sessions.attendence_type_id,student_sessions.id as student_session_id, attendence_type.type as `att_type`,attendence_type.key_value as `key` from students ,(SELECT student_session.id,student_session.student_id ,IFNULL(student_attendences.date, 'xxx') as date,student_attendences.remark, IFNULL(student_attendences.id, 0) as attendence_id,student_attendences.attendence_type_id FROM `student_session` LEFT JOIN student_attendences ON student_attendences.student_session_id=student_session.id  and student_attendences.date=" . $this->db->escape($date) . " where  student_session.session_id=" . $this->db->escape($this->current_session) . " and student_session.class_id=" . $this->db->escape($class_id) . " and student_session.section_id=" . $this->db->escape($section_id) . ") as student_sessions   LEFT JOIN attendence_type ON attendence_type.id=student_sessions.attendence_type_id where student_sessions.student_id = students.id and students.is_active = 'yes' ";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function searchAttendenceClassSectionnew($class_id, $section_id, $date) {

        //student_attendences.subject_id ='-1' and (student_attendences.subject_id !='' and OR student_attendences.subject_id IS NULL) and
        $sql = "select student_sessions.attendence_id,students.firstname,student_sessions.date,student_sessions.remark,students.roll_no,students.admission_no,students.lastname,student_sessions.attendence_type_id,student_sessions.id as student_session_id, attendence_type.type as `att_type`,attendence_type.key_value as `key` from students ,(SELECT student_session.id,student_session.student_id ,IFNULL(student_attendences.date, 'xxx') as date,student_attendences.remark, IFNULL(student_attendences.id, 0) as attendence_id,student_attendences.attendence_type_id FROM `student_session` LEFT JOIN student_attendences ON student_attendences.student_session_id=student_session.id  and student_attendences.date=" . $this->db->escape($date) . " where
             student_session.session_id=" . $this->db->escape($this->current_session) . " and student_session.class_id=" . $this->db->escape($class_id) . " and student_session.section_id=" . $this->db->escape($section_id) . ") as student_sessions   LEFT JOIN attendence_type ON attendence_type.id=student_sessions.attendence_type_id where student_sessions.student_id = students.id and students.is_active = 'yes' ";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function searchAttendenceReport($class_id, $section_id, $date, $subjectId) {

        if($subjectId == '-1'){
            $sql = "select student_sessions.attendence_id,students.firstname,student_sessions.date,student_sessions.remark,students.roll_no,students.admission_no,students.lastname,student_sessions.attendence_type_id,student_sessions.id as student_session_id, attendence_type.type as `att_type`,attendence_type.key_value as `key` from students ,(SELECT student_session.id,student_session.student_id ,IFNULL(student_attendences.date, 'xxx') as date,student_attendences.remark, IFNULL(student_attendences.id, 0) as attendence_id,student_attendences.attendence_type_id FROM `student_session` LEFT JOIN student_attendences ON student_attendences.student_session_id=student_session.id  and student_attendences.date=" . $this->db->escape($date) . " where  student_session.session_id=" . $this->db->escape($this->current_session) . " and student_session.class_id=" . $this->db->escape($class_id) . " and student_session.section_id=" . $this->db->escape($section_id) . ") as student_sessions   LEFT JOIN attendence_type ON attendence_type.id=student_sessions.attendence_type_id where student_sessions.student_id=students.id  and students.is_active = 'yes'";
        }else{
            
            $sql = "select student_sessions.attendence_id,students.firstname,student_sessions.date,student_sessions.remark,students.roll_no,students.admission_no,students.lastname,student_sessions.attendence_type_id,student_sessions.id as student_session_id, attendence_type.type as `att_type`,attendence_type.key_value as `key` from students ,(SELECT student_session.id,student_session.student_id ,IFNULL(student_attendences.date, 'xxx') as date,student_attendences.remark, IFNULL(student_attendences.id, 0) as attendence_id,student_attendences.attendence_type_id FROM `student_session` LEFT JOIN student_attendences ON student_attendences.student_session_id=student_session.id  and student_attendences.date=" . $this->db->escape($date) . " and student_attendences.subject_id=". $this->db->escape($subjectId) ." where  student_session.session_id=" . $this->db->escape($this->current_session) . " and student_session.class_id=" . $this->db->escape($class_id) . " and student_session.section_id=" . $this->db->escape($section_id) . ") as student_sessions   LEFT JOIN attendence_type ON attendence_type.id=student_sessions.attendence_type_id where student_sessions.student_id=students.id  and students.is_active = 'yes'";
        }



        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function searchAttendenceClassSectionPrepare($class_id, $section_id, $date) {
        $query = $this->db->query("select 
            student_sessions.attendence_id,
            student_sessions.remark,
            students.firstname,
            students.admission_no,.
            student_sessions.date,
            students.roll_no,
            students.lastname,
            student_sessions.attendence_type_id,
            student_sessions.id as student_session_id from students ,
            (SELECT 
            student_session.id,
            student_session.student_id ,
            IFNULL(student_attendences.date, 'xxx') as date,
            student_attendences.remark,
            IFNULL(student_attendences.id, 0) as attendence_id,
            student_attendences.attendence_type_id FROM `student_session` 
            RIGHT JOIN student_attendences ON student_attendences.student_session_id=student_session.id  and student_attendences.date=" . $this->db->escape($date) . " where  student_session.session_id=" . $this->db->escape($this->current_session) . " and student_session.class_id=" . $this->db->escape($class_id) . " and student_session.section_id=" . $this->db->escape($section_id) . ") as student_sessions where student_sessions.student_id=students.id ");
        return $query->result_array();
    }
   
    public function searchSchoolAttendance($date) {
      
     $query = $this->db->select(" COUNT(student_attendences.student_session_id) AS total_attendances,
        sum(case when student_attendences.attendence_type_id != '4' then 1 else 0 end) AS present_count, date,student_attendences.id,subjects.name,student_attendences.subject_id,classes.class,sections.section ")
         ->join("subjects","subjects.id = student_attendences.subject_id",'left')
         ->join("student_session","student_session.id = student_attendences.student_session_id")
         ->join("classes","classes.id = student_session.class_id")
         ->join("sections","sections.id = student_session.section_id")
         ->where("student_attendences.date",$date)
         ->group_by("class,section,subject_id")
         ->order_by("student_attendences.subject_id")
         ->get("student_attendences");
    

        
        return $query->result_array();
    }
     /*public function searchSchoolAttendance($date) {
      
     $query = $this->db->select(" classes.*, class_sections.section_id,sections.section,subjects.name,teacher_subjects.subject_id")

         ->join("class_sections","class_sections.class_id = classes.id")
         ->join("sections","sections.id = class_sections.section_id")
         ->join("teacher_subjects","teacher_subjects.class_section_id = class_sections.id","left")
         ->join("subjects","subjects.id = teacher_subjects.subject_id","left")
         //->join("student_session","student_session.id = student_attendences.student_session_id")
         //->join("classes","classes.id = student_session.class_id")
         //->join("sections","sections.id = student_session.section_id")
         //->where("student_attendences.date",$date)
         //->group_by("class,section,subject_id")
         ->order_by('classes.id', 'DESC')
         ->order_by('class_sections.id', 'asc')
         ->get("classes");
    

        
        return $query->result_array();
    }*/
    public function searchSchoolAttendanceByStaff($staff_id, $date) {

     //print_r($staff_id);exit(); 
     $query = $this->db->query("select 
     COUNT(student_attendences.student_session_id) AS total_attendances, 
     sum(case when student_attendences.attendence_type_id != '4' then 1 else 0 end) AS present_count,
        class_teacher.staff_id, 
        class_teacher.class_id, 
        class_teacher.section_id,
        classes.class,
        sections.section,
        
        student_attendences.date,
        student_attendences.subject_id,
        student_attendences.student_session_id,
        subjects.name  
        from student_attendences

        Inner join student_session
        ON student_session.id = student_attendences.student_session_id 

        inner join class_teacher
        ON class_teacher.class_id =  student_session.class_id   and
        class_teacher.section_id =  student_session.section_id 

        inner join classes
        ON classes.id =  student_session.class_id
        inner join sections
        ON sections.id =  student_session.section_id

         left join subjects
        ON subjects.id = student_attendences.subject_id

        where student_attendences.date= '$date' and 
        student_attendences.subject_id = '-1' and
        class_teacher.staff_id = '$staff_id'
        group by class_teacher.class_id, class_teacher.section_id, student_attendences.subject_id

        UNION SELECT 
     COUNT(student_attendences.student_session_id) AS total_attendances, 
     sum(case when student_attendences.attendence_type_id != '4' then 1 else 0 end) AS present_count,

        teacher_subjects.teacher_id, 
        teacher_subjects.class_id, 
        teacher_subjects.class_section_id,
        classes.class,
        sections.section,
        student_attendences.date,
        student_attendences.subject_id,
        student_attendences.student_session_id,
        subjects.name  
        from student_attendences

        Inner join student_session
        ON student_session.id = student_attendences.student_session_id

        inner join class_sections
        ON class_sections.class_id =   student_session.class_id and
         class_sections.section_id =   student_session.section_id

        inner join teacher_subjects
        ON teacher_subjects.subject_id =   student_attendences.subject_id and
          teacher_subjects.class_id = student_session.class_id  and 
          teacher_subjects.class_section_id = class_sections.id

        inner join classes
        ON classes.id =  student_session.class_id
        inner join sections
        ON sections.id =  student_session.section_id

         left join subjects
        ON subjects.id = student_attendences.subject_id 

        where student_attendences.date= '$date' and 
        student_attendences.subject_id != '-1' and
        teacher_subjects.teacher_id = '$staff_id'
        group by teacher_subjects.class_id, teacher_subjects.class_section_id, student_attendences.subject_id

         ");

        /*$query = $this->db->get();OR WHERE teacher_subjects.teacher_id = '$staff_id' where class_teacher.staff_id= '$staff_id'
        OR teacher_subjects.teacher_id = '$staff_id'
        and student_attendences.date= '2019-12-05'*/
        
        return $query->result_array();
    }

    function count_attendance_obj($month, $year, $student_id, $attendance_type = 1,  $subject_id) {


        $query = $this->db->select('count(*) as attendence')->join("student_session", "student_attendences.student_session_id = student_session.id")->where(array('student_attendences.student_session_id' => $student_id, 'month(date)' => $month, 'year(date)' => $year, 'student_attendences.attendence_type_id' => $attendance_type,'student_attendences.subject_id' => $subject_id))->get("student_attendences");

        return $query->row()->attendence;

    }
     // edited
     function count_attendance_total_obj($startdate, $enddate, $student_id, $attendance_type = 1, $subject_id) {
   

        //$this->db->where('books.created_at >=', $start_date);
        //$this->db->where('books.created_at <=', $end_date.);
        $query = $this->db->select('count(*) as attendence')->join("student_session", "student_attendences.student_session_id = student_session.id")->where(array('student_attendences.student_session_id' => $student_id, 'date >= ' => $startdate, 'date <= ' => $enddate,'student_attendences.attendence_type_id' => $attendance_type,'student_attendences.subject_id' => $subject_id))->get("student_attendences");

        return $query->row()->attendence;

    }

    function attendanceYearCount() {

        $query = $this->db->select("distinct year(date) as year")->get("student_attendences");

        return $query->result_array();
    }

}
