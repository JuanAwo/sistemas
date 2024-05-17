
<?php if(count($student)) { ?>
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('printablediv')"><span class="fa fa-print"></span> <?=$this->lang->line('print')?> </button>
                <?php
                 echo btn_add_pdf('mark/print_preview/'.$student->studentID."/".$set, $this->lang->line('pdf_preview'))
                ?>
                <button class="btn-cs btn-sm-cs" data-toggle="modal" data-target="#mail"><span class="fa fa-envelope-o"></span> <?=$this->lang->line('mail')?></button>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li><a href="<?=base_url("mark/index/$set")?>"><?=$this->lang->line('menu_mark')?></a></li>
                    <li class="active"><?=$this->lang->line('view')?></li>
                </ol>
            </div>
        </div>
    </div>

    <div id="printablediv">
        <section class="panel">
            <div class="profile-view-head">
                <a href="#">
                    <?=img(base_url('uploads/images/'.$student->photo))?>
                </a>

                <h1><?=$student->name?></h1>
                <p><?=$this->lang->line("mark_classes")." ".$classes->classes?></p>

            </div>
            <div class="panel-body profile-view-dis">
                <h1><?=$this->lang->line("personal_information")?></h1>
                <div class="row">
                    <div class="profile-view-tab">
                        <p><span><?=$this->lang->line("student_dni")?> </span>: <?=$student->dni?></p>
                    </div>
                    <div class="profile-view-tab">
                        <p><span><?=$this->lang->line("mark_registerNO")?> </span>: <?=$student->registerNO?></p>
                    </div>
                    <div class="profile-view-tab">
                        <p><span><?=$this->lang->line("mark_roll")?> </span>: <?=$student->roll?></p>
                    </div>
                    <div class="profile-view-tab">
                        <p><span><?=$this->lang->line("menu_section")?> </span>: <?php if(count($section)) { echo $section->section;} else { echo $student->section;}?></p>
                    </div>
                    <div class="profile-view-tab">
                        <p><span><?=$this->lang->line("mark_dob")?> </span>: <?=date("d M Y", strtotime($student->dob))?></p>
                    </div>
                    <div class="profile-view-tab">
                        <p><span><?=$this->lang->line("mark_sex")?> </span>: <?=$student->sex?></p>
                    </div>
                    <div class="profile-view-tab">
                        <p><span><?=$this->lang->line("mark_bloodgroup")?> </span>: <?php if(isset($allbloodgroup[$student->bloodgroup])) { echo $student->bloodgroup; } ?></p>
                    </div>
                    <div class="profile-view-tab">
                        <p><span><?=$this->lang->line("mark_email")?> </span>: <?=$student->email?></p>
                    </div>
                    <div class="profile-view-tab">
                        <p><span><?=$this->lang->line("mark_phone")?> </span>: <?=$student->phone?></p>
                    </div>
                    <div class="profile-view-tab">
                        <p><span><?=$this->lang->line("mark_address")?> </span>: <?=$student->address?></p>
                    </div>
                    <div class="profile-view-tab">
                        <p><span><?=$this->lang->line("mark_state")?> </span>: <?=$student->state?></p>
                    </div>
                    <div class="profile-view-tab">
                        <p><span><?=$this->lang->line("mark_country")?> </span>: <?php if(isset($allcountry[$student->country])) { echo $allcountry[$student->country]; } ?></p>
                    </div>
                    <div class="profile-view-tab">
                        <p><span><?=$this->lang->line("mark_username")?> </span>: <?=$student->username?></p>
                    </div>
                </div>

                <h1><?=$this->lang->line("mark_information")?></h1>

                <div class="row">
                    <?php if($marks && $exams) { ?>
                        <div class="col-lg-12">
                            <div id="hide-table">
                                <?php

                                    $map1 = function($r) { return intval($r->examID);};
                                    $marks_examsID = array_map($map1, $marks);
                                    $max_semester = max($marks_examsID);

                                    $map2 = function($r) { return intval($r->examID);};
                                    $examsID = array_map($map2, $exams);

                                    $map3 = function($r) { return array("mark" => intval($r->mark), "semester"=>$r->examID);};
                                    $all_marks = array_map($map3, $marks);

                                    $map4 = function($r) { return array("gradefrom" => $r->gradefrom, "gradeupto" => $r->gradeupto);};
                                    $grades_check = array_map($map4, $grades);



                                    foreach ($exams as $exam) {
                                        $allSubject = 0; $totalMark = 0;
                                        echo '<div class="box" id="e'.$exam->examID.'">';

                                            if($exam->examID <= $max_semester) {

                                                $check = array_search($exam->examID, $marks_examsID);

                                                if($check>=0) {
                                                    $f = 0;
                                                    foreach ($grades_check as $key => $range) {
                                                        foreach ($all_marks as $value) {
                                                            if($value['semester'] == $exam->examID ) {
                                                                if($value['mark']>=$range['gradefrom'] && $value['mark']<=$range['gradeupto'])
                                                                {
                                                                    $f=1;
                                                                }
                                                            }
                                                        }
                                                        if($f==1)
                                                        {
                                                            break;
                                                        }
                                                    }

                                                    $headerColor = ['bg-sky', 'bg-purple-shipu','bg-sky-total-grade', 'bg-sky-light', 'bg-sky-total' ];

                                                    echo '<div class="box-header">';
                                                        echo '<h3 class="box-title">';
                                                           echo $exam->exam;
                                                        echo '</h3>';
                                                    echo '</div>';
                                                    echo '<div class="box-body" style="border:1px solid #23292F">';

                                                    echo "<table class=\"table table-striped table-bordered\">";
                                                    echo "<thead>";
                                                        echo "<tr>";
                                                            echo "<th class='text-center' rowspan='2' style='background-color:#395C7F;color:#fff;'>";
                                                                echo $this->lang->line("mark_subject");
                                                            echo "</th>";
                                                            $headerCount = 1;
                                                            foreach ($markpercentages as $value) {
                                                                $color = 'bg-aqua';
                                                                if($headerCount % 2 == 0) {
                                                                    $color = 'bg-aqua';
                                                                }
                                                                echo "<th colspan='2' class=' text-center' style='background-color:#395C7F;color:#fff;'>";
                                                                    echo $value->markpercentagetype;
                                                                echo "</th>";
                                                                $headerCount++;
                                                            }
                                                            echo "<th colspan='3' class='text-center ' style='background-color:#395C7F;color:#fff;'>";
                                                                echo $this->lang->line("mark_total");
                                                            echo "</th>";
                                                        echo "</tr>";
                                                        echo "<tr>";
                                                            foreach ($markpercentages as $value) {
                                                                echo "<th class='".$headerColor[0]." text-center '>";
                                                                    echo $this->lang->line("mark_mark");
                                                                echo "</th>";

                                                                echo "<th class='".$headerColor[3]." text-center' data-title='".$this->lang->line('mark_highest_mark')."'>";
                                                                    echo $this->lang->line("mark_highest_mark");
                                                                echo "</th>";
                                                            }
                                                            echo "<th class='".$headerColor[4]." text-center'>";
                                                                echo $this->lang->line("mark_mark");
                                                            echo "</th>";
                                                            if(count($grades) && $f == 1) {
                                                                echo "<th class='".$headerColor[1]." text-center' data-title='".$this->lang->line('mark_point')."'>";
                                                                    echo $this->lang->line("mark_point");
                                                                echo "</th>";
                                                                echo "<th class='".$headerColor[2]." text-center' data-title='".$this->lang->line('mark_grade')."'>";
                                                                    echo $this->lang->line("mark_grade");
                                                                echo "</th>";
                                                            }

                                                        echo "</tr>";
                                                    echo "</thead>";
                                                }
                                            }

                                            echo "<tbody>";

                                        if(isset($separatedMarks[$exam->examID]) && is_array($separatedMarks[$exam->examID])) {
                                            foreach ($separatedMarks[$exam->examID] as $subjectID => $mark) {
                                                echo "<tr>";
                                                    echo "<td class='text-black' data-title='".$this->lang->line('mark_subject')."'>";
                                                        echo $mark['subject'];
                                                    echo "</td>";
                                                    $totalSubjectMark = 0;
                                                    foreach ($markpercentages as $markpercentage) {

                                                        echo "<td class='text-black' data-title='".$this->lang->line('mark_mark')."'>";
                                                            echo $mark[$markpercentage->markpercentageID];
                                                            $totalSubjectMark += $mark[$markpercentage->markpercentageID];

                                                        echo "</td>";
                                                        echo "<td class='text-black' data-title='".$this->lang->line('mark_highest_mark')."'>";
                                                            if($highestMarks[$exam->examID][$subjectID][$markpercentage->markpercentageID] != -1) {
                                                                echo $highestMarks[$exam->examID][$subjectID][$markpercentage->markpercentageID];
                                                            }
                                                        echo "</td>";
                                                    }
                                                    echo "<td class='text-black' data-title='".$this->lang->line('mark_mark')."'>";
                                                        echo $totalSubjectMark;
                                                        $totalMark += $totalSubjectMark;
                                                        $allSubject++;
                                                    echo "</td>";
                                                    $flag = 1;
                                                    if(count($grades)) {
                                                        foreach ($grades as $grade) {
                                                            if($grade->gradefrom <= $totalSubjectMark && $grade->gradeupto >= $totalSubjectMark) {
                                                                echo "<td class='text-black' data-title='".$this->lang->line('mark_point')."'>";
                                                                    echo $grade->point;
                                                                echo "</td>";
                                                                echo "<td class='text-black' data-title='".$this->lang->line('mark_grade')."'>";
                                                                    echo $grade->grade;
                                                                echo "</td>";
                                                                $flag = 0;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                    if($flag) {
                                                        echo "<td></td>";
                                                        echo "<td></td>";
                                                    }
                                                echo "</tr>";
                                            }



                                            echo "</tbody>";
                                            echo "</table>";

                                            echo '<div class="box-footer" style="padding-left:0px;">';
                                                $totalAverageMark = ($totalMark == 0) ? 0 :  ($totalMark/$allSubject);
                                                echo '<p class="text-black">'. $this->lang->line('mark_total_marks').' : <span class="text-red text-bold">'. number_format((float)($totalMark), 2, '.', '').'</span>';
                                                echo '&nbsp;&nbsp;&nbsp;&nbsp;'.$this->lang->line('mark_average_marks').' : <span class="text-red text-bold">'. number_format((float)($totalAverageMark), 2, '.', '').'</span>';
                                                if(count($grades)) {
                                                    foreach ($grades as $grade) {
                                                        if($grade->gradefrom <= $totalAverageMark && $grade->gradeupto >= $totalAverageMark) {
                                                            echo '&nbsp;&nbsp;&nbsp;&nbsp;'.$this->lang->line('mark_average_point').' : <span class="text-red text-bold">'.$grade->point.'</span>';
                                                            echo '&nbsp;&nbsp;&nbsp;&nbsp;'.$this->lang->line('mark_average_grade').' : <span class="text-red text-bold">'.$grade->grade.'</span>';
                                                            break;
                                                        }
                                                    }
                                                }
                                                echo '</p>';
                                                echo '<a class="btn bg-black-btn no-print" target="_blank" href="#" onclick="javascript:printDiv(\'e'.$exam->examID.'\')">'.$this->lang->line('print').'</a>';
                                            echo '</div>';

                                        }
                                        echo '</div>';
                                        echo '</div>';
                                    }
                                ?>

                            </div>
                        </div>
                    <?php } ?>
                </div>


            </div>
        </section>
    </div>
<!-- email modal starts here -->
<form class="form-horizontal" role="form" action="<?=base_url('teacher/send_mail');?>" method="post">
    <div class="modal fade" id="mail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><?=$this->lang->line('mail')?></h4>
            </div>
            <div class="modal-body">

                <?php
                    if(form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("to")?>
                    </label>
                    <div class="col-sm-6">
                        <input type="email" class="form-control" id="to" name="to" value="<?=set_value('to')?>" >
                    </div>
                    <span class="col-sm-4 control-label" id="to_error">
                    </span>
                </div>

                <?php
                    if(form_error('subject'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="subject" class="col-sm-2 control-label">
                        <?=$this->lang->line("subject")?>
                    </label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="subject" name="subject" value="<?=set_value('subject')?>" >
                    </div>
                    <span class="col-sm-4 control-label" id="subject_error">
                    </span>

                </div>

                <?php
                    if(form_error('message'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="message" class="col-sm-2 control-label">
                        <?=$this->lang->line("message")?>
                    </label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("send")?>" />
            </div>
        </div>
      </div>
    </div>
</form>
<!-- email end here -->
<script language="javascript" type="text/javascript">
    function printDiv(divID) {
        //Get the HTML of div
        var divElements = document.getElementById(divID).innerHTML;
        //Get the HTML of whole page
        var oldPage = document.body.innerHTML;

        //Reset the page's HTML with div's HTML only
        document.body.innerHTML =
          "<html><head><title></title></head><body>" +
          divElements + "</body>";

        //Print Page
        window.print();

        //Restore orignal HTML
        document.body.innerHTML = oldPage;
    }
    function closeWindow() {
        location.reload();
    }


    function check_email(email) {
        var status = false;
        var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
        if (email.search(emailRegEx) == -1) {
            $("#to_error").html('');
            $("#to_error").html("<?=$this->lang->line('mail_valid')?>").css("text-align", "left").css("color", 'red');
        } else {
            status = true;
        }
        return status;
    }

    $("#send_pdf").click(function(){
        var to = $('#to').val();
        var subject = $('#subject').val();
        var message = $('#message').val();
        var id = "<?=$student->studentID;?>";
        var set = "<?=$set;?>";
        var error = 0;

        if(to == "" || to == null) {
            error++;
            $("#to_error").html("");
            $("#to_error").html("<?=$this->lang->line('mail_to')?>").css("text-align", "left").css("color", 'red');
        } else {
            if(check_email(to) == false) {
                error++
            }
        }

        if(subject == "" || subject == null) {
            error++;
            $("#subject_error").html("");
            $("#subject_error").html("<?=$this->lang->line('mail_subject')?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if(error == 0) {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('mark/send_mail')?>",
                data: 'to='+ to + '&subject=' + subject + "&id=" + id+ "&message=" + message+ "&set=" + set,
                dataType: "html",
                success: function(data) {
                    location.reload();
                }
            });
        }
    });
</script>
<?php } ?>
