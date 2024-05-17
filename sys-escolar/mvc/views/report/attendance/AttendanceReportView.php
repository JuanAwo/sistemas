
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-attendancereport"></i> <?=$this->lang->line('report_attendance')?> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_report')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">

            <div class="col-sm-12">
                <div class="form-group col-sm-2" id="attendancetypeDiv">
                    <label><?=$this->lang->line("report_attendancetype")?></label>
                    <?php
                         $array = array(
                             "0" => $this->lang->line("report_select_attendancetype"),
                             "A" => $this->lang->line("report_absent"),
                             "P" => $this->lang->line("report_present")

                         );
                         echo form_dropdown("attendancetype", $array, set_value("attendancetype"), "id='attendancetype' class='form-control'");
                     ?>
                </div>

                <div class="form-group col-sm-2" id="classesDiv">
                    <label><?=$this->lang->line("report_class")?></label>
                    <?php
                         $array = array("0" => $this->lang->line("report_select_class"));
                         foreach ($classes as $classa) {
                             $array[$classa->classesID] = $classa->classes;
                         }
                         echo form_dropdown("classesID", $array, set_value("classesID"), "id='classesID' class='form-control'");
                     ?>
                </div>

                <div class="form-group col-sm-2" id="sectionDiv">
                    <label><?=$this->lang->line("report_section")?></label>
                    <select id="sectionID" name="sectionID" class="form-control">
                        <option value=""><?php echo $this->lang->line("report_select_section"); ?></option>
                    </select>
                </div>

                <?php if($subjectWise) {?>
                    <div class="form-group col-sm-2" id="subjectDiv">
                        <label><?=$this->lang->line("report_subject")?></label>
                        <select id="subjectID" name="subjectID" class="form-control">
                            <option value=""><?php echo $this->lang->line("report_select_subject"); ?></option>
                        </select>
                    </div>
                <?php }?>

                <div class="form-group col-sm-2" id="dateDiv">
                    <label><?=$this->lang->line("report_date")?></label>
                    <input class="form-control" name="date" id="date" value="" type="text">
                </div>

                <div class="col-sm-4">
                    <button id="get_attendancereport" class="btn btn-success" style="margin-top:23px;"> <?=$this->lang->line("report_submit")?></button>
                    <button class="btn btn-danger" style="margin-top:23px;" onclick="javascript:printDiv('printablediv')"><span class="fa fa-print"></span> <?=$this->lang->line('report_print')?> </button>
                </div>

            </div>

        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<div class="box" id="load_attendance_report"></div>


<script type="text/javascript">
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

    function divHide() {
        $("#classesDiv").hide("slow");
        $("#sectionDiv").hide("slow");
        $("#dateDiv").hide("slow");
        $("#subjectDiv").hide("slow");
    }

    function divShow() {
        $("#classesDiv").show("slow");
        $("#sectionDiv").show("slow");
        $("#dateDiv").show("slow");
        $("#subjectDiv").show("slow");
    }

    $(function(){
        $("#attendancetype").val('0');
        $("#classesID").val(0);
        divHide();
    });
    $("#date").datepicker();

    $("#attendancetype").change(function() {
        var type = $(this).val();
        if(type === '0') {
            $('#attendancetypeDiv').addClass('has-error');
            divHide();
        } else {
            $('#attendancetypeDiv').removeClass('has-error');
            divShow();

        }
    });

    $("#classesID").change(function() {
        var id = $(this).val();
        if(parseInt(id)) {
            if(id === '0') {
                $('#sectionID').val(0);
            } else {
                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('report/getSection')?>",
                    data: {"id" : id},
                    dataType: "html",
                    success: function(data) {
                       $('#sectionID').html(data);
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('report/getSubject')?>",
                    data: {"classID" : id},
                    dataType: "html",
                    success: function(data) {
                       $('#subjectID').html(data);
                    }
                });
            }
        }
    });

    $("#get_attendancereport").click(function() {
        var type = $('#attendancetype').val();
        var classID = $('#classesID').val();
        var sectionID = $('#sectionID').val();
        var dateValue = $('#date').val();
        var subjectID = $('#subjectID').val();
        var passData = {"classID" : classID, 'sectionID': sectionID, 'date': dateValue, 'type': type};

        if(parseInt(subjectID)) {
            passData = {"classID" : classID, 'sectionID': sectionID, 'date': dateValue, 'type': type, 'subjectID': subjectID};
        }

        if(parseInt(classID) && (type == 'A' || type == 'P') && ( parseInt(sectionID) || parseInt(sectionID) == 0 ) && dateValue != "") {
            $('#classesDiv').removeClass('has-error');
            $.ajax({
                type: 'POST',
                url: "<?=base_url('report/getAttendacneReport')?>",
                data: passData,
                dataType: "html",
                success: function(data) {
                   $('#load_attendance_report').html(data);
                }
            });
        } else {
            if(parseInt(classID) == 0) {
                $('#classesDiv').addClass('has-error');
            } else {
                $('#classesDiv').removeClass('has-error');
            }

            if(parseInt(type) == 0) {
                $('#attendancetypeDiv').addClass('has-error');
            } else {
                $('#attendancetypeDiv').removeClass('has-error');
            }

            if(dateValue == '') {
                $('#dateDiv').addClass('has-error');
            } else {
                $('#dateDiv').removeClass('has-error');
            }



        }
    });
</script>
