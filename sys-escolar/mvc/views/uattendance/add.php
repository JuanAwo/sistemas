
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-user-secret"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("uattendance/index")?>"><?=$this->lang->line('menu_uattendance')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_uattendance')?></li>
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
                                            <?=$this->lang->line('uattendance_date')?>
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
                            echo '<h5><center>'.$this->lang->line('uattendance_details').'</center></h5>';
                            echo '<h5><center>'.$this->lang->line('uattendance_day').' : '. $dateinfo['day'].'</center></h5>';
                            echo '<h5><center>'.$this->lang->line('uattendance_date').' : '. $dateinfo['date'].'</center></h5>';
                        ?>
                    </div>
                <?php } ?>
            </div>
            <div class="col-sm-12">
                <?php if(count($users)) { ?>

                <div id="hide-table">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('uattendance_photo')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('uattendance_name')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('uattendance_email')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('uattendance_role')?></th>
                                <th class="col-sm-2"><?=btn_attendance('', '', 'all_attendance', $this->lang->line('add_all_attendance')).$this->lang->line('uattendance_presentandabsent')?></th>
                                <th class="col-sm-2"><?=btn_attendance('', '', 'all_attendance_late', $this->lang->line('add_all_attendance_late')).$this->lang->line('uattendance_late')?></th>
                            </tr>
                        </thead>
                        <tbody id="list">
                            <?php if(count($users)) {$i = 1; foreach($users as $user) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('uattendance_photo')?>">
                                        <?php $array = array(
                                                "src" => base_url('uploads/images/'.$user->photo),
                                                'width' => '35px',
                                                'height' => '35px',
                                                'class' => 'img-rounded'

                                            );
                                            echo img($array);
                                        ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('uattendance_name')?>">
                                        <?php echo $user->name; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('uattendance_email')?>">
                                        <?php echo $user->email; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('uattendance_role')?>">
                                        <?php echo $user->usertype; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('uattendance_presentandabsent')?>">
                                        <?php
                                            $aday = "a".abs($day);

                                            foreach ($uattendances as $uattendance) {
                                                if ($monthyear == $uattendance->monthyear && $uattendance->userID == $user->userID) {
                                                    $method = '';
                                                    if($uattendance->$aday == "P") {$method = "checked";}
                                                    echo  btn_attendance($uattendance->uattendanceID, $method, "attendance btn btn-warning attendance-$uattendance->uattendanceID", $this->lang->line('add_title'));
                                                    break;
                                                }
                                            }
                                        ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('uattendance_late')?>">
                                        <?php
                                            $aday = "a".abs($day);
                                            foreach ($uattendances as $uattendance) {
                                                if ($monthyear == $uattendance->monthyear && $uattendance->userID == $user->userID) {
                                                    $method = '';
                                                    if($uattendance->$aday == "L") {$method = "checked";}
                                                    echo  btn_attendance($uattendance->uattendanceID, $method, "attendance_late btn btn-warning attendance_late-$uattendance->uattendanceID", $this->lang->line('add_title'));
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
                    $('.all_attendance').click(function() {
                        var day = "<?=$day?>";
                        var monthyear = "<?=$monthyear?>";
                        var status = "";

                        if($(".all_attendance").prop('checked')) {
                            status = "checked";
                            $('.attendance').prop("checked", true);
                            $('.attendance_late').prop("checked", false);
                        } else {
                            status = "unchecked";
                            $('.attendance').prop("checked", false);
                            $('.attendance_late').prop("checked", false);
                        }

                        if(parseInt(day) && monthyear) {
                            $.ajax({
                                type: 'POST',
                                url: "<?=base_url('uattendance/all_add')?>",
                                data: {"day" : day, "monthyear" : monthyear , "method" : 'presentandabsent' , "status" : status },
                                dataType: "html",
                                success: function(data) {
                                    $('.all_attendance_late').prop('checked', false);
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

                    $('.all_attendance_late').click(function() {
                        var day = "<?=$day?>";
                        var monthyear = "<?=$monthyear?>";
                        var status = "";

                        if($(".all_attendance_late").prop('checked')) {
                            status = "checked";
                            $('.attendance_late').prop("checked", true);
                            $('.attendance').prop("checked", false);
                        } else {
                            status = "unchecked";
                            $('.attendance_late').prop("checked", false);
                            $('.attendance').prop("checked", false);
                        }


                        if(parseInt(day) && monthyear) {
                            $.ajax({
                                type: 'POST',
                                url: "<?=base_url('uattendance/all_add')?>",
                                data: {"day" : day , "monthyear" : monthyear , "method" : 'late' , "status" : status },
                                dataType: "html",
                                success: function(data) {
                                    $('.all_attendance').prop('checked', false);
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

                    $('.attendance').click(function() {
                        var id = $(this).attr("id");
                        var day = "<?=$day?>";
                        var status = '';
                        var method = '';

                        if($(this).prop('checked')) {
                            status = "checked";
                            method = 'presentandabsent';
                            $('.attendance_late-'+id).prop("checked", false);
                        } else {
                            status = "unchecked";
                            method = 'presentandabsent';
                        }

                        if(parseInt(id) && parseInt(day)) {
                            $.ajax({
                                type: 'POST',
                                url: "<?=base_url('uattendance/singl_add')?>",
                                data: {"id" : id, "day" : day, 'method' : method, 'status' : status},
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

                    $('.attendance_late').click(function() {
                        var id = $(this).attr("id");
                        var day = "<?=$day?>";
                        var status = '';
                        var method = '';

                        if($(this).prop('checked')) {
                            status = "checked";
                            method = 'late';
                            $('.attendance-'+id).prop("checked", false);
                        } else {
                            status = "unchecked";
                            method = 'late';
                        }

                        if(parseInt(id) && parseInt(day)) {
                            $.ajax({
                                type: 'POST',
                                url: "<?=base_url('uattendance/singl_add')?>",
                                data: {"id" : id, "day" : day, 'method' : method, 'status' : status},
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


