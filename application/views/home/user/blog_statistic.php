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
    var myChart = echarts.init(document.getElementById('main'));
    // 模拟数据
    function getVirtulData(year) {
        year = year || '2017';
        var date = +echarts.number.parseDate(year + '-01-01');
        var end = +echarts.number.parseDate(year + '-12-31');
        var dayTime = 3600 * 24 * 1000;
        var data = [];
        for (var time = date; time <= end; time += dayTime) {
            data.push([
                echarts.format.formatTime('yyyy-MM-dd', time),
                Math.floor(Math.random() * 10000)
            ]);
        }
        return data;
    }
    var option = {
        visualMap: {
            show: false,
            min: 0,
            max: 10000
        },
        calendar: {
            range: '2017'
        },
        series: {
            type: 'heatmap',
            coordinateSystem: 'calendar',
            data: getVirtulData(2017)
        }
    };
    myChart.setOption(option);
</script>
<?php
    $this->load->view('home/public/footer');
?>