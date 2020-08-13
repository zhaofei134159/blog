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
                       <div id="calendar-eachart" style="width:100%;height:400px;"></div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
<script type="text/javascript" src="<?=HOME_PUBLIC_URL?>js/echarts.min.js"></script>
<script type="text/javascript"> 
    var myChart = echarts.init(document.getElementById('calendar-eachart'));
    function getVirtulData(year) {
        year = year || '2017';
        var date = +echarts.number.parseDate(year + '-01-01');
        var end = +echarts.number.parseDate((+year + 1) + '-01-01');
        var dayTime = 3600 * 24 * 1000;
        var data = [];
        for (var time = date; time < end; time += dayTime) {
            data.push([
                echarts.format.formatTime('yyyy-MM-dd', time),
                Math.floor(Math.random() * 10000)
            ]);
        }
        return data;
    }

    option = {
        title: {
            top: 10,
            left: 'center',
            text: '博客文章统计(近12个月)'
        },
        tooltip: {
            formatter: '{c0}'
        },
        visualMap: {
            min: 0,
            max: 10000,
            type: 'piecewise',
            orient: 'horizontal',
            left: 'center',
            color:['#006230','#009B46','#BEE58D'],
            textStyle: {
                color: '#000'
            },
            show:false
        },
        calendar: {
            top: 65,
            left: 30,
            right: 30,
            cellSize: ['auto', 13],
            range: '2016',
            itemStyle: {
                borderWidth: 0.5
            },
            yearLabel: {show: true}
        },
        series: {
            type: 'heatmap',
            coordinateSystem: 'calendar',
            data: getVirtulData(2016)
        }
    };


    myChart.setOption(option);
</script>
<?php
    $this->load->view('home/public/footer');
?>