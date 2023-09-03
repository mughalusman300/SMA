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

                            <div class="col-sm-8">

                                <form role="form" action="<?php echo site_url('admin/member/issuedBooksByclass') ?>" method="post" class=""> 

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
                            <div class="col-md-4">
                                <div class="row">
                                    <form role="form" action="<?php echo site_url('admin/member/issuedBooksByclass') ?>" method="post" class="">
                                        <?php //echo $this->customlib->getCSRF(); ?>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('library_card_no'); ?></label>
                                                <input type="text" name="library_card_no" class="form-control"   placeholder="<?php echo $this->lang->line('search_by_library_card_no'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="submit" name="search" value="search_full" class="btn btn-primary pull-right btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
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
                                            <th><?php echo $this->lang->line('admission_no'); ?></th>
                                            <th><?php echo $this->lang->line('student_name'); ?></th>
                                            <th><?php echo $this->lang->line('class'); ?></th>
                                            <th><?php echo $this->lang->line('father_name'); ?></th>
                                            <th><?php echo $this->lang->line('date_of_birth'); ?></th>
                                            <th><?php echo $this->lang->line('gender'); ?></th>
                                            <th><?php echo $this->lang->line('mobile_no'); ?></th>
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
                                                    <td><?php echo $student['admission_no']; ?></td>

                                                   

                                                    <td>

                                                        <?php echo $student['firstname'] . " " . $student['lastname']; ?>



                                                    </td>

                                                    <td><?php echo $student['class'] . "(" . $student['section'] . ")" ?></td>

                                                    <td><?php echo $student['father_name']; ?></td>
                                                    <td><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['dob'])); ?></td>

                                                    <td><?php echo $student['gender']; ?></td>



                                                    <td><?php echo $student['mobileno']; ?></td>
                
                                            
                                                    <td class="text text-right">
                                                     <?php if(!empty($student['library_card_no'])){ ?>
                                                            <a href="<?php echo base_url(); ?>admin/member/issue/<?php echo  $member_id; ?>" target="_blank" class="btn btn-default btn-xs"  data-toggle="tooltip" title="Issue">
                                                            <i class="fa fa-sign-out"></i>
                                                        </a>
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

</script>



