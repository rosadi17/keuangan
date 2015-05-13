<title><?= $title ?></title>
<div class="titling"><h1><?= $title ?></h1></div>
<script type="text/javascript">
    $(function() {
        $('#tabs').tabs();
        //get_data_series();
        $('#cari_button_cashbon').button({
            icons: {
                secondary: 'ui-icon-search'
            }
        }).click(function() {
            get_data_series();
        });
        $('#reload_cashbon').button({
            icons: {
                secondary: 'ui-icon-refresh'
            }
        }).click(function() {
            $('#loaddata').load('<?= base_url('laporan/grafik') ?>');
        });
    });
    function get_data_series(){
        $.ajax({
            url: '<?= base_url('laporan/grafik_list') ?>',
            data: 'bulan='+$('#year').val()+'&id_satker='+$('#id_satker').val(),
            dataType: 'json',
            success: function(data) {
                if (data.ratarata !== '') {
                    draw_bar_chart(data);
                }
            }
        });
    }
    
    function draw_bar_chart(data){
        $('#container').highcharts({
           chart: {
           },
           exporting: {
               enabled: false
           },
           title: {
               text: 'Grafik Penggunaan Anggaran'
           },
           subtitle: {
               text: 'Satker '+data.satker
           },
           xAxis: {
               categories: data.bulan
           },
           tooltip: {
                formatter: function() {
                    var s;
                    if (this.point.name) { // the pie chart
                        s = ''+
                            this.point.name +': '+ this.y +' fruits';
                    } else {
                        s = ''+
                            this.x  +': Rp. '+ numberToCurrency(this.y);
                    }
                    return s;
                }
            },
            series: [
                {
                 type: 'column',
                 name: 'Realisasi',
                 data: data.realisasi
                },{
                 type: 'column',
                 name: 'Pagu Perbulan',
                 data: data.ratarata
            }]
       });
   }
</script>
<div class="kegiatan">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Entri Rencana Kebutuhan</a></li>
        </ul>
        <div id="tabs-1">

        <div class="inputan">
            <table width="100%" cellspacing="0">
                <tr><td width=10%>Tahun:</td><td><select name="year" id="year" style="width: 72px;"><option value="">Select Year ....</option><?php for($i = 2010; $i <= date("Y"); $i++) { ?> <option value="<?= $i ?>" <?php if ($i == date("Y")) echo "selected"; else echo ""; ?>><?= $i ?></option><?php } ?></select></td></tr>
                <tr><td>Satuan Kerja:</td><td><select name=id_satker id=id_satker><option value="">Pilih Satker ...</option><?php foreach ($satker as $data) { ?><option value="<?= $data->id ?>" <?= ($data->id == 1)?'selected':NULL ?>><?= $data->nama ?></option><?php } ?></select></td></tr>
                <tr><td></td><td>
                    <button class="btn" id="cari_button_cashbon"><i class="fa fa-eye"></i> Tampilkan Grafik</button>
                    <button class="btn" id="reload_cashbon"><i class="fa fa-refresh"></i> Refresh</button>
                </td></tr>
            </table>
        </div><br/><br/>
        <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        </div>
    </div>
</div>