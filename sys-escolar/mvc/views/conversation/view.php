
<div class="box">
    <div class="box-body">
        <div class="row">
            <?php include_once 'sidebar.php'; ?>
            <!-- reply error -->

            <!-- message box -->
            <div class="col-md-9">
                <!-- Chat box -->
                <div class="box box-success">
                    <div class="box-header">
                      <h3 class="box-title"><?=$this->lang->line('conversation_conversation')?></h3>
                    </div>
                    <div class="box-body chat" id="chat-box">
                      <!-- chat item -->
                      <?php foreach ($messages as $message): ?>
                          <div class="item">
                            <img src="<?php echo base_url("uploads/images/$message->photo"); ?>" alt="user image" class="online"/>
                            <p class="message">
                              <a href="#" class="name">
                                <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> <?php echo $message->create_date; ?></small>
                                <?php echo $message->sender; ?>
                              </a>
                              <?php echo $message->msg; ?>
                            </p>
                            <?php if ($message->attach): ?>
                            <div class="attachment">
                              <h4><?=$this->lang->line('attachment')?>:</h4>
                              <p class="filename">
                                <?php echo $message->attach; ?>
                              </p>
                              <div class="pull-right">
                                <a target="_blank" href="<?php echo base_url("uploads/attach/$message->attach_file_name"); ?>"
                                download class="btn btn-primary btn-sm btn-flat"><?=$this->lang->line('open')?></a>
                              </div>
                            </div><!-- /.attachment -->              
                            <?php endif ?>
                          </div><!-- /.item -->
                      <?php endforeach ?>
                      <!-- chat item -->
                    </div><!-- /.chat -->
                    <div class="box-footer">
                    <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input class="form-control" id="reply" name="reply" placeholder="<?=$this->lang->line('typemessage')?>..."/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="btn btn-info btn-file">
                                    <i class="fa fa-paperclip"></i> <?=$this->lang->line('attachment')?><input type="file" name="attachment" id="attachment">
                                </div>
                                <div style="padding-left:0;" class="col-sm-3">
                                    <input disabled="" placeholder="<?=$this->lang->line('choosefile')?>" id="uploadFile" class="form-control">
                                </div>
                                <div class="has-error">
                                    <p class="text-danger"> </p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8">
                                <input type="submit" value="<?=$this->lang->line('reply')?>" class="btn btn-success">
                            </div>
                        </div>
                    </form>
                    </div>
                </div><!-- /.box (chat box) -->
            </div><!-- /.col -->
        </div>
    </div>
</div>
<!-- Slimscroll -->
<script src="<?php echo base_url(); ?>assets/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script type="text/javascript">
    document.getElementById("attachment").onchange = function() {
        document.getElementById("uploadFile").value = this.value;
    };
    $('#chat-box').slimScroll({
        height: '250px'
    });
</script>
