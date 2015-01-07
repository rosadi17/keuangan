<title><?= $title ?></title>
<div class="titling"><h1><?= $title ?></h1></div>
<?= $this->load->view('message') ?>
<script type="text/javascript">
$(function() {
    $('#tabs').tabs();
    get_list_realisasi(1);
    $('#add_realisasi').button({
        icons: {
            secondary: 'ui-icon-newwin'
        }
    }).click(function() {
        form_realisasi();
    });
    $('#cari_button').button({
        icons: {
            secondary: 'ui-icon-search'
        }
    }).click(function() {
        form_cari();
    });
    $('#reload_realisasi').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        get_list_realisasi();
    });
});
function get_list_realisasi(page, src, id) {
    var tahun = $('#year').val();
    if (tahun === undefined) {
        var year = '<?= date("Y") ?>';
    } else {
        year = tahun;
    }
    $.ajax({
        url: '<?= base_url('laporan/manage_realisasi') ?>/list/'+page,
        data: 'tahun='+year+'&satker='+$('#id_satker').val(),
        cache: false,
        success: function(data) {
            $('#result').html(data);
        }
    });
}

function form_cari() {
    var str = '<div id="dialog_realisasi"><form action="" id="save_realisasi">'+
            '<?= form_hidden('id_realisasi', NULL, 'id=id_realisasi') ?>'+
            '<table width=100% cellpadding=0 cellspacing=0 class=inputan>'+
                '<tr><td width=30%>Tahun:</td><td><select name="year" id="year" style="width: 74px;"><?php for($i = 2010; $i <= date("Y"); $i++) { ?> <option value="<?= $i ?>" <?php if ($i == date("Y")) { echo "selected"; } ?>><?= $i ?></option><?php } ?></select></td></tr>'+
                '<tr><td>Satuan Kerja:</td><td><select name=id_satker id=id_satker style="width: 150px;"><option value="">Pilih Satker ...</option><?php foreach ($satker as $data) { ?><option value="<?= $data->id ?>"><?= $data->nama ?></option><?php } ?></select></td></tr>'+
            '</table>'+
            '</form></div>';
    $(str).dialog({
        title: 'Form Pencarian',
        autoOpen: true,
        width: 400,
        height: 200,
        modal: true,
        hide: 'clip',
        show: 'blind',
        buttons: {
            "Cari": function() {
                get_list_realisasi();
            }, "Cancel": function() {
                $(this).dialog().remove();
            }
        }, close: function() {
            $(this).dialog().remove();
        }, open: function() {
            $('#uraian').focus();
        }
    });
}

function edit_realisasi(str) {
    var arr = str.split('#');
    form_realisasi();
    $('#id_realisasi').val(arr[0]);
    $('#uraian').val(arr[1]);
    $('#keterangan').val(arr[2]);
    $('#jml_realisasi').val(arr[3]);
    $('#penerima').val(arr[4]);
    $('#id_uraian').val(arr[5]);
    $('#tanggal').val(arr[6]);
    $('#dialog_realisasi').dialog({ title: 'Edit realisasi satuan kerja' });
}

function print_realisasi(id) {
    var wWidth = $(window).width();
    var dWidth = wWidth * 1;
    var wHeight= $(window).height();
    var dHeight= wHeight * 1;
    var x = screen.width/2 - dWidth/2;
    var y = screen.height/2 - dHeight/2;
    window.open('<?= base_url('laporan/manage_realisasi') ?>/print?id='+id, 'realisasi Cetak', 'width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
}

function paging(page, tab, search) {
    get_list_realisasi(page, search);
}
</script>
<div class="kegiatan">
    <button id="cari_button">Cari Data</button>
    <button id="reload_realisasi">Refresh</button>
    <div id="result" style="overflow-x: auto;">

    </div>
</div>