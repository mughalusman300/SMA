<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Creditnotes_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->current_date = $this->setting_model->getDateYmd();
    }

    public function add_credit_note($data) {

        $this->db->insert("credit_note", $data);
        return $this->db->insert_id();
    }

    public function add_creditnote($data) {

        $this->db->insert("credit_note", $data);
        return $this->db->insert_id();
    }
    public function add_creditnote_invoice($data){
        $this->db->insert("credit_note", $data);
        $creditNoteId = $this->db->insert_id();
        $insertCreditNote = "INSERT INTO  creditnote_details (`creditnote_id`,
                            `student_fees_master_id`,
                            `fee_groups_feetype_id`,
                            `amount_detail`,
                            `date_created`,
                            `book_order_id`,
                            `book_id`)"
                . " SELECT '".$creditNoteId."', student_fees_master_id,
                            `fee_groups_feetype_id`,
                            null,
                            `date_created`,
                            `book_order_id`,
                            `book_id`"
                . "  FROM invoices_details WHERE invoice_id = '".$data['invoice_number']."' ";
        $this->db->query($insertCreditNote);

        return $creditNoteId;
    }
    public function update_creditnote_number($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('credit_note', $data);
    }
    public function update_credit_note_number($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('credit_note', $data);
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function get($id = null, $guardian_id = null) {

        $this->db->select("credit_note.*, students.parent_id,students.guardian_id")->from('credit_note');
       // $this->db->join('(select students.parent_id,students.guardian_id  from students group by parent_id) as students', ' credit_note.parent_id = students.parent_id1');
	   $this->db->join('students', ' `credit_note`.`admission_no` = `students`.`admission_no`');
        if ($guardian_id != '')
            $this->db->where('students.guardian_id', $guardian_id);
        if ($id != null) {
            $this->db->where('credit_note.id', $id);
        } else {
            $this->db->order_by('credit_note.id');
        }
         $this->db->group_by('credit_note.id');
        $query = $this->db->get();
        if ($id != null) {
            $credit_notelist = $query->row_array();
        } else {
            $credit_notelist = $query->result_array();
        }


        return $credit_notelist;
    }
    public function getCreditsNoteByStudentId($student_id){
        $this->db->select("parent_id")->from('students')->where('students.id', $student_id);
        $studentquery = $this->db->get();
        $studentArray   = $studentquery->row_array();
        
        $this->db->select("credit_note.*, students.parent_id,students.guardian_id")->from('credit_note');
        $this->db->join('students', ' `credit_note`.`parent_id` = `students`.`parent_id`');
        $this->db->where('students.parent_id', $studentArray["parent_id"]);
		$this->db->where("credit_note.status <> 'deleted'");
        $this->db->where('(credit_note.creditnote_amount - credit_note.credit_paid) >0');
        $this->db->group_by('credit_note.id');
        $query = $this->db->get();
        $credit_notelist = $query->result_array();
        return $credit_notelist;
    }
    public function update_creditnotes_balance($creditnoteid,$amount){
        $this->db->query("UPDATE credit_note SET credit_paid = (credit_paid +".$amount."), credit_balance=(creditnote_amount-credit_paid) WHERE id = ".$creditnoteid);
    }
            
            
            
            
    public function getCreditnoteList($id = null, $guardian_id = null,$admission_no = null,$invoice_no = null, $getTotal = false,$date_from=null, $date_to = null, $limit = 10, $start = 0, $export=false) {

        $this->db->select("credit_note.*, students.parent_id,students.guardian_id")->from('credit_note');
        $this->db->join('students', ' `credit_note`.`admission_no` = `students`.`admission_no`');
        if ($guardian_id != '')
            $this->db->where('students.guardian_id', $guardian_id);
        if ($id != null) {
            $this->db->where('credit_note.id', $id);
        }
         if ($admission_no != null) {
            $this->db->where('credit_note.admission_no', $admission_no);
        }
         if ($invoice_no != null) {
            $this->db->where('credit_note.creditnote_number', $invoice_no);
        }
		if ($date_from != null) {
            $this->db->where('credit_note.creditnote_date>=', $date_from);
        }
		if ($date_to != null) {
            $this->db->where('credit_note.creditnote_date<=', $date_to);
        }
        $this->db->order_by('credit_note.creditnote_date', 'desc');

         
        if ($getTotal) {
            return $this->db->count_all_results();
        } else {
            $this->db->group_by('credit_note.id');
            if(!$export)
            	$this->db->limit($limit, $start);
            $query = $this->db->get();
    //    echo $this->db->last_query();
    //    die;
            
                $credit_notelist = $query->result_array();
        }



        return $credit_notelist;
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function getCreditnoteDetails($invoiceId = null, $guardian_id = null,$admission_no = null,$invoice_no = null, $getTotal = false,$date_from=null, $date_to = null) {

        $this->db->select(
                "credit_note.*,"
                . "creditnote_details.student_fees_master_id,"
                . "creditnote_details.fee_groups_feetype_id,"
                
                . "creditnote_details.date_created,"
                //      . "parents.*,"
        )->from('credit_note');
        $this->db->join('creditnote_details', ' credit_note.id = creditnote_details.creditnote_id');
		
      
        if ($invoiceId != null) {
            $this->db->where('credit_note.id', $invoiceId);
        } else {
            $this->db->order_by('credit_note.id');
        }
		if ($guardian_id != ''){
            $this->db->join('students as students_parent', ' credit_note.parent_id = students_parent.parent_id');
			$this->db->where('students_parent.guardian_id', $guardian_id);
		}
		 if ($admission_no != null)
            $this->db->where('credit_note.admission_no', $admission_no);
        
         if ($invoice_no != null) 
            $this->db->where('credit_note.invoice_number', $invoice_no);
        
		if ($date_from != null) 
            $this->db->where('credit_note.invoice_date>=', $date_from);
        
		if ($date_to != null) 
            $this->db->where('credit_note.invoice_date<=', $date_to);
        
		
        // $this->db->group_by('students.parent_id');
        $query = $this->db->get()->result_array();
       // echo $this->db->last_query();
       //  die;
        // Loop through the products array
        foreach ($query as $i => $creditnote) {

            $this->db->where('parent_id', $creditnote['parent_id']);
            $parent_query = $this->db->get('students')->row_array();
            $query[$i]['parents'] = $parent_query;

            $sql = 'select 
                            students.*, classes.class,sections.section  , fees_discounts.name AS discount_name,
                        fees_discounts.code AS discount_code,
                        fees_discounts.amount AS discount_amount from student_session 
                        INNER JOIN 
                            students ON students.id = student_session.student_id
                        INNER JOIN  
                            student_fees_master ON  student_session.id = student_fees_master.student_session_id  
                        INNER JOIN 
                            classes ON classes.id = student_session.class_id
                        INNER JOIN 
                            sections ON sections.id = student_session.section_id
                        LEFT JOIN
                             fees_discounts ON fees_discounts.id = student_fees_master.fee_discount_id       
                        WHERE
                            student_fees_master.id = ' . $creditnote['student_fees_master_id'];
            $student_query = $this->db->query($sql);
			
            $query[$i]['students'] = $student_query->result_array();
			if(count($query[$i]['students'])>0){
			//echo "<pre>";
			//print_r($query[$i]['students']);
			//die;
			
			if(isset($query[$i]['students'][0]))
				$admission_no = $query[$i]['students'][0]['admission_no'];
			else
				$admission_no = $query[$i]['students']['admission_no'];
			
            	$query[$i]['student_discount_fee'] = $this->feediscount_model->getStudentIndiviualDiscounts($admission_no);
			

            $this->db->where('id', $creditnote['fee_groups_feetype_id']);
            $fee_groups_feetype_query = $this->db->get('fee_groups_feetype')->result_array();
			if(count($fee_groups_feetype_query)>0){
            foreach ($fee_groups_feetype_query as $j => $fee_groups_feetype_row) {
                $this->db->where('id', $fee_groups_feetype_row['fee_groups_id']);
                $fee_groups_query = $this->db->get('fee_groups')->row_array();
                $fee_groups_feetype_query[$j]['fee_groups'] = $fee_groups_query;

                $this->db->where('id', $fee_groups_feetype_row['feetype_id']);
                $feetype_query = $this->db->get('feetype')->row_array();
                $fee_groups_feetype_query[$j]['feetype'] = $feetype_query;

                $this->db->where('id', $fee_groups_feetype_row['feetype_id']);
                $feetype_query = $this->db->get('feetype')->row_array();
                $fee_groups_feetype_query[$j]['feetype'] = $feetype_query;
            }
            $query[$i]['fee_groups_feetype'] = $fee_groups_feetype_query;
			}
			}
        }
        return $query;
    }

    public function getStudentFeesArray($ids = null, $student_session_id) {
        $query = "SELECT feemasters.id as feemastersid, feemasters.amount as amount,IFNULL(student_fees.id, 'xxx') as invoiceno,IFNULL(student_fees.payment_mode, 'xxx') as payment_mode,IFNULL(student_fees.amount_discount, 'xxx') as discount,IFNULL(student_fees.amount_fine, 'xxx') as fine, IFNULL(student_fees.date, 'xxx') as date,feetype.type ,feecategory.category FROM feemasters LEFT JOIN (select student_fees.id,student_fees.payment_mode,student_fees.feemaster_id,student_fees.amount_fine,student_fees.amount_discount,student_fees.date,student_fees.student_session_id from student_fees , student_session where student_fees.student_session_id=student_session.id and student_session.id=" . $this->db->escape($student_session_id) . ") as student_fees ON student_fees.feemaster_id=feemasters.id LEFT JOIN feetype ON feemasters.feetype_id = feetype.id LEFT JOIN feecategory ON feetype.feecategory_id = feecategory.id where feemasters.id IN (" . $ids . ")";

        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function getTotalCollectionBydate($date) {
        $sql = "SELECT sum(amount) as `amount`, SUM(amount_discount) as `amount_discount` ,SUM(amount_fine) as `amount_fine` FROM `student_fees` where date=" . $this->db->escape($date);
        $query = $this->db->query($sql);
        return $query->row();
    }

    public function getStudentFees($id = null) {
        $this->db->select('feecategory.category,student_fees.id as `invoiceno`,student_fees.date,student_fees.id,student_fees.amount,student_fees.amount_discount,student_fees.amount_fine,student_fees.created_at,feetype.type')->from('student_fees');
        $this->db->join('student_session', 'student_session.id = student_fees.student_session_id');
        $this->db->join('feemasters', 'feemasters.id = student_fees.feemaster_id');
        $this->db->join('feetype', 'feetype.id = feemasters.feetype_id');
        $this->db->join('feecategory', 'feetype.feecategory_id = feecategory.id');
        $this->db->where('student_session.student_id', $id);
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->order_by('student_fees.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getFeeByInvoice($id = null) {
        $this->db->select('feecategory.category,student_fees.date,student_fees.payment_mode,student_fees.id as `student_fee_id`,student_fees.amount,student_fees.amount_discount,student_fees.amount_fine,student_fees.created_at,classes.class,sections.section,feetype.type,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,students.dob ,students.current_address,    students.permanent_address,students.category_id,    students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name, students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.rte')->from('student_fees');
        $this->db->join('student_session', 'student_session.id = student_fees.student_session_id');
        $this->db->join('feemasters', 'feemasters.id = student_fees.feemaster_id');
        $this->db->join('feetype', 'feetype.id = feemasters.feetype_id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('feecategory', 'feetype.feecategory_id = feecategory.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->where('student_fees.id', $id);
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->order_by('student_fees.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getTodayStudentFees() {
        $this->db->select('student_fees.date,student_fees.id,student_fees.amount,student_fees.amount_discount,student_fees.amount_fine,student_fees.created_at,classes.class,sections.section,students.firstname,students.lastname,students.admission_no,students.roll_no,students.dob,students.guardian_name,feetype.type')->from('student_fees');
        $this->db->join('student_session', 'student_session.id = student_fees.student_session_id');
        $this->db->join('feemasters', 'feemasters.id = student_fees.feemaster_id');
        $this->db->join('feetype', 'feetype.id = feemasters.feetype_id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->where('student_fees.date', $this->current_date);
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->order_by('student_fees.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function remove($id, $sub_invoice) {
        $this->db->where('id', $id);
        $q = $this->db->get('student_fees_deposite');
        if ($q->num_rows() > 0) {
            $result = $q->row();
            $a = json_decode($result->amount_detail, true);
            unset($a[$sub_invoice]);
            if (!empty($a)) {
                $data['amount_detail'] = json_encode($a);
                $this->db->where('id', $id);
                $this->db->update('student_fees_deposite', $data);
            } else {
                $this->db->where('id', $id);
                $this->db->delete('student_fees_deposite');
            }
        }
    }

    public function add($data) {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('student_fees', $data);
        } else {
            $this->db->insert('student_fees', $data);
            return $this->db->insert_id();
        }
    }

    public function getDueStudentFees($feegroup_id = null, $fee_groups_feetype_id = null, $class_id = null, $section_id = null) {

        $query = "SELECT IFNULL(student_fees_deposite.id, 0) as student_fees_deposite_id, IFNULL(student_fees_deposite.fee_groups_feetype_id, 0) as fee_groups_feetype_id, IFNULL(student_fees_deposite.amount_detail, 0) as amount_detail, student_fees_master.id as `fee_master_id`,fee_groups_feetype.feetype_id ,fee_groups_feetype.amount,fee_groups_feetype.due_date, `classes`.`id` AS `class_id`, `student_session`.`id` as `student_session_id`, `students`.`id`, `classes`.`class`, `sections`.`id` AS `section_id`, `sections`.`section`, `students`.`id`, `students`.`admission_no`, `students`.`roll_no`, `students`.`admission_date`, `students`.`firstname`, `students`.`lastname`, `students`.`image`, `students`.`mobileno`, `students`.`email`, `students`.`state`, `students`.`city`, `students`.`pincode`, `students`.`religion`, `students`.`dob`, `students`.`current_address`, `students`.`permanent_address`, IFNULL(students.category_id, 0) as `category_id`, IFNULL(categories.category, '') as `category`, `students`.`adhar_no`, `students`.`samagra_id`, `students`.`bank_account_no`, `students`.`bank_name`, `students`.`ifsc_code`, `students`.`guardian_name`, `students`.`guardian_relation`, `students`.`guardian_phone`, `students`.`guardian_address`, `students`.`is_active`, `students`.`created_at`, `students`.`updated_at`, `students`.`father_name`, `students`.`rte`, `students`.`gender` FROM `students` JOIN `student_session` ON `student_session`.`student_id` = `students`.`id` JOIN `classes` ON `student_session`.`class_id` = `classes`.`id` JOIN `sections` ON `sections`.`id` = `student_session`.`section_id` LEFT JOIN `categories` ON `students`.`category_id` = `categories`.`id` INNER JOIN student_fees_master on student_fees_master.student_session_id=student_session.id and student_fees_master.fee_session_group_id=" . $this->db->escape($feegroup_id) . " LEFT JOIN student_fees_deposite on student_fees_deposite.student_fees_master_id=student_fees_master.id and student_fees_deposite.fee_groups_feetype_id=" . $this->db->escape($fee_groups_feetype_id) . "  INNER JOIN fee_groups_feetype on fee_groups_feetype.id = " . $this->db->escape($fee_groups_feetype_id) . " WHERE `student_session`.`session_id` = " . $this->current_session . " AND 
            `students`.`is_active` = 'yes'  AND 
            `student_session`.`class_id` = " . $this->db->escape($class_id) . " AND `student_session`.`section_id` = " . $this->db->escape($section_id) . " ORDER BY `students`.`id`";
        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function getDueFeeBystudent($class_id = null, $section_id = null, $student_id = null) {
        $query = "SELECT feemasters.id as feemastersid, feemasters.amount as amount,IFNULL(student_fees.id, 'xxx') as invoiceno,IFNULL(student_fees.amount_discount, 'xxx') as discount,IFNULL(student_fees.amount_fine, 'xxx') as fine,IFNULL(student_fees.payment_mode, 'xxx') as payment_mode,IFNULL(student_fees.date, 'xxx') as date,feetype.type ,feecategory.category,student_fees.description FROM feemasters LEFT JOIN (select student_fees.id,student_fees.feemaster_id,student_fees.payment_mode,student_fees.amount_fine,student_fees.amount_discount,student_fees.date,student_fees.student_session_id,student_fees.description  from student_fees , student_session where student_fees.student_session_id=student_session.id and student_session.student_id=" . $this->db->escape($student_id) . " and student_session.class_id=" . $this->db->escape($class_id) . " and student_session.section_id=" . $this->db->escape($section_id) . ") as student_fees ON student_fees.feemaster_id=feemasters.id JOIN feetype ON feemasters.feetype_id = feetype.id JOIN feecategory ON feetype.feecategory_id = feecategory.id  where  feemasters.class_id=" . $this->db->escape($class_id) . " and feemasters.session_id=" . $this->db->escape($this->current_session);
        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function getDueFeeBystudentSection($class_id = null, $section_id = null, $student_session_id = null) {
        $query = "SELECT feemasters.id as feemastersid, feemasters.amount as amount,IFNULL(student_fees.id, 'xxx') as invoiceno,IFNULL(student_fees.amount_discount, 'xxx') as discount,IFNULL(student_fees.amount_fine, 'xxx') as fine, IFNULL(student_fees.date, 'xxx') as date,feetype.type ,feecategory.category FROM feemasters LEFT JOIN (select student_fees.id,student_fees.feemaster_id,student_fees.amount_fine,student_fees.amount_discount,student_fees.date,student_fees.student_session_id from student_fees , student_session where student_fees.student_session_id=student_session.id and student_session.id=" . $this->db->escape($student_session_id) . " ) as student_fees ON student_fees.feemaster_id=feemasters.id LEFT JOIN feetype ON feemasters.feetype_id = feetype.id LEFT JOIN feecategory ON feetype.feecategory_id = feecategory.id  where  feemasters.class_id=" . $this->db->escape($class_id) . " and feemasters.session_id=" . $this->db->escape($this->current_session);
        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function getFeesByClass($class_id = null, $section_id = null, $student_id = null) {
        $query = "SELECT feemasters.id as feemastersid, feemasters.amount as amount,IFNULL(student_fees.id, 'xxx') as invoiceno,IFNULL(student_fees.amount_discount, 'xxx') as discount,IFNULL(student_fees.amount_fine, 'xxx') as fine, IFNULL(student_fees.date, 'xxx') as date,feetype.type ,feecategory.category FROM feemasters LEFT JOIN (select student_fees.id,student_fees.feemaster_id,student_fees.amount_fine,student_fees.amount_discount,student_fees.date,student_fees.student_session_id from student_fees , student_session where student_fees.student_session_id=student_session.id and student_session.student_id=" . $this->db->escape($student_id) . " and student_session.class_id=" . $this->db->escape($class_id) . " and student_session.section_id=" . $this->db->escape($section_id) . ") as student_fees ON student_fees.feemaster_id=feemasters.id LEFT JOIN feetype ON feemasters.feetype_id = feetype.id LEFT JOIN feecategory ON feetype.feecategory_id = feecategory.id  where  feemasters.class_id=" . $this->db->escape($class_id) . " and feemasters.session_id=" . $this->db->escape($this->current_session);
        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function getFeeBetweenDate($start_date, $end_date) {

        $this->db->select('student_fees.date,student_fees.id,student_fees.amount,student_fees.amount_discount,student_fees.amount_fine,student_fees.created_at,students.rte,classes.class,sections.section,students.firstname,students.lastname,students.admission_no,students.roll_no,students.dob,students.guardian_name,feetype.type')->from('student_fees');
        $this->db->join('student_session', 'student_session.id = student_fees.student_session_id');
        $this->db->join('feemasters', 'feemasters.id = student_fees.feemaster_id');
        $this->db->join('feetype', 'feetype.id = feemasters.feetype_id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->where('student_fees.date >=', $start_date);
        $this->db->where('student_fees.date <=', $end_date);
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->order_by('student_fees.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getStudentTotalFee($class_id, $student_session_id) {
        $query = "SELECT a.totalfee,b.fee_deposit,b.payment_mode  FROM ( SELECT COALESCE(sum(amount),0) as totalfee FROM `feemasters` WHERE session_id =$this->current_session and class_id=" . $this->db->escape($class_id) . ") as a, (select COALESCE(sum(amount),0) as fee_deposit,payment_mode from student_fees WHERE student_session_id =" . $this->db->escape($student_session_id) . ") as b";
        $query = $this->db->query($query);
        return $query->row();
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function getStudentFeeDetails($invoiceId = null) {
        ini_set('max_execution_time', 0);
        // phpinfo();
        // die;
        $tax_payable = $this->setting_model->getFeeTax();
        $sql = "SELECT 
                            students.id as student_id,
                            students.parent_id,
                            students.guardian_id,
                            students.admission_no,
                            classes.class,
                            sections.section,
                            student_fees_master.id as student_fees_master_id,
                            fee_groups_feetype.id AS `fee_groups_feetype_id`,
                            fee_groups_feetype.amount,
                            
                            student_fees_master.student_session_id,
                            student_fees_master.fee_session_group_id,
                            
                            
                            fee_groups_feetype.due_date,
                            fee_groups_feetype.fee_groups_id,
                            fee_groups.name,
                            fee_groups_feetype.feetype_id,
                            feetype.code,
                            feetype.is_taxable,
                            feetype.type
                        FROM
                            student_session
                                INNER JOIN
                            students ON students.id = student_session.student_id
                                INNER JOIN
                            student_fees_master ON student_session.id = student_fees_master.student_session_id
                                INNER JOIN
                            classes ON classes.id = student_session.class_id
                                INNER JOIN
                            sections ON sections.id = student_session.section_id
                                INNER JOIN
                            fee_session_groups ON fee_session_groups.id = student_fees_master.fee_session_group_id
                                INNER JOIN
                            fee_groups_feetype ON fee_groups_feetype.fee_session_group_id = fee_session_groups.id
                                INNER JOIN
                            fee_groups ON fee_groups.id = fee_groups_feetype.fee_groups_id
                                INNER JOIN
                            feetype ON feetype.id = fee_groups_feetype.feetype_id
                            WHERE students.admission_no <>  ''
                                AND sections.id NOT IN (28 , 29, 1)
                                AND classes.id IN (8 , 9, 10, 11,  12,  13,  14,  15,  16, 17, 18,  19,  20,  21, 22)
                                AND fee_groups_feetype.due_date  < '".date('Y-m-d')."'
                                AND (student_fees_master.is_invoiced is null or student_fees_master.is_invoiced = 0)
                            ORDER BY fee_groups_feetype.due_date ASC";
                        $student_query = $this->db->query($sql);
                        $query = $student_query->result_array();
                        $studentdata = [];

        // Loop through the products array
        foreach ($query as $i => $StudentData) {
            $studentdata[$StudentData['due_date']][$StudentData['student_id']]['data'][] = [
                'student_fees_master_id' => $StudentData['student_fees_master_id'],
                'fee_groups_feetype_id' => $StudentData['fee_groups_feetype_id'],
                'amount' => $StudentData['amount'],
                'code' => $StudentData['code'],
                'name' => $StudentData['name'],
                'is_taxable' => $StudentData['is_taxable'],
                'type' => $StudentData['type'],
                'due_date' => $StudentData['due_date'],
            ];
            $studentdata[$StudentData['due_date']][$StudentData['student_id']]['parent_id'] = $StudentData['parent_id'];
            $studentdata[$StudentData['due_date']][$StudentData['student_id']]['admission_no'] = $StudentData['admission_no'];
        }
        $count = 1;
        foreach ($studentdata as $dueDate => $dueDateArray) {

            foreach ($dueDateArray as $student_id => $studentdataArray) {

                $parentId = $studentdataArray['parent_id'];
                $admission_no = $studentdataArray['admission_no'];
                $amount = 0.00;
                $date = $dueDate;
                $dataInvoices = [
                    "invoice_amount" => $amount,
                    "parent_id" => $parentId,
                    "admission_no" => $admission_no,
                    "invoice_date" => $date,
                    "status" => "active"
                ];
                $lastInsertId = $this->invoices_model->add_invoices($dataInvoices);
                $invoiceAmount = 0;
                foreach ($studentdataArray['data'] as $dateKeys => $dateArray) {
                    if($dateArray['type'] == "Tuition Fee"  || $dateArray['type'] == "Saudi Student") {
                        $discountPercent = $this->getStudentIndiviualDiscounts($dateArray['admission_no']);
                    } else {
                        $discountPercent = 0.00;
                    }
                    $discountAmoutnt = $discountPercent*$dateArray['amount'];
                    $amountPayable = $dateArray['amount']-$discountAmoutnt;
                   
                    if(trim($dateArray['is_taxable']) == 'YES'){
                        $taxAmount = ((($amountPayable) * $tax_payable['fee_tax']) /100);
                        $taxPayableDb = $tax_payable['fee_tax'];
                    }else{ 
                        $taxAmount = 0;
                        $taxPayableDb = 0;
                    }
                    $dataInvoiceData = [];
                    $dataInvoiceData["invoice_id"] = $lastInsertId;
                    $dataInvoiceData["student_fees_master_id"] = $dateArray['student_fees_master_id'];
                    $dataInvoiceData["fee_groups_feetype_id"] = $dateArray['fee_groups_feetype_id'];
                    $dataInvoiceData["amount"] = $dateArray['amount'];
                    $dataInvoiceData["discount"] = $discountAmoutnt;
                    $dataInvoiceData["discount_percent"] = $discountPercent;
                    $dataInvoiceData["tax"] = $taxAmount;
                    $dataInvoiceData["tax_percent"] = $taxPayableDb;
                    $dataInvoiceData["total_amount"] = ($amountPayable+$taxAmount);
                    $this->db->insert('invoices_details', $dataInvoiceData);
                    $invoiceAmount += $dataInvoiceData["total_amount"];
                            
                    $this->db->where('id', $dateArray['student_fees_master_id']);
                    $this->db->update('student_fees_master', ["is_invoiced"=>1, "invoice_number"=>$lastInsertId]);
//                	echo $this->db->last_query();
//di//	echo $this->db->last_query();
//die;e;
                
                }
                $dataInvoicesNumber = [
                    "invoice_number" => $lastInsertId,
                    "invoice_amount" => $invoiceAmount,
                ];
                $this->invoices_model->update_invoices_number($lastInsertId, $dataInvoicesNumber);
                
                
                echo $count . " -- " . $student_id . " recepit generated successfully <br>";
                if($count>5000)
                    die;
                $count++;
            }
            
        }
        die;
        // return $query;
    }
    
    
    public function getStudentIndiviualDiscounts($admission_no){
        $this->db->select("student_indiviual_discount.discount_ids");
        $this->db->where("student_admission_no", $admission_no);
        $query = $this->db->get("student_indiviual_discount");

        $totatDiscountAmount = 0.0;

        if(count($query->result_array()) > 0){
            $tempQuery =$query->result_array();
            $ids = $tempQuery[0]["discount_ids"];
            $idsArray = explode(',', $ids);

            $amountArray = array();

            foreach ($idsArray as $id){
                   $this->db->select("fees_discounts.amount");
                   $this->db->where("id", $id);
                   $query = $this->db->get("fees_discounts");
                   $res = $query->result_array();
                   if(count($res) > 0){
                      $totatDiscountAmount+=(int)$res[0]["amount"];  
                   }
                    
                   //$totatDiscountAmount+=(int)$query->result_array()[0]["amount"];
            }
        }
        return $totatDiscountAmount;



    }

    
    
    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function setStudentFeeDetails($fee_groups_feetype_id, $fee_master_id, $fee_session_group_id) {
        ini_set('max_execution_time', 0);
        // phpinfo();
        // die;
        $tax_payable = $this->setting_model->getFeeTax();
        $sql = "SELECT 
                            students.id as student_id,
                            students.parent_id,
                            students.guardian_id,
                            students.admission_no,
                            classes.class,
                            sections.section,
                            student_fees_master.id as student_fees_master_id,
                            fee_groups_feetype.id AS `fee_groups_feetype_id`,
                            fee_groups_feetype.amount,
                            
                            student_fees_master.student_session_id,
                            student_fees_master.fee_session_group_id,
                            
                            
                            fee_groups_feetype.due_date,
                            fee_groups_feetype.fee_groups_id,
                            fee_groups.name,
                            fee_groups_feetype.feetype_id,
                            feetype.code,
                            feetype.is_taxable,
                            feetype.type
                        FROM
                            student_session
                                INNER JOIN
                            students ON students.id = student_session.student_id
                                INNER JOIN
                            student_fees_master ON student_session.id = student_fees_master.student_session_id
                                INNER JOIN
                            classes ON classes.id = student_session.class_id
                                INNER JOIN
                            sections ON sections.id = student_session.section_id
                                INNER JOIN
                            fee_session_groups ON fee_session_groups.id = student_fees_master.fee_session_group_id
                                INNER JOIN
                            fee_groups_feetype ON fee_groups_feetype.fee_session_group_id = fee_session_groups.id
                                INNER JOIN
                            fee_groups ON fee_groups.id = fee_groups_feetype.fee_groups_id
                                INNER JOIN
                            feetype ON feetype.id = fee_groups_feetype.feetype_id
                            WHERE students.admission_no <>  ''
                                AND sections.id NOT IN (28 , 29, 1)
                                AND classes.id IN (8 , 9, 10, 11,  12,  13,  14,  15,  16, 17, 18,  19,  20,  21, 22)
                                AND (student_fees_master.is_invoiced is null or student_fees_master.is_invoiced = 0)
                            AND student_fees_master.fee_session_group_id  in ('".implode("','",$fee_session_group_id )."')
                            AND fee_groups_feetype.id in ('".implode("','",$fee_groups_feetype_id )."')
                            AND student_fees_master.id in ('".implode("','",$fee_master_id )."')
                            ORDER BY fee_groups_feetype.due_date ASC";
                        $student_query = $this->db->query($sql);
                        $query = $student_query->result_array();
                        $studentdata = [];

        // Loop through the products array
        foreach ($query as $i => $StudentData) {
            $studentdata[$StudentData['student_id']]['data'][] = [
                'student_fees_master_id' => $StudentData['student_fees_master_id'],
                'fee_groups_feetype_id' => $StudentData['fee_groups_feetype_id'],
                'amount' => $StudentData['amount'],
                'code' => $StudentData['code'],
                'name' => $StudentData['name'],
                'is_taxable' => $StudentData['is_taxable'],
                'type' => $StudentData['type'],
                'due_date' => $StudentData['due_date'],
            ];
            $studentdata[$StudentData['student_id']]['parent_id'] = $StudentData['parent_id'];
            $studentdata[$StudentData['student_id']]['admission_no'] = $StudentData['admission_no'];
        }
        $count = 1;

            foreach ($studentdata as $student_id => $studentdataArray) {

                $parentId = $studentdataArray['parent_id'];
                $admission_no = $studentdataArray['admission_no'];
                $amount = 0.00;
                $date = date("Y-m-d");
                $dataInvoices = [
                    "invoice_amount" => $amount,
                    "parent_id" => $parentId,
                    "admission_no" => $admission_no,
                    "invoice_date" => $date,
                    "status" => "active"
                ];
               $lastInsertId = $this->invoices_model->add_invoices($dataInvoices);
                $invoiceAmount = 0;
                foreach ($studentdataArray['data'] as $dateKeys => $dateArray) {
                    $totalAmountInvoicesdata = 0;
                    if($dateArray['type'] == "Tuition Fee"  || $dateArray['type'] == "Saudi Student") {
                        $discountPercent = $this->getStudentIndiviualDiscounts($admission_no);
                    } else {
                        $discountPercent = 0.00;
                    }
                    $discountAmoutnt = ($discountPercent/100)*$dateArray['amount'];
                    $amountPayable = $dateArray['amount']-$discountAmoutnt;
                   
                    if(trim($dateArray['is_taxable']) == 'YES'){
                        $taxAmount = ((($amountPayable) * $tax_payable['fee_tax']) /100);
                        $taxPayableDb = $tax_payable['fee_tax'];
                    }else{ 
                        $taxAmount = 0;
                        $taxPayableDb = 0;
                    }
                    $dataInvoiceData = [];
                    $dataInvoiceData["invoice_id"] = $lastInsertId;
                    $dataInvoiceData["student_fees_master_id"] = $dateArray['student_fees_master_id'];
                    $dataInvoiceData["fee_groups_feetype_id"] = $dateArray['fee_groups_feetype_id'];
                    $dataInvoiceData["amount"] = $dateArray['amount'];
                    $dataInvoiceData["discount"] = $discountAmoutnt;
                    $dataInvoiceData["discount_percent"] = $discountPercent;
                    $dataInvoiceData["tax"] = $taxAmount;
                    $dataInvoiceData["tax_percent"] = $taxPayableDb;
                    $totalAmountInvoicesdata = $amountPayable+$taxAmount;
                    $dataInvoiceData["total_amount"] = $totalAmountInvoicesdata;
                    $this->db->insert('invoices_details', $dataInvoiceData);
                    $invoiceAmount += $totalAmountInvoicesdata;
                            
                    $this->db->where('id', $dateArray['student_fees_master_id']);
                    $this->db->update('student_fees_master', ["is_invoiced"=>1, "invoice_number"=>$lastInsertId]);
                
                }
                $dataInvoicesNumber = [
                    "invoice_number" => $lastInsertId,
                    "invoice_amount" => $invoiceAmount,
                ];
                
                $this->invoices_model->update_invoices_number($lastInsertId, $dataInvoicesNumber);
                $count++;
            }
            
        
         return true;
    }
    
    
    
    public function getCreditNoteBookDetails($invoiceId = null, $guardian_id = null,$admission_no = null,$invoice_no = null, $getTotal = false,$date_from=null, $date_to = null) {
        $this->db->select(
                "credit_note.*,"
                . "creditnote_details.book_order_id,"
                . "creditnote_details.book_id,"
                #. "creditnote_details.amount,"
                . "creditnote_details.total_amount,"
                . "creditnote_details.date_created"
                //      . "parents.*,"
        )->from('credit_note');
        $this->db->join('creditnote_details', ' credit_note.id = creditnote_details.creditnote_id');
		
      
        if ($invoiceId != null) {
            $this->db->where('credit_note.id', $invoiceId);
        } else {
            $this->db->order_by('credit_note.id');
        }
        if ($guardian_id != ''){
                $this->db->join('students as students_parent', ' credit_note.parent_id = students_parent.parent_id');
                $this->db->where('students_parent.guardian_id', $guardian_id);
        }
        if ($admission_no != null)
            $this->db->where('credit_note.admission_no', $admission_no);
        
        if ($invoice_no != null) 
            $this->db->where('credit_note.invoice_number', $invoice_no);
        
        if ($date_from != null) 
            $this->db->where('credit_note.invoice_date>=', $date_from);
        
        if ($date_to != null) 
            $this->db->where('credit_note.invoice_date<=', $date_to);
        
		
        // $this->db->group_by('students.parent_id');
        $query = $this->db->get()->result_array();
       // echo $this->db->last_query();
       //  die;
        // Loop through the products array
        foreach ($query as $i => $invoices) {

            $this->db->where('parent_id', $invoices['parent_id']);
            $parent_query = $this->db->get('students')->row_array();
            $query[$i]['parents'] = $parent_query;

            $sql = 'select 
                            books.*, 
                        book_store_orders.* FROM books 
                        INNER JOIN 
                            book_store_orders ON books.id = book_store_orders.book_id
                        INNER JOIN 
                            students ON students.id = book_store_orders.std_id
                        WHERE
                            book_store_orders.order_id = ' . $invoices['book_order_id'];
            $querybooks = $this->db->query($sql);
			
            $query['books'] = $querybooks->result_array();
            
        }
        
        return $query;
    }

    
    
    
}
