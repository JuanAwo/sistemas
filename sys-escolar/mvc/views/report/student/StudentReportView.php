
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-studentreport"></i> <?=$this->lang->line('report_student')?> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_report')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">

            <div class="col-sm-12">
                <div class="form-group col-sm-4" id="reportDiv">
                    <label><?=$this->lang->line("report_report_for")?></label>
                    <?php
                         $array = array(
                             "0" => $this->lang->line("report_please_select"),
                             "blood" => $this->lang->line("report_blood_group"),
                             "country" => $this->lang->line("report_country"),
                             "gender" => $this->lang->line("report_gender"),
                             "transport" => $this->lang->line("report_transport"),
                             "hostel" => $this->lang->line("report_hostel")
                         );
                         echo form_dropdown("reportfor", $array, set_value("reportfor"), "id='reportfor' class='form-control'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="bloodDiv">
                    <label><?=$this->lang->line("report_blood_group")?></label>
                    <?php
                         $array = array(
                             "0" => $this->lang->line("report_please_select"),
                             "A+" => 'A+',
                             "A-" => 'A-',
                             "B+" => 'B+',
                             "B-" => 'B-',
                             "O+" => 'O+',
                             "O-" => 'O-',
                             "AB+" => 'AB+',
                             "AB-" => 'AB-',
                         );
                         echo form_dropdown("blood", $array, set_value("blood"), "id='blood' class='form-control'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="countryDiv">
                    <label><?=$this->lang->line("report_country")?></label>
                    <?php
                         $array = array(
                             "0" => $this->lang->line("report_please_select"),
                         );
                         foreach ($allcountry as $key => $value) {
                             $array[$key] = $value;
                         }
                         echo form_dropdown("country", $array, set_value("country"), "id='country' class='form-control'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="transportDiv">
                    <label><?=$this->lang->line("report_route")?></label>
                    <?php
                         $array = array(
                             "0" => $this->lang->line("report_please_select"),
                         );
                         foreach ($transports as $key => $value) {
                             $array[$value->transportID] = $value->route;
                         }
                         echo form_dropdown("transport", $array, set_value("transport"), "id='transport' class='form-control'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="hostelDiv">
                    <label><?=$this->lang->line("report_hostel")?></label>
                    <?php
                         $array = array(
                             "0" => $this->lang->line("report_please_select"),
                         );
                         foreach ($hostels as $key => $value) {
                             $array[$value->hostelID] = $value->name;
                         }
                         echo form_dropdown("hostel", $array, set_value("hostel"), "id='hostel' class='form-control'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="genderDiv">
                    <label><?=$this->lang->line("report_gender")?></label>
                    <?php
                         $array = array(
                             "0" => $this->lang->line("report_please_select"),
                             "Male" => $this->lang->line("report_male"),
                             "Female" => $this->lang->line("report_female"),
                         );
                         echo form_dropdown("gender", $array, set_value("gender"), "id='gender' class='form-control'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="schoolorclassDiv">
                    <label><?=$this->lang->line("report_school_or_class")?></label>
                    <?php
                         $array = array(
                             "0" => $this->lang->line("report_please_select"),
                             "school" => $this->lang->line("report_school"),
                             "class" => $this->lang->line("report_class"),
                         );
                         echo form_dropdown("schoolorclass", $array, set_value("schoolorclass"), "id='schoolorclass' class='form-control'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="classDiv">
                    <label><?=$this->lang->line("report_class")?></label>
                    <?php
                         $array = array("0" => $this->lang->line("report_select_class"));
                         foreach ($classes as $classa) {
                             $array[$classa->classesID] = $classa->classes;
                         }
                         echo form_dropdown("classesID", $array, set_value("classesID"), "id='classesID' class='form-control'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="sectionDiv">
                    <label><?=$this->lang->line("report_section")?></label>
                    <select id="sectionID" name="sectionID" class="form-control">
                        <option value=""><?php echo $this->lang->line("report_select_section"); ?></option>
                    </select>
                </div>

                <div class="col-sm-4">
                    <button id="get_classreport" class="btn btn-success" style="margin-top:23px;"> <?=$this->lang->line("report_submit")?></button>
                    <button class="btn btn-danger" style="margin-top:23px;" onclick="javascript:printDiv('printablediv')"><span class="fa fa-print"></span> <?=$this->lang->line('report_print')?> </button>
                </div>

            </div>

        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<div class="box" id="load_studentreport"></div>


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

    $(function(){
        $("#classesID").val(0);
        $("#reportfor").val(0);
        $("#schoolorclass").val(0);
        $("#bloodDiv").hide();
        $("#countryDiv").hide();
        $("#transportDiv").hide();
        $("#hostelDiv").hide();
        $("#genderDiv").hide();
        $("#classDiv").hide();
        $("#sectionDiv").hide();
    });

    $("#reportfor").change(function() {
        var reportfor = $(this).val();
        if(reportfor == 'blood') {
            $("#bloodDiv").show("slow");
            $("#countryDiv").hide();
            $("#genderDiv").hide();
            $("#transportDiv").hide();
            $("#hostelDiv").hide();
        } else if(reportfor == 'country') {
            $("#bloodDiv").hide();
            $("#genderDiv").hide();
            $("#transportDiv").hide();
            $("#hostelDiv").hide();
            $("#countryDiv").show("slow");
        } else if(reportfor == 'gender') {
            $("#bloodDiv").hide();
            $("#countryDiv").hide();
            $("#transportDiv").hide();
            $("#hostelDiv").hide();
            $("#genderDiv").show("slow");
        } else if(reportfor == 'transport') {
            $("#bloodDiv").hide();
            $("#countryDiv").hide();
            $("#hostelDiv").hide();
            $("#genderDiv").hide();
            $("#transportDiv").show("slow");
        } else if(reportfor == 'hostel') {
            $("#bloodDiv").hide();
            $("#countryDiv").hide();
            $("#transportDiv").hide();
            $("#genderDiv").hide();
            $("#hostelDiv").show("slow");
        }
    });

    $("#schoolorclass").change(function() {
        var schoolorclass = $(this).val();
        var reportfor = $("#reportfor").val();
        if(reportfor != 0) {
            if(schoolorclass == 'class') {
                $("#classDiv").show("slow");
                $("#sectionDiv").show("slow");
            } else {
                $("#classDiv").hide("slow");
                $("#sectionDiv").hide("slow");
            }
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
            }
        }
    });

    function makingPostDataPreviousofAjaxCall(name) {
        $('#'+name+'Div').removeClass('has-error');
        $('#schoolorclassDiv').removeClass('has-error');
        $('#classDiv').removeClass('has-error');

        var schoolorclass = $("#schoolorclass").val();
        var option = $('#'+name).val();
        var classID = 0;
        var sectionID = -1;
        flag = 0;
        if(schoolorclass == 'class') {
            classID = $("#classesID").val();
            sectionID = $("#sectionID").val();
            if(classID != 0 && sectionID != -1) {
                flag = 1;
            }
        }

        passData = {"classID" : classID, 'sectionID': sectionID, 'reportfor': name, 'schoolorclass': schoolorclass, 'value': option}

        if(option != 0 && ( schoolorclass != 'class'  || ( schoolorclass == 'class' && flag==1 ) )) {
            ajaxCall(passData);
        } else {
            if(schoolorclass == 'class' && classID == 0) {
                $('#classDiv').addClass('has-error');
            } else {
                $('#classDiv').removeClass('has-error');
            }

            if(option == 0) {
                $('#'+name+'Div').addClass('has-error');
            } else {
                $('#'+name+'Div').removeClass('has-error');
            }
        }
    }

    function ajaxCall(passData) {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('report/getStudentReport')?>",
            data: passData,
            dataType: "html",
            success: function(data) {
               $('#load_studentreport').html(data);
            }
        });
    }

    $("#get_classreport").click(function() {
        var reportfor = $("#reportfor").val();
        var schoolorclass = $("#schoolorclass").val();
        var passData;
        var flag;

        if(reportfor != 0 && schoolorclass != 0) {
            makingPostDataPreviousofAjaxCall(reportfor);
            // if(reportfor == 'blood' || reportfor == 'country' || reportfor == 'gender') {
            //     makingPostDataPreviousofAjaxCall(reportfor);
            // }
        } else {
            if(parseInt(reportfor) == 0) {
                $('#reportDiv').addClass('has-error');
            } else {
                $('#reportDiv').removeClass('has-error');
            }

            if(parseInt(schoolorclass) == 0) {
                $('#schoolorclassDiv').addClass('has-error');
            } else {
                $('#schoolorclassDiv').removeClass('has-error');
            }

            if(reportfor == 0) {
                $('#'+reportfor+'Div').addClass('has-error');
            } else {
                $('#'+reportfor+'Div').removeClass('has-error');
            }
        }

    });
</script>
