<script type="text/javascript">
$(function() {
    $('#tabs').tabs();
    get_list_uraian(1);
    $('#add_uraian').button({
        icons: {
            secondary: 'ui-icon-newwin'
        }
    }).click(function() {
        form_uraian();
    });
    
    $('#reload_uraian').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        get_list_uraian();
    });
});

function get_auto_last_code_uraian(sub_kegiatan, element) {
    $.ajax({
        url: '<?= base_url('autocomplete/get_auto_last_code_uraian') ?>/'+sub_kegiatan,
        dataType: 'json',
        success: function(data) {
            $(element).val(data.kode);
        }
    });
}

function get_list_uraian(page, src, id) {
    $.ajax({
        url: '<?= base_url('masterdata/manage_uraian') ?>/list/'+page,
        data: 'search='+src+'&id='+id,
        cache: false,
        success: function(data) {
            $('#result-uraian').html(data);
        }
    });
}

function form_uraian() {
    var str = '<div id="dialog_uraian"><form action="" id="save_uraian">'+
            '<?= form_hidden('id_uraian', NULL, 'id=id_uraian') ?>'+
            '<table width=100% cellpadding=0 cellspacing=0 class=inputan>'+
                '<tr><td width=30%>Satuan Kerja:</td><td><select name=id_satker id=id_satker><option value="">Pilih Satker ...</option><?php foreach ($satker as $data) { ?><option value="<?= $data->id ?>"><?= $data->nama ?></option><?php } ?></select></td></tr>'+
                '<tr><td>Status:</td><td><select name=status id=status><option value="SPP">SPP</option><option value="NON SPP">NON SPP</option></select></td></tr>'+
                '<tr><td>Kode / Nama Sub kegiatan:</td><td><?= form_input('sub_kegiatan', NULL, 'id=sub_kegiatan size=60') ?><?= form_hidden('id_sub_kegiatan', NULL, 'id=id_sub_kegiatan') ?></td></tr>'+
                '<tr><td>Kode:</td><td><?= form_input('kode', NULL, 'id=kode size=60') ?></td></tr>'+
                '<tr><td>Uraian:</td><td><?= form_input('uraian', NULL, 'id=uraian size=60') ?></td></tr>'+
            '</table>'+
            '</form></div>';
    
    $(str).dialog({
        title: 'Tambah Uraian',
        autoOpen: true,
        width: 480,
        autoResize: true,
        modal: true,
        hide: 'explode',
        show: 'blind',
        position: ['center',47],
        buttons: {
            "Cancel": function() {
                $(this).dialog().remove();
            },
            "Simpan": function() {
                $('#save_uraian').submit();
            }
        }, close: function() {
            $(this).dialog().remove();
        }
    });
    $('#id_satker').change(function() {
        var id = $(this).val();
        if (id !== '') {
            $('#program').removeAttr('disabled');
        } else {
            $('#program').attr('disabled','disabled');
        }
    });
    $('#sub_kegiatan').autocomplete("<?= base_url('autocomplete/sub_kegiatan') ?>",
    {
        extraParams: { 
            id_satker: function() { 
                return $('#id_satker').val();
            },
            status: function() {
                return $('#status').val();
            }
        },
        parse: function(data){
            var parsed = [];
            for (var i=0; i < data.length; i++) {
                parsed[i] = {
                    data: data[i],
                    value: data[i].nama_sub_kegiatan // nama field yang dicari
                };
            }
            return parsed;
        },
        formatItem: function(data,i,max){
            if ($('#id_satker').val() === '') {
                var str = '<div class=result>'+pad(data.code,5)+' - '+data.nama_sub_kegiatan+'<br/>'+data.satker+' ('+data.status+')</div>';
            } else {
                var str = '<div class=result>'+pad(data.code,5)+' - '+data.nama_sub_kegiatan+'</div>';
            }
            return str;
        },
        width: 400, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
        cacheLength: 0,
        max: 100
    }).result(
    function(event,data,formated){
        $(this).val(pad(data.code,5)+' '+data.nama_sub_kegiatan);
        $('#id_sub_kegiatan').val(data.id);
        get_auto_last_code_uraian(data.id, '#kode');
        $('#id_satker').val(data.id_satker);
        $('#status').val(data.status);
    });
    $('#save_uraian').submit(function() {
        if ($('#nama').val() === '') {
            alert('Nama bank tidak boleh kosong !');
            $('#nama').focus(); return false;
        }
        var cek_id = $('#id_uraian').val();
        $.ajax({
            url: '<?= base_url('masterdata/manage_uraian/save') ?>',
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            cache: false,
            success: function(data) {
                if (data.status === true) {
                    if (cek_id === '') {
                        alert_tambah();
                        $('input, select').val('');
                        get_list_uraian('1','',data.id_uraian);
                    } else {
                        alert_edit();
                        $('#dialog_uraian').dialog().remove();
                        get_list_uraian($('.noblock').html(),'');
                    }
                }
            }
        });
        return false;
    });
}

function edit_uraian(str) {
    var arr = str.split('#');
/*'<?= form_hidden('id_uraian', NULL, 'id=id_uraian') ?>'+
'<table width=100% cellpadding=0 cellspacing=0 class=inputan>'+
'<tr><td width=30%>Satuan Kerja:</td><td><select name=id_satker id=id_satker><option value="">Pilih Satker ...</option><?php foreach ($satker as $data) { ?><option value="<?= $data->id ?>"><?= $data->nama ?></option><?php } ?></select></td></tr>'+
'<tr><td>Status:</td><td><select name=status id=status><option value="SPP">SPP</option><option value="NON SPP">NON SPP</option></select></td></tr>'+
'<tr><td>Kode / Nama Sub kegiatan:</td><td><?= form_input('sub_kegiatan', NULL, 'id=sub_kegiatan size=60') ?><?= form_hidden('id_sub_kegiatan', NULL, 'id=id_sub_kegiatan') ?></td></tr>'+
'<tr><td>Kode:</td><td><?= form_input('kode', NULL, 'id=kode size=60') ?></td></tr>'+
'<tr><td>Uraian:</td><td><?= form_input('uraian', NULL, 'id=uraian size=60') ?></td></tr>'+*/

    /*$str = $data->id.'#'.$data->id_satker.'#'.$data->status.'#'.$data->id_sub_kegiatan.'#'.
    $data->kode_sub_kegiatan.'#'.$data->nama_sub_kegiatan.'#'.$data->kode.'#'.$data->uraian;*/
    form_uraian();
    $('#id_satker, #status').attr('disabled','disabled');
    $('#id_uraian').val(arr[0]);
    $('#id_satker').val(arr[1]);
    $('#status').val(arr[2]);
    $('#sub_kegiatan').val(pad(arr[4],5)+' '+arr[5]);
    $('#id_sub_kegiatan').val(arr[3]);
    $('#kode').val(arr[6]);
    $('#uraian').val(arr[7]);
    
    $('#dialog_uraian').dialog({ title: 'Edit uraian' });
}

function paging(page, tab, search) {
    get_list_uraian(page, search);
}

function delete_uraian(id, page) {
    $('<div id=alert>Anda yakin akan menghapus data ini?</div>').dialog({
        title: 'Konfirmasi Penghapusan',
        autoOpen: true,
        modal: true,
        buttons: {
            "OK": function() {
                
                $.ajax({
                    url: '<?= base_url('masterdata/manage_uraian/delete') ?>?id='+id,
                    cache: false,
                    success: function() {
                        get_list_uraian(page);
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
<button id="add_uraian">Tambah Data</button>
<button id="reload_uraian">Refresh</button>
<div id="result-uraian">

</div>
