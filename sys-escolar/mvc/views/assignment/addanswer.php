
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-assignment"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("assignment/index")?>"></i> <?=$this->lang->line('menu_assignment')?></a></li>
            <li class="active">
            	<?php 
            		if($this->session->userdata('usertypeID') != 3) { 
	            		echo $this->lang->line('menu_add').' '; 
	            		echo $this->lang->line('menu_assignment');
	            	} else {
	            		echo $this->lang->line('menu_assignment').' ';
	            		echo $this->lang->line('assignment_ans'); 
	            	}
            	?>
            	
            </li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
        	<?php $usertypeID = $this->session->userdata('usertypeID'); ?>
            <?php if($usertypeID == 3) { ?>
            	<div class="col-sm-10">
                	<form class="form-horizontal" enctype="multipart/form-data" role="form" method="POST" >

                		<div class="form-group <?php if(form_error('file')) { echo 'has-error'; } ?>" >
	                        <label for="file" class="col-sm-2 control-label">
	                            <?=$this->lang->line("assignment_file")?>
	                        </label>
	                        <div class="col-sm-6">
	                            <div class="input-group image-preview">
	                                <input type="text" class="form-control image-preview-filename" disabled="disabled">
	                                <span class="input-group-btn">
	                                    <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
	                                        <span class="fa fa-remove"></span>
	                                        <?=$this->lang->line('assignment_clear')?>
	                                    </button>
	                                    <div class="btn btn-success image-preview-input">
	                                        <span class="fa fa-repeat"></span>
	                                        <span class="image-preview-input-title">
	                                        <?=$this->lang->line('assignment_file_browse')?></span>
	                                        <input type="file" accept="image/png, image/jpeg, image/gif, application/pdf, application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf" name="file"/>
	                                    </div>
	                                </span>
	                            </div>
	                        </div>

	                        <span class="col-sm-4 control-label">
	                            <?php echo form_error('file'); ?>
	                        </span>
	                    </div>


	                    <div class="form-group">
	                        <div class="col-sm-offset-2 col-sm-8">
	                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_syllabus_ans")?>" name="submit" >
	                        </div>
	                    </div>
	                </form>
	            </div>
            <?php } ?>
        </div>
    </div>
</div>

<script>


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
        $(".image-preview-input-title").text("<?=$this->lang->line('assignment_file_browse')?>"); 
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
            $(".image-preview-input-title").text("<?=$this->lang->line('assignment_file_browse')?>");
            $(".image-preview-clear").show();
            $(".image-preview-filename").val(file.name);
        }        
        reader.readAsDataURL(file);
    });  
});


</script>