<script type="text/javascript">
function load_sub_rekening(id_rekening, id_sub_rekening) {
    $.ajax({
        url: '<?= base_url('masterdata/get_sub_rekening_dropdown') ?>/'+id_rekening,
        cache: false,
        success: function(data) {
            $('#sub_rekening').append(data).val(id_sub_rekening);
        }
    });
}
function load_sub_sub_rekening(id_sub_rekening, id_sub_sub_rekening) {
    $.ajax({
        url: '<?= base_url('masterdata/get_sub_sub_rekening_dropdown') ?>/'+id_sub_rekening,
        cache: false,
        success: function(data) {
            $('#sub_sub_rekening').append(data).val(id_sub_sub_rekening);
        }
    });
}
function load_sub_sub_sub_rekening(id_sub_sub_rekening, id_sub_sub_sub_rekening) {
    $.ajax({
        url: '<?= base_url('masterdata/get_sub_sub_sub_rekening_dropdown') ?>/'+id_sub_sub_rekening,
        cache: false,
        success: function(data) {
            $('#sub_sub_sub_rekening').append(data).val(id_sub_sub_sub_rekening);
        }
    });
}
function load_sub_sub_sub_sub_rekening(id) {
    $.ajax({
        url: '<?= base_url('masterdata/get_sub_sub_sub_sub_rekening_dropdown') ?>/'+id,
        cache: false,
        dataType: 'json',
        success: function(data) {
            $('#nama_ssss').val(data.nama);
            $('#pairing').val(data.pairing);
            $('input[name=id_pairing]').val(data.id_sub_sub_sub_sub_rekening_pairing);
            
            if (data.jenis_laporan === 'SHU') {
                $('#shu').attr('checked','checked');
            }
            if (data.jenis_laporan === 'Neraca') {
                $('#neraca').attr('checked','checked');
            }
            
        }
    });
}

function get_last_code(table, kolom, id_parent, element) {
    $.ajax({
        url: '<?= base_url('autocomplete/get_last_code') ?>/'+table+'/'+kolom+'/'+id_parent,
        dataType: 'json',
        success: function(data) {
            $(element).val(data.id);
        }
    });
}

$("#example-advanced").treetable({ expandable: true });
//$('#example-advanced').treetable('expandAll');
// Highlight selected row
/*$("#example-advanced tbody tr").mousedown(function() {
    $("tr.selected").removeClass("selected");
    $(this).addClass("selected");
});*/

$('.add_subsubrekening').click(function() {
    var id = $(this).attr('id').split('#');
    dialog_sub_sub_rekening(id[0]);
    get_last_code('sub_sub_rekening', 'id', id[0], '#kode_sub_sub_rek');
    $('#sub_rekening_id').val(id[1]);
    return false;
});
$('.add_subsubsubrekening').click(function() {
    var arr = $(this).attr('id').split('#');
    dialog_sub_sub_sub_rekening(arr[0]);
    get_last_code('sub_sub_sub_rekening', 'id', arr[0], '#kode');
    $('#sub_sub_rek_id').val(arr[1]);
    return false;
});

$('.add_subsubsubsubrekening').click(function() {
    $('input[name=id_sub_sub_sub_sub_reks]').val('');
    var id = $(this).attr('id').split('#');
    dialog_s4_rekening();
    get_last_code('sub_sub_sub_sub_rekening', 'id', id[0], '#kode_subsubsubsub');
    $('#sub_sub_sub_rekening').val(id[0]);
    $('#id_sss_rek').val(id[1]);
    $('#nama_ssss').val('').focus();
    return false;
});
function dialog_s4_rekening() {
    var str = '<div class=inputan id=dialogx><form action="" id=form_rekening>'+
        '<?= form_hidden('id_sub_sub_sub_sub_reks', NULL, 'id=id_sub_sub_sub_sub_reks') ?>'+
        '<table width=100% cellspacing=0 cellpadding=0 class=inputan>'+
            '<tr><td width=25%>Kode.:</td><td><?= form_input('kode_subsubsubsub', NULL, 'size=10 onKeyup="Angka(this)" id=kode_subsubsubsub') ?></td></tr>'+
            '<tr><td>Kode Sub Sub Sub:</td><td><?= form_input('', NULL, 'id=id_sss_rek disabled size=40') ?><?= form_hidden('sub_sub_sub_rekening', NULL, 'id=sub_sub_sub_rekening') ?></td></tr>'+
            '<tr><td>Nama:</td><td><?= form_input('nama_ssss', NULL, 'id=nama_ssss size=40') ?></td></tr>'+
        '</table>'+
        '</form></div>';
    
    $('#dialog_form').append(str);
    $('#dialogx').dialog({
        autoOpen: true,
        title :'Tambah Rekening',
        autoResize: true,
        width: 450,
        modal: true,
        close: function() {
            $(this).dialog().remove(); 
        },
        open: function() {
            $('#nama_rekeningx').focus();
        },
        buttons: {
            "Simpan": function() { 
                $('#form_rekening').submit();
                $(this).dialog().remove();
            },
            "Batal": function() { 
                $(this).dialog().remove(); 
            }
        } 
    });
    $('#form_rekening').submit(function() {
        var rek = $('#id_sub_sub_sub_sub_reks').val();
        if (rek === '') {
            url = '<?= base_url('masterdata/save_sub_sub_sub_sub_rekening') ?>';
        } else {
            url = '<?= base_url('masterdata/save_edit_sub_sub_sub_sub_rekening') ?>';
        }
        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            cache: false,
            success: function(data) {
                $('#list_rekening').html(data);
                $('#kode_subsubsubsub').val(parseInt($('#kode_subsubsubsub').val())+1);
                $('#pairing, input[name=id_pairing]').val('');
                $('#simpan').hide();
                if (rek === '') {
                    alert_tambah();
                } else {
                    alert_edit();
                }
                $("#example-advanced").treetable({ expandable: true });
                $('#example-advanced').treetable('expandAll');
            },
            error: function() {
                alert_tambah_failed();
            }
        });
        return false;
    });
}
$('.edit_rek').click(function() {
    dialog_rekening('Edit Rekening');
    var id = $(this).attr('id');
    var arr = id.split('#');
    $('#kode_rek, #kode_rek_id').val(arr[0]);
    $('#nama_rekeningx').val(arr[1]);
    $('#posisi').val(arr[2]);
    return false;
});
$('.edit_sub_rek').click(function() {
    var id = $(this).attr('id');
    var arr = id.split('#');
    dialog_subrekening(arr[0], 'Edit Rekening');
    $('#kode_sub_rek_id,#kode_sub_rek').val(arr[1]);
    $('#rekening_id').val(arr[0]);
    $('#nama_sub').val(arr[2]);
    return false;
});
$('#rekening').live('change', function() {
    var id_rek = $(this).val();
    $.ajax({
        url: '<?= base_url('masterdata/get_sub_rekening_dropdown') ?>/'+id_rek,
        cache: false,
        success: function(data) {
            $('#nama_sub_rekening').html(data);
        }
    });
});
$('.delete').click(function() {
    $("<div id='dialogq'>Anda yakin akan menghapus data ini ?</div>").dialog({
        title: 'Konfirmasi Penghapusan',
        modal: true,
        buttons: {
            "OK": function() {
                $(this).dialog().remove();
                var parent = el.parentNode.parentNode;
                parent.parentNode.removeChild(parent);
                $.ajax({
                    url: $(this).attr('id'),
                    cache: false,
                    success: function(data) {
                        alert_delete();
                        get_list_rekening(1, null);
                    }
                });
            },
            "Batal": function() {
                $(this).dialog().remove();
            }
        }
    });
    return false;
});

/*Rekening Manage*/
function dialog_rekening(titled) {
    var str = '<div class=inputan id=dialogx><form action="" id=form_rekening>'+
        '<table width=100% cellspacing=0 cellpadding=0 class=inputan>'+
            '<tr><td width=25%>Kode.:</td><td><?= form_input('kode_rek',NULL,'id=kode_rek') ?><input type=hidden name=kode_rek_id id=kode_rek_id /></td></tr>'+
            '<tr><td>Nama:</td><td><?= form_input('nama_rekening', NULL, 'id=nama_rekeningx size=40') ?></td></tr>'+
            '<tr><td>Posisi:</td><td><select name="posisi" id="posisi"><option value="D">Debet</option><option value="C">Kredit</option></select></td></tr>'
        '</table>'+
        '</form></div>';
    
    $('#dialog_form').append(str);
    $('#dialogx').dialog({
        autoOpen: true,
        title : titled,
        autoResize: true,
        width: 450,
        modal: true,
        close: function() {
            $(this).dialog().remove(); 
        },
        open: function() {
            $('#nama_rekeningx').focus();
        },
        buttons: {
            "Simpan": function() { 
                save_rekening();
                $(this).dialog().remove();
            },
            "Batal": function() { 
                $(this).dialog().remove(); 
            }
        } 
    });
}
function save_rekening() {
    if ($('#kode_rek').val() === '') {
        custom_message('Peringatan','Kode rekening tidak boleh kosong !');
        $('#kode_rek').focus();
        return false;
    }
    if ($('#nama_rekeningx').val() === '') {
        custom_message('Peringatan','Nama rekening tidak boleh kosong !');
        $('#nama_rekeningx').focus();
        return false;
    }
    if ($('#kode_rek_id').val() === '') {
        var url = '<?= base_url('masterdata/manage_rekening') ?>/add/';
    } else {
        var url = '<?= base_url('masterdata/manage_rekening') ?>/edit_rek/';
    }
    $.ajax({
        url: url,
        type: 'POST',
        data: $('#form_rekening').serialize(),
        success: function(data) {
            $('#dialogx').dialog().remove(); 
            if ($('#kode_rek_id').val() === '') {
                alert_tambah();
            } else {
                alert_edit();
            }
            $('#list_rekening').html(data);
        },
        error: function() {
            alert_tambah_failed();
        }
    });
}

/*Sub Rekening Manage*/
$('.add_subrekening').click(function() {
    var value = $(this).attr('id');
    dialog_subrekening(value, 'Tambah Rekening');
    get_last_code('sub_rekening', 'id', value, '#kode_sub_rek');
    return false;
});
function dialog_subrekening(id_rek, titled) {
    var str = '<div class="inputan" id="dialogy">'+
    '<form action="" id=form_subrekening>'+
    '<table width=100% cellspacing=0 cellpadding=0 class=inputan>'+
        '<tr><td>Kode:</td><td><?= form_input('kode_sub_rek', NULL, 'id=kode_sub_rek') ?><input type=hidden name=kode_sub_rek_id id=kode_sub_rek_id />'+
        '<tr><td>Nama Rekening:</td><td><select name="rekening_id" id="rekening_id"><option value="">Pilih rekening ...</option><?php foreach ($list_rekening as $rows) { echo '<option value="'.$rows->id.'">'.$rows->nama.'</option>'; } ?></select>'+
        '<tr><td>Nama:</td><td><?= form_input('nama_sub', NULL, 'id=nama_sub size=40') ?>'+
    '</table>'+
    '</form>'+
    '</div>';
    $('#dialog_form').append(str);
    $('#rekening_id').val(id_rek);
    $('#dialogy').dialog({
        autoOpen: true,
        title : titled,
        autoResize: true,
        width: 450,
        modal: true,
        close: function() {
            $(this).dialog().remove(); 
        },
        open: function() {
            $('#nama_sub').focus();
        },
        buttons: {
            "Simpan": function() { 
                save_subrekening();
                $(this).dialog().remove();
            },
            "Batal": function() { 
                $(this).dialog().remove(); 
            }
        } 
    });
}
function save_subrekening() {
    if ($('#kode_sub_rek').val() === '') {
        custom_message('Peringatan','Kode sub rekening tidak boleh kosong !');
        $('#kode_sub_rek').focus();
        return false;
    }
    if ($('#rekening_id').val() === '') {
        custom_message('Peringatan','Rekening tidak boleh kosong !');
        $('#rekening_id').focus();
        return false;
    }
    if ($('#nama_sub').val() === '') {
        custom_message('Peringatan','Nama Sub rekening tidak boleh kosong !');
        $('#nama_sub').focus();
        return false;
    }
    if ($('#kode_sub_rek_id').val() === '') {
        var url = '<?= base_url('masterdata/manage_rekening') ?>/add_sub/';
    } else {
        var url = '<?= base_url('masterdata/manage_rekening') ?>/edit_sub/';
    }
    $.ajax({
        url: url,
        type: 'POST',
        data: $('#form_subrekening').serialize(),
        success: function(data) {
            $('#dialogy').dialog().remove(); 
            if ($('#kode_sub_rek_id').val() === '') {
                alert_tambah();
            } else {
                alert_edit();
            }
            $('#list_rekening').html(data);
            $('#example-advanced').treetable('expandAll');
        },
        error: function() {
            alert_tambah_failed();
        }
    });
}

function dialog_sub_sub_rekening(id_sub_rekening) {
    var str = '<div class="inputan" id="dialogz">'+
    '<form action="" id=form_subsubrekening>'+
    '<table width=100% cellspacing=0 cellpadding=0 class=inputan>'+
        '<tr><td>Kode:</td><td><?= form_input('kode_sub_sub_rek', NULL, 'id=kode_sub_sub_rek') ?><input type=hidden name=sub_sub_rek_id id=sub_sub_rek_id />'+
        '<tr><td>Sub Rekening:</td><td><?= form_input('', NULL, 'id=sub_rekening_id size=30') ?><input name=sub_rekening_id type=hidden />'+
        '<tr><td>Nama:</td><td><?= form_input('nama_sub_sub', NULL, 'id=nama_sub_sub size=40') ?>'+
    '</form>'+
    '</div>';
    $('#loaddata').append(str);
    $('#sub_rekening_id').autocomplete("<?= base_url('masterdata/get_sub_rekening_auto') ?>",
    {
        parse: function(data){
            var parsed = [];
            for (var i=0; i < data.length; i++) {
                parsed[i] = {
                    data: data[i],
                    value: data[i].srekening // nama field yang dicari
                };
            }
            $('input[name=id_pairing]').val('');
            return parsed;
        },
        formatItem: function(data,i,max){
            var str = '<div class=result>'+data.srekening+'</div>';
            return str;
        },
        width: 320, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json' // tipe data yang diterima oleh library ini disetup sebagai JSON
    }).result(
    function(event,data,formated){
        $(this).val(data.nama);
        $('input[name=sub_rekening_id]').val(data.id);
    });
    $('input[name=sub_rekening_id]').val(id_sub_rekening);
    $('#dialogz').dialog({
        autoOpen: true,
        title: 'Sub Sub Rekening',
        width: 400,
        autoResize: true,
        modal: true,
        open: function() {
            $('#nama_sub_sub').focus();
        },
        buttons: {
            "Simpan": function() {
                save_sub_sub();
                $(this).dialog().remove();
            }, 
            "Batal": function() {
                $(this).dialog().remove();
            }
        }, close: function() {
            $(this).dialog().remove();
        }
    });

}

function save_sub_sub() {
    var id = $('#sub_sub_rek_id').val();
    if (id === '') {
        var url = '<?= base_url('masterdata/manage_rekening/add_sub_sub_rek') ?>';
    } else {
        var url = '<?= base_url('masterdata/manage_rekening/edit_sub_sub_rek') ?>';
    }
    $.ajax({
        url: url,
        type: 'POST',
        data: $('#form_subsubrekening').serialize(),
        success: function(data) {
            $('#dialogz').dialog().remove(); 
            if (id === '') {
                alert_tambah();
            } else {
                alert_edit();
            }
            $('#list_rekening').html(data);
            $('#example-advanced').treetable('expandAll');
        },
        error: function() {
            alert_tambah_failed();
        }
    });
}

function dialog_sub_sub_sub_rekening(id_sub_sub) {
    var str = '<div class=inputan id=dialogq>'+
        '<form action="" id="form_sub_sub_sub">'+
        '<table width=100% cellspacing=0 cellpadding=0 class=inputan>'+
            '<tr><td>Kode:</td><td><?= form_input('kode', NULL, 'id=kode') ?><input type=hidden name=id_sub_sub_sub id=id_sub_sub_sub />'+
            '<tr><td>Sub Sub Rekening:</td><td><?= form_input('', NULL, 'id=sub_sub_rek_id size=30') ?><input type=hidden name=sub_sub_rek_id />'+
            '<tr><td>Nama:</td><td><?= form_input('nama', NULL, 'id=nama_sss size=30') ?>'+
        '</table>'+
        '</form>'+
        '</div>';
    $('#loaddata').append(str);
    $('#sub_sub_rek_id').val();
    $('input[name=sub_sub_rek_id]').val(id_sub_sub);
    $('#sub_sub_rek_id').autocomplete("<?= base_url('masterdata/get_sub_sub_rek_auto') ?>",
    {
        parse: function(data){
            var parsed = [];
            for (var i=0; i < data.length; i++) {
                parsed[i] = {
                    data: data[i],
                    value: data[i].ssrekening // nama field yang dicari
                };
            }
            $('input[name=id_pairing]').val('');
            return parsed;
        },
        formatItem: function(data,i,max){
            var str = '<div class=result>'+data.ssrekening+'</div>';
            return str;
        },
        width: 320, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json' // tipe data yang diterima oleh library ini disetup sebagai JSON
    }).result(
    function(event,data,formated){
        $(this).val(data.nama);
        $('input[name=sub_sub_rek_id]').val(data.id);
    });
    $('#dialogq').dialog({
        autoOpen: true,
        title: 'Sub Sub Sub Rekening',
        width: 450,
        autoResize: true,
        modal: true,
        open: function() {
            $('#nama_sss').focus();
        },
        buttons: {
            "Simpan": function() {
                save_sub_sub_sub();
                $(this).dialog().remove();
            }, 
            "Batal": function() {
                $(this).dialog().remove();
            }
        },
        close: function() {
            $(this).dialog().remove();
        }
    });
}

function save_sub_sub_sub() {
    var id = $('#id_sub_sub_sub').val();
    if (id === '') {
        var url = '<?= base_url('masterdata/manage_rekening/add_sub_sub_sub_rek') ?>';
    } else {
        var url = '<?= base_url('masterdata/save_edit_sub_sub_sub_rek') ?>';
    }
    $.ajax({
        url: url,
        type: 'POST',
        data: $('#form_sub_sub_sub').serialize(),
        success: function(data) {
            $('#dialogq').dialog().remove(); 
            if (id === '') {
                alert_tambah();
            } else {
                alert_edit();
            }
            $('#list_rekening').html(data);
            $('#example-advanced').treetable('expandAll');
        }
    });
}

function delete_ssss(el, id) {
    $("<div id='dialogq'>Anda yakin akan menghapus data ini ?</div>").dialog({
        title: 'Konfirmasi Penghapusan',
        modal: true,
        buttons: {
            "OK": function() {
                $(this).dialog().remove();
                var parent = el.parentNode.parentNode;
                parent.parentNode.removeChild(parent);
                $.ajax({
                    url: '<?= base_url('masterdata/delete_subsubsubsubrekening') ?>/'+id,
                    cache: false,
                    success: function(data) {
                        
                    }
                });
            },
            "Batal": function() {
                $(this).dialog().remove();
            }
        }
    });
    return false;
}

function delete_sss(el, id) {
    $("<div id='dialogq'>Anda yakin akan menghapus data ini ?</div>").dialog({
        title: 'Konfirmasi Penghapusan',
        modal: true,
        buttons: {
            "OK": function() {
                $(this).dialog().remove();
//                var parent = el.parentNode.parentNode;
//                parent.parentNode.removeChild(parent);
                $.ajax({
                    url: '<?= base_url('masterdata/delete_subsubsubrekening') ?>/'+id,
                    cache: false,
                    dataType: 'json',
                    success: function(data) {
                        if (data.status === true) {
                            alert_delete();
                            $('#loaddata').load('<?= base_url('masterdata/rekening') ?>');
                        }
                    }
                });
            },
            "Batal": function() {
                $(this).dialog().remove();
            }
        }
    });
    return false;
}
$('.edit_ssss').click(function() {
    var arr = $(this).attr('id').split('#');
    dialog_s4_rekening();
    $('#id_sub_sub_sub_sub_reks').val(arr[0]);
    $('#kode_subsubsubsub').val(arr[0]);
    $('#id_sss_rek').val(arr[2]);
    $('#sub_sub_sub_rekening').val(arr[1]);
    $('#nama_ssss').val(arr[3]);
    return false;
});

$('.edit_sss').click(function() {
    var arr = $(this).attr('id').split('#');
    dialog_sub_sub_sub_rekening(arr[2]);

    $('#kode, #id_sub_sub_sub').val(arr[1]);
    $('input[name=sub_sub_rek_id]').val(arr[0]);
    $('#sub_sub_rek_id').val(arr[3]).attr('disabled','disabled');
    $('#nama_sss').val(arr[2]);
    return false;
});
$('.edit_sub_sub').click(function() {
    var id = $(this).attr('id').split('#');
    dialog_sub_sub_rekening(id[2]);
    $('#sub_rekening_id').val(id[1]);
    $('#kode_sub_sub_rek, #sub_sub_rek_id').val(id[0]);
    $('#nama_sub_sub').val(id[3]);
    return false;
});
</script>
<div class="data-list">
    <div id="dialog_form"></div>
    <table class="tabel-advance list-data" width="100%" id="example-advanced">
        <tr>
            <th width="15%">Kode</th>
            <th width="55%">Nama Rekening</th>
            <th width="5%">D / C</th>
            <th width="25%">Aksi</th>
        </tr>
        <?php 
        // Rekening
        foreach ($list_data as $r1 => $data) { ?>
        <tr data-tt-id='<?= $r1 ?>' class="<?= ($r1%2==1)?'even':'odd' ?>">
            <td><?= anchor('', $data->id, 'class=edit_rek id="'.$data->id.'#'.$data->nama.'#'.$data->posisi.'"') ?></td>
            <td><?= $data->rekening ?></td>
            <td><?= $data->posisi ?></td>
            <td>
                <button type="button" class="btn btn-default btn-xs delete" id="<?= base_url('masterdata/delete_rekening/'.$data->id) ?>"><i class="fa fa-minus-circle"></i> Hapus</button>
                <button type="button" class="btn btn-default btn-xs add_subrekening" id="<?= $data->id ?>"><i class="fa fa-plus-circle"></i> Tambah Sub</button>
        </tr>
        <?php 
            // Sub Rekening
            if (isset($id_sub)) {
                $id_sub = $id_sub;
            } else if (isset($data->id_sub_rekening)) {
                $id_sub = $data->id_sub_rekening;
            } else {
                $id_sub = NULL;
            }
            $sub_rekening = $this->m_masterdata->data_subrekening_load_data($id_sub, $data->id)->result();
            foreach ($sub_rekening as $r2 => $rows) { ?>
                <tr data-tt-id='<?= $r1 ?>-<?= $r2 ?>' data-tt-parent-id='<?= $r1 ?>' class="even">
                    <td><?= anchor('', $rows->id, 'class=edit_sub_rek id="'.$data->id.'#'.$rows->id.'#'.$rows->nama.'"') ?></td>
                    <td style="padding-left: 15px;"><?= $rows->nama ?></td>
                    <td><?= $data->posisi ?></td>
                    <td style="padding-left: 15px;">
                        <button type="button" class="btn btn-default btn-xs delete" id="<?= base_url('masterdata/delete_subrekening/'.$rows->id) ?>"><i class="fa fa-minus-circle"></i> Hapus</button>
                        <button type="button" class="btn btn-default btn-xs add_subsubrekening" title="<?= $rows->id.'#'.$rows->nama.'#'.$data->nama.'#'.$data->id ?>" id="<?= $rows->id.'#'.$rows->nama.'#'.$data->nama.'#'.$data->id ?>"><i class="fa fa-plus-circle"></i> Tambah Sub</button>
                    </td>
                </tr>
                    <?php 
                    $sub_sub_rekening = $this->m_masterdata->data_subsubrekening_load_data(isset($id_sub_sub)?$id_sub_sub:NULL, $rows->id)->result();
                    foreach ($sub_sub_rekening as $r3 => $rowx) { ?>
                        <tr data-tt-id='<?= $r1 ?>-<?= $r2 ?>-<?= $r3 ?>' data-tt-parent-id='<?= $r1 ?>-<?= $r2 ?>' class="even">
                            <td><?= anchor('', $rowx->id, 'class=edit_sub_sub id="'.$rowx->id.'#'.$rows->nama.'#'.$rowx->id_subrekening.'#'.$rowx->nama.'"') ?></td>
                            <td style="padding-left: 30px;"><?= $rowx->nama ?></td>
                            <td><?= $data->posisi ?></td>
                            <td style="padding-left: 30px;">
                                <button type="button" class="btn btn-default btn-xs delete" id="<?= base_url('masterdata/delete_subsubrekening/'.$rowx->id) ?>"><i class="fa fa-minus-circle"></i> Hapus</button>
                                <button type="button" class="btn btn-default btn-xs add_subsubsubrekening" id="<?= $rowx->id.'#'.$rowx->nama ?>"><i class="fa fa-plus-circle"></i> Tambah Sub</button>
                            </td>
                        </tr>
                        <?php
                        $sub_sub_sub_rekening = $this->m_masterdata->data_subsubsub_rekening_load_data(isset($id_sub_sub_sub)?$id_sub_sub_sub:NULL, $rowx->id)->result();
                        foreach ($sub_sub_sub_rekening as $r4 => $rowy) { 
                            $str_s4 = $rowx->id.'#'.$rowy->id.'#'.$rowy->nama.'#'.$rowx->nama;
                            ?>
                            <tr data-tt-id='<?= $r1 ?>-<?= $r2 ?>-<?= $r3 ?>-<?= $r4 ?>' data-tt-parent-id='<?= $r1 ?>-<?= $r2 ?>-<?= $r3 ?>' class="even">
                                <td><?= anchor('',$rowy->id,'class=edit_sss id="'.$str_s4.'"') ?></td>
                                <td style="padding-left: 45px;"><?= $rowy->nama ?></td>
                                <td><?= $data->posisi ?></td>
                                <td style="padding-left: 45px;">
                                    <button type="button" class="btn btn-default btn-xs delete" onclick="delete_sss(this, <?= $rowy->id ?>)"><i class="fa fa-minus-circle"></i> Hapus</button>
                                    <button type="button" class="btn btn-default btn-xs add_subsubsubsubrekening" id="<?= $rowy->id_sub_sub_sub_rekening.'#'.$rowy->nama ?>"><i class="fa fa-plus-circle"></i> Tambah Sub</button>
                                </td>
                            </tr>
                        <?php
                            $sub_sub_sub_sub_rekening = $this->m_masterdata->data_subsubsubsub_rekening_load_data(isset($id_sub_sub_sub_sub)?$id_sub_sub_sub_sub:NULL, $rowy->id)->result();
                            foreach ($sub_sub_sub_sub_rekening as $r5 => $rowz) { 
                                $str_s5 = $rowz->id.'#'.$rowy->id.'#'.$rowy->nama.'#'.$rowz->sub_sub_sub_sub_rekening;
                                ?>
                                <tr data-tt-id='<?= $r1 ?>-<?= $r2 ?>-<?= $r3 ?>-<?= $r4 ?>-<?= $r5 ?>' data-tt-parent-id='<?= $r1 ?>-<?= $r2 ?>-<?= $r3 ?>-<?= $r4 ?>' class="even">
                                    <td><?= anchor('',$rowz->id,'class=edit_ssss id="'.$str_s5.'"') ?></td>
                                    <td style="padding-left: 60px;"><?= $rowz->sub_sub_sub_sub_rekening ?></td>
                                    <td><?= $data->posisi ?></td>
                                    <td style="padding-left: 60px;">
                                        <button type="button" class="btn btn-default btn-xs delete" onclick="delete_ssss(this, '<?= $rowz->id ?>')"><i class="fa fa-minus-circle"></i> Hapus</button>
                                    </td>
                                </tr>
                            <?php
                            }
                        }
                    }
            }
        } ?>
    </table>
</div>