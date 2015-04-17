<title><?= $title ?></title>
<div class="titling"><h1><?= $title ?></h1></div>
<?= $this->load->view('message') ?>
<script type="text/javascript">
$(function() {
    $('#tabx').tabs();
    get_list_rekap_renbut(1);
    $('#cari_rekap_button').button({
        icons: {
            secondary: 'ui-icon-search'
        }
    }).click(function() {
        $('#rekap_dialog_renbut').dialog({
            title: 'Cari Renbut',
            autoOpen: true,
            width: 480,
            autoResize:true,
            modal: true,
            hide: 'explode',
            show: 'blind',
            position: ['center',47],
            buttons: {
                "Cancel": function() {
                    $('#rekap_dialog_renbut').dialog('destroy');
                },
                "Cari": function() {
                    get_list_rekap_renbut();
                    $('#rekap_dialog_renbut').dialog('destroy');
                }
            }, close: function() {
                $('#rekap_dialog_renbut').dialog('destroy');
            }, open: function() {
                $('#awal_lrenbut, #akhir_lrenbut, #awal_keg, #akhir_keg').datepicker('hide');
                $('#jenis_renbut').focus();
            }
        });
    });
    $('#awal_lrenbut, #akhir_lrenbut, #awal_keg, #akhir_keg').datepicker({
        changeYear: true,
        changeMonth: true
    });
    $('#reload_rekap_renbut').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        get_list_rekap_renbut(1);
    });
    $('#print_excel').button({
        icons: {
            secondary: 'ui-icon-print'
        }
    }).click(function() {
        window.location='<?= base_url('laporan/export_excel_renbut') ?>/?'+$('#search_renbut').serialize();
    });
});
function get_list_rekap_renbut(page, src) {
    $.ajax({
        url: '<?= base_url('laporan/manage_renbut') ?>/list/'+page,
        data: $('#search_renbut').serialize(),
        cache: false,
        success: function(data) {
            $('#result-rekap').html(data);
        }
    });
}


//function print_renbut(id) {
//    var wWidth = $(window).width();
//    var dWidth = wWidth * 1;
//    var wHeight= $(window).height();
//    var dHeight= wHeight * 1;
//    var x = screen.width/2 - dWidth/2;
//    var y = screen.height/2 - dHeight/2;
//    window.open('<?= base_url('transaksi/manage_renbut') ?>/print?id='+id, 'Renbut Cetak', 'width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
//}

function paging(page, tab, search) {
    get_list_rekap_renbut(page, search);
}
</script>
<div class="kegiatan">
    <div id="tabx">
        <ul>
            <li><a href="#tabs-1"><?= $title ?></a></li>
        </ul>
        <div id="tabs-1">
            <button id="cari_rekap_button">Cari Data</button>
            <button id="print_excel">Export Excel</button>
            <button id="reload_rekap_renbut">Reload Data</button>
            <div id="result-rekap">

            </div>
        </div>
    </div>
    <div id="rekap_dialog_renbut" class="nodisplay">
        <form action="" id="search_renbut">
        <table width=100% cellpadding=0 cellspacing=0 class=inputan>
            <tr><td>Tanggal Renbut:</td><td><input type="text" name="awal" id="awal_lrenbut" value="<?= date("01/m/Y") ?>" size="10" /> s.d <input type="text" name="akhir" id="akhir_lrenbut" value="<?= date("d/m/Y") ?>" /></td></tr>
            <tr><td>Tanggal Kegiatan:</td><td><input type="text" name="awal_keg" id="awal_keg" value="" size="10" /> s.d <input type="text" name="akhir_keg" id="akhir_keg" value="" /></td></tr>
            <tr><td>Jenis Renbut:</td><td><select name=jenis_renbut id="jenis_renbut"><option value="">Semua ...</option><option value="murni">Murni Renbut</option><option value="cashbon">Dari Cashbon</option></select></td></tr>
            <tr><td>Kegiatan:</td><td><input type="text" name="kegiatan" id="kegiatan" /></td></tr>
            <tr><td>Satuan Kerja:</td><td><select name=id_satker id=id_satker><option value="">Pilih Satker ...</option><?php foreach ($satker as $data) { ?><option value="<?= $data->id ?>"><?= $data->nama ?></option><?php } ?></select></td></tr>
        </table>
        </form>
    </div>
</div>