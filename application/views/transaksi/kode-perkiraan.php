<title><?= $title ?></title>
<?= $this->load->view('message') ?>
<div class="titling"><h1><?= $title ?></h1></div>
<script type="text/javascript">
function get_last_code_kasir(trans) {
    $.ajax({
        url: '<?= base_url('autocomplete/get_last_code_kasir') ?>/'+trans,
        dataType: 'json',
        success: function(data) {
            $('#no').val(data.no);
        }
    });
}
function get_nominal_renbut(id) {
    $.ajax({
        url: '<?= base_url('autocomplete/get_nominal_renbut') ?>/'+id,
        dataType: 'json',
        success: function(data) {
           $('#jumlah').val(numberToCurrency(data.total));
        }
    });
}
$(function() {
    $('#tabs').tabs();
    $('#simpan').button({
        icons: {
            secondary: 'ui-icon-circle-check'
        }
    }).click(function() {
        $('#form').submit();
    });
    $('#reset').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        $('#loaddata').load('<?= base_url('transaksi/kode_perkiraan') ?>');
    });
    $('#jenis').change(function() {
        var jenis = $(this).val();
        get_last_code_kasir(jenis);
    });
    $('#tanggal').datepicker();
    
    $('#form').submit(function() {
        
        if ($('#jumlah').val() === '') {
            custom_message('Peringatan', 'Jumlah tidak boleh kosong', '#jumlah'); return false;
        }
        if ($('#kode_perkiraan').val() === '') {
            custom_message('Peringatan', 'Kode perkiraan harus dipilih', '#kode_perkiraan'); return false;
        }
        if ($('#kode').val() === '') {
            custom_message('Peringatan', 'Kode MA / Proja harus dipilih', '#kode'); return false;
        }
        if ($('#jumlah').val() === '') {
            custom_message('Peringatan', 'Jumlah tidak boleh kosong', '#jumlah'); return false;
        }
        $('<div id=alert>Anda yakin akan menyimpan transaksi ini ?</div>').dialog({
            title: 'Konfirmasi Penghapusan',
            autoOpen: true,
            modal: true,
            buttons: {
                "OK": function() {
                    $.ajax({
                        url: '<?= base_url('transaksi/jurnal_save') ?>',
                        type: 'POST',
                        data: $('#form').serialize(),
                        dataType: 'json',
                        success: function(data) {
                            custom_message('Informasi','Proses entri lawan perkiraan berhasil');
                            $('#simpan').hide();
                        }
                    });
                    $(this).dialog().remove();
                },
                "Cancel": function() {
                    $(this).dialog().remove();
                }
            }
        });
        return false;
    });
    $('#kode').autocomplete("<?= base_url('autocomplete/ma_proja') ?>",
    {
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
            var str = '<div class=result>'+pad(data.ma_proja,5)+' <br/> '+data.keterangan+'</div>';
            return str;
        },
        width: 400, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
        cacheLength: 0,
        max: 100
    }).result(
    function(event,data,formated){
        $(this).val(pad(data.ma_proja,5));
        $('#id_kode').val(data.id);
        $('#uraian').val(data.keterangan);
        $('#pengguna').val(data.satker);
        $('#keterangan').val(data.uraian);
        get_nominal_renbut(data.id);
    });
    $('#kode2').autocomplete("<?= base_url('autocomplete/ma_proja') ?>",
    {
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
            var str = '<div class=result>'+pad(data.ma_proja,5)+' <br/> '+data.keterangan+'</div>';
            return str;
        },
        width: 400, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
        cacheLength: 0,
        max: 100
    }).result(
    function(event,data,formated){
        $(this).val(pad(data.ma_proja,5));
        $('#uraian2').val(data.uraian);
        $('#pengguna2').val(data.satker);
        $('#keterangan2').val(data.uraian);
    });
    $('#kode_perkiraan').autocomplete("<?= base_url('autocomplete/kode_perkiraan') ?>",
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
            <?= form_open('', 'id=form') ?>
            <table class="inputan" width="100%">
                <tr><td>Tanggal:</td><td><?= form_input('tanggal', date("d/m/Y"), 'size=15 id=tanggal') ?></td></tr>
                <tr><td>Kode Perkiraan:</td><td><?= form_input('kode_perkiraan', NULL, 'id=kode_perkiraan size=60') ?></td></tr>
                <tr><td>Kode MA/Proja:</td><td><?= form_input('kode', NULL, 'id=kode') ?><?= form_hidden('id_kode', NULL, 'id=id_kode') ?></td></tr>
                <tr><td valign="top">Uraian:</td><td><?= form_textarea('uraian', NULL, 'id=uraian rows=4') ?></td></tr>
                <tr><td>Perwabku:</td><td><?= form_dropdown('perwabku', array('Default' => 'Default', 'Belum' => 'Belum (DP)', 'Sudah' => 'Sudah (Pusat Biaya)'), NULL, 'id=perwabku') ?></td></tr>
                <tr><td>Jumlah Biaya:</td><td><?= form_input('jumlah', NULL, 'id=jumlah') ?></td></tr>
                <tr><td></td><td><?= form_button('Simpan', 'id=simpan') ?> <?= form_button('Reset', 'id=reset') ?></td></tr>
            </table>
            
            <?= form_close() ?>
        </div>
    </div>
</div>