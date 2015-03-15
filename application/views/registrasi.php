<title>Home | Ubhara</title>
<div class="titling"><h1>Home</h1></div>
<script type="text/javascript">
    $(function() {
        $('#tabs').tabs();
        get_data_series();
        
    });
    function get_data_series(){
        $.ajax({
            url: '<?= base_url('laporan/grafik_home') ?>',
            data: '',
            dataType: 'json',
            success: function(data) {
                draw_pie_chart('#result',data);
            }
        });
    }
    
    function draw_pie_chart(div, data) {
        $(div).highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: data.title
            },
            tooltip: {
                pointFormat: 'Rp. {point.y} ({point.percentage:.1f} %)'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        format: '<b>{point.name}</b><br/>{point.y} ({point.percentage:.1f} %)'
                    },
                    showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                name: data.kategori,
                data: data.data
            }]
        });
    }
</script>
<div id="result" style="width: 50%;"></div>