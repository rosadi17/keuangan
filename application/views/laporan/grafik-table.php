<script type="text/javascript">
    function get_data_series(url){
        $.ajax({
            url: '<?= base_url("'+url+'") ?>',
            dataType: 'json',
            success: function(data) {
                draw_bar_chart(data);
            }
        });
    }
    
    function draw_bar_chart(data){
        $('#container').highcharts({
           chart: {
               type: 'bar'
           },
           exporting: {
               enabled: false
           },
           title: {
               text: 'Grafik Penggunaan Anggaran'
           },
           subtitle: {
               text: '<?= date("F Y") ?>'
           },
           xAxis: {
               categories: data.barang
           },
           yAxis: {
               title: {
                   text: 'Dalam %'
               }
           },
           series: [{
               data: data.persen
           }]
       });
   }
//    $('#container').highcharts({
//        chart: {
//        },
//        title: {
//            text: 'Combination chart'
//        },
//        xAxis: {
//            categories: ['Apples', 'Oranges', 'Pears', 'Bananas', 'Plums']
//        },
//        tooltip: {
//            formatter: function() {
//                var s;
//                if (this.point.name) { // the pie chart
//                    s = ''+
//                        this.point.name +': '+ this.y +' fruits';
//                } else {
//                    s = ''+
//                        this.x  +': '+ this.y;
//                }
//                return s;
//            }
//        },
//        labels: {
//            items: [{
//                html: 'Total fruit consumption',
//                style: {
//                    left: '40px',
//                    top: '8px',
//                    color: 'black'
//                }
//            }]
//        },
//        series: [{
//            type: 'column',
//            name: 'Jane',
//            data: [3, 2, 1, 3, 4]
//        }, {
//            type: 'column',
//            name: 'John',
//            data: [2, 3, 5, 7, 6]
//        }, {
//            type: 'column',
//            name: 'Joe',
//            data: [4, 3, 3, 9, 0]
//        }, {
//            type: 'spline',
//            name: 'Average',
//            data: [3, 2.67, 3, 6.33, 3.33],
//            marker: {
//                    lineWidth: 2,
//                    lineColor: Highcharts.getOptions().colors[3],
//                    fillColor: 'white'
//            }
//        }]
//    });
</script>
