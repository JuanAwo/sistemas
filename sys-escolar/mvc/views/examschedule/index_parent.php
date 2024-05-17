<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-puzzle-piece"></i> <?=$this->lang->line('panel_title')?></h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_examschedule')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <h5 class="page-header">
                    <?php if(permissionChecker('examschedule_add')) { ?>
                        <a class="btn btn-success" href="<?php echo base_url('examschedule/add') ?>">
                            <i class="fa fa-plus"></i> 
                            <?=$this->lang->line('add_title')?>
                        </a>
                    <?php } ?>

                    <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12 pull-right drop-marg">
                        <?php
                            $array = array("0" => $this->lang->line("examschedule_select_student"));
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
                                <th><?=$this->lang->line('slno')?></th>
                                <th><?=$this->lang->line('examschedule_name')?></th>
                                <th>
                                    <?php 
                                        if($siteinfos->school_type == 'classbase') {
                                            echo $this->lang->line('examschedule_classes');
                                        } else {
                                            echo $this->lang->line("examschedule_department");
                                        } 
                                    ?>
                                </th>
                                <th><?=$this->lang->line('examschedule_section')?></th>
                                <th><?=$this->lang->line('examschedule_subject')?></th>
                                <th><?=$this->lang->line('examschedule_date')?></th>
                                <th><?=$this->lang->line('examschedule_time')?></th>
                                <th><?=$this->lang->line('examschedule_room')?></th>
                                <?php if(permissionChecker('examschedule_edit') || permissionChecker('examschedule_delete')) { ?>
                                    <th><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($examschedules)) {$i = 1; foreach($examschedules as $examschedule) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('examschedule_name')?>">
                                        <?php echo $examschedule->exam; ?>
                                    </td>
                                    <td data-title="
                                        <?php 
                                            if($siteinfos->school_type == 'classbase') {
                                                echo $this->lang->line('examschedule_classes');
                                            } else {
                                                echo $this->lang->line("examschedule_department");
                                            } 
                                        ?> 
                                    ">
                                        <?php echo $examschedule->classes; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('examschedule_section')?>">
                                        <?php echo $examschedule->section; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('examschedule_subject')?>">
                                        <?php echo $examschedule->subject; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('examschedule_time')?>">
                                        <?php echo date("d M Y", strtotime($examschedule->edate)); ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('examschedule_time')?>">
                                        <?php echo $examschedule->examfrom, " - ", $examschedule->examto ; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('examschedule_room')?>">
                                        <?php echo $examschedule->room; ?>
                                    </td>
                                    <?php if(permissionChecker('examschedule_edit') || permissionChecker('examschedule_delete')) { ?>
                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_edit('examschedule/edit/'.$examschedule->examscheduleID."/".$set, $this->lang->line('edit')) ?>
                                            <?php echo btn_delete('examschedule/delete/'.$examschedule->examscheduleID."/".$set, $this->lang->line('delete')) ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.select2').select2();
    $('#studentID').change(function() {
        var studentID = $(this).val();
        if(studentID == 0) {
            $('#hide-table').hide();
            $('.nav-tabs-custom').hide();
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('examschedule/student_list')?>",
                data: "id=" + studentID,
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });
</script>
