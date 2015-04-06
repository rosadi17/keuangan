<title><?= $title ?></title>
<div class="titling"><h1><?= $title ?></h1></div>
<?= $this->load->view('message') ?>
<script type="text/javascript">
$(function() {
    $('#tabs').tabs();
    get_list_sub_uraian(1);
    $('#add_sub_uraian').button({
        icons: {
            secondary: 'ui-icon-newwin'
        }
    }).click(function() {
        form_sub_uraian();
    });
    
    $('#reload_sub_uraian').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        reset_form();
        get_list_sub_uraian(1);
    });
    $('#cari_button').button({
        icons: {
            secondary: 'ui-icon-search'
        }
    }).click(function() {
        $('#anggaran_search').dialog({
            title: 'Cari Data <?= $title ?>',
            autoOpen: true,
            width: 480,
            autoResize:true,
            modal: true,
            hide: 'explode',
            show: 'blind',
            position: ['center',47],
            buttons: {
                "Cancel": function() {
                    $('#anggaran_search').dialog('close');
                },
                "Cari": function() {
                    get_list_sub_uraian(1);
                    $('#anggaran_search').dialog('close');
                } 
            }, close: function() {
                $('#anggaran_search').dialog('close');
            }, open: function() {
                $('#tahun').focus();
            }
        });
    });
});

function reset_form() {
    $('input[type=text], select, textarea').val('');
    $('#year').val('<?= date("Y") ?>');
}

function get_list_sub_uraian(page, id) {
    var idx = '';
    if (id !== undefined) {
        idx = id;
    }
    $.ajax({
        url: '<?= base_url('masterdata/manage_sub_uraian') ?>/list/'+page+'/'+idx,
        data: $('#search_anggaran').serialize(),
        cache: false,
        success: function(data) {
            $('#result-sub_uraian').html(data);
        }
    });
}

function form_sub_uraian() {
    var str = '<div id="dialog_sub_uraian"><form action="" id="save_sub_uraian">'+
            '<?= form_hidden('id_sub_uraian', NULL, 'id=id_sub_uraian') ?>'+
            '<table width=100% cellpadding=0 cellspacing=0 class=inputan>'+
                '<tr><td width=30%>Satuan Kerja:</td><td><select name=id_satker id=id_satker><option value="">Pilih Satker ...</option><?php foreach ($satker as $data) { ?><option value="<?= $data->id ?>"><?= $data->nama ?></option><?php } ?></select></td></tr>'+
                '<tr><td>Tahun:</td><td><?= form_input('tahun', NULL, 'id=tahun size=60') ?></td></tr>'+
                '<tr><td>Status:</td><td><select name=status id=status><option value="SPP">SPP</option><option value="NON SPP">NON SPP</option></select></td></tr>'+
                '<tr><td>Kode / Nama Uraian:</td><td><?= form_input('uraian', NULL, 'id=uraian size=60') ?><?= form_hidden('id_uraian', NULL, 'id=id_uraian') ?></td></tr>'+
                '<tr><td>Sub uraian:</td><td><?= form_input('sub_uraian', NULL, 'id=sub_uraian size=60') ?></td></tr>'+
//                '<tr><td>Data Kuat Organisasi:</td><td><?= form_input('kuat', NULL, 'id=kuat size=60') ?></td></tr>'+
//                '<tr><td>&Sigma; Orang:</td><td><?= form_input('vol_orang', NULL, 'id=vol_orang size=60') ?></td></tr>'+
//                '<tr><td>&Sigma; Hari/Bulan:</td><td><?= form_input('haribulan', NULL, 'id=haribulan size=60') ?></td></tr>'+
                '<tr><td>Harga Satuan:</td><td><?= form_input('harga', NULL, 'id=harga onkeyup="FormNum(this);" size=60') ?></td></tr>'+
            '</table>'+
            '</form></div>';
    
    $(str).dialog({
        title: 'Tambah Anggaran Kegiatan',
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
                $('#save_sub_uraian').submit();
            }
        }, close: function() {
            $(this).dialog().remove();
        }
    });
    $('#tahun').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'yy',
        onClose: function(dateText, inst) { 
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, 1));
        }
    });
    $("#tahun").focus(function () {
        $(".ui-datepicker-month").hide();
    });
    $('#id_satker').change(function() {
        var id = $(this).val();
        if (id !== '') {
            $('#program').removeAttr('disabled');
        } else {
            $('#program').attr('disabled','disabled');
        }
    });
    $('#uraian').autocomplete("<?= base_url('autocomplete/uraian') ?>",
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
                    value: data[i].uraian // nama field yang dicari
                };
            }
            return parsed;
        },
        formatItem: function(data,i,max){
            if ($('#id_satker').val() === '') {
                var str = '<div class=result>'+pad(data.code,5)+' - '+data.uraian+'<br/>'+data.satker+' ('+data.status+')</div>';
            } else {
                var str = '<div class=result>'+pad(data.code,5)+' - '+data.uraian+'</div>';
            }
            return str;
        },
        width: 400, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
        cacheLength: 0,
        max: 100
    }).result(
    function(event,data,formated){
        $(this).val(pad(data.code,5)+' '+data.uraian);
        $('#id_uraian').val(data.id);
        $('#id_satker').val(data.id_satker);
        $('#status').val(data.status);
    });
    $('#save_sub_uraian').submit(function() {
        if ($('#nama').val() === '') {
            alert('Nama bank tidak boleh kosong !');
            $('#nama').focus(); return false;
        }
        var cek_id = $('#id_sub_uraian').val();
        $.ajax({
            url: '<?= base_url('masterdata/manage_sub_uraian/save') ?>',
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            cache: false,
            success: function(data) {
                if (data.status === true) {
                    if (cek_id === '') {
                        alert_tambah();
                        $('input, select').val('');
                        get_list_sub_uraian('1','',data.id_sub_uraian);
                    } else {
                        alert_edit();
                        $('#dialog_sub_uraian').dialog().remove();
                        get_list_sub_uraian($('.noblock').html(),'');
                    }
                }
            }
        });
        return false;
    });
}

function edit_sub_uraian(str) {

    var arr = str.split('#');
    form_sub_uraian();
    $('#id_satker, #status').attr('disabled','disabled');
    $('#id_sub_uraian').val(arr[0]);
    $('#id_satker').val(arr[1]);
    $('#status').val(arr[2]);
    $('#uraian').val(arr[10]+' '+arr[4]);
    $('#id_uraian').val(arr[3]);
    $('#sub_uraian').val(arr[5]);
    $('#kuat').val(arr[6]);
    $('#vol_orang').val(arr[7]);
    $('#haribulan').val(arr[8]);
    $('#harga').val(arr[9]);
    $('#tahun').val(arr[11]);
    $('#dialog_sub_uraian').dialog({ title: 'Edit Anggaran Kegiatan' });
}

function paging(page, tab, search) {
    get_list_sub_uraian(page, search);
}

function delete_sub_uraian(id, page) {
    $('<div id=alert>Anda yakin akan menghapus data ini?</div>').dialog({
        title: 'Konfirmasi Penghapusan',
        autoOpen: true,
        modal: true,
        buttons: {
            "OK": function() {
                
                $.ajax({
                    url: '<?= base_url('masterdata/manage_sub_uraian/delete') ?>?id='+id,
                    cache: false,
                    success: function() {
                        get_list_sub_uraian(page);
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
            <li><a href="#tabs-1">Entri <?= $title ?></a></li>
        </ul>
        <div id="tabs-1">
            <button id="add_sub_uraian">Tambah</button>
            <button id="cari_button">Cari</button>
            <button id="reload_sub_uraian">Reload Data</button>
            <div id="result-sub_uraian">

            </div>
        </div>
    </div>
    <div id="anggaran_search" class="nodisplay">
        <form action="" id="search_anggaran">
        <table width=100% cellpadding=0 cellspacing=0 class=inputan>
            <tr><td width=25%>Tahun:</td><td><select name="year" id="year" style="width: 74px;"><option value="">Select Year ....</option><?php for($i = 2014; $i <= date("Y"); $i++) { ?> <option value="<?= $i ?>" <?php if ($i == date("Y")) { echo "selected"; } ?>><?= $i ?></option><?php } ?></select></td></tr>
            <tr><td>Satuan Kerja:</td><td><select name=id_satker id=id_satker><option value="">Pilih Satker ...</option><?php foreach ($satker as $data) { ?><option value="<?= $data->id ?>"><?= $data->kode ?> <?= $data->nama ?></option><?php } ?></select></td></tr>
            <tr><td>Sub Uraian:</td><td><input type="text" name="suburaian" id="suburaian" /></td></tr>
        </table>
        </form>
    </div>
</div>