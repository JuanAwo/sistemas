<div class="box">
    <div class="box-body">
        <div class="row">
            <?php include_once 'sidebar.php'; ?>
            <div class="col-md-9">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title"><?=$this->lang->line('draft')?></h3>
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
                                    <th><?=$this->lang->line('action')?></th>
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
                                      <td data-title="<?=$this->lang->line('time')?>" class="mailbox-date"><?=date('d M Y H.i A', strtotime($conversation->create_date))?></td>
                                      <td data-title="<?=$this->lang->line('action')?>" class="mailbox-reply">
                                        
                                        <a href="<?=base_url('conversation/draft_send/'.$conversation->id);?>" class="btn btn-xs btn-primary">
                                          <i class="fa fa-paper-plane" aria-hidden="true"></i> 
                                          <?=$this->lang->line('sent');?>
                                        </a>
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
                    window.location.href = data;
                }
            });
        }
    });
    $('#refresh').click(function(){
        location.reload();
    });

</script>
