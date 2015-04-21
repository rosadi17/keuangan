<title><?= $title ?></title>
<?= $this->load->view('message') ?>
<div class="titling"><h1><?= $title ?></h1></div>
<script type="text/javascript">
$(function() {
    get_list_jurnal(1);
    $('#tanggal').datepicker({
        changeYear: true,
        changeMonth: true
    });
    $('#tabs').tabs();
    $('#simpan').button({
        icons: {
            secondary: 'ui-icon-circle-check'
        }
    }).click(function() {
        $('#form').submit();
    });
    
    $('#add_jurnal').button({
        icons: {
            secondary: 'ui-icon-newwin'
        }
    }).click(function() {
        form_jurnal();
    });
    
    $('#reload').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        get_list_jurnal(1);
    });
    
    $('#jenis').change(function() {
        var jenis = $(this).val();
        get_last_code_kasir(jenis);
    });
    $('#tanggal').datepicker();
   
    
    $('#kode_perkiraan2').autocomplete("<?= base_url('autocomplete/kode_perkiraan') ?>",
    {
        parse: function(data){
            var parsed = [];
            for (var i=0; i < data.length; i++) {
                parsed[i] = {
                    data: data[i],
                    value: data[i].id_akun // nama field yang dicari
                };
            }
            return parsed;
        },
        formatItem: function(data,i,max){
            var str = '<div class=result>'+data.id_akun+' <br/> '+data.akun+'</div>';
            return str;
        },
        width: 400, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
        cacheLength: 0,
        max: 100
    }).result(
    function(event,data,formated){
        $(this).val(data.id_akun);
    });
});

function save_jurnal() {
    $.ajax({
        url: '<?= base_url('transaksi/save_jurnal_transaksi') ?>',
        data: $('#form').serialize(),
        dataType: 'json',
        type: 'POST',
        success: function(data) {
            if (data.status === true) {
                alert_tambah();
                reset_form();
                $('.dialog').dialog('close');
                get_list_jurnal(1);
            } else {
                alert_tambah_failed();
            }
        }
    });
}

function reset_form() {
    $('input[type=text], textarea').val('');
}

function get_list_jurnal(page, src, id) {
    $.ajax({
        url: '<?= base_url('transaksi/manage_jurnal') ?>/list/'+page,
        cache: false,
        beforeSend: function() {
            show_ajax_indicator();
        },
        success: function(data) {
            hide_ajax_indicator();
            $('#result').html(data);
        }
    });
}

function paging(page, tab, search) {
    get_list_jurnal(page, search);
}

function form_jurnal() {
    $('.dialog').dialog({
        title: 'Jurnal Transaksi',
        autoOpen: true,
        width: 520,
        autoResize: true,
        modal: true,
        hide: 'explode',
        show: 'blind',
        position: ['center',47],
        buttons: {
            "Cancel": function() {
                $(this).dialog('close');
            },
            "Simpan": function() {
                save_jurnal();
            }
        }, close: function() {
            $('#rows_debet').empty();
            $('#rows_kredit').empty();
            $(this).dialog('close');
        }, open: function() {
            rek_debet_add_row();
            rek_kredit_add_row();
        }
    });
}

function removeDebet(el) {
    var parent = el.parentNode;
    parent.parentNode.removeChild(parent);
    var jml = $('.rows_debet').length;
    var baris = 0;
    for (i = 1; i <= jml; i++) {
        $('.rows_debet:eq('+baris+')').children('.kode_perkiraan_d').attr('id', 'kode_perkiraan_d'+i);
        $('.rows_debet:eq('+baris+')').children('.hide_kode_perkiraan_d').attr('id', 'hide_kode_perkiraan_d'+i);
        $('.rows_debet:eq('+baris+')').children('.jumlah_d').attr('id', 'jumlah_d'+i);
        baris++;
    }
}

function rek_debet_add_row() {
    var i = $('.rows_debet').length+1;
    str = '<div class="rows_debet" style="margin-bottom: 3px;">'+
            '<input type="text" id="kode_perkiraan_d'+i+'" class="kode_perkiraan_d" style="width: 200px;" placeholder="Kode akun ..." /> '+
            '<input type="hidden" name="kode_perkiraan_d[]" class="hide_kode_perkiraan_d" id="hide_kode_perkiraan_d'+i+'" />'+
            '<input type="text" name="jumlah_d[]" id="jumlah_d'+i+'" onkeyup="FormNum(this);" class="jumlah_d" style="width: 70px;" placeholder="Nominal ..." /> '+
            '<button type="button" class="btn btn-default btn-xs delete" onClick="removeDebet(this);"><i class="fa fa-minus-circle"></i></button>'+
          '</div>';
    $('#rows_debet').append(str);
    $('#kode_perkiraan_d'+i).autocomplete("<?= base_url('autocomplete/kode_perkiraan') ?>",
    {
        parse: function(data){
            var parsed = [];
            for (var i=0; i < data.length; i++) {
                parsed[i] = {
                    data: data[i],
                    value: data[i].id_akun // nama field yang dicari
                };
            }
            return parsed;
        },
        formatItem: function(data,i,max){
            var str = '<div class=result>'+data.id_akun+' <br/> '+data.akun+'</div>';
            return str;
        },
        width: 400, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
        cacheLength: 0,
        max: 100
    }).result(
    function(event,data,formated){
        $(this).val(data.id_akun+' '+data.perkiraan);
        $('#hide_kode_perkiraan_d'+i).val(data.id_akun);
    });
}

function removeKredit(el) {
    var parent = el.parentNode;
    parent.parentNode.removeChild(parent);
    var jml = $('.rows_kredit').length;
    var baris = 0;
    for (i = 1; i <= jml; i++) {
        $('.rows_kredit:eq('+baris+')').children('.kode_perkiraan_k').attr('id', 'kode_perkiraan_k'+i);
        $('.rows_kredit:eq('+baris+')').children('.hide_kode_perkiraan_k').attr('id', 'hide_kode_perkiraan_k'+i);
        $('.rows_kredit:eq('+baris+')').children('.jumlah_k').attr('id', 'jumlah_k'+i);
        baris++;
    }
}

function rek_kredit_add_row() {
    var i = $('.rows_kredit').length+1;
    str = '<div class="rows_kredit" style="margin-bottom: 3px;">'+
            '<input type="text" id="kode_perkiraan_k'+i+'" style="width: 200px;" placeholder="Kode akun ..." /> '+
            '<input type="hidden" name="kode_perkiraan_k[]" id="hide_kode_perkiraan_k'+i+'" />'+
            '<input type="text" name="jumlah_k[]" id="jumlah_k'+i+'" onkeyup="FormNum(this);" style="width: 70px;" placeholder="Nominal ..." /> '+
            '<button type="button" class="btn btn-default btn-xs delete" onClick="removeKredit(this);"><i class="fa fa-minus-circle"></i></button>'+
          '</div>';
    $('#rows_kredit').append(str);
    $('#kode_perkiraan_k'+i).autocomplete("<?= base_url('autocomplete/kode_perkiraan') ?>",
    {
        parse: function(data){
            var parsed = [];
            for (var i=0; i < data.length; i++) {
                parsed[i] = {
                    data: data[i],
                    value: data[i].id_akun // nama field yang dicari
                };
            }
            return parsed;
        },
        formatItem: function(data,i,max){
            var str = '<div class=result>'+data.id_akun+' <br/> '+data.akun+'</div>';
            return str;
        },
        width: 400, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
        cacheLength: 0,
        max: 100
    }).result(
    function(event,data,formated){
        $(this).val(data.id_akun+' '+data.perkiraan);
        $('#hide_kode_perkiraan_k'+i).val(data.id_akun);
    });
}

function delete_jurnal(id, page) {
    $('<div id=alert>Anda yakin akan menghapus data ini?</div>').dialog({
        title: 'Konfirmasi Penghapusan',
        autoOpen: true,
        modal: true,
        buttons: {
            "OK": function() {
                $.ajax({
                    url: '<?= base_url('transaksi/manage_jurnal/delete') ?>?id='+id,
                    cache: false,
                    success: function() {
                        get_list_jurnal(page);
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
            <li><a href="#tabs-1">Parameter</a></li>
        </ul>
        <div id="tabs-1">
            <button id="add_jurnal">Tambah Data</button>
            <button id="reload">Refresh</button>
            <div id="result"></div>
        </div>
    </div>
</div>
<div class="dialog nodisplay">
    <?= form_open('', 'id=form') ?>
    <table class="inputan" width="100%">
        <tr><td>Tanggal:</td><td><input type="text" name="tanggal" id="tanggal" value="<?= date("d/m/Y") ?>" /></td></tr>
        <tr><td>Kode BKK/BKM:</td><td><?= form_input('kode_transaksi', NULL, 'id=kode_transaksi size=60') ?></td></tr>
        <tr><td></td><td><button type="button" class="btn btn-default btn-xs delete" onclick="rek_debet_add_row();"><i class="fa fa-plus-circle"></i> Tambah Kode (D)</button> <button type="button" class="btn btn-default btn-xs delete" onclick="rek_kredit_add_row();"><i class="fa fa-plus-circle"></i> Tambah Kode (K)</button></td></tr>
        <tr><td valign="top">Kode Akun (D):</td><td id="rows_debet"></td></tr>
        <tr><td valign="top">Kode Akun (K):</td><td id="rows_kredit"></td></tr>
        <tr><td valign="top">Uraian:</td><td><?= form_textarea('uraian', NULL, 'id=uraian rows=4 style="width: 294px;"') ?></td></tr>
    </table>
    <?= form_close() ?>
</div>