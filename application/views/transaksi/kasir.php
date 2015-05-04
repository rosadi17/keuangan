<title><?= $title ?></title>
<?= $this->load->view('message') ?>
<div class="titling"><h1><?= $title ?></h1></div>
<script type="text/javascript">
    
function get_last_code_kasir(trans, tanggal) {
    $.ajax({
        url: '<?= base_url('autocomplete/get_last_code_kasir') ?>/'+trans,
        data: 'tanggal='+tanggal,
        dataType: 'json',
        success: function(data) {
            $('#no').val(data.no);
        }
    });
}

function get_nominal_renbut(id, tahun) {
    $.ajax({
        url: '<?= base_url('autocomplete/get_nominal_renbut') ?>/'+id,
        data: 'tahun='+tahun,
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
    window.open('<?= base_url('transaksi/print_bukti_kas') ?>?id='+id, 'Renbut Cetak', 'width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
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
    $('#jenis').change(function() {
        var nilai = $(this).val();
        if (nilai === 'bkk') {
            $('#perwabku, #kode_renbut').removeAttr('disabled');
            $('#kdatas').html('Kode Perkiraan (K)*:');
            $('#kdbawah').html('Kode Perkiraan Lawan (D)*:');
            $('#pngjwb').html('Penerima:');
        }
        if (nilai === 'bkm') {
            $('.hidden').hide();
            $('#kode_renbut, #id_renbut').val('');
            $('#perwabku, #kode_renbut').attr('disabled','disabled');
            $('#kdatas').html('Kode Perkiraan (D)*:');
            $('#kdbawah').html('Kode Perkiraan Lawan (K)*:');
            $('#pngjwb').html('Penyetor:');
        }
        if (nilai === 'mts') {
            $('#perwabku').val('Default');
            $('#kdatas').html('Kode Perkiraan (D)*:');
            $('#kdbawah').html('Kode Perkiraan Lawan (K)*:');
            $('#pngjwb').html('-');
        }
    });
    $('#cari_button').button({
        icons: {
            secondary: 'ui-icon-search'
        }
    }).click(function() {
        $('#dialog_kasir_search').dialog({
            title: 'Cari Transaksi Kasir',
            autoOpen: true,
            width: 480,
            autoResize:true,
            modal: true,
            hide: 'explode',
            show: 'blind',
            position: ['center',47],
            buttons: {
                "Cancel": function() {
                    $('#dialog_kasir_search').dialog('close');
                },
                "Cari": function() {
                    $('#dialog_kasir_search').dialog('close');
                    get_list_rekap_kasir(1);
                } 
            }, close: function() {
                $('#dialog_kasir_search').dialog('close');
            }, open: function() {
                $('#awal_kasir, #akhir_kasir').datepicker('hide');
                $('#jenis_transaksi').focus();
            }
        });
    });
    $('#cari_rekap_button').button({
        icons: {
            secondary: 'ui-icon-circle-plus'
        }
    }).click(function() {
        $('#form_kasir').dialog({
            title: 'Form Kasir BKK / BKM / Mutasi',
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
            }, open: function() {
                reset_form();
            }
        });
    });
    $('#reload_kasir_data').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        reset_form();
        get_list_rekap_kasir(1);
    });
    $('#excel_kasir').button({
        icons: {
            secondary: 'ui-icon-print'
        }
    }).click(function() {
        location.href='<?= base_url('transaksi/manage_kasir/export_excel') ?>/?'+$('#search_kasir').serialize();
    });
    $('#jenis').change(function() {
        var jenis = $(this).val();
        var tanggal = $('#tanggal').val();
        if (jenis !== '') {
            get_last_code_kasir(jenis, tanggal);
        } else {
            //get_last_code_kasir(jenis, tanggal);
            $('#no').val('');
        }
    });
    $('#awal_kasir, #akhir_kasir').datepicker({
        changeYear: true,
        changeMonth: true
    });
    $('#tanggal').datepicker({
        changeYear: true,
        changeMonth: true,
        onSelect: function() {
            var jenis = $('#jenis').val();
            if ($('#id_kasir').val() === '') {
                get_last_code_kasir(jenis, $(this).val());
                get_nominal_renbut($('#id_kode').val(), $('#tahun').val());
            }
        }
    });
    $('#form').submit(function() {
        if ($('#jenis').val() === '') {
            custom_message('Peringatan', 'Nama transaksi harus dipilih', '#jenis'); return false;
        }
//        if ($('#id_renbut').val() === '') {
//            custom_message('Peringatan', 'Nomor renbut harus dipilih', '#kode_renbut'); return false;
//        }
        if ($('#jenis').val() !== 'mts') {
            if ($('#kode').val() === '') {
                custom_message('Peringatan', 'Kode MA / Proja harus dipilih', '#kode'); return false;
            }
            if ($('#nama_user').val() === '') {
                custom_message('Peringatan', 'Penyetor / Penerima anggaran tidak boleh kosong', '#nama_user'); return false;
            }
        }
        if ($('#jumlah').val() === '') {
            custom_message('Peringatan', 'Jumlah tidak boleh kosong', '#jumlah'); return false;
        }
        if ($('#perwabku').val() === '' && $('#jenis').val() !== 'bkm') {
            custom_message('Peringatan','Jenis transaksi harus dipilih !','#perwabku'); return false;
        }
        $('<div id=alert>Anda yakin akan menyimpan transaksi ini ?</div>').dialog({
            title: 'Konfirmasi',
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
                                    //cetak_bukti_kas(data.id, 'bkm');
                                } else {
                                    get_list_rekap_kasir(1);
                                    custom_message('Informasi','Transaksi BKK berhasil dilakukan !');
                                    //cetak_bukti_kas(data.id, 'bkk');
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
    $('#kode_renbut').autocomplete("<?= base_url('autocomplete/kode_renbut') ?>",
    {
        parse: function(data){
            var parsed = [];
            for (var i=0; i < data.length; i++) {
                parsed[i] = {
                    data: data[i],
                    value: data[i].id // nama field yang dicari
                };
            }
            return parsed;
        },
        formatItem: function(data,i,max){
            var str = '<div class=result>'+data.kode_rk+' '+data.uraian+' &Rightarrow; <i><small>'+data.keterangan+'</small></i></div>';
            return str;
        },
        width: 400, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
        cacheLength: 0,
        max: 100
    }).result(
    function(event,data,formated){
        $('#id_renbut').val(data.id_rk);
        $(this).val(data.kode_rk);
        $('#kode').val(pad(data.ma_proja,5));
        $('#id_kode').val(data.id);
        $('#uraian').val(data.keterangan_light);
        $('#pengguna').val(data.satker);
        $('#keterangan').val(data.uraian);
        $('#jumlah').val(numberToCurrency(data.jml_renbut));
        $('#nama_user').val(data.penerima);
    });
    $('#kode_renbut').setOptions({
        extraParams:{
            tanggal: function(){
                return $('#tanggal').val();
            }
        }
   });
    $('#kode').autocomplete("<?= base_url('autocomplete/ma_proja') ?>",
    {
        extraParams: { 
            tahun: function() { 
                return $('#tahun').val();
            }
        },
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
            var str = '<div class=result>'+pad(data.ma_proja,5)+' / '+data.uraian+' &Rightarrow; <i>'+data.keterangan+'</i></div>';
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
        $('#label_uraian').html(data.keterangan);
        $('#pengguna').val(data.satker);
        $('#keterangan').val(data.uraian);
        get_nominal_renbut(data.id, $('#tahun').val());
    });
    $('#kodema').autocomplete("<?= base_url('autocomplete/ma_proja') ?>",
    {
        extraParams: { 
            tahun: function() { 
                return $('#tahun').val();
            }
        },
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
            var str = '<div class=result>'+pad(data.ma_proja,5)+' / '+data.uraian+' &Rightarrow; <i>'+data.keterangan+'</i></div>';
            return str;
        },
        width: 400, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
        cacheLength: 0,
        max: 100
    }).result(
    function(event,data,formated){
        $(this).val(pad(data.ma_proja,5));
        $('#id_kodema').val(data.id);
        $('#keterangan_ma').html(data.keterangan);
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
        $(this).val(data.id_akun+' '+data.perkiraan);
        $('#hide_kode_perkiraan').val(data.id_akun);
    });
    
    $('#kode_perkiraan').setOptions({
        extraParams:{
            kategori: function(){
                return $('#sumberdana').val();
            }
        }
   });
   
   $('#kode_perkiraan_pwk').autocomplete("<?= base_url('autocomplete/kode_perkiraan_pwk') ?>",
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
        $('#hide_kode_perkiraan_pwk').val(data.id_akun);
    });
    
    $('#kode_perkiraan_pwk').setOptions({
        extraParams:{
            perwabku: function(){
                return $('#perwabku').val();
            }
        }
   });
});

function reset_form() {
    $('input[type=text], input[type=hidden], select, textarea').val('');
    $('#s2id_supplier_auto a .select2-chosen, #label_uraian').html('');
    $('#tanggal').val('<?= date("d/m/Y") ?>');
    $('#awal_kasir').val('<?= date("01/m/Y") ?>');
    $('#akhir_kasir').val('<?= date("d/m/Y") ?>');
    $('#perwabku, #kode_renbut').removeAttr('disabled');
    $('#keterangan_ma').html('');
}

function edit_kasir(id, transaksi) {
    $('#perwabku, #kode_renbut').removeAttr('disabled');
    $('#form_kasir').dialog({
        title: 'Form Kasir BKK / BKM / MUTASI',
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
        }, open: function() {
            $.ajax({
                url: '<?= base_url('transaksi/get_data_kasir') ?>/'+id+'/'+transaksi,
                cache: false,
                dataType: 'json',
                success: function(data) {
                    //$('#result-kasir').html(data);
                    $('#id_kasir').val(data.id);
                    $('#jenis').val(data.kode_trans.toLowerCase());
                    $('#tanggal').val(datefmysql(data.tanggal));
                    $('#no').val(data.kode);
                    $('#sumberdana').val(data.sumberdana);
                    $('#kode_perkiraan').val(data.id_rekening+' '+data.rekening);
                    $('#hide_kode_perkiraan').val(data.id_rekening);
                    $('#kode').val(data.kode_uraian+' '+data.keterangan_ma);
                    $('#id_kode').val(data.id_uraian);
                    $('#pengguna').val(data.satker);
                    $('#label_uraian').html(data.keterangan);
                    $('#uraian').val(data.keterangan_kasir);
                    $('#jumlah').val(numberToCurrency(data.pengeluaran));
                    $('#nama_user').val(data.penerima);
                    $('#perwabku').val(data.perwabku);
                    $('#user').val('Penyetor');
                    $('#kode_renbut').val(data.kode_renbut);
                    $('#id_renbut').val(data.id_renbut);
                    if (data.jenis === 'BKK') {
                        $('#kdatas').html('Kode Perkiraan (K)*:');
                        $('#kdbawah').html('Kode Perkiraan Lawan (D)*:');
                        $('#pngjwb').html('Penerima:');
                        $('#kode_renbut').removeAttr('disabled');
                    }
                    if (data.jenis === 'BKM') {
                        $('#kdatas').html('Kode Perkiraan (D)*:');
                        $('#kdbawah').html('Kode Perkiraan Lawan (K)*:');
                        $('#pngjwb').html('Penyetor:');
                        $('#kode_renbut').attr('disabled','disabled');
                    }
                    if (data.jenis === 'MTS') {
                        $('#kdatas').html('Kode Perkiraan (D)*:');
                        $('#kdbawah').html('Kode Perkiraan Lawan (K)*:');
                        $('#pngjwb').html('-');
                        $('#kode_renbut').removeAttr('disabled');
                    }
                    $('#user').val('Penerima');
                    var kd_pwk = data.kode_rekening_pwk;
                    var id_kd_pwk = data.id_rekening_pwk;
                    
                    $('#kode_perkiraan_pwk').val(kd_pwk);
                    $('#hide_kode_perkiraan_pwk').val(id_kd_pwk);
                }
            });
        }
});
}

function delete_kasir(id, page, kode) {
    $('<div id=alert>Anda yakin akan menghapus data ini?</div>').dialog({
        title: 'Konfirmasi Penghapusan',
        autoOpen: true,
        modal: true,
        buttons: {
            "OK": function() {
                
                $.ajax({
                    url: '<?= base_url('transaksi/manage_kasir/delete') ?>?id='+id+'&kode='+kode,
                    cache: false,
                    success: function() {
                        get_list_rekap_kasir(page);
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

function get_list_rekap_kasir(page) {
    $.ajax({
        url: '<?= base_url('transaksi/manage_kasir') ?>/list/'+page,
        data: $('#search_kasir').serialize(),
        cache: false,
        beforeSend: function() {
            show_ajax_indicator();
        },
        success: function(data) {
            hide_ajax_indicator();
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
            <button id="cari_rekap_button">Tambah</button>
            <button id="cari_button">Cari</button>
            <button id="excel_kasir">Export Excel</button>
            <button id="reload_kasir_data">Reload Data</button>
            <div id="result-kasir">

            </div>
        </div>
    </div>
    <div id="form_kasir" class="nodisplay">
        <?= form_open('', 'id=form') ?>
        <input type="hidden" name="id_kasir" id="id_kasir" />
        <table class="inputan" width="100%">
            <tr><td>Nama Transaksi:</td><td><?= form_dropdown('jenis', array('' => 'Pilih ...', 'bkk' => 'Kas Keluar', 'bkm' => 'Kas Masuk','mts' => 'Mutasi'), NULL, 'id=jenis style="width: 300px;"') ?></td></tr>
            <tr><td>Tanggal Kegiatan:</td><td><?= form_input('tanggal', date("d/m/Y"), 'size=15 id=tanggal') ?></td></tr>
            <tr><td>No.</td><td><?= form_input('no', NULL, 'id=no') ?></td></tr>
            <tr><td>Sumber Dana:</td><td><?= form_dropdown('sumberdana', array('' => 'Pilih ...', 'Kas' => 'Kas', 'Bank' => 'Bank'), NULL, 'id="sumberdana" style="width: 300px;"') ?></td></tr>
            <tr><td id="kdatas">Kode Perkiraan (D)*:</td><td><?= form_input('', NULL, 'id=kode_perkiraan size=60') ?><?= form_hidden('kode_perkiraan', NULL, 'id=hide_kode_perkiraan') ?></td></tr>
            <tr><td>Nomor Renbut:</td><td><?= form_input('kode_renbut', NULL, 'id=kode_renbut size=60') ?><!--<small style="font-style: italic;">Mengacu ke bulan kegiatan</small>--><?= form_hidden('id_renbut', NULL, 'id=id_renbut') ?></td></tr>
            <tr><td>Tahun Anggaran</td><td>
                <select name="tahun" id="tahun">
                <?php for ($i = date("Y"); $i >=2014 ; $i--) { ?>
                    <option value="<?= $i ?>" <?= (($i === date("Y"))?'selected':'') ?>><?= $i ?></option>
                <?php } ?>
                </select></td></tr>
            <tr><td>Kode MA/Proja:</td><td><?= form_input('kode', NULL, 'id=kode') ?><?= form_hidden('id_kode', NULL, 'id=id_kode') ?></td></tr>
            <tr><td>Pengguna Anggaran:</td><td><?= form_input('pengguna', NULL, 'id=pengguna') ?></td></tr>
            <tr><td valign="top">Uraian:</td><td id="label_uraian"></td></tr>
            <tr><td valign="top">Keterangan <i>Memorial</i>:</td><td><?= form_textarea('uraian', NULL, 'id=uraian rows=4 style="width: 294px;"') ?></td></tr>
            <tr><td>Nominal Rp.:</td><td><?= form_input('jumlah', NULL, 'id=jumlah onkeyup="FormNum(this);"') ?></td></tr>
            <tr><td id="pngjwb">-</td><td><?= form_input('nama_user', NULL, 'id=nama_user') ?></td></tr>
            <tr><td>Jenis Transaksi:</td><td><?= form_dropdown('perwabku', array('' => 'Pilih ...', 'Default' => 'Default', 'Belum' => 'Belum (DP)', 'Sudah' => 'Sudah (Pusat Biaya)'), NULL, 'id=perwabku') ?></td></tr>
            <tr><td style="white-space: nowrap;" id="kdbawah">Kode Perkiraan Lawan (K)*:</td><td><?= form_input('', NULL, 'id=kode_perkiraan_pwk size=60') ?><?= form_hidden('kode_perkiraan_pwk', NULL, 'id=hide_kode_perkiraan_pwk') ?></td></tr>
            <!--<tr><td></td><td><?= form_button('Simpan', 'id=simpan') ?> <?= form_button('Reset', 'id=reset') ?></td></tr>-->
        </table>
        <?= form_close() ?>
    </div>
    <div id="dialog_kasir_search" class="nodisplay">
        <form action="" id="search_kasir">
            <table width=100% cellpadding=0 cellspacing=0 class=inputan>
                <tr><td>Range Tanggal:</td><td><input type="text" name="awal" id="awal_kasir" value="<?= date("01/m/Y") ?>" size="10" /> s.d <input type="text" name="akhir" id="akhir_kasir" value="<?= date("d/m/Y") ?>" /></td></tr>
                <tr><td>Transaksi:</td><td><?= form_dropdown('jenis', array('' => 'Semua Jenis ...', 'BKK' => 'Kas Keluar', 'BKM' => 'Kas Masuk','MTS' => 'Mutasi'), NULL, 'id=jenis_transaksi style="width: 300px;"') ?></td></tr>
                <tr><td>Kode MA/Proja:</td><td><?= form_input('kode', NULL, 'id=kodema') ?><?= form_hidden('id_kode', NULL, 'id=id_kodema') ?></td></tr>
                <tr><td>Keterangan MA:</td><td id="keterangan_ma"></td></tr>
                <tr><td>Kegiatan:</td><td><input type="text" name="kegiatan" id="kegiatan" /></td></tr>
                <tr><td>Penanggung Jawab:</td><td><input type="text" name="png_jwb" id="png_jwb" /></td></tr>
            </table>
        </form>
    </div>
</div>