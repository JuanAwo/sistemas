
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-user"></i> <?=$this->lang->line('panel_title')?></h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_parents')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php
                    $usertype = $this->session->userdata("usertype");
                    if(permissionChecker('parents_add')) {
                ?>
                    <h5 class="page-header">
                        <a class="btn btn-success" href="<?php echo base_url('parents/add') ?>">
                            <i class="fa fa-plus"></i>
                            <?=$this->lang->line('add_title')?>
                        </a>
                    </h5>
                <?php } ?>


                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('parents_photo')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('parents_dni')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('parents_name')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('parents_email')?></th>
                                <?php if(permissionChecker('parents_edit')) { ?>
                                <th class="col-sm-1"><?=$this->lang->line('parents_status')?></th>
                                <?php } ?>
                                <?php if(permissionChecker('parents_edit') || permissionChecker('parents_delete') || permissionChecker('parents_view')) { ?>
                                <th class="col-sm-3"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($parents)) {$i = 1; foreach($parents as $parent) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('parents_photo')?>">
                                        <?php $array = array(
                                                "src" => base_url('uploads/images/'.$parent->photo),
                                                'width' => '35px',
                                                'height' => '35px',
                                                'class' => 'img-rounded'

                                            );
                                            echo img($array);
                                        ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('parents_dni')?>">
                                        <?php echo $parent->dni; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('parents_name')?>">
                                        <?php echo $parent->name; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('parents_email')?>">
                                        <?php echo $parent->email; ?>
                                    </td>
                                    <?php if(permissionChecker('parents_edit')) { ?>
                                    <td data-title="<?=$this->lang->line('parents_status')?>">
                                      <div class="onoffswitch-small" id="<?=$parent->parentsID?>">
                                          <input type="checkbox" id="myonoffswitch<?=$parent->parentsID?>" class="onoffswitch-small-checkbox" name="parents_status" <?php if($parent->active === '1') echo "checked='checked'"; ?>>
                                          <label for="myonoffswitch<?=$parent->parentsID?>" class="onoffswitch-small-label">
                                              <span class="onoffswitch-small-inner"></span>
                                              <span class="onoffswitch-small-switch"></span>
                                          </label>
                                      </div>
                                    </td>
                                    <?php } ?>
                                    <?php if(permissionChecker('parents_edit') || permissionChecker('parents_delete') || permissionChecker('parents_view')) { ?>
                                    <td data-title="<?=$this->lang->line('action')?>">
                                        <?php echo btn_view('parents/view/'.$parent->parentsID, $this->lang->line('view')) ?>
                                        <?php echo btn_edit('parents/edit/'.$parent->parentsID, $this->lang->line('edit')) ?>
                                        <?php echo btn_delete('parents/delete/'.$parent->parentsID, $this->lang->line('delete')) ?>
                                    </td>
                                    <?php } ?>
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
  var status = '';
  var id = 0;
  $('.onoffswitch-small-checkbox').click(function() {
      if($(this).prop('checked')) {
          status = 'chacked';
          id = $(this).parent().attr("id");
      } else {
          status = 'unchacked';
          id = $(this).parent().attr("id");
      }

      if((status != '' || status != null) && (id !='')) {
          $.ajax({
              type: 'POST',
              url: "<?=base_url('parents/active')?>",
              data: "id=" + id + "&status=" + status,
              dataType: "html",
              success: function(data) {
                  if(data == 'Success') {
                      toastr["success"]("Success")
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
                  } else {
                      toastr["error"]("Error")
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
              }
          });
      }
  });
</script>
