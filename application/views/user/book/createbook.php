<?php

$currency_symbol = $this->customlib->getSchoolCurrencyFormat();

?>

<div class="content-wrapper">

    <section class="content-header">

        <h1>

            <i class="fa fa-book"></i> <?php echo $this->lang->line('library_book'); ?> <small></small>        </h1>

    </section>

    <!-- Main content -->

    <section class="content">

        <div class="row">

            <div class="col-md-12">
				<div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="row">
                                    <form role="form" action="<?php echo site_url('user/book/index') ?>" method="get" class="">
                                        <?php echo $this->customlib->getCSRF(); ?>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('search_by_book_title'); ?></label>
                                                <input type="text" name="search_title" class="form-control" placeholder="<?php echo $this->lang->line('search_by_book_title'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('search_by_book_no'); ?></label>
                                                <input type="text" name="search_book_no" class="form-control" placeholder="<?php echo $this->lang->line('search_by_book_no'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('search_by_book_isbn'); ?></label>
                                                <input type="text" name="search_isbn" class="form-control" placeholder="<?php echo $this->lang->line('search_by_book_isbn'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('search_by_book_author'); ?></label>
                                                <input type="text" name="search_author" class="form-control" placeholder="<?php echo $this->lang->line('search_by_book_author'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('search_barcode'); ?></label>
                                                <input type="text" name="search_barcode" class="form-control" placeholder="<?php echo $this->lang->line('search_barcode'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('search_subject'); ?></label>
                                                <input type="text" name="search_subject" class="form-control" placeholder="<?php echo $this->lang->line('search_subject'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?php echo "Seach By Class"; ?></label>
                                                <input type="text" name="search_class" class="form-control" placeholder="<?php echo "Search By Class"; ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="submit" name="search" value="search_full" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="box box-primary">

                    <div class="box-body">

                        <div class="table-responsive mailbox-messages">

                            <div class="download_label"><?php echo $this->lang->line('library_book'); ?></div>

                            <table class="table table-striped table-bordered table-hover">

                                <thead>

                                    <tr>

                                        <th><?php echo $this->lang->line('book_title'); ?>

                                        <th><?php echo $this->lang->line('publisher'); ?>

                                        </th>

                                        <th><?php echo $this->lang->line('author'); ?>

                                        </th>

                                        <th><?php echo $this->lang->line('subject'); ?></th>

                                        <th><?php echo $this->lang->line('location'); ?></th>

                                        <th><?php echo $this->lang->line('isbn_no'); ?></th>
                                        <th><?php echo $this->lang->line('barcode'); ?></th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php if (empty($listbook)) {

                                        ?>

                                        <tr>

                                            <td colspan="12" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>

                                        </tr>

                                        <?php

                                    } else {

                                        $count = 1;

                                        foreach ($listbook as $book) {

                                            ?>

                                            <tr>

                                                <td class="mailbox-name">

                                                    <a href="#" data-toggle="popover" class="detail_popover"><?php echo $book['book_title'] ?></a>

                                                    <div class="fee_detail_popover" style="display: none">

                                                        <?php

                                                        if ($book['description'] == "") {

                                                            ?>

                                                            <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>

                                                            <?php

                                                        } else {

                                                            ?>

                                                            <p class="text text-info"><?php echo $book['description']; ?></p>

                                                            <?php

                                                        }

                                                        ?>

                                                    </div>

                                                </td>

                                                <td class="mailbox-name"> <?php echo $book['publish'] ?></td>

                                                <td class="mailbox-name"> <?php echo $book['author'] ?></td>

                                                <td class="mailbox-name"> <?php echo $book['subject'] ?></td>

                                                <td class="mailbox-name"> <?php echo $book['location'] ?></td>

                                                <td class="mailbox-name"> <?php echo $book['isbn_no'] ?></td>
                                                <td class="mailbox-name"> <?php echo $book['other'] ?></td>
                                                
                                            </tr>

                                            <?php

                                        }

                                        $count++;

                                    }

                                    ?>

                                </tbody>

                            </table>
<?php echo $links;?>
                        </div>

                    </div>

                    <div class="box-footer">

                        <div class="mailbox-controls">  

                            <div class="pull-right">

                            </div>

                        </div>

                    </div>

                </div>

            </div>  

        </div>

        <div class="row">           

            <div class="col-md-12">

            </div>

        </div> 

    </section>

</div>



<script>

    $(document).ready(function () {

        $('.detail_popover').popover({

            placement: 'right',

            trigger: 'hover',

            container: 'body',

            html: true,

            content: function () {

                return $(this).closest('td').find('.fee_detail_popover').html();

            }

        });

    });

</script>