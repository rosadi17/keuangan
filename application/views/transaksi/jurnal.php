<title><?= $title ?></title>
<?= $this->load->view('message') ?>
<div class="titling"><h1><?= $title ?></h1></div>
<script type="text/javascript">

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
        success: function(data) {
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
            $(this).dialog('close');
        }
    });
}

function rek_debet_add_row() {
    str = '<div class="rows_debet" style="margin-bottom: 3px;"><?= form_input('kode_perkiraan_d[]', NULL, 'id=kode_perkiraan_d style="width: 200px;" placeholder="Kode akun ..."') ?>&nbsp;<?= form_input('jumlah_d[]', NULL, 'id=jumlah onkeyup="FormNum(this);" style="width: 70px;" placeholder="Nominal ..."') ?> <button type="button" class="btn btn-default btn-xs delete" id=""><i class="fa fa-minus-circle"></i></button></div>';
    $('#rows_debet').append(str);
}

function rek_kredit_add_row() {
    str = '<div class="rows_debet" style="margin-bottom: 3px;"><?= form_input('kode_perkiraan_k[]', NULL, 'id=kode_perkiraan_k style="width: 200px;" placeholder="Kode akun ..."') ?>&nbsp;<?= form_input('jumlah_k[]', NULL, 'id=jumlah onkeyup="FormNum(this);" style="width: 70px;" placeholder="Nominal ..."') ?> <button type="button" class="btn btn-default btn-xs delete" id=""><i class="fa fa-minus-circle"></i></button></div>';
    $('#rows_kredit').append(str);
}
//<tr><td>Kode Perkiraan (K):</td><td><?= form_input('kode_perkiraan_k', NULL, 'id=kode_perkiraan_k size=60') ?></td></tr>
$(function() {
    get_list_jurnal(1);
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
        <tr><td>Tanggal:</td><td><?= date("d F Y") ?></td></tr>
        <tr><td>Kode BKK/BKM:</td><td><?= form_input('kode_transaksi', NULL, 'id=kode_transaksi size=60') ?></td></tr>
        <tr><td></td><td><button type="button" class="btn btn-default btn-xs delete" onclick="rek_debet_add_row();"><i class="fa fa-plus-circle"></i> Tambah Kode (D)</button> <button type="button" class="btn btn-default btn-xs delete" onclick="rek_kredit_add_row();"><i class="fa fa-plus-circle"></i> Tambah Kode (K)</button></td></tr>
        <tr><td valign="top">Kode Akun (D):</td><td id="rows_debet"></td></tr>
        <tr><td valign="top">Kode Akun (K):</td><td id="rows_kredit"></td></tr>
        <tr><td valign="top">Uraian:</td><td><?= form_textarea('uraian', NULL, 'id=uraian rows=4 style="width: 294px;"') ?></td></tr>
    </table>
    <?= form_close() ?>
</div>