
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-tattendance"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("tattendance/index")?>"><?=$this->lang->line('menu_tattendance')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_tattendance')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-4 col-md-offset-4">
                                    <div class="<?php echo form_error('date') ? 'form-group has-error' : 'form-group'; ?>" >
                                        <label for="date" class="control-label">
                                            <?=$this->lang->line('tattendance_date')?>
                                        </label>
                                        <input type="text" class="form-control" name="date" id="date" value="<?=set_value("date", $date)?>" >
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <input type="submit" class="btn btn-success" style="margin-top:20px" value="<?=$this->lang->line("add_attendance")?>" >
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <?php if(count($dateinfo)) { ?>
                    <div class="col-sm-4 col-sm-offset-4 box-layout-fame">
                        <?php 
                            echo '<h5><center>'.$this->lang->line('tattendance_details').'</center></h5>';
                            echo '<h5><center>'.$this->lang->line('tattendance_day').' : '. $dateinfo['day'].'</center></h5>';
                            echo '<h5><center>'.$this->lang->line('tattendance_date').' : '. $dateinfo['date'].'</center></h5>';
                        ?>
                    </div>
                <?php } ?>



                <?php if(count($teachers)) { ?>
                    <div id="hide-table">
                        <table class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                                <tr>
                                    <th class="col-sm-2"><?=$this->lang->line('slno')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('tattendance_photo')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('tattendance_name')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('tattendance_email')?></th>
                                    <th class="col-sm-2"><?=btn_attendance('', '', 'all_attendance', $this->lang->line('add_all_attendance')).$this->lang->line('action')?></th>
                                </tr>
                            </thead>
                            <tbody id="list">
                                <?php if(count($teachers)) {$i = 1; foreach($teachers as $teacher) { ?>
                                    <tr>
                                        <td data-title="<?=$this->lang->line('slno')?>">
                                            <?php echo $i; ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('tattendance_photo')?>">
                                            <?php $array = array(
                                                    "src" => base_url('uploads/images/'.$teacher->photo),
                                                    'width' => '35px',
                                                    'height' => '35px',
                                                    'class' => 'img-rounded'

                                                );
                                                echo img($array);
                                            ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('tattendance_name')?>">
                                            <?php echo $teacher->name; ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('tattendance_email')?>">
                                            <?php echo $teacher->email; ?>
                                        </td>
                                        <td data-title=<?=$this->lang->line('action')?>>
                                            <?php
                                                $aday = "a".abs($day);
                                                foreach ($tattendances as $tattendance) {
                                                    if($monthyear == $tattendance->monthyear && $tattendance->teacherID == $teacher->teacherID) {
                                                        $method = '';
                                                        if($tattendance->$aday == "P") {$method = "checked";}
                                                        echo  btn_attendance($tattendance->tattendanceID, $method, 'attendance btn btn-warning', $this->lang->line('add_title'));
                                                        break;
                                                    }

                                                }
                                            ?>
                                        </td>
                                    </tr>
                                <?php $i++; }} ?>

                            </tbody>
                        </table>
                    </div>

                    <script type="text/javascript">
                        $('.attendance').click(function() {
                            var id = $(this).attr("id");
                            var day = "<?=$day?>";

                            if(parseInt(id) && parseInt(day)) {
                                $.ajax({
                                    type: 'POST',
                                    url: "<?=base_url('tattendance/singl_add')?>",
                                    data: {"id" : id, "day" : day},
                                    dataType: "html",
                                    success: function(data) {
                                        toastr["success"](data)
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
                                    }
                                });
                            }
                        });

                        $('.all_attendance').click(function() {
                            var day = "<?=$day?>";
                            var monthyear = "<?=$monthyear?>"
                            var status = "";

                            if($(".all_attendance").prop('checked')) {
                                status = "checked";
                                $('.attendance').prop("checked", true);
                            } else {
                                status = "unchecked";
                                $('.attendance').prop("checked", false);
                            }

                            if(parseInt(day)) {
                                $.ajax({
                                    type: 'POST',
                                    url: "<?=base_url('tattendance/all_add')?>",
                                    data: {"day" : day, "monthyear" : monthyear , "status" : status },
                                    dataType: "html",
                                    success: function(data) {
                                        toastr["success"](data)
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
                                    }
                                });
                            }
                        });
                    </script>
                <?php } ?>


            </div> <!-- col-sm-12 -->
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->
<script type="text/javascript">
    $("#date").datepicker();
</script>
