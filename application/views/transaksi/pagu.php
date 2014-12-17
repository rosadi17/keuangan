<title><?= $title ?></title>
<div class="titling"><h1><?= $title ?></h1></div>
<?= $this->load->view('message') ?>
<script type="text/javascript">
$(function() {
    $('#tabs').tabs();
    get_list_pagu(1);
    $('#add_pagu').button({
        icons: {
            secondary: 'ui-icon-newwin'
        }
    }).click(function() {
        form_pagu();
    });
    
    $('#reload_pagu').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        get_list_pagu();
        $('#tahun').val('');
    });
    $('#tahun').change(function() {
        var tahun = $(this).val();
        get_list_pagu('undefined', tahun);
    });
});
function get_list_pagu(page, src, id) {
    $.ajax({
        url: '<?= base_url('transaksi/manage_pagu') ?>/list/'+page,
        data: 'search='+src+'&id='+id,
        cache: false,
        success: function(data) {
            $('#result').html(data);
        }
    });
}

function form_pagu() {
    var str = '<div id="dialog_pagu"><form action="" id="save_pagu">'+
            '<?= form_hidden('id_pagu', NULL, 'id=id_pagu') ?>'+
            '<table width=100% cellpadding=0 cellspacing=0 class=data-input>'+
                '<tr><td width=25%>Tahun:</td><td><select name="year" id="year"><option value="">Pilih Tahun ....</option><?php for($i = 2010; $i <= date("Y")+1; $i++) { ?> <option value="<?= $i ?>" <?php if ($i == date("Y")) echo "selected";  ?>><?= $i ?></option><?php } ?></select></td></tr>'+
                '<tr><td>Satuan Kerja:</td><td><select name=id_satker id=id_satker><option value="">Pilih Satker ...</option><?php foreach ($satker as $data) { ?><option value="<?= $data->id ?>"><?= $data->nama ?></option><?php } ?></select></td></tr>'+
                '<tr><td>Jumlah Pagu (Rp.):</td><td><?= form_input('nama', NULL, 'id=nama size=40 onKeyup="FormNum(this);"') ?></td></tr>'+
            '</table>'+
            '</form></div>';
    $(str).dialog({
        title: 'Tambah pagu',
        autoOpen: true,
        width: 480,
        height: 200,
        modal: true,
        hide: 'clip',
        show: 'blind',
        buttons: {
            "Simpan": function() {
                $('#save_pagu').submit();
            }, "Cancel": function() {
                $(this).dialog().remove();
            }
        }, close: function() {
            $(this).dialog().remove();
        }
    });
    $('#save_pagu').submit(function() {
        if ($('#nama').val() === '') {
            alert('Nama bank tidak boleh kosong !');
            $('#nama').focus(); return false;
        }
        var cek_id = $('#id_pagu').val();
        $.ajax({
            url: '<?= base_url('transaksi/manage_pagu/save') ?>',
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            cache: false,
            success: function(data) {
                if (data.status === true) {
                    if (cek_id === '') {
                        alert_tambah();
                        $('input').val('');
                        get_list_pagu('1','',data.id_pagu);
                    } else {
                        alert_edit();
                        $('#form_add').dialog().remove();
                        get_list_pagu($('.noblock').html(),'');
                    }
                }
            },
            error: function() {
                alert_tambah_failed();
            }
        });
        return false;
    });
}

function edit_pagu(str) {
    var arr = str.split('#');
    form_pagu();
    $('#id_pagu').val(arr[0]);
    $('#year').val(arr[1]);
    $('#id_satker').val(arr[2]);
    $('#nama').val(arr[3]);
    $('#dialog_pagu').dialog({ title: 'Edit pagu anggaran' });
}

function paging(page, tab, search) {
    get_list_pagu(page, search);
}

function delete_pagu(id, page) {
    $('<div id=alert>Anda yakin akan menghapus data ini?</div>').dialog({
        title: 'Konfirmasi Penghapusan',
        autoOpen: true,
        modal: true,
        buttons: {
            "OK": function() {
                $.ajax({
                    url: '<?= base_url('transaksi/manage_pagu/delete') ?>?id='+id,
                    cache: false,
                    success: function() {
                        get_list_pagu(page);
                        $('#alert').dialog().remove();
                    }
                });
            },
            "Cancel": function() {
                $(this).dialog().remove();
            }
        }
    });
}
</script>
<div class="kegiatan">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Pagu Anggaran</a></li>
        </ul>
        <div id="tabs-1">
            <button id="add_pagu">Tambah Data</button>
            <button id="reload_pagu">Refresh</button>
            <div class="searching-box">
            <select name="tahun" id="tahun"><option value="">Pilih  tahun ...</option><?php for($i = 2013; $i <= date("Y")+1; $i++) { ?><option value="<?= $i ?>"><?= $i ?></option><?php } ?></select>
            </div>
            <div id="result">

            </div>
        </div>
</div>