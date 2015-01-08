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
                "Cari": function() {
                    get_list_rekap_renbut();
                    $('#rekap_dialog_renbut').dialog('destroy');
                }, "Cancel": function() {
                    $('#rekap_dialog_renbut').dialog('destroy');
                }
            }, close: function() {
                $('#rekap_dialog_renbut').dialog('destroy');
            }
        });
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
        window.location='<?= base_url('laporan/export_excel_renbut') ?>/?'+$('#form_search_renbut').serialize();
    });
});
function get_list_rekap_renbut(page, src, id) {
    $.ajax({
        url: '<?= base_url('laporan/manage_renbut') ?>/list/'+page,
        data: $('#form_search_renbut').serialize(),
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
            <button id="reload_rekap_renbut">Refresh</button>
            <button id="print_excel">Export Excel</button>
            <div id="result-rekap">

            </div>
        </div>
    </div>
    <div id="rekap_dialog_renbut" class="nodisplay"><form action="" id="form_search_renbut">
    <?= form_hidden('id_renbut', NULL, 'id=id_renbut') ?>
    <table width=100% cellpadding=0 cellspacing=0 class=inputan>
        <tr><td width=25%>Bulan Tahun:</td><td><select name="bln" id="bulan" style="width: 74px;"><?php foreach ($bulan as $bln) { ?> <option value="<?= $bln[0] ?>" <?= (($bln[0] === date("m"))?'selected':NULL) ?>><?= $bln[1] ?></option><?php } ?></select><select name="year" id="tahun" style="width: 74px;"><option value="">Select Year ....</option><?php for($i = 2010; $i <= date("Y"); $i++) { ?> <option value="<?= $i ?>" <?php if ($i == date("Y")) { echo "selected"; } ?>><?= $i ?></option><?php } ?></select></td></tr>
        <tr><td>Satuan Kerja:</td><td><select name="id_satker" id=id_satuan_kerja><option value="">Pilih Satker ...</option><?php foreach ($satker as $data) { ?><option value="<?= $data->id ?>"><?= $data->nama ?></option><?php } ?></select></td></tr>
    </table>
    </form></div>
</div>