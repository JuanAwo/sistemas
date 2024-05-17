    
    <?php if($student && $lmember) { ?>
    <div class="well">
        <div class="row">

            <div class="col-sm-7">
                <button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('printablediv')"><span class="fa fa-print"></span> <?=$this->lang->line('print')?> </button>
                <?php
                 echo btn_add_pdf('lmember/print_preview/'.$lmember->studentID."/".$set, $this->lang->line('pdf_preview')) 
                ?>
                <button class="btn-cs btn-sm-cs" data-toggle="modal" data-target="#libraryCard"><span class="fa fa-floppy-o"></span> <?=$this->lang->line('librarycard')?> </button>

                <?php 
                    if(permissionChecker('lmember_edit')) {
                        echo btn_sm_edit('lmember/edit/'.$lmember->studentID."/".$set, $this->lang->line('edit'));
                    } 
                ?>


                <button class="btn-cs btn-sm-cs" data-toggle="modal" data-target="#mail"><span class="fa fa-envelope-o"></span> <?=$this->lang->line('mail')?></button>
            </div>

            <div class="col-sm-5">
                <ol class="breadcrumb">
                    <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li><a href="<?=base_url("lmember/index/$set")?>"><?=$this->lang->line('panel_title')?></a></li>
                    <li class="active"><?=$this->lang->line('view')?></li>
                </ol>
            </div>

        </div>
    </div>
    <?php } ?>

    <?php if($student && $lmember) { ?>
        <div id="printablediv">
            <section class="panel">
                <div class="profile-view-head">
                    <a href="#">
                        <?=img(base_url('uploads/images/'.$student->photo))?>
                    </a>

                    <h1><?=$student->name?></h1>
                    <p><?=$this->lang->line("lmember_classes")." ".$class->classes?></p>

                </div>
                <div class="panel-body profile-view-dis">
                    <h1><?=$this->lang->line("personal_information")?></h1>
                    <div class="row">
                        <div class="profile-view-tab">
                            <p><span><?=$this->lang->line("student_dni")?> </span>: <?=$student->dni?></p>
                        </div>
                        <div class="profile-view-tab">
                            <p><span><?=$this->lang->line("lmember_registerNO")?> </span>: <?=$student->registerNO?></p>
                        </div>

                        <div class="profile-view-tab">
                            <p><span><?=$this->lang->line("lmember_roll")?> </span>: <?=$student->roll?></p>
                        </div>
                        <div class="profile-view-tab">
                            <p><span><?=$this->lang->line("menu_section")?> </span>: <?php if(count($section)) { echo $section->section;} else { echo $student->section;}?></p>
                        </div>
                        <div class="profile-view-tab">
                            <p><span><?=$this->lang->line("lmember_lID")?> </span>: <?=$lmember->lID?></p>
                        </div>
                        <div class="profile-view-tab">
                            <p><span><?=$this->lang->line("lmember_lfee")?> </span>: <?=$lmember->lbalance?></p>
                        </div>
                        <div class="profile-view-tab">
                            <p><span><?=$this->lang->line("lmember_joindate")?> </span>: <?=date("d M Y", strtotime($lmember->ljoindate))?></p>
                        </div>
                        <div class="profile-view-tab">
                            <p><span><?=$this->lang->line("lmember_dob")?> </span>: 
                            <?php if($student->dob) { echo date("d M Y", strtotime($student->dob)); } else { echo ''; }?></p>
                        </div>
                        <div class="profile-view-tab">
                            <p><span><?=$this->lang->line("lmember_sex")?> </span>: <?=$student->sex?></p>
                        </div>
                        <div class="profile-view-tab">
                            <p><span><?=$this->lang->line("lmember_bloodgroup")?> </span>: <?php if(isset($allbloodgroup[$student->bloodgroup])) { echo $student->bloodgroup; } ?></p>
                        </div>
                        <div class="profile-view-tab">
                            <p><span><?=$this->lang->line("lmember_email")?> </span>: <?=$student->email?></p>
                        </div>
                        <div class="profile-view-tab">
                            <p><span><?=$this->lang->line("lmember_phone")?> </span>: <?=$student->phone?></p>
                        </div>
                        <div class="profile-view-tab">
                            <p><span><?=$this->lang->line("lmember_address")?> </span>: <?=$student->address?></p>
                        </div>
                        <div class="profile-view-tab">
                            <p><span><?=$this->lang->line("lmember_state")?> </span>: <?=$student->state?></p>
                        </div>
                        <div class="profile-view-tab">
                            <p><span><?=$this->lang->line("lmember_country")?> </span>: <?php if(isset($allcountry[$student->country])) { echo $allcountry[$student->country]; } ?></p>
                        </div>
                    </div>

                    <h1><?=$this->lang->line("book_issue_history")?></h1>

                    <div class="row">
                        <div class="col-sm-12">
                            <div id="hide-table">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th><?=$this->lang->line("slno")?></th>
                                            <th><?=$this->lang->line("lmember_book")?></th>
                                            <th><?=$this->lang->line("lmember_author")?></th>
                                            <th><?=$this->lang->line("lmember_serial_no")?></th>
                                            <th><?=$this->lang->line("lmember_issue_date")?></th>
                                            <th><?=$this->lang->line("lmember_due_date")?></th>
                                            <th><?=$this->lang->line("lmember_return_date")?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $i = 1;
                                            foreach ($issues as $issue) {
                                        ?>
                                        <tr>
                                            <td data-title="<?=$this->lang->line('slno')?>"><?php  echo $i; ?></td>
                                            <td data-title="<?=$this->lang->line('lmember_book')?>"><?php  echo $issue->book; ?></td>
                                            <td data-title="<?=$this->lang->line('lmember_author')?>"><?php  echo $issue->author; ?></td>
                                            <td data-title="<?=$this->lang->line('lmember_serial_no')?>"><?php  echo $issue->serial_no; ?></td>
                                            <td data-title="<?=$this->lang->line('lmember_issue_date')?>"><?php  echo date("d M Y", strtotime($issue->issue_date)); ?></td>
                                            <td data-title="<?=$this->lang->line('lmember_due_date')?>"><?php  echo date("d M Y", strtotime($issue->due_date)); ?></td>
                                            <td data-title="<?=$this->lang->line('lmember_return_date')?>"><?php if($issue->return_date !="" && !empty($issue->return_date)) {  echo date("d M Y", strtotime($issue->return_date)); } ?></td>
                                        </tr>

                                        <?php $i++; } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>
            </section>
        </div>

        <!-- Modal content start here -->
        <div class="modal fade" id="libraryCard">
          <div class="modal-dialog">
            <div class="modal-content">
                <div id="idCardPrint">
                  <div class="modal-header">
                    <?=$this->lang->line('librarycard')?>
                  </div>
                  <div class="modal-body" > 
                    <table>
                        <tr>
                            <td>
                                <h4 style="margin:0;">
                                <?php 
                                    if($siteinfos->photo) {
                                        $array = array(
                                            "src" => base_url('uploads/images/'.$siteinfos->photo),
                                            'width' => '25px',
                                            'height' => '25px',
                                            "style" => "margin-bottom:10px;"
                                        );
                                        echo img($array);
                                    }
                                    
                                ?>

                                </h4>
                            </td>
                            <td style="padding-left:5px;">
                                <h4><?=$siteinfos->sname;?></h4>
                            </td>
                        </tr>
                    </table>

                    <table class="idcard-Table" style="background-color:#A6654E">
                        <tr>
                            <td>
                                <h4>
                                    <?php 
                                        $image_properties = array(
                                            'src' => base_url('uploads/images/'.$student->photo),
                                            'style' => 'border: 8px solid #BF9169',
                                        );
                                        echo img($image_properties);
                                    ?>
                                </h4> 
                            </td>
                            <td class="row-style">
                                <h3><?php  echo $student->name; ?></h3>
                                <h5><?php  echo $this->lang->line("lmember_lID")." : ".$lmember->lID; ?>
                                </h5>
                                <h5><?php  echo $this->lang->line("lmember_classes")." : ".$class->classes; ?>
                                </h5>
                                <h5>
                                    <?php  echo $this->lang->line("lmember_roll")." : ".$student->roll; ?>
                                </h5>
                            </td>
                        </tr>
                    </table>    
                  </div>
                </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" onclick="javascript:closeWindow()" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <button type="button" class="btn btn-success" onclick="javascript:printDiv('idCardPrint')"><?=$this->lang->line('print')?></button>
              </div>
            </div>
          </div>
        </div>
        <!-- Modal content End here -->

    <?php } else {  ?>
        <div class="col-sm-12">
        <?php echo "<h2><center>". $this->lang->line("lmember_message") ."</center></h2>"; ?>
        </div>
    <?php } ?>
<!-- email modal starts here -->
<form class="form-horizontal" role="form" action="<?=base_url('lmember/send_mail');?>" method="post">
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
                url: "<?=base_url('lmember/send_mail')?>",
                data: 'to='+ to + '&subject=' + subject + "&id=" + id+ "&message=" + message+ "&set=" + set,
                dataType: "html",
                success: function(data) {
                    location.reload();
                }
            });
        }
    });
</script>