<?php 
    $this->load->view('home/public/top');
?>
<!-- MENU SECTION END-->
<div class="content-wrapper" style="min-height:600px;">
    <div class="container">
        <hr>
        <div class="row">
            <?php 
              $this->load->view('home/public/info_nav');
            ?>
             <div class="col-md-9 col-sm-12 col-xs-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        博客统计 
                    </div>
                    <div class="panel-body">
                       <div id="calendar-eachart" style="width:100%;height:230px;"></div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
<script type="text/javascript" src="<?=HOME_PUBLIC_URL?>js/echarts.min.js"></script>
<script type="text/javascript"> 
    var myChart = echarts.init(document.getElementById('calendar-eachart'));

    option = {
        title: {
            top: 10,
            left: 'center',
            text: '博客文章统计(近12个月)'
        },
        tooltip: {
            formatter(params){
                return params.data[0]+' : '+params.data[1];
            }
        },
        visualMap: {
            min: 0,
            max: 4,
            type: 'piecewise',
            orient: 'horizontal',
            left: 'center',
            color:['#006230','#009B46','#59CB74','#BEE58D','#EBEEF1'],
            textStyle: {
                color: '#000'
            },
            show:false
        },
        calendar: {
            top: 65,
            left: 30,
            right: 30,
            cellSize: [0, 15],
            range: ['<?=$startDate;?>','<?=$endDate;?>'],
            itemStyle: {
                borderWidth: 2.5,
                borderColor: 'rgb(255, 255, 255)'
            },
            dayLabel:{nameMap: 'cn'},
            monthLabel:{nameMap: 'cn'},
            yearLabel: {show: false}
        },
        series: {
            type: 'heatmap',
            coordinateSystem: 'calendar',
            data: JSON.parse('<?=$json_data;?>')
        }
    };


    myChart.setOption(option);
</script>
<?php
    $this->load->view('home/public/footer');
?>