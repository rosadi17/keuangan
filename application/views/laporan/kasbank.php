<title><?= $title ?></title>
<div class="titling"><h1><?= $title ?></h1></div>
<script type="text/javascript">
    $(function() {
        $('#tabss').tabs();
        $('#cari_button_cashbon').button({
            icons: {
                secondary: 'ui-icon-search'
            }
        }).click(function() {
            get_data_kas_bank();
        });
        $('#reload_cashbon').button({
            icons: {
                secondary: 'ui-icon-refresh'
            }
        }).click(function() {
            $('#loaddata').load('<?= base_url('laporan/kasbank') ?>');
        });
    });
    
    function get_data_kas_bank() {
        $.ajax({
            url: '<?= base_url('laporan/get_data_kas_bank') ?>',
            data: 'tahun='+$('#year').val()+'-'+$('#bln').val(),
            success: function(data) {
                $('#result').html(data);
            }
        });
    }
</script>
<div class="kegiatan">
    <div id="tabss">
        <ul>
            <li><a href="#tabss-1">Parameter</a></li>
        </ul>
        <div id="tabss-1">
        <table width="100%" cellspacing="0" class="inputan">
            <tr><td width=10%>Tahun:</td><td><select name=bln id=bln style="width: 74px;"><?php foreach ($bulan as $bln) { ?> <option value="<?= $bln[0] ?>" <?= (($bln[0] == date("m"))?'selected':NULL) ?>><?= $bln[1] ?></option><?php } ?></select><select name="year" id="year" style="width: 72px;"><option value="">Select Year ....</option><?php for($i = 2010; $i <= date("Y"); $i++) { ?> <option value="<?= $i ?>" <?php if ($i == date("Y")) echo "selected"; else echo ""; ?>><?= $i ?></option><?php } ?></select></td></tr>
            <tr><td></td><td>
                <button id="cari_button_cashbon">Tampilkan</button>
                <button id="reload_cashbon">Refresh</button>
            </td></tr>
        </table>

        <div id="result">

        </div>
        </div>
    </div>
</div>