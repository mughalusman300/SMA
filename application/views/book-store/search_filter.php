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
                                    <form role="form" action="<?php echo $url;?>" method="get" class="">
                                        <?php echo $this->customlib->getCSRF(); ?>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('search_by_order_id'); ?></label>
                                                <input type="text" name="search_order_id" value="<?php echo $search_order_id;?>" class="form-control" placeholder="<?php echo $this->lang->line('search_by_order_id'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('search_by_admission_no'); ?></label>
                                                <input type="text" name="search_admission_no" value="<?php echo $search_admission_no;?>" class="form-control" placeholder="<?php echo $this->lang->line('search_by_admission_no'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('search_by_parent_id'); ?></label>
                                                <input type="text" name="search_parent_id" value="<?php echo $search_parent_id;?>" class="form-control" placeholder="<?php echo $this->lang->line('search_by_parent_id'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('search_by_order_placed'); ?></label>
                                                <input type="text" name="search_order_placed" value="<?php echo $search_order_placed;?>" class="form-control" placeholder="<?php echo $this->lang->line('search_by_order_placed'); ?>">
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
                </div>
        </div>
