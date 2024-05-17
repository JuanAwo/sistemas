<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-puzzle-piece"></i> <?=$this->lang->line('panel_title')?></h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_mark')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <?php if(permissionChecker('mark_add')) { ?>
                    <h5 class="page-header">
                        <a href="<?php echo base_url('mark/add') ?>">
                            <i class="fa fa-plus"></i>
                            <?=$this->lang->line('add_title')?>
                        </a>
                    </h5>
                <?php } ?>
                <?php if(count($students) > 0 ) { ?>
                    <div id="hide-table">
                        <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                                <tr>
                                    <th class="col-sm-2"><?=$this->lang->line('slno')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('mark_photo')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('mark_name')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('mark_roll')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('mark_email')?></th>
                                    <?php if(permissionChecker('mark_view')) { ?>
                                    <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($students)) {$i = 1; foreach($students as $student) { ?>
                                    <tr>
                                        <td data-title="<?=$this->lang->line('slno')?>">
                                            <?php echo $i ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('mark_photo')?>">
                                            <?php $array = array(
                                                    "src" => base_url('uploads/images/'.$student->photo),
                                                    'width' => '35px',
                                                    'height' => '35px',
                                                    'class' => 'img-rounded'

                                                );
                                                echo img($array); 
                                            ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('mark_name')?>">
                                            <?php echo $student->name; ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('mark_roll')?>">
                                            <?php echo $student->roll; ?>
                                        </td>
                                        
                                        <td data-title="<?=$this->lang->line('mark_email')?>">
                                            <?php echo $student->email; ?>
                                        </td>
                                        <?php if(permissionChecker('mark_view')) { ?>
                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_view('mark/view/'.$student->studentID."/".$student->classesID, $this->lang->line('view')); ?>
                                        </td>
                                        <?php } ?>
                                   </tr>
                                <?php $i++; }} ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                    <div id="hide-table">
                        <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                                <tr>
                                    <th class="col-sm-2"><?=$this->lang->line('slno')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('mark_photo')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('mark_name')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('mark_roll')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('mark_email')?></th>
                                    <?php if(permissionChecker('mark_view')) { ?>
                                    <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($students)) {$i = 1; foreach($students as $student) { ?>
                                    <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                            <?php echo $i; ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('mark_photo')?>">
                                            <?php $array = array(
                                                    "src" => base_url('uploads/images/'.$student->photo),
                                                    'width' => '35px',
                                                    'height' => '35px',
                                                    'class' => 'img-rounded'

                                                );
                                                echo img($array); 
                                            ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('mark_name')?>">
                                            <?php echo $student->name; ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('mark_roll')?>">
                                            <?php echo $student->roll; ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('mark_email')?>">
                                            <?php echo $student->email; ?>
                                        </td>
                                        <?php if(permissionChecker('mark_view')) { ?>
                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_view('mark/view/'.$student->studentID."/".$student->classesID, $this->lang->line('view')); ?>
                                        </td>
                                        <?php } ?>
                                   </tr>
                                <?php $i++; }} ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?> 


            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#studentID').change(function() {
        var studentID = $(this).val();
        if(studentID == 0) {
            $('#hide-table').hide();
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('mark/student_list')?>",
                data: "id=" + studentID,
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });
</script>
