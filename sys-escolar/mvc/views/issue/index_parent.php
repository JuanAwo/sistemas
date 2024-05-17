
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-issue"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_issue')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                
                <h5 class="page-header">
                    <?php if(permissionChecker('issue_add')) { ?>
                        <a class="btn btn-success" href="<?php echo base_url('issue/add') ?>">
                            <i class="fa fa-plus"></i>
                            <?=$this->lang->line('add_title')?>
                        </a>
                    <?php } ?>

                    <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12 pull-right drop-marg">
                        <?php
                            $array = array("0" => $this->lang->line("issue_select_student"));
                            if($students) {
                                foreach ($students as $student) {
                                    $array[$student->studentID] = $student->name;
                                }
                            }
                            echo form_dropdown("studentID", $array, set_value("studentID", $set), "id='studentID' class='form-control select2'");
                        ?>
                    </div>

                </h5>

                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-lg-2"><?=$this->lang->line('slno')?></th>
                                <th class="col-lg-2"><?=$this->lang->line('issue_book')?></th>
                                <th class="col-lg-2"><?=$this->lang->line('issue_serial_no')?></th>
                                <th class="col-lg-2"><?=$this->lang->line('issue_due_date')?></th>
                                <th class="col-lg-2"><?=$this->lang->line('issue_status')?></th>
                                <?php if(permissionChecker('issue_view') || permissionChecker('issue_edit')) { ?>
                                    <th class="col-lg-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>

                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                if(count($issues)) {$i = 1; foreach($issues as $issue) { 
                                    if($issue->return_date == "" || empty($issue->return_date)) {
                            ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('issue_book')?>">
                                        <?php echo $issue->book; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('issue_serial_no')?>">
                                        <?php echo $issue->serial_no; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('issue_due_date')?>">
                                        <?php echo date("d M Y", strtotime($issue->due_date)); ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('issue_status')?>">
                                        <?php
                                            $date = date("Y-m-d");
                                            if(strtotime($date) > strtotime($issue->due_date)) {
                                                echo '<button class="btn btn-xs btn-danger">';
                                                echo $this->lang->line('issue_overdue');
                                                echo '</button>';
                                            }
                                        ?>
                                    </td>
                                    <?php if(permissionChecker('issue_view') || permissionChecker('issue_edit')) { ?>
                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php
                                                echo btn_view('issue/view/'.$issue->issueID, $this->lang->line('view'));
                                                echo " ". btn_edit('issue/edit/'.$issue->issueID."/".$issue->lID, $this->lang->line('edit'));
                                                
                                            ?>
                                            <?php
                                                if(permissionChecker('issue_add') && permissionChecker('issue_edit')) {
                                                    if($issue->return_date == "" || empty($issue->return_date)) {
                                                        echo " ". btn_return('issue/returnbook/'.$issue->issueID."/".$issue->lID, $this->lang->line('return'));
                                                    }
                                                    $date = date("Y-m-d");
                                                    if(strtotime($date) > strtotime($issue->due_date)) {
                                                        echo '<a href="#invoice" class="btn btn-xs btn-danger mrg"  data-toggle="modal" rel="tooltip">';
                                                        echo '<i class="fa icon-invoice" data-toggle="tooltip" data-placement="top" data-original-title="'.$this->lang->line('issue_add_fine').'" ></i>';
                                                        echo '</a>';
                                                    }
                                                }
                                            ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php $i++; }}} ?>
                        </tbody>
                    </table>
                </div>

            </div> <!-- col-sm-12 -->
            
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->


<!-- For invoice -->
<form class="form-horizontal" role="form" action="<?=base_url('student/send_mail');?>" method="post">
    <div class="modal fade" id="invoice">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><?=$this->lang->line('issue_fine')?></h4>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control" id="libraryID" name="libraryID" value="<?=$libraryID?>" style="display:none" >
                <div class="form-group">
                    <label for="amount" class="col-sm-2 control-label">
                        <?=$this->lang->line("issue_amount")?>
                    </label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="amount" name="amount" value="<?=set_value('amount')?>" >
                    </div>
                    <span class="col-sm-4 control-label" id="amount_error">
                    </span>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="add_invoice" class="btn btn-success" value="<?=$this->lang->line("issue_add_fine")?>" />
            </div>
        </div>
      </div>
    </div>
</form>
<!-- invoice end here -->

<script type="text/javascript">
    $('.select2').select2();
    $('#studentID').change(function() {
        var studentID = $(this).val();
        if(studentID == 0) {
            $('#hide-table').hide();
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('issue/student_list')?>",
                data: "id=" + studentID,
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });

    $('#add_invoice').click(function() {
        var amount = $('#amount').val();
        var libraryID = $('#libraryID').val();
        if(amount != '') {
            if(amount.length < 21) {
                var valid = !/^\s*$/.test(amount) && !isNaN(amount);
                if(valid) {
                    $.ajax({
                        type: 'POST',
                        url: "<?=base_url('issue/add_invoice')?>",
                        data: {'libraryID' : libraryID, 'amount' : amount},
                        dataType: "html",
                        success: function(data) {
                            window.location.href = data;
                        }
                    });

                    $('#amount_error').html("");
                } else {
                    $('#amount_error').html("<?=$this->lang->line('issue_amount_number_message');?>").css('color', 'red');
                }
            } else {
                $('#amount_error').html("<?=$this->lang->line('issue_amount_max_message');?>").css('color', 'red');
            }
        } else {
            $('#amount_error').html("<?=$this->lang->line('issue_amount_required_message');?>").css('color', 'red');
        }
    });
</script>