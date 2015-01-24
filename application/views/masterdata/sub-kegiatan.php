<script type="text/javascript">
$(function() {
    $('#tabs').tabs();
    get_list_sub_kegiatan(1);
    $('#add_sub_kegiatan').button({
        icons: {
            secondary: 'ui-icon-newwin'
        }
    }).click(function() {
        form_sub_kegiatan();
    });
    
    $('#reload_sub_kegiatan').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        get_list_sub_kegiatan();
    });
});

function get_auto_last_code_sub_kegiatan(id_kegiatan, element) {
    $.ajax({
        url: '<?= base_url('autocomplete/get_auto_last_code_sub_kegiatan') ?>/'+id_kegiatan,
        dataType: 'json',
        success: function(data) {
            $(element).val(data.kode);
        }
    });
}

function get_list_sub_kegiatan(page, src, id) {
    $.ajax({
        url: '<?= base_url('masterdata/manage_sub_kegiatan') ?>/list/'+page,
        data: 'search='+src+'&id='+id,
        cache: false,
        success: function(data) {
            $('#result-sub_kegiatan').html(data);
        }
    });
}

function form_sub_kegiatan() {
    var str = '<div id="dialog_sub_kegiatan"><form action="" id="save_sub_kegiatan">'+
            '<?= form_hidden('id_sub_kegiatan', NULL, 'id=id_sub_kegiatan') ?>'+
            '<table width=100% cellpadding=0 cellspacing=0 class=inputan>'+
                '<tr><td width=30%>Satuan Kerja:</td><td><select name=id_satker id=id_satker><option value="">Pilih Satker ...</option><?php foreach ($satker as $data) { ?><option value="<?= $data->id ?>"><?= $data->nama ?></option><?php } ?></select></td></tr>'+
                '<tr><td>Status:</td><td><select name=status id=status><option value="SPP">SPP</option><option value="NON SPP">NON SPP</option></select></td></tr>'+
                '<tr><td>Kode / Nama kegiatan:</td><td><?= form_input('kegiatan', NULL, 'id=kegiatan size=60') ?><?= form_hidden('id_kegiatan', NULL, 'id=id_kegiatan') ?></td></tr>'+
                '<tr><td>Kode:</td><td><?= form_input('kode', NULL, 'id=kode size=60') ?></td></tr>'+
                '<tr><td>Nama Sub Kegiatan:</td><td><?= form_input('sub_kegiatan', NULL, 'id=sub_kegiatan size=60') ?></td></tr>'+
            '</table>'+
            '</form></div>';
    
    $(str).dialog({
        title: 'Tambah Sub Kegiatan',
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
                $('#save_sub_kegiatan').submit();
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
    $('#kegiatan').autocomplete("<?= base_url('autocomplete/kegiatan') ?>",
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
                    value: data[i].nama_kegiatan // nama field yang dicari
                };
            }
            return parsed;
        },
        formatItem: function(data,i,max){
            if ($('#id_satker').val() === '') {
                var str = '<div class=result>'+pad(data.code,5)+' - '+data.nama_kegiatan+'<br/>'+data.satker+' ('+data.status+')</div>';
            } else {
                var str = '<div class=result>'+pad(data.code,5)+' - '+data.nama_kegiatan+'</div>';
            }
            return str;
        },
        width: 400, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
        cacheLength: 0,
        max: 100
    }).result(
    function(event,data,formated){
        $(this).val(pad(data.code,5)+' '+data.nama_kegiatan);
        $('#id_kegiatan').val(data.id_kegiatan);
        get_auto_last_code_sub_kegiatan(data.id_kegiatan, '#kode')
        $('#id_satker').val(data.id_satker);
        $('#status').val(data.status);
    });
    $('#save_sub_kegiatan').submit(function() {
        if ($('#nama').val() === '') {
            alert('Nama bank tidak boleh kosong !');
            $('#nama').focus(); return false;
        }
        var cek_id = $('#id_sub_kegiatan').val();
        $.ajax({
            url: '<?= base_url('masterdata/manage_sub_kegiatan/save') ?>',
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            cache: false,
            success: function(data) {
                if (data.status === true) {
                    if (cek_id === '') {
                        alert_tambah();
                        $('input, select').val('');
                        get_list_sub_kegiatan('1','',data.id_sub_kegiatan);
                    } else {
                        alert_edit();
                        $('#dialog_sub_kegiatan').dialog().remove();
                        get_list_sub_kegiatan($('.noblock').html(),'');
                    }
                }
            }
        });
        return false;
    });
}

function edit_sub_kegiatan(str) {
    var arr = str.split('#');
    form_sub_kegiatan();
    $('#id_satker, #status').attr('disabled','disabled');
    $('#id_sub_kegiatan').val(arr[0]);
    $('#id_satker').val(arr[1]);
    $('#status').val(arr[2]);
    $('#kegiatan').val(pad(arr[4],5)+' '+arr[5]);
    $('#id_kegiatan').val(arr[3]);
    $('#kode').val(arr[6]);
    $('#sub_kegiatan').val(arr[7]);
    
    $('#dialog_sub_kegiatan').dialog({ title: 'Edit sub_kegiatan' });
}

function paging(page, tab, search) {
    get_list_sub_kegiatan(page, search);
}

function delete_sub_kegiatan(id, page) {
    $('<div id=alert>Anda yakin akan menghapus data ini?</div>').dialog({
        title: 'Konfirmasi Penghapusan',
        autoOpen: true,
        modal: true,
        buttons: {
            "OK": function() {
                
                $.ajax({
                    url: '<?= base_url('masterdata/manage_sub_kegiatan/delete') ?>?id='+id,
                    cache: false,
                    success: function() {
                        get_list_sub_kegiatan(page);
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
<button id="add_sub_kegiatan">Tambah Data</button>
<button id="reload_sub_kegiatan">Refresh</button>
<div id="result-sub_kegiatan">

</div>
