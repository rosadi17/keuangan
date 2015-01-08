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

function cetak_bukti_kas(id, jenis) {
    var wWidth = $(window).width();
    var dWidth = wWidth * 1;
    var wHeight= $(window).height();
    var dHeight= wHeight * 1;
    var x = screen.width/2 - dWidth/2;
    var y = screen.height/2 - dHeight/2;
    window.open('<?= base_url('transaksi/print_bukti_kas') ?>?id='+id+'&jenis='+jenis, 'Renbut Cetak', 'width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
}

function cetak_bukti_kas_masuk(id) {
    var wWidth = $(window).width();
    var dWidth = wWidth * 1;
    var wHeight= $(window).height();
    var dHeight= wHeight * 1;
    var x = screen.width/2 - dWidth/2;
    var y = screen.height/2 - dHeight/2;
    window.open('<?= base_url('transaksi/manage_pemasukkan') ?>/print_bukti_kas?id='+id, 'Renbut Cetak', 'width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
}

function print_kasir(id, jenis) {
    if (jenis === 'BKM') {
        cetak_bukti_kas(id, 'bkm');
    } else {
        cetak_bukti_kas(id, 'bkk');
    }
}

$(function() {
    get_list_rekap_kasir(1);
    $('#tabs').tabs();
    $('#reset').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        $('#loaddata').load('<?= base_url('transaksi/kasir') ?>');
    });
    $('#cari_rekap_button').button({
        icons: {
            secondary: 'ui-icon-search'
        }
    }).click(function() {
        $('#form_kasir').dialog({
            title: 'Form Kasir BKK / BKM',
            autoOpen: true,
            width: 480,
            autoResize:true,
            modal: true,
            hide: 'explode',
            show: 'blind',
            position: ['center',47],
            buttons: {
                "Cancel": function() {
                    $('#form_kasir').dialog('destroy');
                }, "Simpan": function() {
                    $('#form').submit();
                }
            }, close: function() {
                $('#form_kasir').dialog('destroy');
            }
        });
    });
    $('#reload_kasir_data').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        get_list_rekap_kasir(1);
    });
    $('#jenis').change(function() {
        var jenis = $(this).val();
        if (jenis === 'bkm') {
            $('#user').val('Penyetor');
        } else {
            $('#user').val('Penerima');
        }
        get_last_code_kasir(jenis);
    });
    $('#tanggal').datepicker();
    $('#form').submit(function() {
        if ($('#jenis').val() === '') {
            custom_message('Peringatan', 'Jenis transaksi harus dipilih', '#jenis'); return false;
        }
        if ($('#kode').val() === '') {
            custom_message('Peringatan', 'Kode MA / Proja harus dipilih', '#kode'); return false;
        }
        if ($('#nama_user').val() === '') {
            custom_message('Peringatan', 'Penyetor / Penerima anggaran tidak boleh kosong', '#nama_user'); return false;
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
                        url: '<?= base_url('transaksi/kasir_save') ?>',
                        type: 'POST',
                        data: $('#form').serialize(),
                        dataType: 'json',
                        success: function(data) {
                            if (data.status === true) {
                                if (data.act === 'bkm') {
                                    get_list_rekap_kasir(1);
                                    custom_message('Informasi','Transaksi BKM berhasil dilakukan !');
                                    cetak_bukti_kas(data.id, 'bkm');
                                } else {
                                    custom_message('Informasi','Transaksi BKK berhasil dilakukan !');
                                    cetak_bukti_kas(data.id, 'bkk');
                                }
                                $('#form_kasir').dialog('destroy');
                            }
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
});

function get_list_rekap_kasir(page) {
    $.ajax({
        url: '<?= base_url('transaksi/manage_kasir') ?>/list/'+page,
        data: $('#form_kasir').serialize(),
        cache: false,
        success: function(data) {
            $('#result-kasir').html(data);
        }
    });
}

function paging(p) {
    get_list_rekap_kasir(p);
}
</script>
<div class="kegiatan">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Parameter</a></li>
        </ul>
        <div id="tabs-1">
            <button id="cari_rekap_button">Cari Data</button>
            <button id="reload_kasir_data">Refresh</button>
            <div id="result-kasir">

            </div>
        </div>
    </div>
    <div id="form_kasir" class="nodisplay">
        <?= form_open('', 'id=form') ?>
        <table class="inputan" width="100%">
            <tr><td>Jenis Transaksi:</td><td><?= form_dropdown('jenis', array('' => 'Pilih ...', 'bkk' => 'Kas Keluar', 'bkm' => 'Kas Masuk'), NULL, 'id=jenis') ?></td></tr>
            <tr><td>Tanggal:</td><td><?= form_input('tanggal', date("d/m/Y"), 'size=15 id=tanggal') ?></td></tr>
            <tr><td>No.</td><td><?= form_input('no', NULL, 'id=no') ?></td></tr>
            <tr><td>Sumber Dana:</td><td><?= form_dropdown('sumberdana', array('Kas' => 'Kas', 'Bank' => 'Bank'), NULL, 'id=sumberdana') ?></td></tr>
            <tr><td>Kode Perkiraan:</td><td><?= form_input('kode_perkiraan', NULL, 'id=kode_perkiraan size=60') ?></td></tr>
            <tr><td>Kode MA/Proja:</td><td><?= form_input('kode', NULL, 'id=kode') ?><?= form_hidden('id_kode', NULL, 'id=id_kode') ?></td></tr>
            <tr><td>Pengguna Anggaran:</td><td><?= form_input('pengguna', NULL, 'id=pengguna') ?></td></tr>
            <tr><td valign="top">Uraian:</td><td><?= form_textarea('uraian', NULL, 'id=uraian rows=4 style="width: 294px;"') ?></td></tr>
            <tr><td>Jumlah Biaya:</td><td><?= form_input('jumlah', NULL, 'id=jumlah') ?></td></tr>
            <tr><td><?= form_dropdown('user', array('Penerima' => 'Penerima', 'Penyetor' => 'Penyetor'), NULL, 'id=user style="width: 120px;"') ?></td><td><?= form_input('nama_user', NULL, 'id=nama_user') ?></td></tr>
            <tr><td>Perwabku:</td><td><?= form_dropdown('perwabku', array('Default' => 'Default', 'Belum' => 'Belum (DP)', 'Sudah' => 'Sudah (Pusat Biaya)'), NULL, 'id=perwabku') ?></td></tr>
            <!--<tr><td></td><td><?= form_button('Simpan', 'id=simpan') ?> <?= form_button('Reset', 'id=reset') ?></td></tr>-->
        </table>
        <?= form_close() ?>
    </div>
</div>