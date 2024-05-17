<?php
    $email = $this->session->userdata('email');
    $usertype=$this->session->userdata('usertype');
?>
<div class="box">
    <div class="box-body">
        <div class="row">
            <?php include_once 'sidebar.php'; ?>
            <div class="col-md-9">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title"><?=$this->lang->line('compose_new')?></h3>
                </div>
                <div class="box-body">
                    <form role="form" method="post" enctype="multipart/form-data">
                      <div class="form-group">
                        <div class="select2-wrapper">
                          <select id="userGroup" class="Group form-control select2" name="userGroup">
                            <option value="0"><?=$this->lang->line('select_group')?></option>
                            <?php
                              if(count($usertypes)) {
                                foreach ($usertypes as $key => $usertype) {
                                  echo '<option value="'.$usertype->usertypeID.'">'.$usertype->usertype.'</option>';
                                }
                              }
                            ?>
                          </select>
                        </div>
                        <div class="has-error">
                            <?php if (form_error('userGroup')): ?>
                                <p class="text-danger"> <?php echo form_error('userGroup'); ?></p>
                            <?php endif ?>
                        </div>
                      </div>
                      <div id="classDiv" class="form-group" style="display:none;">
                        <div class="select2-wrapper">
                          <select id="classID" class="Group form-control select2" name="classID">
                            <option value=""><?=$this->lang->line('select_class')?></option>
                          </select>
                        </div>
                        <div class="has-error" id="selectDiv">
                            <?php if (form_error('classID')): ?>
                                <p class="text-danger"> <?php echo form_error('classID'); ?></p>
                            <?php endif ?>
                        </div>
                      </div>
                      <div id="stdDiv" class="form-group" style="display:none;">
                        <div class="select2-wrapper">
                          <select id="studentID" class="Group form-control select2" name="studentID">
                            <option value=""><?=$this->lang->line('select_student')?></option>
                          </select>
                        </div>
                        <div class="has-error" id="selectDiv">
                            <?php if (form_error('studentID')): ?>
                                <p class="text-danger"> <?php echo form_error('studentID'); ?></p>
                            <?php endif ?>
                        </div>
                      </div>
                      <div id="adminDiv" class="form-group" style="display:none;">
                        <div class="select2-wrapper">
                          <select id="systemadminID" class="Group form-control select2" name="systemadminID">
                            <option value=""><?=$this->lang->line('select_admin')?></option>
                          </select>
                        </div>
                        <div class="has-error" id="selectDiv">
                            <?php if (form_error('systemadminID')): ?>
                                <p class="text-danger"> <?php echo form_error('systemadminID'); ?></p>
                            <?php endif ?>
                        </div>
                      </div>
                      <div id="teacherDiv" class="form-group" style="display:none;">
                        <div class="select2-wrapper">
                          <select id="teacherID" class="Group form-control select2" name="teacherID">
                            <option value=""><?=$this->lang->line('select_teacher')?></option>
                          </select>
                        </div>
                        <div class="has-error" id="selectDiv">
                            <?php if (form_error('teacherID')): ?>
                                <p class="text-danger"> <?php echo form_error('teacherID'); ?></p>
                            <?php endif ?>
                        </div>
                      </div>
                      <div id="parentDiv" class="form-group" style="display:none;">
                        <div class="select2-wrapper">
                          <select id="parentID" class="Group form-control select2" name="parentID">
                            <option value=""><?=$this->lang->line('select_parent')?></option>
                          </select>
                        </div>
                        <div class="has-error" id="selectDiv">
                            <?php if (form_error('parentID')): ?>
                                <p class="text-danger"> <?php echo form_error('parentID'); ?></p>
                            <?php endif ?>
                        </div>
                      </div>
                      <div id="userDiv" class="form-group" style="display:none;">
                        <div class="select2-wrapper">
                          <select id="userID" class="Group form-control select2" name="userID">
                            <option value=""><?=$this->lang->line('select_user')?></option>
                          </select>
                        </div>
                        <div class="has-error" id="selectDiv">
                            <?php if (form_error('userID')): ?>
                                <p class="text-danger"> <?php echo form_error('userID'); ?></p>
                            <?php endif ?>
                        </div>
                      </div>
                      <div class="form-group">
                        <input class="form-control" name="subject" value="<?=set_value('subject')?>" placeholder="<?=$this->lang->line('subject')?>"/>
                        <div class="has-error">
                            <?php if (form_error('subject')): ?>
                                <p class="text-danger"> <?php echo form_error('subject'); ?></p>
                            <?php endif ?>
                        </div>
                      </div>
                      <div class="form-group">
                        <textarea class="form-control" name="message" rows="10" placeholder="<?=$this->lang->line('message')?>"><?=set_value('message')?></textarea>
                        <div class="has-error">
                            <?php if (form_error('message')): ?>
                                <p class="text-danger"> <?php echo form_error('message'); ?></p>
                            <?php endif ?>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="btn btn-info btn-file">
                          <i class="fa fa-paperclip"></i> <?=$this->lang->line('attachment')?>
                          <input type="file" id="attachment" name="attachment"/>
                        </div>
                        <div class="col-sm-3" style="padding-left:0;">
                            <input class="form-control"  id="uploadFile" placeholder="<?=$this->lang->line('choosefile');?>" disabled />
                        </div>
                        <div class="has-error">
                            <p class="text-danger"> <?php if(isset($attachment_error)) echo $attachment_error; ?></p>
                        </div>
                      </div>
                      <div class="pull-right">
                        <button type="submit" value="send" name="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> <?=$this->lang->line('send')?></button>
                      </div>
                      <button type="submit" value="draft" name="submit" class="btn btn-warning"><i class="fa fa-times"></i> <?=$this->lang->line('draft')?></button>
                    </form>
                </div><!-- /.box-body -->
              </div><!-- /. box -->
            </div><!-- /.col -->
        </div>
    </div>
</div>
<script>
$('.select2').select2();
document.getElementById("attachment").onchange = function() {
    document.getElementById("uploadFile").value = this.value;
};

$( "#userGroup" ).change(function() {
  if($(this).val() == 1) {
    $("#classDiv").hide();
    $("#stdDiv").hide();
    $("#teacherDiv").hide();
    $("#parentDiv").hide();
    $("#userDiv").hide();
    $("#adminDiv").show();
    $.ajax({
        type: 'POST',
        url: "<?=base_url('conversation/adminCall')?>",
        dataType: "html",
        success: function(data) {
           $('#systemadminID').html(data);
        }
    });
  } else if($(this).val() == 2) {
    $("#classDiv").hide();
    $("#stdDiv").hide();
    $("#adminDiv").hide();
    $("#parentDiv").hide();
    $("#userDiv").hide();
    $("#teacherDiv").show();
    $.ajax({
        type: 'POST',
        url: "<?=base_url('conversation/teacherCall')?>",
        dataType: "html",
        success: function(data) {
           $('#teacherID').html(data);
        }
    });
  } else if($(this).val() == 3) {
    $("#classDiv").show();
    $("#stdDiv").show();
    $("#adminDiv").hide();
    $("#teacherDiv").hide();
    $("#userDiv").hide();
    $("#parentDiv").hide();
    $.ajax({
        type: 'POST',
        url: "<?=base_url('conversation/classCall')?>",
        dataType: "html",
        success: function(data) {
           $('#classID').html(data);
        }
    });
  } else if($(this).val() == 4) {
    $("#classDiv").hide();
    $("#stdDiv").hide();
    $("#adminDiv").hide();
    $("#parentDiv").hide();
    $("#teacherDiv").hide();
    $("#userDiv").hide();
    $("#parentDiv").show();
    $.ajax({
        type: 'POST',
        url: "<?=base_url('conversation/parentCall')?>",
        dataType: "html",
        success: function(data) {
           $('#parentID').html(data);
        }
    });
  } else {
    var id = $(this).val();
    $("#classDiv").hide();
    $("#stdDiv").hide();
    $("#adminDiv").hide();
    $("#parentDiv").hide();
    $("#teacherDiv").hide();
    $("#parentDiv").hide();
    $("#userDiv").show();
    $.ajax({
        type: 'POST',
        url: "<?=base_url('conversation/userCall')?>",
        data : {id : id},
        dataType: "html",
        success: function(data) {
           $('#userID').html(data);
        }
    });
  }
});

$('#classID').change(function(event) {
    var classID = $(this).val();
    if(classID === '0') {
        $('#studentID').val(0);
    } else {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('conversation/call_all_student')?>",
            data: "id=" + classID,
            dataType: "html",
            success: function(data) {
               $('#studentID').html(data);
            }
        });
    }
});

</script>
