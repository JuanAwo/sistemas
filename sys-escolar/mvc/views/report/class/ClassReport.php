<div id="printablediv">
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> <?=$this->lang->line('report_class')?> <?=$class->classes?> ( <?=$sectionName?> ) <?=$this->lang->line('panel_title')?></h3>
<!--        <div class="box-tools pull-right">-->

<!--        </div>-->
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">

            <div class="col-sm-6">
                <div class="box box-solid " style="border: 1px #ccc solid; border-left: 2px black solid">
                    <div class="box-header bg-gray with-border">
                        <h3 class="box-title text-navy"><?=$this->lang->line("report_class_info")?></h3>
                        <ol class="breadcrumb">
                            <li><i class="fa fa-info fa-2x"></i></li>
                        </ol>
                    </div>
                    <div class="box-body">
                        <span class='text-blue'><?=$this->lang->line("report_class_number_of_students")?> : <?=count($students)?></span><br>
                        <span class='text-blue'><?=$this->lang->line("report_class_total_subject_assigned")?> : <?=count($subjects)?></span>
                    </div>
                </div>

                <br>

                <div class="box box-solid " style="border: 1px #ccc solid; border-left: 2px green solid">
                    <div class="box-header bg-gray with-border">
                        <h3 class="box-title text-navy"><?=$this->lang->line("report_subject_and_teachers")?></h3>
                        <ol class="breadcrumb">
                            <li><i class="fa fa-book fa-2x"></i></li>
                        </ol>
                    </div>
                    <div class="box-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>
                                        <?=$this->lang->line("report_subject")?>
                                    </th>
                                    <th>
                                        <?=$this->lang->line("report_teacher")?>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach ($subjects as $subject ) {

                                ?>
                                <tr>
                                    <td>
                                        <?=$subject->subject?>
                                    </td>
                                    <td>
                                        <?php
                                        if(isset($teachers[$subject->teacherID])) {
                                            $teacher = $teachers[$subject->teacherID];
                                            echo "<a class='text-blue' href='".base_url('teacher/view/'.$teacher->teacherID)."'>".$teacher->name."</a>";
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <br>

                <div class="box box-solid " style="border: 1px #ccc solid; border-left: 2px blue solid">
                    <div class="box-header bg-gray with-border">
                        <h3 class="box-title text-navy"><?=$this->lang->line("report_feetype_details")?></h3>
                        <ol class="breadcrumb">
                            <li><i class="fa fa-pie-chart fa-2x"></i></li>
                        </ol>
                    </div>
                    <div class="box-body">
                        <?php if(count($students)) { ?>
                        <div id="feetype_chart">
                        </div>
                        <?php } else { ?>
                            <?=$this->lang->line("report_chart_not_found")?>
                        <?php }?>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="box box-solid " style="border: 1px #ccc solid; border-left: 2px red solid">
                    <div class="box-header bg-gray with-border">
                        <h3 class="box-title text-navy"><?=$this->lang->line("report_class_teacher")?></h3>
                        <ol class="breadcrumb">
                            <li><i class="fa icon-teacher fa-2x"></i></li>
                        </ol>
                    </div>
                    <div class="box-body">
                        <?php
                            if(isset($teachers[$class->teacherID])) {
                                $teacher = $teachers[$class->teacherID];
                        ?>
                        <section class="panel">
                          <div class="profile-db-head bg-maroon-light">
                              <a href="<?=base_url('teacher/view/'.$teacher->teacherID)?>">
                                <?=img(base_url('uploads/images/'.$teacher->photo));?>
                              </a>
                            <h1><?=$teacher->name?></h1>
                          </div>
                          <table class="table table-hover">
                              <tbody>
                                  <tr>
                                    <td>
                                      <i class="fa fa-phone text-maroon-light" ></i>
                                    </td>
                                    <td><?=$this->lang->line('report_phone')?></td>
                                    <td><?=$teacher->phone?></td>
                                  </tr>
                                  <tr>
                                      <td>
                                        <i class="fa fa-envelope text-maroon-light"></i>
                                      </td>
                                      <td><?=$this->lang->line('report_email')?></td>
                                    <td><?=$teacher->email?></td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <i class=" fa fa-globe text-maroon-light"></i>
                                    </td>
                                    <td><?=$this->lang->line('report_address')?></td>
                                    <td><?=$teacher->address?></td>
                                  </tr>
                              </tbody>
                          </table>
                        </section>
                        <?php
                            }
                        ?>
                    </div>
                </div>

                <br>

                <div class="box box-solid " style="border: 1px #ccc solid; border-left: 2px orange solid">
                    <div class="box-header bg-gray with-border">
                        <h3 class="box-title text-navy"><?=$this->lang->line("report_feetypes_collection")?></h3>
                        <ol class="breadcrumb">
                            <li><i class="fa fa-dollar fa-2x"></i></li>
                        </ol>
                    </div>
                    <div class="box-body">
                        <?php if(count($feetypes)) {?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>
                                        <?=$this->lang->line("report_feetype")?>
                                    </th>
                                    <th>
                                        <?=$this->lang->line("report_collection")?>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach ($feetypes as $feetype => $collection ) {

                                ?>
                                <tr>
                                    <td>
                                        <?=$feetype?>
                                    </td>
                                    <td>
                                        <?=$collection?>
                                    </td>
                                </tr>
                                <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                        <?php } ?>
                    </div>
                </div>

                <br>

                <div class="box box-solid " style="border: 1px #ccc solid; border-left: 2px gray solid">
                    <div class="box-header bg-gray with-border">
                        <h3 class="box-title text-navy"><?=$this->lang->line("report_student_account_info")?></h3>
                        <ol class="breadcrumb">
                            <li><i class="fa fa-bar-chart fa-2x"></i></li>
                        </ol>
                    </div>
                    <div class="box-body">
                        <?php if(count($students)) {?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>
                                        <?=$this->lang->line("report_student_roll")?>
                                    </th>
                                    <th>
                                        <?=$this->lang->line("report_student")?>
                                    </th>
                                    <th>
                                        <?=$this->lang->line("report_total_amount")?>
                                    </th>
                                    <th>
                                        <?=$this->lang->line("report_paid_amount")?>
                                    </th>
                                    <th>
                                        <?=$this->lang->line("report_due_amount")?>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach ($studentInvoices as $studentID => $info ) {
                                        if(!isset($students[$studentID])) continue;
                                        $student = $students[$studentID];

                                ?>
                                <tr>
                                    <td>
                                        <?=$student->roll?>
                                    </td>
                                    <td>
                                        <?=$student->name?>
                                    </td>
                                    <td>
                                        <?=$info['amount']?>
                                    </td>
                                    <td>
                                        <?php
                                        if(isset($info['payment'])) {
                                            echo $info['payment'];
                                        } else {
                                            echo 0;
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if(isset($info['payment'])) {
                                            echo $info['amount'] - $info['payment'];
                                        } else {
                                            echo 0;
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                        <?php } else {?>
                            <?=$this->lang->line("report_student_not_found")?>
                        <?php } ?>
                    </div>
                </div>

            </div>

        </div><!-- row -->
    </div><!-- Body -->
</div>

<script type="application/javascript">

    $(function () {
        $('#feetype_chart').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: ''
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y:f}</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.y:f} ',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            series: [{
                name: 'Amount',
                colorByPoint: true,
                data: [{
                    name: '<?=$this->lang->line("report_collection")?>',
                    y: <?=$collectionAmount?>
                }, {
                    name: '<?=$this->lang->line("report_due")?>',
                    y: <?=$dueAmount?>,
                    sliced: true,
                    selected: true
                }]
            }]
        });
    });
</script>
