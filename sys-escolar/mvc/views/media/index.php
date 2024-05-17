
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-film"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_media')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <div class="box-body">
                    <?php if(permissionChecker('media_add')) { ?>
                    <h5 class="page-header">
                        <button class="btn-cs btn-sm-cs" data-toggle="modal" data-target="#folder"><span class="fa fa-folder"></span> <?=$this->lang->line('add_title')?></button>                
                    </h5>
                    <?php } ?>

                    <div class="row">
                        <?php if(permissionChecker('media_add')) { ?>
                        <div class="col-lg-3 col-sm-12">
                            <a class="btn btn-app bg-aqua" id="media-upload" data-toggle="modal" data-target="#file_upload">
                            <i class="fa fa-cloud-upload fa-2x"></i>                        
                            </a>
                            <input id="upload_media" name="upload_media" type="file"/>
                        </div>  
                        <?php } ?>                                                                                                                                               
                        <?php if (count($folders)): ?>
                            <?php foreach ($folders as $folder): ?>
                                <div class="col-lg-3 col-sm-12">
                                    <a href="<?=base_url("media/view/$folder->mcategoryID")?>" class="btn btn-app bg-navy" id="media-folder">
                                    <?php  
                                    if(strlen($folder->folder_name) > 12) {
                                       echo substr($folder->folder_name, 0,12). ".."; 
                                    } else {
                                        echo $folder->folder_name;
                                    }
                                    ?>                                  
                                    <i class="fa fa-folder fa-2x"></i>
                                    </a>
                                    <?php if (permissionChecker('media_delete') && ($usertypeID == 1 || ($usertypeID==$folder->usertypeID && $userID == $folder->userID))): ?>
                                    <?php echo delete_file(base_url("media/deletef/$folder->mcategoryID"), "close_folder") ?>
                                    <a id="<?='folder'.$folder->mcategoryID?>" data-toggle="modal" data-target="#share_modal" class="share_file pull-right" ><i class="fa fa-globe fa-2x"></i></a>
                                    <?php endif ?>
                                    <b style="font-size:10px;"><?=$this->lang->line('media_uploadedby')?> <?=$allusertype[$folder->usertypeID];?>: <?=$folder->shared_by;?></b>                                                                                                                   
                                </div>
                            <?php endforeach ?>
                        <?php endif ?>

                        <?php if (count($files)): ?>
                            <?php foreach ($files as $file): ?>
                                <div class="col-lg-3 col-sm-12">                                
                                    <a href="<?=base_url("uploads/media/$file->file_name");?>" download="<?=base_url("uploads/media/$file->file_name");?>" target="_blink" class="btn btn-app" id="media-folder">
                                    <?php 
                                    if(strlen($file->file_name_display) > 15) {
                                       echo substr($file->file_name_display, 0,15). ".."; 
                                    } else {
                                        echo $file->file_name_display;
                                    }
                                    ?>
                                    <i class="fa fa-file fa-2x"></i>
                                    </a>
                                    <?php if (permissionChecker('media_delete') && ($usertypeID == 1 || ($usertypeID == $file->usertypeID && $userID == $file->userID))): ?>
                                    <?php echo delete_file(base_url("media/delete/$file->mediaID"), "close_folder") ?>
                                    <a id="<?=$file->mediaID?>" data-toggle="modal" data-target="#share_modal" class="share_file pull-right" ><i class="fa fa-globe fa-2x"></i></a>
                                    <?php endif ?>
                                    <b style="font-size:10px;"><?=$this->lang->line('media_uploadedby')?> <?=$allusertype[$file->usertypeID];?>: <?=$file->shared_by;?></b> 
                                </div>
                            <?php endforeach ?>                 
                        <?php endif ?>
                    </div>  

                </div>

            </div>
        </div>
    </div>
</div>
<!-- share modal starts here -->
<form class="form-horizontal" role="form" method="post" action="<?=base_url('media/media_share')?>">
    <div class="modal fade" id="share_modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><?=$this->lang->line('share')?></h4>
            </div>
            <div class="modal-body">
                
                <input id="media_info" name="media_info" type="hidden" value="">  
                
                <?php 
                    if(form_error('share_with')) 
                        echo "<div class='form-group has-error' >";
                    else     
                        echo "<div class='form-group' >";
                ?>
                    <label for="share_with" class="col-sm-3 control-label">
                        <?=$this->lang->line("share_with")?>
                    </label>
                    <div class="col-sm-6">
                        <select name="share_with" id="share_with" class="form-control">
                            <option value="0"><?=$this->lang->line("share_with")?></option>                        
                            <option value="public"><?=$this->lang->line("public")?></option>
                            <option value="class"><?=$this->lang->line("class")?></option>
                        </select>
                    </div>
                    <span class="col-sm-4 col-sm-offset-3 control-label" id="share_with_error">
                    </span>
                </div>

                <?php 
                    if(form_error('classesID')) 
                        echo "<div class='form-group has-error' id='di' >";
                    else     
                        echo "<div class='form-group' id='di'>";
                ?>
                    <label for="classesID" class="col-sm-3 control-label">
                        <?=$this->lang->line("select_class")?>
                    </label>
                    <div class="col-sm-6">
                        <select name="classesID" id="classesID" class="form-control">
                        </select>
                    </div>
                    <span class="col-sm-4 col-sm-offset-3 control-label" id="share_with_error">
                    </span>
                </div>



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="submit" id="share_files" class="btn btn-success" value="<?=$this->lang->line("share")?>" />
            </div>
        </div>
      </div>
    </div>
</form>
<!-- share end here -->
<!-- folder modal starts here -->
<form class="form-horizontal" role="form" method="post">
    <div class="modal fade" id="folder">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><?=$this->lang->line('add_title')?></h4>
            </div>
            <div class="modal-body">
                

                <?php 
                    if(form_error('folder_name')) 
                        echo "<div class='form-group has-error' >";
                    else     
                        echo "<div class='form-group' >";
                ?>
                    <label for="folder_name" class="col-sm-3 control-label">
                        <?=$this->lang->line("folder_name")?>
                    </label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="folder_name" name="folder_name" value="<?=set_value('folder_name')?>" >
                    </div>
                    <span class="col-sm-4 col-sm-offset-3 control-label" id="folder_name_error">
                    </span>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="create_folder" class="btn btn-success" value="<?=$this->lang->line("add_title")?>" />
            </div>
        </div>
      </div>
    </div>
</form>
<!-- folder end here -->
<!-- file modal starts here -->
<form action="<?=base_url("media/add")?>" class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
    <div class="modal fade" id="file_upload">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><?=$this->lang->line('upload_file')?></h4>
            </div>
            <div class="modal-body">
                <div class='form-group' >
                    <label for="photo" class="col-sm-3 control-label col-xs-8 col-md-2">
                        <?=$this->lang->line("file")?>
                    </label>

                    <div class="col-sm-9">
                        <div class="input-group image-preview">
                            <input type="text" class="form-control image-preview-filename" disabled="disabled">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                    <span class="fa fa-remove"></span>
                                    <?=$this->lang->line('media_clear')?>
                                </button>
                                <div class="btn btn-success image-preview-input">
                                    <span class="fa fa-repeat"></span>
                                    <span class="image-preview-input-title">
                                    <?=$this->lang->line('media_file_browse')?></span>
                                    <input type="file" accept="image/png, image/jpeg, image/gif, application/pdf, application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf" name="file"/>
                                </div>
                            </span>
                        </div>
                    </div>


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="submit" id="upload_file" class="btn btn-success" value="<?=$this->lang->line("upload_file")?>" />
            </div>
        </div>
      </div>
    </div>
</form>
<!-- folder end here -->
<script type="text/javascript">
    $('#di').hide();
    $("#create_folder").click(function(){
        var folder_name = $('#folder_name').val();
        if(folder_name == "") {
            $("#folder_name_error").html("Please enter folder name").css("text-align", "left").css("color", 'red');
        } else {
            $("#folder_name_error").html("");
            $.ajax({
                type: 'POST',
                url: "<?=base_url('media/create_folder')?>",
                data: 'folder_name='+ folder_name,
                dataType: "html",
                success: function(data) {
                    location.reload();
                }
            });
        }
    });

    $('.share_file').click(function() {
       $('#media_info').val($(this).attr('id'));
    });

    $("#share_with").change(function() {
        var share_with = $(this).val();
        if (share_with=="class") {
            $('#di').show();
        } else {
            $('#di').hide();
        }
    });

    $("#share_with").change(function() {
        var share_with = $(this).val();
        if (share_with=="class") {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('media/classcall')?>",
                dataType: "html",
                success: function(data) {
                   $('#classesID').html(data);
                }
            });
        } else {
            $('#classesID').html("");            
        }
    });


    $(document).on('click', '#close-preview', function(){ 
        $('.image-preview').popover('hide');
        // Hover befor close the preview
        $('.image-preview').hover(
            function () {
               $('.image-preview').popover('show');
               $('.content').css('padding-bottom', '100px');
            }, 
             function () {
               $('.image-preview').popover('hide');
               $('.content').css('padding-bottom', '20px');
            }
        );    
    });

    $(function() {
        // Create the close button
        var closebtn = $('<button/>', {
            type:"button",
            text: 'x',
            id: 'close-preview',
            style: 'font-size: initial;',
        });
        closebtn.attr("class","close pull-right");
        // Set the popover default content
        $('.image-preview').popover({
            trigger:'manual',
            html:true,
            title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
            content: "There's no image",
            placement:'bottom'
        });
        // Clear event
        $('.image-preview-clear').click(function(){
            $('.image-preview').attr("data-content","").popover('hide');
            $('.image-preview-filename').val("");
            $('.image-preview-clear').hide();
            $('.image-preview-input input:file').val("");
            $(".image-preview-input-title").text("<?=$this->lang->line('media_file_browse')?>"); 
        }); 
        // Create the preview image
        $(".image-preview-input input:file").change(function (){     
            var img = $('<img/>', {
                id: 'dynamic',
                width:250,
                height:200,
                overflow:'hidden'
            });      
            var file = this.files[0];
            var reader = new FileReader();
            // Set preview image into the popover data-content
            reader.onload = function (e) {
                $(".image-preview-input-title").text("<?=$this->lang->line('media_file_browse')?>");
                $(".image-preview-clear").show();
                $(".image-preview-filename").val(file.name);
            }        
            reader.readAsDataURL(file);
        });  
    });    
</script>