<div class="box">
    <div class="box-body">
        <div class="row">
            <?php include_once 'sidebar.php'; ?>
            <div class="col-md-9">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">
                      <?php
                        if($methodpass == 'index' || $methodpass == false) {
                            echo $this->lang->line('conversation');
                        } elseif($methodpass == 'sent') {
                            echo $this->lang->line('sent');
                        } elseif($methodpass == 'trash_msg') {
                            echo $this->lang->line('trash');
                        }

                      ?>
                  </h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="margin-bottom">
                        <div class="btn-group">
                            <button id="all" class="btn btn-info btn-sm" data-original-title="<?=$this->lang->line('selectmail')?>" data-toggle="tooltip" data-placement="top">
                                <i class="fa fa-square-o"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" id="delete_submit" data-original-title="<?=$this->lang->line('deletemail')?>" data-toggle="tooltip" data-placement="top">
                                <i class="fa fa-trash-o"></i>
                            </button>
                            <button class="btn btn-primary btn-sm" id="refresh" data-original-title="<?=$this->lang->line('refresh')?>" data-toggle="tooltip" data-placement="top">
                                <i class="fa fa-refresh"></i>
                            </button>
                        </div>
                    </div>
                    <div id="hide-table">
                        <table id="withoutBtn" class="table table-hover dataTable no-footer">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?=$this->lang->line('status')?></th>
                                    <th><?=$this->lang->line('name')?></th>
                                    <th><?=$this->lang->line('subject')?></th>
                                    <th><?=$this->lang->line('attach')?></th>
                                    <th><?=$this->lang->line('time')?></th>
                                    <th><?=$this->lang->line('reply')?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if ($conversations): ?>
                                <?php if(count($conversations)) {$i = 1; foreach($conversations as $conversation) { ?>
                                    <tr class="">
                                      <td data-title="#"><input id="<?=$conversation->id?>" type="checkbox" value="<?=$conversation->id?>" class="checkbox btn btn-warning" data-original-title="<?=$this->lang->line('selectmail')?>" data-toggle="tooltip" data-placement="top"/></td>
                                     <td data-title="<?=$this->lang->line('status')?>" class="mailbox-star"><a class="fav" href="#" value="<?=$conversation->id?>"><?php if ($conversation->fav_status == 0) {?><i class="fa fa-star-o text-yellow"></i><?php } else {?> <i class="fa fa-star text-yellow"></i><?php } ?></a></td>
                                      <td data-title="<?=$this->lang->line('name')?>" class="mailbox-name"><a href='<?=base_url("conversation/view/$conversation->id")?>'><?=$conversation->sender;?></a></td>
                                      <td data-title="<?=$this->lang->line('subject')?>" class="mailbox-subject"><a href='<?=base_url("conversation/view/$conversation->id")?>'> <b><?=substr($conversation->subject, 0,10).".."?></b> </a></td>
                                      <td data-title="<?=$this->lang->line('attach')?>" class="mailbox-attachment"><?php if ($conversation->attach != '') {?><i class="fa fa-paperclip"></i><?php } ?></td>
                                      <?php $newDateTime = date('h:i:m A', strtotime($conversation->create_date));?>
                                      <td data-title="<?=$this->lang->line('time')?>" class="mailbox-date"><?=date('d M Y H:i A', strtotime($conversation->create_date))?></td>
                                      <td data-title="<?=$this->lang->line('reply')?>" class="mailbox-reply"><span class="label label-info">
                                        <?php
                                            if($conversation->msgCount == 1) {
                                                echo '0';
                                            } else {
                                                echo $conversation->msgCount;
                                            }
                                        ?>        
                                            </span>
                                        </td>
                                    </tr>
                                <?php $i++; }} ?>    
                            <?php endif ?>
                            </tbody>
                        </table>
                    </div><!-- /.mail-box-conversations -->
                </div><!-- /.box-body -->
              </div><!-- /. box -->
            </div><!-- /.col -->
        </div>
         <?php if ($siteinfos->note==1) { ?>
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
                            Para enviar mensajes no necesita de conexión a <strong>internet.</strong>
                            </div>
                        </div>
                    </div>
                </div> 
            </div> 
        </div>
         <?php } ?>

    </div>
</div>

<script type="text/javascript">
    $('#all').click(function() {
        if(!$('.checkbox').is(':checked'))
            $('.checkbox').prop('checked', true)
        else
            $('.checkbox').prop('checked', false);
    });
    $('.fav').click(function () {
        var id = $(this).attr('value');
        $.ajax({
            type: 'POST',
            url: "<?=base_url('conversation/fav_status')?>",
            data: "id=" + id,
            dataType: "html",
            success: function(data) {
                window.location.href = data;
            }
        });
    });
    

    $('#refresh').click(function(){
        location.reload();
    });

</script>

<?php if(isset($methodpass)) { if($methodpass == 'trash_msg') { ?>
    <script>
        $('#delete_submit').click(function() {
            var conversations = "";
            var result = [];
            $('input:checkbox.checkbox').each(function (index) {
                 conversations = (this.checked ? $(this).attr('id') : "");
                 result.push(conversations);

            });
            if (result.lenth!=0) {
                    $.ajax({
                    type: 'POST',
                    url: "<?=base_url('conversation/delete_trash_to_trash')?>",
                    data: "id=" + result,
                    dataType: "html",
                    success: function(data) {
                        location.reload();
                    }
                });
            }
        });
    </script>
<?php } elseif($methodpass == 'sent') { ?>
    <script>
        $('#delete_submit').click(function() {
            var conversations = "";
            var result = [];
            $('input:checkbox.checkbox').each(function (index) {
                 conversations = (this.checked ? $(this).attr('id') : "");
                 result.push(conversations);

            });
            if (result.lenth!=0) {
                    $.ajax({
                    type: 'POST',
                    url: "<?=base_url('conversation/delete_conversation')?>",
                    data: "id=" + result,
                    dataType: "html",
                    success: function(data) {
                        location.reload();
                    }
                });
            }
        });
    </script>
<?php } else { ?>
    <script>
        $('#delete_submit').click(function() {
            var conversations = "";
            var result = [];
            $('input:checkbox.checkbox').each(function (index) {
                 conversations = (this.checked ? $(this).attr('id') : "");
                 result.push(conversations);

            });
            if (result.lenth!=0) {
                    $.ajax({
                    type: 'POST',
                    url: "<?=base_url('conversation/delete_conversation')?>",
                    data: "id=" + result,
                    dataType: "html",
                    success: function(data) {
                        location.reload();
                    }
                });
            }
        });
    </script>
<?php }} ?>
