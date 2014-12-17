<script type="text/javascript">
$(function() {
    $('#tabs').tabs();
    get_list_keg_program(1);
    $('#add_keg_program').button({
        icons: {
            secondary: 'ui-icon-newwin'
        }
    }).click(function() {
        form_keg_program();
    });
    
    $('#reload_keg_program').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        get_list_keg_program();
    });
});

function get_auto_last_code_kegiatan(id_program, element) {
    $.ajax({
        url: '<?= base_url('autocomplete/get_auto_last_code_kegiatan') ?>/'+id_program,
        dataType: 'json',
        success: function(data) {
            $(element).val(data.kode);
        }
    });
}

function get_list_keg_program(page, src, id) {
    $.ajax({
        url: '<?= base_url('masterdata/manage_keg_program') ?>/list/'+page,
        data: 'search='+src+'&id='+id,
        cache: false,
        success: function(data) {
            $('#result-keg_program').html(data);
        }
    });
}

function form_keg_program() {
    var str = '<div id="dialog_keg_program"><form action="" id="save_keg_program">'+
            '<?= form_hidden('id_keg_program', NULL, 'id=id_keg_program') ?>'+
            '<table width=100% cellpadding=0 cellspacing=0 class=data-input>'+
                '<tr><td width=30%>Satuan Kerja:</td><td><select name=id_satker id=id_satker><option value="">Pilih Satker ...</option><?php foreach ($satker as $data) { ?><option value="<?= $data->id ?>"><?= $data->nama ?></option><?php } ?></select></td></tr>'+
                '<tr><td>Status:</td><td><select name=status id=status><option value="SPP">SPP</option><option value="NON SPP">NON SPP</option></select></td></tr>'+
                '<tr><td>Nama Program:</td><td><?= form_input('program', NULL, 'id=program size=60') ?><?= form_hidden('id_program', NULL, 'id=id_program') ?></td></tr>'+
                '<tr><td>Kode:</td><td><?= form_input('kode', NULL, 'id=kode size=60') ?></td></tr>'+
                '<tr><td>Nama Kegiatan:</td><td><?= form_input('kegiatan', NULL, 'id=kegiatan size=60') ?></td></tr>'+
            '</table>'+
            '</form></div>';
    
    $(str).dialog({
        title: 'Tambah Kegiatan',
        autoOpen: true,
        width: 480,
        height: 230,
        modal: true,
        hide: 'clip',
        show: 'blind',
        buttons: {
            "Simpan": function() {
                $('#save_keg_program').submit();
            }, "Cancel": function() {
                $(this).dialog().remove();
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
    $('#program').autocomplete("<?= base_url('autocomplete/program') ?>",
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
                    value: data[i].nama_program // nama field yang dicari
                };
            }
            return parsed;
        },
        formatItem: function(data,i,max){
            var str = '<div class=result>('+pad(data.kode, 5)+') '+data.nama_program+'</div>';
            return str;
        },
        width: 400, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
        cacheLength: 0,
        max: 100
    }).result(
    function(event,data,formated){
        $(this).val(data.nama_program);
        $('#id_program').val(data.id);
        $('#id_satker').val(data.id_satker);
        get_auto_last_code_kegiatan(data.id, '#kode');
    });
    $('#save_keg_program').submit(function() {
        if ($('#nama').val() === '') {
            alert('Nama bank tidak boleh kosong !');
            $('#nama').focus(); return false;
        }
        var cek_id = $('#id_keg_program').val();
        $.ajax({
            url: '<?= base_url('masterdata/manage_keg_program/save') ?>',
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            cache: false,
            success: function(data) {
                if (data.status === true) {
                    if (cek_id === '') {
                        alert_tambah();
                        $('input, select').val('');
                        get_list_keg_program('1','',data.id_keg_program);
                    } else {
                        alert_edit();
                        $('#dialog_keg_program').dialog().remove();
                        get_list_keg_program($('.noblock').html(),'');
                    }
                }
            }
        });
        return false;
    });
}

function edit_keg_program(str) {
    var arr = str.split('#');
    form_keg_program();
    $('#id_keg_program').val(arr[0]);
    $('#id_satker').val(arr[1]);
    $('#status').val(arr[2]);
    $('#program').val(arr[3]);
    $('#id_program').val(arr[4]);
    $('#kode').val(arr[5]);
    $('#kegiatan').val(arr[6]);
    $('#dialog_keg_program').dialog({ title: 'Edit keg_program' });
}

function paging(page, tab, search) {
    get_list_keg_program(page, search);
}

function delete_keg_program(id, page) {
    $('<div id=alert>Anda yakin akan menghapus data ini?</div>').dialog({
        title: 'Konfirmasi Penghapusan',
        autoOpen: true,
        modal: true,
        buttons: {
            "OK": function() {
                
                $.ajax({
                    url: '<?= base_url('masterdata/manage_keg_program/delete') ?>?id='+id,
                    cache: false,
                    success: function() {
                        get_list_keg_program(page);
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
<button id="add_keg_program">Tambah Data</button>
<button id="reload_keg_program">Refresh</button>
<div id="result-keg_program">

</div>
