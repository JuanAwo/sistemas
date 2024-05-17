
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-gears"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_setting')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                    <?php
                        if(form_error('sname'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="sname" class="col-sm-2 control-label ">
                            <?=$this->lang->line("setting_school_name")?>
                        </label>

                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="sname" name="sname" value="<?=set_value('sname', $setting->sname)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('sname'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('phone'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="phone" class="col-sm-2 control-label">
                            <?=$this->lang->line("setting_school_phone")?>
                            &nbsp;
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="phone" name="phone" value="<?=set_value('phone', $setting->phone)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('phone'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('email'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="email" class="col-sm-2 control-label">
                            <?=$this->lang->line("setting_school_email")?>
                            
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="email" name="email" value="<?=set_value('email', $setting->email)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('email'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('auto_invoice_generate') || form_error('automaiton'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="auto_invoice_generate" class="col-sm-2 control-label">
                            <?=$this->lang->line("setting_school_auto_invoice_generate")?>
                            
                        </label>
                        <div id="autoinvoicediv" class="">
                            <?php
                                $array = array(
                                    "0" => $this->lang->line("setting_school_no"),
                                    "1" => $this->lang->line("setting_school_yes")
                                );
                                echo form_dropdown("auto_invoice_generate", $array, set_value("auto_invoice_generate",$setting->auto_invoice_generate), "id='auto_invoice_generate' class='form-control select2'");
                            ?>
                        </div>
                        <div class="col-sm-3">
                            <?php
                                $dayArray = array();
                                for($i =1; $i<=28; $i++) {
                                    $dayArray[$i] = $i;
                                }
                                echo form_dropdown("automation", $dayArray, set_value("automation",$setting->automation), "id='automation' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php 
                                if(form_error('auto_invoice_generate')) 
                                    echo form_error('auto_invoice_generate');
                                else 
                                    form_error('automation'); 
                            ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('currency_code'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="currency_code" class="col-sm-2 control-label">
                            <?=$this->lang->line("setting_school_currency_code")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="currency_code" name="currency_code" value="<?=set_value('currency_code', $setting->currency_code)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('currency_code'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('currency_symbol'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="currency_symbol" class="col-sm-2 control-label">
                            <?=$this->lang->line("setting_school_currency_symbol")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="currency_symbol" name="currency_symbol" value="<?=set_value('currency_symbol', $setting->currency_symbol)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('currency_symbol'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('footer'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="footer" class="col-sm-2 control-label">
                            <?=$this->lang->line("setting_school_footer")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="footer" name="footer" value="<?=set_value('footer', $setting->footer)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('footer'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('address'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="address" class="col-sm-2 control-label">
                            <?=$this->lang->line("setting_school_address")?>
                        </label>
                        <div class="col-sm-6">
                            <textarea class="form-control" style="resize:none;" id="address" name="address"><?=set_value('address', $setting->address)?></textarea>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('address'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('lang'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="lang" class="col-sm-2 control-label">
                            <?=$this->lang->line("setting_school_lang")?>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                echo form_dropdown("language", array("spanish" => $this->lang->line("setting_spanish"),
                                
                                "portuguese" => $this->lang->line("setting_portuguese"),
                                "english" => $this->lang->line("setting_english"),
                                ),
                                set_value("lang", $setting->language), "id='lang' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('lang'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('attendance'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="attendance" class="col-sm-2 control-label">
                            <?=$this->lang->line("setting_school_default_attendance")?>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $array = array("0" => $this->lang->line("setting_school_select_attendance"),
                                          "day" => $this->lang->line("setting_school_select_day_attendance"),
                                          "subject" => $this->lang->line("setting_school_select_subject_attendance")
                                );
                                echo form_dropdown("attendance", $array, set_value("attendance",$setting->attendance), "id='attendance' class='form-control select2'");
                            ?>

                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('attendance'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('school_year'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="school_year" class="col-sm-2 control-label">
                            <?=$this->lang->line("setting_school_default_school_year")?>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $array = array("0" => $this->lang->line("setting_school_select_school_year")  
                                );

                                if(count($schoolyears)) {
                                    foreach ($schoolyears as $key => $schoolyear) {
                                        if($schoolyear->schooltype == 'semesterbase') {
                                            $array[$schoolyear->schoolyearID] = $schoolyear->schoolyeartitle.' ('.$schoolyear->schoolyear.')'; 
                                        } else {
                                            $array[$schoolyear->schoolyearID] = $schoolyear->schoolyear;    
                                        }
                                    }
                                     
                                }

                                echo form_dropdown("school_year", $array, set_value("school_year",$setting->school_year), "id='school_year' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('school_year'); ?>
                        </span>
                    </div>
                    <?php
                        if(form_error('note'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="note" class="col-sm-2 control-label">
                            <?=$this->lang->line("setting_school_note")?>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $noteArray[0] = $this->lang->line('setting_school_no');
                                $noteArray[1] = $this->lang->line('setting_school_yes');
                                echo form_dropdown("note", $noteArray, set_value("note",$setting->note), "id='note' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('note'); ?>
                        </span>
                    </div>
                    <?php
                        if(form_error('photo'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="photo" class="col-sm-2 control-label">
                            <?=$this->lang->line("setting_school_photo")?>
                        </label>
                        <div class="col-sm-6">
                            <div class="input-group image-preview">
                                <input type="text" class="form-control image-preview-filename" disabled="disabled">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                        <span class="fa fa-remove"></span>
                                        <?=$this->lang->line('setting_clear')?>
                                    </button>
                                    <div class="btn btn-success image-preview-input">
                                        <span class="fa fa-repeat"></span>
                                        <span class="image-preview-input-title">
                                        <?=$this->lang->line('setting_file_browse')?></span>
                                        <input type="file" accept="image/png, image/jpeg, image/gif" name="photo"/>
                                    </div>
                                </span>
                            </div>
                        </div>

                        <span class="col-sm-4">
                            <?php echo form_error('photo'); ?>
                        </span>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label">
                            <?=$this->lang->line("setting_school_marksetting")?>
                        </label>
                        <div class="col-sm-10">
                        <?php
                            if(count($markpercentages)) {
                                foreach ($markpercentages as $key => $markpercentage) {
                                    $checkbox = '';
                                    $compress = 'mark_'.str_replace(' ', '', $markpercentage->markpercentageID);

                                    if(isset($settingarray[$compress])) {
                                        if($settingarray[$compress] == 1) {
                                            $checkbox = 'checked';
                                        } else {
                                            $checkbox = ''; 
                                        }
                                    }
                                    echo '<div class="checkbox">';
                                        echo '<label>';
                                            echo '<input type="checkbox" '.$checkbox.'  id="mark_'.str_replace(' ', '', $markpercentage->markpercentageID).'" value="1" name="mark_'.str_replace(' ', '', $markpercentage->markpercentageID).'"> &nbsp;';
                                            echo $markpercentage->markpercentagetype;
                                        echo '</label>';
                                    echo '</div>';

                                }
                            }
                        ?>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("update_setting")?>" >
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

<?php if($setting->auto_invoice_generate) { ?>
    $('#automation').show();
    $('#autoinvoicediv').addClass('col-sm-3');
<?php } else { ?>
    $('#automation').hide();
    $('#autoinvoicediv').addClass('col-sm-6');
<?php } ?> 

$('#auto_invoice_generate').change(function() {
    var aig = $(this).val();
    
    if(aig == 1) {
        $('#s2id_automation').show(1000);
        $("#auto_invoice_generate").fadeIn("slow", function() {
            $('#autoinvoicediv').removeClass('col-sm-6');
            $('#autoinvoicediv').addClass('col-sm-3');
        });
    } else {
        $('#s2id_automation').hide(1000);
        $("#auto_invoice_generate").fadeIn("slow", function() {
            $('#autoinvoicediv').removeClass('col-sm-3');
            $('#autoinvoicediv').addClass('col-sm-6');
        });

        
    }
});

$(document).ready(function() {
    var schooltype = $('#school_type').val();
    if(schooltype) {
        $.ajax({
            type: 'POST',
            dataType: "json",
            url: "<?=base_url('setting/callschoolyear')?>",
            data: "schooltype=" + schooltype,
            dataType: "html",
            success: function(data) {
                var response = jQuery.parseJSON(data);
                $('#school_year').html(response.schoolyear);
                $('#student_ID_format').html(response.studentIDformat);
            }
        });
    }
});

$('#school_type').change(function() {
    var schooltype = $(this).val();
    if(schooltype) {
        $.ajax({
            type: 'POST',
            dataType: "json",
            url: "<?=base_url('setting/callschoolyear')?>",
            data: "schooltype=" + schooltype,
            dataType: "html",
            success: function(data) {
                var response = jQuery.parseJSON(data);
                $('#school_year').html(response.schoolyear);
                $('#student_ID_format').html(response.studentIDformat);
            }
        });
    }
});


$(document).on('click', '#close-preview', function(){ 
    $('.image-preview').popover('hide');
    // Hover befor close the preview
    $('.image-preview').hover(
        function () {
           $('.image-preview').popover('show');
           $('.content').css('padding-bottom', '120px');
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
        $(".image-preview-input-title").text("<?=$this->lang->line('setting_file_browse')?>"); 
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
            $(".image-preview-input-title").text("<?=$this->lang->line('setting_clear')?>");
            $(".image-preview-clear").show();
            $(".image-preview-filename").val(file.name);            
            img.attr('src', e.target.result);
            $(".image-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
            $('.content').css('padding-bottom', '120px');
        }        
        reader.readAsDataURL(file);
    });  
});

$( ".select2" ).select2( { placeholder: "", maximumSelectionSize: 6 } );
</script>

