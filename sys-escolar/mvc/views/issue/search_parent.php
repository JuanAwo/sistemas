

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
                            echo form_dropdown("studentID", $array, set_value("studentID"), "id='studentID' class='form-control select2'");
                        ?>
                    </div>

                </h5>

                <div class="col-sm-12">
                    <div class="row">
                        <div id="hide-table">
                            <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                                <thead>
                                    <tr>
                                        <th class="col-lg-2"><?=$this->lang->line('slno')?></th>
                                        <th class="col-lg-2"><?=$this->lang->line('issue_book')?></th>
                                        <th class="col-lg-2"><?=$this->lang->line('issue_serial_no')?></th>
                                        <th class="col-lg-2"><?=$this->lang->line('issue_due_date')?></th>
                                        <th class="col-lg-2"><?=$this->lang->line('issue_status')?></th>
                                        <?php  if(permissionChecker('issue_view') || permissionChecker('issue_edit')) { ?>
                                            <th class="col-lg-2"><?=$this->lang->line('action')?></th>
                                        <?php } ?>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(count($issues)) {$i = 1; foreach($issues as $issue) { 
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
                                                            echo '<i class="fa icon-invoice" data-toggle="tooltip" data-placement="top" data-original-title="'.$this->lang->line('issue_add_invoice').'" ></i>';
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
                    </div>
                </div>
            
            </div> <!-- col-sm-12 -->
            
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->
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
</script>