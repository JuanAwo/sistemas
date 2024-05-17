<div class="col-md-3">
  <a href="<?=base_url('conversation/create')?>" class="btn btn-info btn-block margin-bottom"><?=$this->lang->line('add_title')?></a>
  <div class="box box-solid">
    <div class="box-header with-border">
      <h3 class="box-title"><?=$this->lang->line('folder')?></h3>
    </div>
    <div class="box-body no-padding">
      <ul class="nav nav-pills nav-stacked conversation">
        <li class=""><a href="<?=base_url('conversation/index')?>"><i class="fa fa-inbox"></i> <?=$this->lang->line('conversation')?> <span class="label label-info pull-right" id="inbox"></span></a></li>
        <li class=""><a href="<?=base_url('conversation/draft')?>"><i class="fa fa-floppy-o"></i> <?=$this->lang->line('draft')?> <span class="label label-info pull-right" id="inbox"></span></a></li>
        <li><a href="<?=base_url('conversation/sent')?>"><i class="fa fa-envelope-o"></i> <?=$this->lang->line('sent')?> <span class="label label-info pull-right" id="sent"></span></a></li>
        <li><a href="<?=base_url('conversation/trash_msg')?>"><i class="fa fa-trash-o"></i> <?=$this->lang->line('trash')?></a></li>
      </ul>
    </div><!-- /.box-body -->
  </div><!-- /. box -->
</div><!-- /.col -->