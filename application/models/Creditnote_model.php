<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Creditnote_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->current_date = $this->setting_model->getDateYmd();
    }

    public function add_creditnote($data) {

        $this->db->insert("credit_note", $data);
        return $this->db->insert_id();
    }

    public function update_creditnote_number($id, $data) {
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
        $this->db->join('(select students.parent_id,students.guardian_id  from students group by parent_id) as students', ' credit_note.parent_id = students.parent_id');
        if ($guardian_id != '')
            $this->db->where('students.guardian_id', $guardian_id);
        if ($id != null) {
            $this->db->where('receipts.id', $id);
        } else {
            $this->db->order_by('credit_note.id');
        }
        // $this->db->group_by('students.parent_id');
        $query = $this->db->get();
        if ($id != null) {
            $creditnotelist = $query->row_array();
        } else {
            $creditnotelist = $query->result_array();
        }
        return $creditnotelist;
    }

    public function getCreditnoteList($id = null, $guardian_id = null,$receipt_no =null , $getTotal = false,$limit=10, $start=0) {

        $this->db->select("credit_note.*, students.parent_id,students.guardian_id")->from('credit_note');
        $this->db->join('(select students.parent_id,students.guardian_id  from students group by parent_id) as students', ' credit_note.parent_id = students.parent_id');
        if ($guardian_id != '')
            $this->db->where('students.guardian_id', $guardian_id);
        
        if ($receipt_no != '')
            $this->db->like('credit_note.creditnote_number', $receipt_no);
        
        if ($id != null) {
            $this->db->where('creditnote.id', $id);
        } 
            $this->db->order_by('creditnote.creditnote_date','desc');
        
        // $this->db->group_by('students.parent_id');
        if ($getTotal) {
            return  $this->db->count_all_results();
        } else {
             $this->db->limit($limit, $start);
            $query = $this->db->get();
            if ($id != null) {
                $creditnotelist = $query->row_array();
            } else {
                $creditnotelist = $query->result_array();
            }
        }



        return $creditnotelist;
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function getCreditnoteDetails($creditnoteId = null) {

        $this->db->select(
                "credit_note.*,"
                . "creditnote_details.student_fees_master_id,"
                . "creditnote_details.fee_groups_feetype_id,"
                . "creditnote_details.amount_detail,"
                . "creditnote_details.date_created,"
                //      . "parents.*,"
        )->from('credit_note');
        $this->db->join('creditnote_details', ' credit_note.id = creditnote_details.creditnote_id');
        //$this->db->join('(select students.parent_id,students.guardian_id  from students group by parent_id) as parents', ' receipts.parent_id = parents.parent_id');
        //$this->db->join('students', ' `receipts_details`.`student_fees_master_id` = `students`.`id`');
        if ($receiptId != null) {
            $this->db->where('creditnote.id', $creditnoteId);
        } else {
            $this->db->order_by('creditnote.id');
        }
        // $this->db->group_by('students.parent_id');
        $query = $this->db->get()->result_array();
        //echo $this->db->last_query();
        // die;
        // Loop through the products array
        foreach ($query as $i => $receipts) {

            $this->db->where('parent_id', $receipts['parent_id']);
            $parent_query = $this->db->get('students')->row_array();
            $query[$i]['parents'] = $parent_query;

            $sql = 'select 
                            students.*, classes.class,sections.section , fees_discounts.name AS discount_name,
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
                            student_fees_master.id = ' . $receipts['student_fees_master_id'];
            $student_query = $this->db->query($sql);
            $query[$i]['students'] = $student_query->result_array();
            $query[$i]['student_discount_fee'] = $this->feediscount_model->getStudentIndiviualDiscounts($query[$i]['students'][0]['admission_no']);

            $this->db->where('id', $receipts['fee_groups_feetype_id']);
            $fee_groups_feetype_query = $this->db->get('fee_groups_feetype')->result_array();

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
        return $query;
    }


    
    function  getPaymentId($masterId, $feePayemntid){

        
        $this->db->where('student_fees_master_id', $masterId);
        $this->db->where('fee_groups_feetype_id', $feePayemntid);
        $parent_query = $this->db->get('student_fees_deposite')->row_array();
        return  $parent_query['id'];
    }
}
