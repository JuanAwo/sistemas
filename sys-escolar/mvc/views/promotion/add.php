<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-promotion"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("promotion/index")?>"><?=$this->lang->line('menu_promotion')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_promotion')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="col-sm-4 col-sm-offset-4 box-layout-fame">
                    <h5>
                        <center>
                            <?=$this->lang->line('promotion_details_of_promotion')?>
                        </center>
                    </h5>
                    <h5>
                        <center>
                            <?=$this->lang->line('promotion_currentClass')?> : <?=$currentClass->classes?>
                        </center>
                    </h5>
                    <h5>
                        <center>
                            <?=$this->lang->line('promotion_promotionClass')?> : <?=$promotionClass->classes?>
                        </center>
                    </h5>
                    <h5>
                        <center>
                            <?=$this->lang->line('promotion_currentSchoolyear')?> : <?=$currentSchoolYear->schoolyear?>
                        </center>
                    </h5>
                    <h5>
                        <center>
                            <?=$this->lang->line('promotion_promotionSchoolyear')?> : <?=$promotionSchoolYear->schoolyear?>
                        </center>
                    </h5>
                </div>

                <div id="hide-table">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('promotion_photo')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('promotion_name')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('promotion_roll')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('promotion_section')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('promotion_mark_summary')?></th>
                                <?php if($promotionType != 'normal') {?>
                                    <th class="col-sm-2"><?=$this->lang->line('promotion_total')?></th>
                                    <th class="col-sm-1"><?=$this->lang->line('promotion_result')?></th>
                                <?php } ?>
                                <th class="col-sm-1"><?php if(in_array(2, $student_result)) { echo '<input type="checkbox" class="promotion btn btn-warning" disabled> '.$this->lang->line('action');  } else { echo btn_attendance('', '', 'all_promotion', $this->lang->line('add_all_promotion')).$this->lang->line('action'); }?></th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php if(count($studentStatus)) {$i = 1; foreach($studentStatus as $studentSummary) { if($promotionType != 'normal') { $student = $studentSummary['info']; } else { $student = $studentSummary;}?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('promotion_photo')?>">
                                        <?php $array = array(
                                                "src" => base_url('uploads/images/'.$student->photo),
                                                'width' => '35px',
                                                'height' => '35px',
                                                'class' => 'img-rounded'

                                            );
                                            echo img($array);
                                        ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('promotion_name')?>">
                                        <?php echo $student->name; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('promotion_roll')?>">
                                        <?php echo $student->roll; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('promotion_section')?>">
                                        <?php echo $sections[$student->sectionID]->section; ?>
                                    </td>
                                    <?php if($promotionType != 'normal') {?>
                                        <td data-title="Mark Summary Lang">
                                            <a class="text-blue" href="<?=base_url("promotion/summary/$student->studentID/$student->classesID/$student->schoolyearID")?>" target="_blank">Ver resumen</a>
                                        </td>
                                        <td data-title="Total Lang">
                                            <?php echo $studentSummary['total']; ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('promotion_result')?>">
                                            <?php
                                                if($student_result[$student->studentID] == 1) {
                                                    echo "<button class='btn btn-success btn-xs'>" . $this->lang->line('promotion_pass'). "</button>";
                                                } elseif($student_result[$student->studentID] == 0) {
                                                    echo "<button class='btn btn-danger btn-xs'>" . $this->lang->line('promotion_fail'). "</button>";
                                                } else {
                                                    echo "<button class='btn btn-info btn-xs'>" . $this->lang->line('promotion_modarate'). "</button>";
                                                }
                                            ?>
                                        </td>
                                    <?php } else {?>
                                        <td data-title="Mark Summary Lang">
                                            <a class="text-blue" href="<?=base_url("mark/view/$student->studentID/$student->classesID")?>" target="_blank"><?=$this->lang->line('promotion_view_summary')?></a>
                                        </td>
                                    <?php } ?>

                                    <td data-title="<?=$this->lang->line('action')?>">
                                        <?php
                                            echo  btn_promotion($student->studentID, 'promotion btn btn-warning', $this->lang->line('add_title'));
                                        ?>
                                    </td>
                               </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>

                <div class="col-sm-5 col-sm-offset-7 list-group" style="margin-top: 18px;">
                    <input type="button" class="col-sm-12 btn btn-success" id="save" value="<?=$this->lang->line('add_promotion')?><?=$promotionClass->classes?>" >
                    <input style="margin-top: 18px;" type="button" class="col-sm-12 btn btn-success" id="enroll" value="<?=$this->lang->line('promotion_enroll')?><?=$currentClass->classes?>" >
                </div>


                <div id="dialog"></div>
            </div> <!-- col-sm-12 -->



        </div>


            <div class="panel-group" id="accordion"> 
                <div class="panel panel-default"> 
                    <div class="panel-heading"> 
                        <h4 class="panel-title"> 
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"> 
                        <span class="fa fa-send"></span> Más información</a> 
                        </h4> 
                    </div> 
                    <div id="collapseOne" class="panel-collapse collapse"> 
                        <div class="panel-body">
                            <div class="col-md-12 text-justify">
                                <div class="alert alert-dismissible alert-info">
                                    <p>
                                        Seleccione a un estudiante para ascender a la siguiente clase, para una nueva inscripción el año académico será modificado pero la clase no cambiara, para una promoción la clase y año académico serán cambiados, la sección se genera automáticamente en función del promedio más alto.
                                    </p>
                                </div>
                            </div>
                        </div> 
                    </div> 
                </div> 
            </div>  


            <!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<script type="text/javascript">
    $('#classesID').change(function() {
        var classesID = $(this).val();
        var schoolyearID = $('#schoolyear').val();
        if(classesID == 0) {
            $('#hide-table').hide();
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('promotion/promotion_list')?>",
                data: {"id" : classesID, "year" : schoolyearID},
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });

    $('.all_promotion').click(function() {

        if($(".all_promotion").prop('checked')) {
            status = "checked";
            $('.promotion').prop("checked", true);
        } else {
            status = "unchecked";
            $('.promotion').prop("checked", false);
        }
    });

    $('#save').click(function() {
        if ($('.promotion').filter(':checked').length == 0) {
            toastr["error"]("<?=$this->lang->line('promotion_select_student')?>")
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "500",
                "hideDuration": "500",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
        } else {
            var result = [];
            var status = "";
            var previousClassesID = <?=$set?>;
            var previousYearID = <?=$schoolyearID?>;
            $('.promotion').each(function(index) {
                status = (this.checked ? $(this).attr('id') : 0);
                result.push(status);
            });

            $redirect = (window.location.href);
            $.ajax({
                type: 'POST',
                url: "<?=base_url('promotion/promotion_to_next_class')?>",
                data: "studentIDs=" + result,
                dataType: "html",
                success: function(data) {
                   window.location.replace($redirect);
                }
            });

        }

    });

    $('#enroll').click(function() {
        if ($('.promotion').filter(':checked').length == 0) {
            toastr["error"]("<?=$this->lang->line('promotion_select_student')?>")
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "500",
                "hideDuration": "500",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
        } else {
            var result = [];
            var status = "";
            var previousClassesID = <?=$set?>;
            var previousYearID = <?=$schoolyearID?>;
            $('.promotion').each(function(index) {
                status = (this.checked ? $(this).attr('id') : 0);
                result.push(status);
            });

            $redirect = (window.location.href);
            $.ajax({
                type: 'POST',
                url: "<?=base_url('promotion/promotion_to_next_class')?>",
                data: "studentIDs=" + result + "&enroll=true",
                dataType: "html",
                success: function(data) {
                   window.location.replace($redirect);
                }
            });

        }

    });

</script>
