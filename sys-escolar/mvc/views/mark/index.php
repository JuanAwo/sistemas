
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-flask"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_mark')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <h5 class="page-header">
                    <?php if(permissionChecker('student_add')) { ?>
                        <a class="btn btn-success" href="<?php echo base_url('mark/add') ?>">
                            <i class="fa fa-plus"></i>
                            <?=$this->lang->line('add_title')?>
                        </a>
                    <?php } ?>
                    <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12 pull-right drop-marg">
                        <?php
                            $array = array("0" => $this->lang->line("mark_select_classes"));
                            if(count($classes)) {
                                foreach ($classes as $classa) {
                                    $array[$classa->classesID] = $classa->classes;
                                }
                            }
                            echo form_dropdown("classesID", $array, set_value("classesID", $set), "id='classesID' class='form-control select2'");
                        ?>

                    </div>
                </h5>

                <?php if(count($students) > 0 ) { ?>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#all" aria-expanded="true"><?=$this->lang->line("mark_all_students")?></a></li>
                            <?php foreach ($sections as $key => $section) {
                                echo '<li class=""><a data-toggle="tab" href="#'. $section->section.$section->sectionID.' " aria-expanded="false">'. $this->lang->line("mark_section")." ".$section->section. " ( ". $section->category." )".'</a></li>';
                            } ?>
                        </ul>

                        <div class="tab-content">
                            <div id="all" class="tab-pane active">
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
                                                        <?php echo btn_view('mark/view/'.$student->studentID."/".$set, $this->lang->line('view')); ?>
                                                    </td>
                                                    <?php } ?>
                                               </tr>
                                            <?php $i++; }} ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                            <?php foreach ($sections as $key => $section) { ?>
                                    <div id="<?=$section->section.$section->sectionID?>" class="tab-pane">
                                        <div id="hide-table">
                                            <table class="table table-striped table-bordered table-hover dataTable no-footer">
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
                                                    <?php if(count($allsection[$section->sectionID])) { $i = 1; foreach($allsection[$section->sectionID] as $student) { if($section->sectionID === $student->sectionID) { ?>
                                                        <tr>
                                                            <td data-title="<?=$this->lang->line('mark_roll')?>">
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
                                                                <?php echo btn_view('mark/view/'.$student->studentID."/".$set, $this->lang->line('view')); ?>
                                                            </td>
                                                            <?php } ?>
                                                       </tr>
                                                    <?php $i++; }}} ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                            <?php } ?>
                        </div>
                    </div> <!-- nav-tabs-custom -->
                <?php } else { ?>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#all" aria-expanded="true"><?=$this->lang->line("mark_all_students")?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="all" class="tab-pane active">
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
                                                        <?php echo btn_view('mark/view/'.$student->studentID."/".$set, $this->lang->line('view')); ?>
                                                    </td>
                                                    <?php } ?>
                                               </tr>
                                            <?php $i++; }} ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div> <!-- nav-tabs-custom -->
                <?php } ?>


            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.select2').select2();
    $('#classesID').change(function() {
        var classesID = $(this).val();
        if(classesID == 0) {
            $('#hide-table').hide();
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('mark/mark_list')?>",
                data: "id=" + classesID,
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });
</script>
