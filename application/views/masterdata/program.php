<script type="text/javascript">
$(function() {
    $('#tabs').tabs();
    get_list_program(1);
    $('#add_program').button({
        icons: {
            secondary: 'ui-icon-newwin'
        }
    }).click(function() {
        form_program();
    });
    
    $('#reload_program').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        get_list_program();
    });
});

function get_auto_last_code_program(satker, status,  element) {
    $.ajax({
        url: '<?= base_url('autocomplete/get_auto_last_code_program') ?>/'+satker+'/'+status,
        dataType: 'json',
        success: function(data) {
            $(element).val(data.kode);
        }
    });
}

function get_list_program(page, src, id) {
    $.ajax({
        url: '<?= base_url('masterdata/manage_program') ?>/list/'+page,
        data: 'search='+src+'&id='+id,
        cache: false,
        success: function(data) {
            $('#result-program').html(data);
        }
    });
}

function form_program() {
    var str = '<div id="dialog_program"><form action="" id="save_program">'+
            '<table width=100% cellpadding=0 cellspacing=0 class=data-input>'+
                '<tr><td>Satuan Kerja:</td><td><select name=id_satker id=id_satker><option value="">Pilih Satker ...</option><?php foreach ($satker as $data) { ?><option value="<?= $data->id ?>"><?= $data->nama ?></option><?php } ?></select></td></tr>'+
                '<tr><td>Status:</td><td><select name=status id=status><option value="SPP">SPP</option><option value="NON SPP">NON SPP</option></select></td></tr>'+
                '<tr><td width=30%>Kode:</td><td><?= form_input('id_program', NULL, 'id=id_program size=40') ?><?= form_hidden('id', NULL, 'id=id') ?></td></tr>'+
                '<tr><td>Nama program:</td><td><?= form_input('nama', NULL, 'id=nama size=40') ?></td></tr>'+
            '</table>'+
            '</form></div>';
    
    $(str).dialog({
        title: 'Tambah program',
        autoOpen: true,
        width: 480,
        height: 200,
        modal: true,
        hide: 'clip',
        show: 'blind',
        buttons: {
            "Simpan": function() {
                $('#save_program').submit();
            }, "Cancel": function() {
                $(this).dialog().remove();
            }
        }, close: function() {
            $(this).dialog().remove();
        }
    });
    $('#id_satker, #status').change(function() {
        var id_satker = $('#id_satker').val();
        var status    = $('#status').val();
        get_auto_last_code_program(id_satker, status, '#id_program');
    });
    var lebar = $('#satker').width();
    $('#satker').autocomplete("<?= base_url('autocomplete/satker') ?>",
    {
        parse: function(data){
            var parsed = [];
            for (var i=0; i < data.length; i++) {
                parsed[i] = {
                    data: data[i],
                    value: data[i].nama // nama field yang dicari
                };
            }
            return parsed;
        },
        formatItem: function(data,i,max){
            var str = '<div class=result>'+data.nama+'</div>';
            return str;
        },
        width: lebar, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json' // tipe data yang diterima oleh library ini disetup sebagai JSON
    }).result(
    function(event,data,formated){
        $(this).val(data.nama);
        $('#id_satker').val(data.id);
    });
    $('#save_program').submit(function() {
        if ($('#nama').val() === '') {
            alert('Nama bank tidak boleh kosong !');
            $('#nama').focus(); return false;
        }
        var cek_id = $('#id_program').val();
        $.ajax({
            url: '<?= base_url('masterdata/manage_program/save') ?>',
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            cache: false,
            success: function(data) {
                if (data.status === true) {
                    if (cek_id === '') {
                        
                        alert_tambah();
                        $('input, select').val('');
                        get_list_program('1','',data.id_program);
                    } else {
                        alert_edit();
                        $('#dialog_program').dialog().remove();
                        get_list_program($('.noblock').html(),'');
                    }
                }
            }
        });
        return false;
    });
}

function edit_program(str) {
    var arr = str.split('#');
    form_program();
    $('#id').val(arr[0]);
    $('#id_program').val(arr[1]);
    $('#satker').val(arr[3]);
    $('#id_satker').val(arr[2]);
    $('#nama').val(arr[4]);
    $('#status').val(arr[5]);
    $('#dialog_program').dialog({ title: 'Edit program' });
}

function paging(page, tab, search) {
    get_list_program(page, search);
}

function delete_program(id, page) {
    $('<div id=alert>Anda yakin akan menghapus data ini?</div>').dialog({
        title: 'Konfirmasi Penghapusan',
        autoOpen: true,
        modal: true,
        buttons: {
            "OK": function() {
                
                $.ajax({
                    url: '<?= base_url('masterdata/manage_program/delete') ?>?id='+id,
                    cache: false,
                    success: function() {
                        get_list_program(page);
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
<button id="add_program">Tambah Data</button>
<button id="reload_program">Refresh</button>
<div id="result-program">

</div>
