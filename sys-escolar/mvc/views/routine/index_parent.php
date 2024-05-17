

<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-routine"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_routine')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <h5 class="page-header">
                    <?php if(permissionChecker('routine_add')) { ?>
                        <a class="btn btn-success" href="<?php echo base_url('routine/add') ?>">
                            <i class="fa fa-plus"></i> 
                            <?=$this->lang->line('add_title')?>
                        </a>
                    <?php } ?>
                    <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12 pull-right drop-marg">
                        <?php
                            $array = array("0" => $this->lang->line("routine_select_student"));
                            if($students) {
                                foreach ($students as $student) {
                                    $array[$student->studentID] = $student->name;
                                }
                            }
                            echo form_dropdown("studentID", $array, set_value("studentID", $set), "id='studentID' class='form-control select2'");

                        ?>
                    </div>
                </h5>

   
                <?php if(count($routines) > 0 ) { ?>
                    <div id="hide-table-2">
                        <table id="table" class="table table-bordered ">
                            <tbody>
                                <?php
                                    $us_days = array('LUNES' => $this->lang->line('monday'), 'MARTES' => $this->lang->line('tuesday'), 'MIERCOLES' => $this->lang->line('wednesday'), 'JUEVES' => $this->lang->line('thursday'), 'VIERNES' => $this->lang->line('friday'), 'SABADO' => $this->lang->line('saturday'), 'DOMINGO' => $this->lang->line('sunday'));
                                    $flag = 0;
                                    $map = function($r) {return $r->day;};
                                    $count = array_count_values(array_map($map, $routines));
                                    $max = max($count);
                                    foreach ($us_days as $key => $us_day) {
                                        $row_count = 0;
                                        foreach ($routines as $routine) {
                                            if($routine->day == $key) {
                                                if($flag == 0) {
                                                    echo '<tr>';
                                                    echo '<td>'.$us_day.'</td>';
                                                    $flag = 1;
                                                } 
                                                echo '<td>';
                                                echo '<div class="btn-group">';
                                                echo "<span type=\"button\" class=\"btn btn-success\">"; 
                                                    echo $routine->start_time.'-'.$routine->end_time.'<br/>';
                                                    echo $routine->subject.'<br/>';
                                                    echo $routine->room.'<br/>';
                                                    echo $routine->name.'<br/>';
                                                    if(permissionChecker('routine_edit')) {
                                                        echo btn_edit('routine/edit/'.$routine->routineID.'/'.$set, $this->lang->line('edit'));
                                                    }
                                                    if(permissionChecker('routine_delete')) {
                                                        echo btn_delete('routine/delete/'.$routine->routineID.'/'.$set, $this->lang->line('delete'));
                                                    }
                        
                                                echo '</span>';
                                                echo '</div>';
                                                echo '</td>'; 
                                                $row_count++;
                                            } 
                                        }

                                        if($flag == 1) {
                                            while($row_count<$max) {
                                              // echo "<td></td>";
                                              $row_count++;
                                            }

                                            echo '</tr>';
                                            $flag = 0;
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                    <div id="hide-table-2">
                        <table id="table" class="table table-striped ">
                            <tbody>
                                <?php
                                    $us_days = array('LUNES' => $this->lang->line('monday'), 'MARTES' => $this->lang->line('tuesday'), 'MIERCOLES' => $this->lang->line('wednesday'), 'JUEVES' => $this->lang->line('thursday'), 'VIERNES' => $this->lang->line('friday'), 'SABADO' => $this->lang->line('saturday'), 'DOMINGO' => $this->lang->line('sunday'));
                                    $flag = 0;
                                    foreach ($us_days as $key => $us_day) {
                                        echo '<tr>';
                                        echo '<td>'.$us_day.'</td>';
                                        echo '</tr>';
                                    }  
                                ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
               
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
            $('.nav-tabs-custom').hide();
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('routine/student_list')?>",
                data: "id=" + studentID,
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });
    var mainWidth = $('html').width();
    if(mainWidth >= 980) {
         $('#hide-table-2').mCustomScrollbar({
            axis:"x" // horizontal scrollbar
        });
    } 
   
</script>