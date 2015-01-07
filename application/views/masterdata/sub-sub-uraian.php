<script type="text/javascript">
$(function() {
    $('#tabs').tabs();
    get_list_sub_sub_uraian(1);
    $('#add_sub_sub_uraian').button({
        icons: {
            secondary: 'ui-icon-newwin'
        }
    }).click(function() {
        form_sub_sub_uraian();
    });
    
    $('#reload_sub_sub_uraian').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        get_list_sub_sub_uraian();
    });
});

function get_list_sub_sub_uraian(page, src, id) {
    $.ajax({
        url: '<?= base_url('masterdata/manage_sub_sub_uraian') ?>/list/'+page,
        data: 'search='+src+'&id='+id,
        cache: false,
        success: function(data) {
            $('#result-sub_sub_uraian').html(data);
        }
    });
}

function form_sub_sub_uraian() {
    var str = '<div id="dialog_sub_sub_uraian"><form action="" id="save_sub_sub_uraian">'+
            '<?= form_hidden('id_sub_sub_uraian', NULL, 'id=id_sub_sub_uraian') ?>'+
            '<table width=100% cellpadding=0 cellspacing=0 class=inputan>'+
                '<tr><td width=30%>Satuan Kerja:</td><td><select name=id_satker id=id_satker><option value="">Pilih Satker ...</option><?php foreach ($satker as $data) { ?><option value="<?= $data->id ?>"><?= $data->nama ?></option><?php } ?></select></td></tr>'+
                '<tr><td>Status:</td><td><select name=status id=status><option value="SPP">SPP</option><option value="NON SPP">NON SPP</option></select></td></tr>'+
                '<tr><td>Kode / Nama Sub Uraian:</td><td><?= form_input('sub_uraian', NULL, 'id=sub_uraian size=60') ?><?= form_hidden('id_sub_uraian', NULL, 'id=id_sub_uraian') ?></td></tr>'+
                '<tr><td>Sub Sub uraian:</td><td><?= form_input('sub_sub_uraian', NULL, 'id=sub_sub_uraian size=60') ?></td></tr>'+
                '<tr><td>Data Kuat Organisasi:</td><td><?= form_input('kuat', NULL, 'id=kuat size=60') ?></td></tr>'+
                '<tr><td>&Sigma; Orang:</td><td><?= form_input('vol_orang', NULL, 'id=vol_orang size=60') ?></td></tr>'+
                '<tr><td>&Sigma; Hari/Bulan:</td><td><?= form_input('haribulan', NULL, 'id=haribulan size=60') ?></td></tr>'+
                '<tr><td>Harga Satuan:</td><td><?= form_input('harga', NULL, 'id=harga onkeyup="FormNum(this);" size=60') ?></td></tr>'+
            '</table>'+
            '</form></div>';
    
    $(str).dialog({
        title: 'Tambah sub uraian',
        autoOpen: true,
        width: 480,
        height: 270,
        modal: true,
        hide: 'clip',
        show: 'blind',
        buttons: {
            "Simpan": function() {
                $('#save_sub_sub_uraian').submit();
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
    $('#sub_uraian').autocomplete("<?= base_url('autocomplete/sub_uraian') ?>",
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
                    value: data[i].oketerangan // nama field yang dicari
                };
            }
            return parsed;
        },
        formatItem: function(data,i,max){
            if ($('#id_satker').val() === '') {
                var str = '<div class=result>'+data.code+' - '+data.satker+' - '+data.oketerangan+'</div>';
            } else {
                var str = '<div class=result>'+data.code+' - '+data.oketerangan+'</div>';
            }
            return str;
        },
        width: 400, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
        cacheLength: 0,
        max: 100
    }).result(
    function(event,data,formated){
        $(this).val(data.code+' '+data.oketerangan);
        $('#id_sub_uraian').val(data.id);
        $('#id_satker').val(data.id_satker);
        $('#status').val(data.status);
    });
    $('#save_sub_sub_uraian').submit(function() {
        if ($('#nama').val() === '') {
            alert('Nama bank tidak boleh kosong !');
            $('#nama').focus(); return false;
        }
        var cek_id = $('#id_sub_sub_uraian').val();
        $.ajax({
            url: '<?= base_url('masterdata/manage_sub_sub_uraian/save') ?>',
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            cache: false,
            success: function(data) {
                if (data.status === true) {
                    if (cek_id === '') {
                        alert_tambah();
                        $('#sub_sub_uraian').val('');
                        get_list_sub_sub_uraian('1','',data.id_sub_sub_uraian);
                    } else {
                        alert_edit();
                        $('#dialog_sub_sub_uraian').dialog().remove();
                        get_list_sub_sub_uraian($('.noblock').html(),'');
                    }
                }
            }
        });
        return false;
    });
}

function edit_sub_sub_uraian(str) {

    var arr = str.split('#');
    form_sub_sub_uraian();
    $('#id_satker, #status').attr('disabled','disabled');
    $('#id_sub_sub_uraian').val(arr[0]);
    $('#id_satker').val(arr[1]);
    $('#status').val(arr[2]);
    $('#uraian').val(arr[10]+' '+arr[4]);
    $('#id_uraian').val(arr[3]);
    $('#sub_sub_uraian').val(arr[5]);
    $('#kuat').val(arr[6]);
    $('#vol_orang').val(arr[7]);
    $('#haribulan').val(arr[8]);
    $('#harga').val(arr[9]);
    $('#dialog_sub_sub_uraian').dialog({ title: 'Edit sub_sub_uraian' });
}

function paging(page, tab, search) {
    get_list_sub_sub_uraian(page, search);
}

function delete_sub_sub_uraian(id, page) {
    $('<div id=alert>Anda yakin akan menghapus data ini?</div>').dialog({
        title: 'Konfirmasi Penghapusan',
        autoOpen: true,
        modal: true,
        buttons: {
            "OK": function() {
                
                $.ajax({
                    url: '<?= base_url('masterdata/manage_sub_sub_uraian/delete') ?>?id='+id,
                    cache: false,
                    success: function() {
                        get_list_sub_sub_uraian(page);
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
<button id="add_sub_sub_uraian">Tambah Data</button>
<button id="reload_sub_sub_uraian">Refresh</button>
<div id="result-sub_sub_uraian">

</div>
