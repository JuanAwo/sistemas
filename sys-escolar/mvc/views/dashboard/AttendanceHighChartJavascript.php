<script type="application/javascript">
$(function() {
    LoadClassWiseAttendance();
    function LoadClassWiseAttendance()
    {

        $( '#top_right_graph_title').html("Class Attendence Lang");
        $( "#top_right_graph_back_btn" ).hide();
        $('#attendanceGraph').highcharts({
            chart: {
                type: 'column',
                height: 320
            },
            title: {
                text: "<?=$this->lang->line("dashboard_student_today_attendance")?>"
            },
            subtitle: {
                text: '<?=$this->lang->line("dashboard_student_today_attendance_subtitle")?>'
            },
            xAxis: {
                categories: [
                    <?php
                        echo implode(',', pluck_bind($classes, 'classes', "'", "'"));
                    ?>
                ],
                title: {
                    text: '<?=$this->lang->line("dashboard_class")?>',
                    align: 'low'
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: '<?=$this->lang->line("dashboard_attendance")?>',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y}</b>'
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    }
                },
                series: {
                    cursor: 'pointer',
                    point: {
                        events: {
                            click: function (e) {
                                LoadDayWiseAttendance(this.type, this.classID, this.dayWiseAttendance);
                            }
                        }
                    }
                }
            },
            legend: {
                layout: 'vertical',
                align: 'left',
                verticalAlign: 'top',
                x: 5,
                y: -10,
                floating: true,
                borderWidth: 1,
                backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                shadow: true
            },
            credits: {
                enabled: false
            },
            series: [{
                name: '<?=$this->lang->line("dashboard_present")?>',
                data: [
                    <?php
                        foreach ($classes as $key => $value) {
                            if(isset($todaysAttendance[$key])) {
                                echo "{y:".$todaysAttendance[$key]['P'].", classID:'".$key."', 'dayWiseAttendance': '".json_encode($classWiseAttendance[$key])."', 'type': 'P'},";
                            } else {
                                 echo "{y:0},";
                            }
                        }
                    ?>
                ],
                color: 'rgb(92,184,92)'
            },{
                name: '<?=$this->lang->line("dashboard_absent")?>',
                data: [
                    <?php
                        foreach ($classes as $key => $value) {
                            if(isset($todaysAttendance[$key])) {
                                echo "{y:".$todaysAttendance[$key]['A'].", classID:'".$key."', 'dayWiseAttendance': '".json_encode($classWiseAttendance[$key])."', 'type': 'A'},";
                            } else {
                                 echo "{y:0},";
                            }
                        }
                    ?>
                ],
                color: 'rgb(33,92,135)'
            }]
        });
    }

    function LoadDayWiseAttendance(type, classID, dayWiseAttendance)
    {
        $( "#top_right_graph_back_btn" ).show();
        $( "#top_right_graph_back_btn" ).unbind( "click" );
        $( "#top_right_graph_back_btn" ).on( "click",  function() {
            LoadClassWiseAttendance();
        });
        var categories = [];
        var series = [];
        var attendance = [];
        var color = '#000';
        var attendanceTitle = '';
        if(type == 'P') {
            color = 'rgb(33,92,135)';
            attendanceTitle = '<?=$this->lang->line("dashboard_present")?>';
        } else {
            color = 'rgb(217,83,79)';
            attendanceTitle = '<?=$this->lang->line("dashboard_absent")?>';
        }

        $.ajax({
            type: 'POST',
            url: "<?=base_url('dashboard/getDayWiseAttendance')?>",
            data: {"dayWiseAttendance" : dayWiseAttendance, 'type': type},
            dataType: "html",
            success: function(data) {
                data = $.parseJSON(data);
                // console.log(data);
                $.each(data, function (i, value) {
                    // console.log(i);
                    categories.push('<?=$this->lang->line("dashboard_day")?> '+i);
                    attendance.push(value);
                });
                $('#attendanceGraph').highcharts({
                    chart: {
                        type: 'column',
                        height: 320
                    },
                    title: {
                        text: '<?=$this->lang->line("dashboard_student_this_month_daywise_attendance")?>'
                    },
                    subtitle: {
                        text: ''
                    },
                    xAxis: {
                        categories: categories,
                        title: {
                            text: null
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: '<?=$this->lang->line("dashboard_attendance")?>',
                            align: 'high'
                        },
                        labels: {
                            overflow: 'justify'
                        }
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.y}</b>'
                    },
                    plotOptions: {
                        bar: {
                            dataLabels: {
                                enabled: true
                            }
                        },
                        series: {
                            cursor: 'pointer',
                            point: {
                                events: {
                                    click: function (e) {
                                        // LoadGraphPerSchool(day,this.zone);
                                    }
                                }
                            }
                        }
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'left',
                        verticalAlign: 'top',
                        x: 5,
                        y: -10,
                        floating: true,
                        borderWidth: 1,
                        backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                        shadow: true
                    },
                    credits: {
                        enabled: false
                    },
                    exporting: {
                        buttons: {
                            customButton: {
                                text: "<< Back",
                                x: -40,
                                onclick: function () {
                                   LoadClassWiseAttendance();
                                }
                            }
                        }
                    },
                    series: [{
                        name: attendanceTitle,
                        data: attendance,
                        color: color
                    }]
                });
            }
        });
    }

    $.extend(Highcharts.Renderer.prototype.symbols, {
        anX: function (a,b,c,d){return["M",a,b,"L",a+c,b+d,"M",a+c,b,"L",a,b+d]},
        triangle: function (a,b,c,d){return["M",a,b,"L",a+c,b+c,a+c/2,d,"Z"]},
        exportIcon: function (a,b,c,d){return y(["M",a,b+c,"L",a+c,b+d,a+c,b+d*0.8,a,b+d*0.8,"Z","M",a+c*0.5,b+d*0.8,"L",a+c*0.8,b+d*0.4,a+c*0.4,b+d*0.4,a+c*0.4,b,a+c*0.6,b,a+c*0.6,b+d*0.4,a+c*0.2,b+d*0.4,"Z"])}
    });

});


</script>
