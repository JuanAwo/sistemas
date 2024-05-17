
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-member"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('panel_title')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <h5 class="page-header">
                    <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12 pull-right drop-marg">
                        <?php
                            $array = array("0" => $this->lang->line("tmember_select_class"));
                            foreach ($classes as $classa) {
                                $array[$classa->classesID] = $classa->classes;
                            }
                            echo form_dropdown("classesID", $array, set_value("classesID", $set), "id='classesID' class='form-control select2'");
                        ?>
                    </div>
                </h5>

                <?php if(count($students) > 0 ) { ?>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#all" aria-expanded="true"><?=$this->lang->line("tmember_all_students")?></a></li>
                            <?php foreach ($sections as $key => $section) {
                                echo '<li class=""><a data-toggle="tab" href="#tab'.$section->classesID.$section->sectionID .'" aria-expanded="false">'. $this->lang->line("tmember_section")." ".$section->section. " ( ". $section->category." )".'</a></li>';
                            } ?>
                        </ul>

                        <div class="tab-content">
                            <div id="all" class="tab-pane active">
                                <div id="hide-table">
                                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                                        <thead>
                                            <tr>
                                                <th class="col-sm-2"><?=$this->lang->line('slno')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('tmember_photo')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('tmember_name')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('tmember_roll')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('tmember_email')?></th>
                                                <?php if(permissionChecker('tmember_add') || permissionChecker('tmember_edit') || permissionChecker('tmember_delete') || permissionChecker('tmember_view')) { ?>
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
                                                    <td data-title="<?=$this->lang->line('tmember_photo')?>">
                                                        <?php $array = array(
                                                                "src" => base_url('uploads/images/'.$student->photo),
                                                                'width' => '35px',
                                                                'height' => '35px',
                                                                'class' => 'img-rounded'

                                                            );
                                                            echo img($array);
                                                        ?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('tmember_name')?>">
                                                        <?php echo $student->name; ?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('tmember_roll')?>">
                                                        <?php echo $student->roll; ?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('tmember_email')?>">
                                                        <?php echo $student->email; ?>
                                                    </td>
                                        
                                                    <?php if(permissionChecker('tmember_add') || permissionChecker('tmember_edit') || permissionChecker('tmember_delete') || permissionChecker('tmember_view')) { ?>
                                                    <td data-title="<?=$this->lang->line('action')?>">
                                                        <?php
                                                            if($student->transport == 0) {
                                                                echo btn_add('tmember/add/'.$student->studentID."/".$set, $this->lang->line('tmember'));
                                                            } else {
                                                                echo btn_view('tmember/view/'.$student->studentID."/".$set, $this->lang->line('view')). " ";
                                                                echo btn_edit('tmember/edit/'.$student->studentID."/".$set, $this->lang->line('edit')). " ";
                                                                echo btn_delete('tmember/delete/'.$student->studentID."/".$set, $this->lang->line('delete'));
                                                            }
                                                        ?>
                                                    </td>
                                                    <?php } ?>
                                                </tr>
                                            <?php $i++; }} ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                            <?php foreach ($sections as $key => $section) { ?>
                                    <div id="tab<?=$section->classesID.$section->sectionID?>" class="tab-pane">
                                        <div id="hide-table">
                                            <table class="table table-striped table-bordered table-hover dataTable no-footer">
                                                <thead>
                                                    <tr>
                                                        <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                                        <th class="col-sm-1"><?=$this->lang->line('tmember_photo')?></th>
                                                        <th class="col-sm-2"><?=$this->lang->line('tmember_name')?></th>
                                                        <th class="col-sm-2"><?=$this->lang->line('tmember_roll')?></th>
                                                        <th class="col-sm-2"><?=$this->lang->line('tmember_email')?></th>
                                                        <?php if(permissionChecker('tmember_add') || permissionChecker('tmember_edit') || permissionChecker('tmember_delete') || permissionChecker('tmember_view')) { ?>
                                                            <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                                        <?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if(count($allsection[$section->sectionID])) { $i = 1; foreach($allsection[$section->sectionID] as $student) { if($section->sectionID === $student->sectionID) { ?>
                                                        <tr>
                                                            <td data-title="<?=$this->lang->line('slno')?>">
                                                                <?php echo $i; ?>
                                                            </td>
                                                            <td data-title="<?=$this->lang->line('tmember_photo')?>">
                                                                <?php $array = array(
                                                                        "src" => base_url('uploads/images/'.$student->photo),
                                                                        'width' => '35px',
                                                                        'height' => '35px',
                                                                        'class' => 'img-rounded'

                                                                    );
                                                                    echo img($array);
                                                                ?>
                                                            </td>
                                                            <td data-title="<?=$this->lang->line('tmember_name')?>">
                                                                <?php echo $student->name; ?>
                                                            </td>

                                                            <td data-title="<?=$this->lang->line('tmember_roll')?>">
                                                                <?php echo $student->roll; ?>
                                                            </td>

                                                            <td data-title="<?=$this->lang->line('tmember_email')?>">
                                                                <?php echo $student->email; ?>
                                                            </td>
                                                
                                                            <?php if(permissionChecker('tmember_add') || permissionChecker('tmember_edit') || permissionChecker('tmember_delete') || permissionChecker('tmember_view')) { ?>
                                                            <td data-title="<?=$this->lang->line('action')?>">
                                                                <?php
                                                                    if($student->transport == 0) {
                                                                        echo btn_add('tmember/add/'.$student->studentID."/".$set, $this->lang->line('tmember'));
                                                                    } else {
                                                                        echo btn_view('tmember/view/'.$student->studentID."/".$set, $this->lang->line('view')). " ";
                                                                        echo btn_edit('tmember/edit/'.$student->studentID."/".$set, $this->lang->line('edit')). " ";
                                                                        echo btn_delete('tmember/delete/'.$student->studentID."/".$set, $this->lang->line('delete'));
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
                            <?php } ?>
                        </div>
                    </div> <!-- nav-tabs-custom -->
                <?php } else { ?>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#all" aria-expanded="true"><?=$this->lang->line("tmember_all_students")?></a></li>
                        </ul>


                        <div class="tab-content">
                            <div id="all" class="tab-pane active">
                                <div id="hide-table">
                                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                                        <thead>
                                            <tr>
                                                <th class="col-sm-2"><?=$this->lang->line('slno')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('tmember_photo')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('tmember_name')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('tmember_roll')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('tmember_email')?></th>
                                                <?php if(permissionChecker('tmember_add') || permissionChecker('tmember_edit') || permissionChecker('tmember_delete') || permissionChecker('tmember_view')) { ?>
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
                                                    <td data-title="<?=$this->lang->line('tmember_photo')?>">
                                                        <?php $array = array(
                                                                "src" => base_url('uploads/images/'.$student->photo),
                                                                'width' => '35px',
                                                                'height' => '35px',
                                                                'class' => 'img-rounded'

                                                            );
                                                            echo img($array);
                                                        ?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('tmember_name')?>">
                                                        <?php echo $student->name; ?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('tmember_roll')?>">
                                                        <?php echo $student->roll; ?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('tmember_email')?>">
                                                        <?php echo $student->email; ?>
                                                    </td>
                                        
                                                    <?php if(permissionChecker('tmember_add') || permissionChecker('tmember_edit') || permissionChecker('tmember_delete') || permissionChecker('tmember_view')) { ?>
                                                    <td data-title="<?=$this->lang->line('action')?>">
                                                        <?php
                                                            if($student->transport == 0) {
                                                                echo btn_add('tmember/add/'.$student->studentID."/".$set, $this->lang->line('tmember'));
                                                            } else {
                                                                echo btn_view('tmember/view/'.$student->studentID."/".$set, $this->lang->line('view')). " ";
                                                                echo btn_edit('tmember/edit/'.$student->studentID."/".$set, $this->lang->line('edit')). " ";
                                                                echo btn_delete('tmember/delete/'.$student->studentID."/".$set, $this->lang->line('delete'));
                                                            }
                                                        ?>
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
                url: "<?=base_url('tmember/student_list')?>",
                data: "id=" + classesID,
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });
</script>
