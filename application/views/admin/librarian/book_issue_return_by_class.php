<?php

$currency_symbol = $this->customlib->getSchoolCurrencyFormat();

?>

<div class="content-wrapper" style="min-height: 946px;">    

    <section class="content-header">

        <h1>

            <i class="fa fa-book"></i> <?php echo $this->lang->line('library'); ?></h1>

    </section>  

    <section class="content">

        <div class="row">  

            <div class="col-md-12"> 

                <div class="box box-primary">

                    <div class="box-header with-border">

                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>

                    </div>

                    <div class="box-body">

                        <?php if ($this->session->flashdata('msg')) { ?> <div >  <?php echo $this->session->flashdata('msg') ?> </div> <?php } ?>

                        <div class="row">

                            <div class="">

                                <form role="form" action="<?php echo site_url('admin/member/ReturnBooksByclass') ?>" method="post" class=""> 

                                    <?php echo $this->customlib->getCSRF(); ?>

                                    <div class="col-sm-6">

                                        <div class="form-group">

                                            <label><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>

                                            <select autofocus="" id="class_id" name="class_id" class="form-control" >

                                                <option value=""><?php echo $this->lang->line('select'); ?></option>

                                                <?php

                                                foreach ($classlist as $class) {

                                                    ?>

                                                    <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) echo "selected=selected" ?>><?php echo $class['class'] ?></option>

                                                    <?php

                                                    $count++;

                                                }

                                                ?>

                                            </select>

                                            <span class="text-danger"><?php echo form_error('class_id'); ?></span>

                                        </div>

                                    </div>

                                    <div class="col-sm-6">

                                        <div class="form-group">

                                            <label><?php echo $this->lang->line('section'); ?></label>

                                            <select  id="section_id" name="section_id" class="form-control" >

                                                <option value=""><?php echo $this->lang->line('select'); ?></option>

                                            </select>

                                            <span class="text-danger"><?php echo form_error('section_id'); ?></span>

                                        </div>

                                    </div>





                                    <div class="col-sm-12">

                                        <div class="form-group">

                                            <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>

                                        </div>

                                    </div>

                                </form>

                            </div>



                        </div>

                    </div>

                </div>

                <?php

                if (isset($resultlist)) {

                    ?>

                    <div class="box box-primary" id="tachelist">

                        <div class="box-header ptbnull">

                            <h3 class="box-title titlefix"><?php echo $this->lang->line('students'); ?></h3>

                            <div class="box-tools pull-right">



                            </div>

                        </div>

                        <div class="box-body">

                            <div class="mailbox-controls">

                            </div>

                            <div class="table-responsive mailbox-messages">

                                <div class="download_label"><?php echo $this->lang->line('students'); ?></div>

                                <table class="table table-striped table-bordered table-hover example">

                                    <thead>

                                        <tr>
                                            <th><?php echo $this->lang->line('member_id'); ?></th>
                                            <th><?php echo $this->lang->line('library_card_no'); ?></th>
                                            <th><?php echo $this->lang->line('student_name'); ?></th>
                                            <th><?php echo $this->lang->line('class'); ?></th>
                                            <th><?php echo $this->lang->line('father_name'); ?></th>
                                            <th><?php echo $this->lang->line('barcode'); ?></th>
                                            <th><?php echo $this->lang->line('book_title'); ?></th>
                                            <th><?php echo $this->lang->line('book_no'); ?></th>
                                            <th><?php echo $this->lang->line('issue_date'); ?></th>
                                            <th><?php echo $this->lang->line('due_date'); ?></th>
                                            <th class="text text-right"><?php echo $this->lang->line('action'); ?></th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php

                                        if (empty($resultlist)) {

                                            ?>



                                            <?php

                                        } else {

                                            $count = 1;



                                            foreach ($resultlist as $student) {

                                                $clsactive = "a";

                                                $member_id = "";

                                                $library_card_no = "";

                                                if ($student['libarary_member_id'] != 0) {

                                                    $clsactive = "success";

                                                    $member_id = $student['libarary_member_id'];

                                                    $library_card_no = $student['library_card_no'];

                                                }

                                                ?>

                                                <tr class="<?php echo $clsactive; ?>">

                                                    <td><?php echo $member_id; ?></td>

                                                    <td><?php echo $library_card_no; ?></td>

                                                    <td>

                                                        <?php echo $student['firstname'] . " " . $student['lastname']; ?>



                                                    </td>

                                                    <td><?php echo $student['class'] . "(" . $student['section'] . ")" ?></td>

                                                    <td><?php echo $student['father_name']; ?></td>
                                                    <td><?php echo $student['other']; ?></td> 
                                                      <td><?php echo $student['book_title']; ?></td>  
                                                   
                                                    <td>
                                                      <?php echo $student['book_no']; ?>  
                                                    </td>
                                                    <td>
                                                       <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['issue_date'])) ?> 
                                                    </td>
                                                    <td>
                                                       <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['return_date'])) ?> 
                                                    </td>
                
                                            
                                                    <td class="text text-right">

                                                            <?php if ($student['is_returned'] == 0) {

                                                            ?>

                                                            <!--<a href="<?php echo base_url(); ?>admin/member/bookreturnbyclass/<?php echo $student['book_issue_id'] . "/" . $member_id; ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="Return" onclick="return confirm('Are you sure you want to return this book?')">
                                                            <i class="fa fa-mail-reply"></i>
                                                        </a>-->
                                                        <button type="button" class="btn btn-default btn-xs return-book" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait.." data-toggle="tooltip" data-id="<?php echo $student['book_issue_id'] ?>" title="<?php echo $this->lang->line('return'); ?>"><i class="fa fa-mail-reply"></i></button>

                                                            <?php } ?>
                                                    </td>

                                                </tr>

                                                <?php

                                                $count++;

                                            }

                                        }

                                        ?>

                                    </tbody>

                                </table>

                            </div>

                        </div>



                    </div>

                    <?php

                }

                ?>

            </div>

        </div>

    </section>

</div>





<div class="modal fade" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                <h3 class="modal-title" id="lineModalLabel"><?php echo $this->lang->line('add_member'); ?></h3>

            </div>

            <div class="modal-body">



                <input type="hidden" name="click_member_id" value="0" id="click_member_id">

                <!-- content goes here -->

                <form action="<?php echo site_url('admin/member/add') ?>" id="add_member" method="post">

                    <input type="hidden" name="member_id" value="0" id="member_id">

                    <div class="form-group">

                        <label for="exampleInputEmail1"><?php echo $this->lang->line('library_card_no'); ?></label>

                        <input type="name" class="form-control" name="library_card_no" id="library_card_no" >

                        <span class="text-danger" id="library_card_no_error"></span>

                    </div>

                    <button type="submit" class="btn btn-default btn-sm add-member" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait..">Add</button>

                </form>



            </div>

        </div>

    </div>

</div>



<script type="text/javascript">

    $(document).ready(function () {

        $("#squarespaceModal").modal({

            show: false,

            backdrop: 'static'

        });



    });



    var base_url = '<?php echo base_url() ?>';

    function getSectionByClass(class_id, section_id) {

        if (class_id != "" && section_id != "") {

            $('#section_id').html("");

            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';

            $.ajax({

                type: "GET",

                url: base_url + "sections/getByClass",

                data: {'class_id': class_id},

                dataType: "json",

                success: function (data) {

                    $.each(data, function (i, obj)

                    {

                        var sel = "";

                        if (section_id == obj.section_id) {

                            sel = "selected";

                        }

                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";

                    });

                    $('#section_id').append(div_data);

                }

            });

        }

    }

    $(document).ready(function () {

        var class_id = $('#class_id').val();

        var section_id = '<?php echo set_value('section_id') ?>';

        getSectionByClass(class_id, section_id);

        $(document).on('change', '#class_id', function (e) {

            $('#section_id').html("");

            var class_id = $(this).val();

            var base_url = '<?php echo base_url() ?>';

            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';

            $.ajax({

                type: "GET",

                url: base_url + "sections/getByClass",

                data: {'class_id': class_id},

                dataType: "json",

                success: function (data) {

                    $.each(data, function (i, obj)

                    {

                        div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";

                    });

                    $('#section_id').append(div_data);

                }

            });

        });

    });





    $(".return-book").click(function () {

        if (confirm('Are you sure you want to return this book?')) {

            var id = $(this).data('id');

            var $this = $('.return-book');

            $this.button('loading');

            $.ajax({

                type: "POST",

                url: '<?php echo site_url('admin/member/bookreturnbyclass') ?>',

                data: {'id': id}, // serializes the form's elements.

                dataType: 'JSON',

                success: function (response)

                {



                    if (response.status == "success") {

                        successMsg(response.message);

                        $this.button('reset');

                        window.setTimeout('location.reload()', 3000);

                    }
                    else{
                        alert('Error.....');
                    }

                }

            });

        }



    });







    $(".add-student").click(function () {

        var student = $(this).data('stdid');

        $('#click_member_id').val(student);

        $('#member_id').val(student);

        $('#squarespaceModal').modal('show');

    });



    $("#add_member").submit(function (e) {

        var student = $('#click_member_id').val();

        var $this = $('.add-member');

        $this.button('loading');

        $.ajax({

            type: "POST",

            url: $(this).attr('action'),

            data: $("#add_member").serialize(), // serializes the form's elements.

            dataType: 'JSON',

            success: function (response)

            {



                if (response.status == "success") {

                    $('#squarespaceModal').modal('hide');

                    $('#add_member')[0].reset();

                    successMsg(response.message);

                    $this.button('reset');

                    $('*[data-stdid="' + student + '"]').closest('tr').find('td:first').text(response.inserted_id);

                    $('*[data-stdid="' + student + '"]').closest('tr').find('td:nth-child(2)').text(response.library_card_no);

                    $('*[data-stdid="' + student + '"]').closest("tr").addClass("success");

                    $('*[data-stdid="' + student + '"]').closest("td").empty();

                } else if (response.status == "fail") {

                    $.each(response.error, function (index, value) {

                        var errorDiv = '#' + index + '_error';

                        $(errorDiv).empty().append(value);

                    });

                    $this.button('reset');

                }

            }

        });



        e.preventDefault(); // avoid to execute the actual submit of the form.

    });

</script>



