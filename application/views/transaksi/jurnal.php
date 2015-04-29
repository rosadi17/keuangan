<title><?= $title ?></title>
<?= $this->load->view('message') ?>
<div class="titling"><h1><?= $title ?></h1></div>
<script type="text/javascript">
$(function() {
    get_list_jurnal(1);
    get_list_verifikasi(1);
    $('#tanggal').datepicker({
        changeYear: true,
        changeMonth: true
    });
    $('#awal_verifikasi, #akhir_verifikasi, #awal_jurnal, #akhir_jurnal').datepicker({
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
        $('#awal_jurnal').val('<?= date("01/m/Y") ?>');
        $('#akhir_jurnal').val('<?= date("d/m/Y") ?>');
        $('#kode_rekening, #nobukti').val('');
        get_list_jurnal(1);
    });
    
    $('#reload_verifikasi').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        $('#kegiatan, #jenis_transaksi').val('');
        get_list_verifikasi(1);
    });
    
    $('#cari_verifikasi').button({
        icons: {
            secondary: 'ui-icon-search'
        }
    }).click(function() {
        $('#dialog_verifikasi_search').dialog({
            title: 'Cari Data',
            autoOpen: true,
            width: 480,
            autoResize:true,
            modal: true,
            hide: 'explode',
            show: 'blind',
            position: ['center',47],
            buttons: {
                "Cancel": function() {
                    $('#dialog_verifikasi_search').dialog('close');
                },
                "Cari": function() {
                    $('#dialog_verifikasi_search').dialog('close');
                    get_list_verifikasi(1);
                } 
            }, close: function() {
                $('#dialog_verifikasi_search').dialog('close');
            }, open: function() {
                $('#awal_verifikasi, #akhir_verifikasi').datepicker('hide');
                $('#jenis_transaksi').focus();
            }
        });
    });
    
    $('#cari_jurnal').button({
        icons: {
            secondary: 'ui-icon-search'
        }
    }).click(function() {
        $('#dialog_jurnal_search').dialog({
            title: 'Cari Data',
            autoOpen: true,
            width: 480,
            autoResize:true,
            modal: true,
            hide: 'explode',
            show: 'blind',
            position: ['center',47],
            buttons: {
                "Cancel": function() {
                    $('#dialog_jurnal_search').dialog('close');
                },
                "Cari": function() {
                    $('#dialog_jurnal_search').dialog('close');
                    get_list_jurnal(1);
                } 
            }, close: function() {
                $('#dialog_jurnal_search').dialog('close');
            }, open: function() {
                $('#awal_jurnal, #akhir_jurnal').datepicker('hide');
                $('#kode_rekening').focus();
            }
        });
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

function edit_verifikasi() {
    
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
                        data: $('#formkasir').serialize(),
                        dataType: 'json',
                        success: function(data) {
                            var page = $('.noblock').html();
                            if (data.status === true) {
                                get_list_verifikasi(page);
                                custom_message('Informasi','Transaksi berhasil dilakukan !');
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
}

function verifikasi(id, page) {
    $('#dialog_konfirm_verifikasi').dialog({
            title: 'Konfirmasi Verifikasi ',
            autoOpen: true,
            modal: true,
            width: 480,
            autoResize:true,
            hide: 'explode',
            show: 'blind',
            position: ['center',47],
            buttons: {
                "Batal": function() {
                    $(this).dialog('close');
                },
                "OK": function() {
                    $(this).dialog('close');
                    $.ajax({
                        url: '<?= base_url('transaksi/manage_jurnal/save_verifikasi') ?>',
                        data: 'id='+id,
                        dataType: 'json',
                        success: function(data) {
                            get_list_verifikasi(page);
                            get_list_jurnal(1);
                            if (data.status === true) {
                                custom_message('Informasi','Verifikasi & posting jurnal berhasil dilakukan !');
                            }
                        }
                    });
                }
            },
            open: function() {
                $.ajax({
                    url: '<?= base_url('transaksi/get_data_kasir') ?>/'+id,
                    cache: false,
                    dataType: 'json',
                    success: function(data) {
                        $('#label_tgl_trans').html(datefmysql(data.tanggal));
                        $('#label_uraian_verif').html(data.keterangan);
                        $('#label_keterangan').html(data.keterangan_kasir);
                        $('#no_bukti').html(data.kode);
                        $('#value_rek_one').html(data.id_rekening+' '+data.rekening);
                        if (data.jenis === 'BKK') {
                            $('#rek_one').html('Kode Perkiraan (K)*:');
                            $('#rek_two').html('Kode Perkiraan (D)*:');
                        }
                        if (data.jenis === 'BKM') {
                            $('#rek_one').html('Kode Perkiraan (D)*:');
                            $('#rek_two').html('Kode Perkiraan (K)*:');
                        }
                        if (data.jenis === 'MTS') {
                            $('#rek_one').html('Kode Perkiraan (D)*:');
                            $('#rek_two').html('Kode Perkiraan (K)*:');
                        }
                        $('#value_rek_two').html(data.id_rekening_pwk+' '+data.rekening_pwk);
                    }
                });
            }
    });
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
                $('#form_kasir').dialog('close');
            }, "Simpan": function() {
                edit_verifikasi();
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
                    $('#uraian_kasir').val(data.keterangan_kasir);
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

function reset_form() {
    $('input[type=text], textarea').val('');
}

function get_list_jurnal(page) {
    $.ajax({
        url: '<?= base_url('transaksi/manage_jurnal') ?>/list/'+page,
        cache: false,
        data: $('#search_jurnal').serialize(),
        beforeSend: function() {
            show_ajax_indicator();
        },
        success: function(data) {
            hide_ajax_indicator();
            $('#result').html(data);
        }
    });
}

function get_list_verifikasi(page) {
    $.ajax({
        url: '<?= base_url('transaksi/manage_jurnal') ?>/verifikasi/'+page,
        cache: false,
        data: $('#search_kasir_verifikasi').serialize(),
        beforeSend: function() {
            show_ajax_indicator();
        },
        success: function(data) {
            hide_ajax_indicator();
            $('#result_verifikasi').html(data);
        }
    });
}

function paging(page, tab, search) {
    if (tab === 1) {
        get_list_verifikasi(page);
    } else {
        get_list_jurnal(page, search);
    }
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
            <li><a href="#tabs-1">Verifikasi Data</a></li>
            <li><a href="#tabs-2">Jurnal Transaksi</a></li>
        </ul>
        <div id="tabs-1">
            <button id="cari_verifikasi">Cari</button>
            <button id="reload_verifikasi">Reload Data</button>
            <div id="result_verifikasi"></div>
        </div>
        <div id="tabs-2">
            <button id="add_jurnal">Tambah Data</button>
            <button id="cari_jurnal">Cari</button>
            <button id="reload">Reload Data</button>
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
<div id="dialog_verifikasi_search" class="nodisplay">
    <form action="" id="search_kasir_verifikasi">
        <table width=100% cellpadding=0 cellspacing=0 class=inputan>
            <tr><td>Range Tanggal:</td><td><input type="text" name="awal" id="awal_verifikasi" value="<?= date("01/m/Y") ?>" size="10" /> s.d <input type="text" name="akhir" id="akhir_verifikasi" value="<?= date("d/m/Y") ?>" /></td></tr>
            <tr><td>Transaksi:</td><td><?= form_dropdown('jenis', array('' => 'Semua Jenis ...', 'BKK' => 'Kas Keluar', 'BKM' => 'Kas Masuk','MTS' => 'Mutasi'), NULL, 'id=jenis_transaksi style="width: 300px;"') ?></td></tr>
            <tr><td>Kegiatan:</td><td><input type="text" name="kegiatan" id="kegiatan" /></td></tr>
        </table>
    </form>
</div>
<div id="dialog_jurnal_search" class="nodisplay">
    <form action="" id="search_jurnal">
        <table width=100% cellpadding=0 cellspacing=0 class=inputan>
            <tr><td>Range Tanggal:</td><td><input type="text" name="awal" id="awal_jurnal" value="<?= date("01/m/Y") ?>" size="10" /> s.d <input type="text" name="akhir" id="akhir_jurnal" value="<?= date("d/m/Y") ?>" /></td></tr>
            <tr><td>No. Rekening:</td><td><input type="text" name="kode_rekening" id="kode_rekening" /></td></tr>
            <tr><td>No. Bukti:</td><td><input type="text" name="nobukti" id="nobukti" /></td></tr>
        </table>
    </form>
</div>
<div id="form_kasir" class="nodisplay">
    <?= form_open('', 'id=formkasir') ?>
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
        <tr><td valign="top">Keterangan <i>Memorial</i>:</td><td><?= form_textarea('uraian', NULL, 'id=uraian_kasir rows=4 style="width: 294px;"') ?></td></tr>
        <tr><td>Nominal Rp.:</td><td><?= form_input('jumlah', NULL, 'id=jumlah onkeyup="FormNum(this);"') ?></td></tr>
        <tr><td id="pngjwb">-</td><td><?= form_input('nama_user', NULL, 'id=nama_user') ?></td></tr>
        <tr><td>Jenis Transaksi:</td><td><?= form_dropdown('perwabku', array('' => 'Pilih ...', 'Default' => 'Default', 'Belum' => 'Belum (DP)', 'Sudah' => 'Sudah (Pusat Biaya)'), NULL, 'id=perwabku') ?></td></tr>
        <tr><td style="white-space: nowrap;" id="kdbawah">Kode Perkiraan Lawan (K)*:</td><td><?= form_input('', NULL, 'id=kode_perkiraan_pwk size=60') ?><?= form_hidden('kode_perkiraan_pwk', NULL, 'id=hide_kode_perkiraan_pwk') ?></td></tr>
        <!--<tr><td></td><td><?= form_button('Simpan', 'id=simpan') ?> <?= form_button('Reset', 'id=reset') ?></td></tr>-->
    </table>
    <?= form_close() ?>
</div>
<div id="dialog_konfirm_verifikasi" class="nodisplay">
    <table width=100% cellpadding=0 cellspacing=0 class=inputan>
        <tr><td>Tanggal Transaksi:</td><td id="label_tgl_trans"></td></tr>
        <tr><td>Uraian:</td><td id="label_uraian_verif"></td></tr>
        <tr><td>Keterangan <i>Memorial:</i></td><td id="label_keterangan"></td></tr>
        <tr><td>No. Bukti:</td><td id="no_bukti"></td></tr>
        <tr><td id="rek_one">Kode Perkiraan (D):</td><td id="value_rek_one"></td></tr>
        <tr><td id="rek_two">Kode Perkiraan (K):</td><td id="value_rek_two"></td></tr>
    </table>
    <br/><b>Anda yakin akan memverifikasi data transaksi ini ?</b>
</div>