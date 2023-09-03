<?php



if (!defined('BASEPATH'))

    exit('No direct script access allowed');



class Bookissue_model extends CI_Model {



    public function __construct() {

        parent::__construct();

        $this->current_session = $this->setting_model->getCurrentSession();

    }



    /**

     * This funtion takes id as a parameter and will fetch the record.

     * If id is not provided, then it will fetch all the records form the table.

     * @param int $id

     * @return mixed

     */

    public function get($id = null) {

        $this->db->select()->from('book_issues');

        if ($id != null) {

            $this->db->where('book_issues.id', $id);

        } else {

            $this->db->order_by('book_issues.id');

        }

        $query = $this->db->get();

        if ($id != null) {

            return $query->row_array();

        } else {

            return $query->result_array();

        }

    }



    /**

     * This function will delete the record based on the id

     * @param $id

     */

    public function remove($id) {

        $this->db->where('id', $id);

        $this->db->delete('book_issues');

    }



    public function add($data) {



        $this->db->insert('book_issues', $data);

        return $this->db->insert_id();

    }



    /**

     * This funtion takes id as a parameter and will fetch the record.

     * If id is not provided, then it will fetch all the records form the table.

     * @param int $id

     * @return mixed

     */

    public function getMemberBooks($member_id) {

        $this->db->select('book_issues.id,book_issues.return_date,book_issues.issue_date,book_issues.is_returned,book_issues.created_at,books.book_title,books.book_no,other,books.author')->from('book_issues');

        $this->db->join('books', 'books.id = book_issues.book_id', 'left');

        if ($member_id != null) {

            $this->db->where('book_issues.member_id', $member_id);
            $this->db->where('book_issues.is_active', 'no');
            //$this->db->order_by("book_issues.created_at", "desc");
            $this->db->order_by("book_issues.is_returned", "asc");

        }

        $query = $this->db->get();

        return $query->result_array();

    }


    public function update($data) {

        if (isset($data['id'])) {

            $this->db->where('id', $data['id']);

            $this->db->update('book_issues', $data);

        }

    }



    function book_issuedByMemberID($member_id) {

        $this->db->select('book_issues.return_date,books.book_no,book_issues.issue_date,book_issues.is_returned,books.book_title,books.author')

                ->from('book_issues')

                ->join('libarary_members', 'libarary_members.id = book_issues.member_id', 'left')

                ->join('books', 'books.id = book_issues.book_id', 'left')

                ->where('libarary_members.id', $member_id)

                ->order_by('book_issues.is_returned', 'asc');

        $result = $this->db->get();

        return $result->result_array();

    }
    public function get_book_data_by_bookissue_id($id){
        $query = "Select *
        FROM book_issues
        INNER JOIN books on book_issues.book_id = books.id
        WHERE book_issues.id=?";
         $res = $this->db->query($query,array($id));
         return $res->result_array();


    }
    public function get_bookissue_by_id($id){
        
        $this->db->select('book_issues.*,libarary_members.member_type')
        ->from('book_issues')
        ->join('libarary_members', 'libarary_members.id = book_issues.member_id', 'inner');
        $this->db->where('book_issues.id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function add_fine($id,$days,$balance,$total_fine){
        
        $data = array(
        'book_issues_id' => $id,
        'days'           => $days,
        'balance'        => $balance,
        'total_fine'     => $total_fine
         );
        
        $this->db->insert('book_issues_fine', $data);
        return $this->db->insert_id();
    }
    public function getMemberFine($member_id) {

        $this->db->select('book_issues_fine.id As fid, book_issues_fine.days,book_issues_fine.balance,book_issues_fine.amount_paid,book_issues_fine.status,book_issues_fine.discount,book_issues_fine.remarks,book_issues_fine.total_fine,books.book_no,books.other,books.book_title,book_issues.issue_date,book_issues.return_date,book_issues.created_at')
        ->from('book_issues_fine')

       ->join('book_issues', 'book_issues.id = book_issues_fine.book_issues_id', 'inner')
       ->join('books', 'books.id = book_issues.book_id', 'inner');

        if ($member_id != null) {

            $this->db->where('book_issues.member_id', $member_id);
            $this->db->order_by("book_issues_fine.status", "asc");
            $this->db->order_by("book_issues_fine.id", "desc");
        }

        $query = $this->db->get();

        return $query->result_array();

    }
    public function getFineById($fid) {

        $this->db->select('book_issues_fine.id As fid, book_issues_fine.days,book_issues_fine.balance,book_issues_fine.total_fine,book_issues_fine.amount_paid,book_issues_fine.status,book_issues_fine.discount,book_issues_fine.remarks,books.book_no,books.other,books.book_title,books.is_active,book_issues.issue_date,book_issues.return_date,book_issues.created_at As book_issues_created_at')
        ->from('book_issues_fine')
       ->join('book_issues', 'book_issues.id = book_issues_fine.book_issues_id', 'inner')
       ->join('books', 'books.id = book_issues.book_id', 'inner');

        if ($fid != null) {

            $this->db->where('book_issues_fine.id', $fid);
        }

        $query = $this->db->get();

        return $query->result_array();

    }
   public function update_fine($data) {

    if (isset($data['id'])) {

            $this->db->where('id', $data['id']);

            $this->db->update('book_issues_fine', $data);
             return $this->db->affected_rows();

        }
 
    }
    public function getborrowedbook($member_id) {

        $this->db->select('book_issues.id')->from('book_issues');


        if ($member_id != null) {

            $this->db->where('book_issues.member_id', $member_id);
            $this->db->where('book_issues.is_returned', 0);
        }

        $query = $this->db->get();
       if ($query->num_rows() <= 1 ){
        return $query->result_array();
       }

    }
    
    public function getborrowedbookteacher($member_id) {

        $this->db->select('book_issues.id')->from('book_issues');


        if ($member_id != null) {

            $this->db->where('book_issues.member_id', $member_id);
            $this->db->where('book_issues.is_returned', 0);
        }

        $query = $this->db->get();
        return $query->result_array();

    }
    /*function getBooksIssueReportByStudents($id= null){
        
        $data=array();
        $start_date= $this->input->get('start_date');
        $end_date=$this->input->get('end_date');

        $this->db->select('book_issues.id,book_issues.return_date,book_issues.issue_date,book_issues.is_returned,book_issues.member_id,book_issues.created_at,books.other,books.author,libarary_members.member_type,libarary_members.member_type,students.firstname,students.lastname,classes.class as class_name,
        sections.section')->from('book_issues')
        ->join('books', 'books.id = book_issues.book_id', 'inner')
        ->join('libarary_members', 'libarary_members.id = book_issues.member_id', 'inner')
        ->join('students', 'libarary_members.member_id = students.id', 'inner')
        ->join('student_session', 'student_session.student_id = students.id', 'inner')
        ->join('classes', 'student_session.class_id = classes.id', 'inner')
        ->join('sections', 'sections.id = student_session.section_id', 'inner');
        $this->db->where('book_issues.issue_date >=', $start_date." 00:00:00");
        $this->db->where('book_issues.issue_date <=', $end_date." 23:59:59");
        $this->db->order_by("book_issues.is_returned", "asc");
        $this->db->order_by("book_issues.id", "acs");
        // $this->db->where('book_store_orders.created_at BETWEEN DATE_SUB(NOW(), INTERVAL 3 YEAR) AND NOW()');
        //$this->db->limit(50,1);
        if ($id == null) {
        $query = $this->db->get();
        return $query->result_array();
        }
        else{
          $this->db->where('book_issues.is_returned',$id);
          $query = $this->db->get();
          return $query->result_array();
        }
    }*/
      /*public function getBooks($id= null) {

        $this->db->select('book_issues.id,book_issues.return_date,book_issues.issue_date,book_issues.is_returned,book_issues.member_id,book_issues.created_at,books.book_title,books.book_no,books.author')->from('book_issues');

        $this->db->join('books', 'books.id = book_issues.book_id', 'inner');
        $this->db->order_by("book_issues.is_returned", "asc");
        $this->db->order_by("book_issues.id", "desc");
         if ($id == null) {
        $query = $this->db->get();
        return $query->result_array();
        }
        else{
          $this->db->where('book_issues.is_returned',$id);
          $query = $this->db->get();
          return $query->result_array();
        }

    }*/

    
    function getBooksIssueReport($status = null, $start_date = null, $end_date = null, $getTotalCount = false, $limit = 500, $start = 0) {
        $whereClause = [];
        $query = "";
        if ($status != null)
            $whereClause[] = "   book_issues.is_returned='$status'";
        if ($start_date != null && $end_date != null)
            $whereClause[] = "  book_issues.issue_date >='$start_date'  AND book_issues.issue_date <='$end_date'";

        $this->db->select("book_issues.id AS `book_issue_id`,
					book_issues.return_date,
					book_issues.issue_date,
					book_issues.is_returned,
					book_issues.member_id,
					book_issues.created_at,
					books.other,
					books.book_title,
					books.author,
					libarary_members.member_type,
					IF(libarary_members.member_type = 'student',(select firstname from students where libarary_members.member_id = students.id) ,'') as firstname,
					IF(libarary_members.member_type = 'student',(select lastname from students where libarary_members.member_id = students.id) ,'') as lastname,
					IF(libarary_members.member_type = 'teacher',(select name from staff where libarary_members.member_id = staff.id) ,'') as teacher_name,
					IF(libarary_members.member_type = 'teacher',(select department from staff where libarary_members.member_id = staff.id) ,
					(SELECT classes.class FROM students  
						INNER JOIN
					student_session ON student_session.student_id = students.id
						INNER JOIN
					classes ON student_session.class_id = classes.id
						INNER JOIN
					sections ON sections.id = student_session.section_id WHERE libarary_members.member_id = students.id) ) AS `class_name`,
					IF(libarary_members.member_type = 'teacher',(select designation from staff where libarary_members.member_id = staff.id) ,(SELECT sections.section FROM students  
						INNER JOIN
					student_session ON student_session.student_id = students.id
						INNER JOIN
					classes ON student_session.class_id = classes.id
						INNER JOIN
					sections ON sections.id = student_session.section_id WHERE libarary_members.member_id = students.id )) AS `section`")
                ->from('book_issues')
                ;
        $this->db->join('books', 'books.id = book_issues.book_id',"INNER");
        $this->db->join('libarary_members', 'libarary_members.id = book_issues.member_id',"INNER");
        if (count($whereClause) > 0) {
                $query =   implode(' AND ', $whereClause);
                $this->db->where($query);
            }
        $this->db->order_by('issue_date',"ASC");
        $this->db->order_by('book_issue_id',"ASC");
        if ($getTotalCount) {
            return $this->db->count_all_results();
        } else {
            
            	$this->db->limit($limit, $start);
                $query = $this->db->get();
                return $query->result_array();
        }

    }
    
    public function getBooks($id= null) {

         $query = ""
                . "SELECT 
    book_issues.id as `book_issue_id`,
    book_issues.return_date,
    book_issues.issue_date,
    book_issues.is_returned,
    book_issues.member_id,
    book_issues.created_at,
    books.other,
    books.book_title,
    books.author,
    libarary_members.member_type,
    students.firstname,
    students.lastname,
    null as `teacher_name`,
    classes.class as `class_name`,
    sections.section as `section`
FROM
    `book_issues`
INNER JOIN books ON books.id  = book_issues.book_id
INNER JOIN libarary_members ON libarary_members.id = book_issues.member_id
INNER JOIN students ON libarary_members.member_id = students.id
INNER JOIN student_session ON student_session.student_id = students.id
INNER JOIN classes ON student_session.class_id = classes.id
INNER JOIN sections ON sections.id = student_session.section_id
WHERE
    libarary_members.member_type = 'student'
UNION SELECT 
    book_issues.id as `book_issue_id`,
    book_issues.return_date ,
    book_issues.issue_date,
    book_issues.is_returned,
    book_issues.member_id,
    book_issues.created_at,
    books.other,
    books.book_title,
    books.author,
    libarary_members.member_type,
    null,
    null,
    staff.name,
    staff.department,
    staff.designation
FROM
    `book_issues`
    INNER JOIN books ON books.id  = book_issues.book_id
    INNER JOIN libarary_members ON libarary_members.id = book_issues.member_id
    INNER JOIN staff ON libarary_members.member_id = staff.id
WHERE
    libarary_members.member_type = 'teacher'
        ORDER BY `is_returned` ASC, `book_issue_id` ASC";
        $query2 = ""
                . "SELECT 
    book_issues.id as `book_issue_id`,
    book_issues.return_date,
    book_issues.issue_date,
    book_issues.is_returned,
    book_issues.member_id,
    book_issues.created_at,
    books.other,
    books.book_title,
    books.author,
    libarary_members.member_type,
    students.firstname,
    students.lastname,
    null as `teacher_name`,
    classes.class as `class_name`,
    sections.section as `section`
FROM
    `book_issues`
INNER JOIN books ON books.id  = book_issues.book_id
INNER JOIN libarary_members ON libarary_members.id = book_issues.member_id
INNER JOIN students ON libarary_members.member_id = students.id
INNER JOIN student_session ON student_session.student_id = students.id
INNER JOIN classes ON student_session.class_id = classes.id
INNER JOIN sections ON sections.id = student_session.section_id
WHERE
    libarary_members.member_type = 'student'
        and book_issues.is_returned='$id'   
UNION SELECT 
    book_issues.id as `book_issue_id`,
    book_issues.return_date,
    book_issues.issue_date,
    book_issues.is_returned,
    book_issues.member_id,
    book_issues.created_at,
    books.other,
    books.book_title,
    books.author,
    libarary_members.member_type,
    null,
    null,
    staff.name,
    staff.department,
    staff.designation
FROM
    `book_issues`
    INNER JOIN books ON books.id  = book_issues.book_id
    INNER JOIN libarary_members ON libarary_members.id = book_issues.member_id
    INNER JOIN staff ON libarary_members.member_id = staff.id
WHERE
    libarary_members.member_type = 'teacher'
        and book_issues.is_returned='$id'
        ORDER BY `issue_date` ASC, `book_issue_id` ASC";
        if ($id == null) {
         
        $query = $this->db->query($query );
        return $query->result_array();
        }
        else{
          $query = $this->db->query($query2);
          return $query->result_array();
        }

    }
    function getFineReport($status= null){
        
        $data=array();
        $start_date= $this->input->get('start_date');
        $end_date=$this->input->get('end_date');

        $this->db->select('book_issues_fine.id As fid, book_issues_fine.days,book_issues_fine.balance,book_issues_fine.total_fine,book_issues_fine.amount_paid,book_issues_fine.status,book_issues_fine.discount,book_issues_fine.remarks,book_issues_fine.created_at AS paid_date,books.other,books.book_title,book_issues.issue_date,book_issues.return_date,book_issues.member_id,book_issues.created_at As book_issues_created_at,libarary_members.member_type,students.firstname,students.lastname,classes.class as class_name,
        sections.section')
        ->from('book_issues_fine')
        ->join('book_issues', 'book_issues.id = book_issues_fine.book_issues_id', 'inner')
        ->join('books', 'books.id = book_issues.book_id', 'inner')
        ->join('libarary_members', 'libarary_members.id = book_issues.member_id', 'inner')
        ->join('students', 'libarary_members.member_id = students.id', 'inner')
        ->join('student_session', 'student_session.student_id = students.id', 'inner')
        ->join('classes', 'student_session.class_id = classes.id', 'inner')
        ->join('sections', 'sections.id = student_session.section_id', 'inner'); 
        $this->db->where('book_issues.created_at >=', $start_date." 00:00:00");
        $this->db->where('book_issues.created_at <=', $end_date." 23:59:59");
        $this->db->order_by("book_issues_fine.status", "acs");
        $this->db->order_by("book_issues.created_at", "desc");
        // $this->db->where('book_store_orders.created_at BETWEEN DATE_SUB(NOW(), INTERVAL 3 YEAR) AND NOW()');
        //$this->db->limit(50,1);
        if ($status == null) {
        $query = $this->db->get();
        return $query->result_array();
        }
        else{
          $this->db->where('book_issues_fine.status',$status);
          $query = $this->db->get();
          return $query->result_array();
        }
    }
function getAllFine($status= null){
        


        $this->db->select('book_issues_fine.id As fid, book_issues_fine.days,book_issues_fine.balance,book_issues_fine.total_fine,book_issues_fine.amount_paid,book_issues_fine.status,book_issues_fine.discount,book_issues_fine.remarks,book_issues_fine.created_at AS paid_date,books.other,books.book_title,book_issues.issue_date,book_issues.return_date,book_issues.member_id,book_issues.created_at As book_issues_created_at,libarary_members.member_type,students.firstname,students.lastname,classes.class as class_name,
        sections.section')
        ->from('book_issues_fine')
        ->join('book_issues', 'book_issues.id = book_issues_fine.book_issues_id', 'inner')
        ->join('books', 'books.id = book_issues.book_id', 'inner')
        ->join('libarary_members', 'libarary_members.id = book_issues.member_id', 'inner')
        ->join('students', 'libarary_members.member_id = students.id', 'inner')
        ->join('student_session', 'student_session.student_id = students.id', 'inner')
        ->join('classes', 'student_session.class_id = classes.id', 'inner')
        ->join('sections', 'sections.id = student_session.section_id', 'inner');
        $this->db->order_by("book_issues_fine.status", "acs");
        $this->db->order_by("book_issues.created_at", "desc");

        if($status ==  null){
        $query = $this->db->get();
        return $query->result_array();
        }
        else{
       $this->db->where('book_issues_fine.status', $status);
        $query = $this->db->get();
        return $query->result_array();
          }
    }


}

