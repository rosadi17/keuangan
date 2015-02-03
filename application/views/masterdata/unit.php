<title><?= $title ?></title>
<div class="titling"><h1><?= $title ?></h1></div>
<?= $this->load->view('message') ?>
<script type="text/javascript">
$(function() {
    $('#tabs').tabs();
    get_list_unit(1);
    $('#add_unit').button({
        icons: {
            secondary: 'ui-icon-newwin'
        }
    }).click(function() {
        form_unit();
    });
    
    $('#reload_unit').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        get_list_unit();
    });
});
function get_list_unit(page, src, id) {
    $.ajax({
        url: '<?= base_url('masterdata/manage_unit') ?>/list/'+page,
        data: 'search='+src+'&id='+id,
        cache: false,
        success: function(data) {
            $('#result').html(data);
        }
    });
}

function form_unit() {
    var str = '<div id="dialog_unit"><form action="" id="save_unit">'+
            '<?= form_hidden('id_unit', NULL, 'id=id_unit') ?>'+
            '<table width=100% cellpadding=0 cellspacing=0 class=data-input>'+
                '<tr><td>Kode Satker:</td><td><?= form_input('kode', NULL, 'id=kode maxlength=4') ?></td></tr>'+
                '<tr><td width=30%>Nama Unit Satker:</td><td><?= form_input('nama', NULL, 'id=nama size=40 onKeyup="javascript:this.value=this.value.toUpperCase();"') ?></td></tr>'+
            '</table>'+
            '</form></div>';
    $(str).dialog({
        title: 'Tambah Unit',
        autoOpen: true,
        width: 480,
        height: 160,
        modal: true,
        hide: 'clip',
        show: 'blind',
        buttons: {
            "Simpan": function() {
                $('#save_unit').submit();
            }, "Cancel": function() {
                $(this).dialog().remove();
            }
        }, close: function() {
            $(this).dialog().remove();
        }
    });
    $('#save_unit').submit(function() {
        if ($('#nama').val() === '') {
            alert('Nama bank tidak boleh kosong !');
            $('#nama').focus(); return false;
        }
        var cek_id = $('#id_unit').val();
        $.ajax({
            url: '<?= base_url('masterdata/manage_unit/save') ?>',
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            cache: false,
            success: function(data) {
                if (data.status === true) {
                    if (cek_id === '') {
                        alert_tambah();
                        $('input').val('');
                        get_list_unit('1','',data.id_unit);
                    } else {
                        alert_edit();
                        $('#form_add').dialog().remove();
                        get_list_unit($('.noblock').html(),'');
                    }
                }
            }
        });
        return false;
    });
}

function edit_unit(str) {
    var arr = str.split('#');
    form_unit();
    $('#id_unit').val(arr[0]);
    $('#nama').val(arr[1]);
    $('#kode').val(arr[2]);
    $('#dialog_unit').dialog({ title: 'Edit unit satuan kerja' });
}

function paging(page, tab, search) {
    get_list_unit(page, search);
}

function delete_unit(id, page) {
    $('<div id=alert>Anda yakin akan menghapus data ini?</div>').dialog({
        title: 'Konfirmasi Penghapusan',
        autoOpen: true,
        modal: true,
        buttons: {
            "OK": function() {
                
                $.ajax({
                    url: '<?= base_url('masterdata/manage_unit/delete') ?>?id='+id,
                    cache: false,
                    success: function() {
                        get_list_unit(page);
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
            <li><a href="#tabs-1">Unit Satuan Kerja</a></li>
        </ul>
        <div id="tabs-1">
            <button id="add_unit">Tambah Data</button>
            <button id="reload_unit">Refresh</button>
            <div id="result">

            </div>
        </div>
    </div>
</div>