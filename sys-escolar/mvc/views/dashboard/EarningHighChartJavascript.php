<script type="application/javascript">
$(function() {
    LoadEarningMonthly();
    function LoadEarningMonthly()
    {

        $( '#earning_top_right_graph_title').html("<?=$this->lang->line("dashboard_earning_summary")?>");
        $( "#earning_top_right_graph_back_btn" ).hide();
        $('#earningGraph').highcharts({
            chart: {
                type: 'areaspline'
            },
            title: {
                text: '<?=date('Y')?> <?=$this->lang->line("dashboard_earning_summary")?>'
            },
            subtitle: {
                text: '<?=$this->lang->line("dashboard_earning_summary_subtitle")?>'
            },
            xAxis: {
                categories: [
                    <?php
                        echo implode(',', pluck_bind($months, NULL, "'", "'"));
                    ?>
                ],
                title: {
                    text: '<?=$this->lang->line("dashboard_month")?>',
                    align: 'low'
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: '<?=$this->lang->line("dashboard_amount")?>',
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
                                LoadDayWiseExpenseOrIncome(this.type, this.monthID, this.monthName, this.dayWiseData);
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
                name: '<?=$this->lang->line("dashboard_income")?>',
                data: [
                    <?php
                        foreach ($months as $key => $month) {
                            if(isset($incomeMonthTotal[$key])) {
                                echo "{y:".$incomeMonthTotal[$key].", monthID:'".$key."', monthName:'".$month."', 'dayWiseData': '".json_encode($incomeMonthAndDay[$key])."', 'type': 'income'},";
                            } else {
                                 echo "{y:0},";
                            }
                        }
                    ?>
                ],
                color: 'rgb(92,184,92)'
            },{
                name: '<?=$this->lang->line("dashboard_expense")?>',
                data: [
                    <?php
                        foreach ($months as $key => $month) {
                            if(isset($expenseMonthTotal[$key])) {
                                echo "{y:".$expenseMonthTotal[$key].", monthID:'".$key."', monthName:'".$month."', 'dayWiseData': '".json_encode($expenseMonthAndDay[$key])."', 'type': 'expense'},";
                            } else {
                                 echo "{y:0},";
                            }
                        }
                    ?>
                ],
                color: 'rgb(217,83,79)'
            }]
        });
    }

    function LoadDayWiseExpenseOrIncome(type, monthID, monthName, dayWiseData)
    {
        // alert($.parseJSON(dayWiseData)[22]);
        // console.log($.parseJSON(dayWiseData)[22]);
        $( '#earning_top_right_graph_title').html(monthName+" <?=strtolower($this->lang->line("dashboard_month"))?> "+type);
        $( "#earning_top_right_graph_back_btn" ).show();
        $( "#earning_top_right_graph_back_btn" ).unbind( "click" );
        $( "#earning_top_right_graph_back_btn" ).on( "click",  function() {
            LoadEarningMonthly();
        });
        var categories = [];
        var series = [];
        var chartDayWiseData = [];
        var color = '#000';
        var tooltipTitle = '';

        if(type == 'income') {
            color = 'rgb(92,184,92)';
            tooltipTitle = 'Income';
        } else {
            color = 'rgb(217,83,79)';
            tooltipTitle = 'Expense';
        }

        // $.each(dayWiseData, function (i, value) {
        //     chartDayWiseData.push(value);
        // });

        $.ajax({
            type: 'POST',
            url: "<?=base_url('dashboard/dayWiseExpenseOrIncome')?>",
            data: {"dayWiseData" : dayWiseData, 'type': type, 'monthID': monthID, 'monthName': monthName},
            dataType: "html",
            success: function(data) {
                data = $.parseJSON(data);
                // // console.log(data);
                $.each(data, function (i, value) {
                    // console.log(i);
                    categories.push('Day '+i);
                    chartDayWiseData.push(value);
                });
                $('#earningGraph').highcharts({
                    chart: {
                        type: 'areaspline',
                        events: {
                            drillup: function (e) {
                                alert('drill Up');
                                console.log(this);
                                console.log(this.options.series[0].name);
                                console.log(this.options.series[0].data[0].name);
                            }
                        }
                    },
                    title: {
                        text: monthName+" <?=strtolower($this->lang->line("dashboard_month"))?> "+type
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
                            text: '<?=$this->lang->line("dashboard_amount")?>',
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
                        x: 0,
                        y: 0,
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
                                x: -40,
                                onclick: function () {
                                   LoadEarningMonthly();
                                },
                                text: "<< Back",
                            }
                        }
                    },
                    series: [{
                        name: tooltipTitle,
                        data: chartDayWiseData,
                        color: color
                    }]
                });
            }
        });
    }

});


</script>
