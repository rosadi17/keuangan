<title><?= $title ?></title>
<?= $this->load->view('message') ?>
<div class="titling"><h1><?= $title ?></h1></div>
<script type="text/javascript">
    $(function() {
        get_list_jurnal(1);
        get_list_verifikasi(1);
        $('#tanggal').datepicker({
            format: 'dd/mm/yyyy'
        }).on('changeDate', function(){
            $(this).datepicker('hide');
        });

        $('#awal_verifikasi, #akhir_verifikasi, #awal_jurnal, #akhir_jurnal').datepicker({
            format: 'dd/mm/yyyy'
        }).on('changeDate', function(){
            $(this).datepicker('hide');
        });

        $('#tabs').tabs();
        $('#simpan').click(function() {
            $('#form').submit();
        });

        $('#add_jurnal').click(function() {
            $('#datamodal_tambah_jurnal').modal('show');
            rek_debet_add_row();
            rek_kredit_add_row();
        });

        $('#reload').click(function() {
            $('#awal_jurnal').val('<?= date("01/m/Y") ?>');
            $('#akhir_jurnal').val('<?= date("d/m/Y") ?>');
            $('#kode_rekening, #nobukti').val('');
            get_list_jurnal(1);
        });

        $('#reload_verifikasi').click(function() {
            reset_form();
            get_list_verifikasi(1);
        });

        $('#cari_verifikasi').click(function() {
            $('#datamodal_search_verifikasi').modal('show');
        });

        $('#cari_jurnal').click(function() {
            $('#datamodal_search_jurnal').modal('show');
        });

        $('#jenis').change(function() {
            var jenis = $(this).val();
            get_last_code_kasir(jenis);
        });
        $('#tanggal').datepicker();
        
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
            width: 515, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
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
                //$('#id_kode').val('');
                return parsed;
            },
            formatItem: function(data,i,max){
                var str = '<div class=result>'+pad(data.ma_proja,5)+' / '+data.uraian+' &Rightarrow; <i>'+data.keterangan+'</i></div>';
                return str;
            },
            width: 515, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
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
            width: 515, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
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
            width: 515, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
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

    function save_jurnal() {
        $.ajax({
            url: '<?= base_url('transaksi/save_jurnal_transaksi') ?>',
            data: $('#form').serialize(),
            dataType: 'json',
            type: 'POST',
            success: function(data) {
                $('#datamodal_tambah_jurnal').modal('hide');
                if (data.status === true) {
                    message_add_success();
                    reset_form();
                    get_list_jurnal(1);
                } else {
                    message_add_failed();
                }
            }
        });
    }
    
    function konfirmasi_simpan() {
        if ($('#jenis').val() === '') {
            dc_validation('#jenis','Nama transaksi harus dipilih'); return false;
        }
        /*if ($('#id_renbut').val() === '') {
            custom_message('Peringatan', 'Nomor renbut harus dipilih', '#kode_renbut'); return false;
        }*/
        if ($('#jumlah').val() === '') {
            dc_validation('#jumlah','Jumlah tidak boleh kosong'); return false;
        }
        if ($('#jenis').val() !== 'mts') {
            /*if ($('#kode').val() === '') {
                custom_message('Peringatan', 'Kode MA / Proja harus dipilih', '#kode'); return false;
            }*/
            if ($('#nama_user').val() === '') {
                dc_validation('#nama_user','Penyetor / Penerima anggaran tidak boleh kosong'); return false;
            }
        }
        if ($('#perwabku').val() === '' && $('#jenis').val() !== 'bkm') {
            dc_validation('#perwabku','Jenis transaksi harus dipilih !'); return false;
        }
        
        bootbox.dialog({
          message: "Anda yakin akan menyimpan data ini?",
          title: "Konfirmasi Simpan",
          buttons: {
            batal: {
              label: '<i class="fa fa-refresh"></i> Batal',
              className: "btn-default",
              callback: function() {
                
              }
            },
            ya: {
              label: '<i class="fa fa-check-square-o"></i>  Ya',
              className: "btn-primary",
              callback: function() {
                edit_verifikasi();
              }
            }
          }
        });
    }

    function edit_verifikasi() {    
        $.ajax({
            url: '<?= base_url('transaksi/kasir_save') ?>',
            type: 'POST',
            data: $('#formkasir').serialize(),
            dataType: 'json',
            success: function(data) {
                var page = $('.noblock').html();
                if (data.status === true) {
                    get_list_verifikasi(page);
                    message_edit_success();
                    $('#datamodal_tambah_jurnal').modal('hide');
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
                                    message_add_success();
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
        $('#datamodal_kasir').modal('show');
        $('#datamodal_kasir h4').html('Edit Transaksi Kasir')
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

    function reset_form() {
        $('input[type=text], textarea').val('');
        $('#awal_verifikasi').val('<?= date("01/m/Y") ?>');
        $('#akhir_verifikasi').val('<?= date("d/m/Y") ?>');
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
        $('#datamodal_search_verifikasi').modal('hide');
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
    
    function konfirmasi_save_jurnal() {
        bootbox.dialog({
          message: "Anda yakin akan menyimpan data ini?",
          title: "Konfirmasi Simpan",
          buttons: {
            batal: {
              label: '<i class="fa fa-refresh"></i> Batal',
              className: "btn-default",
              callback: function() {
                
              }
            },
            ya: {
              label: '<i class="fa fa-check-square-o"></i>  Ya',
              className: "btn-primary",
              callback: function() {
                save_jurnal();
              }
            }
          }
        });
    }

    function removeDebet(el) {
        var parent = el.parentNode.parentNode;
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
        str = '<div class="rows_debet">'+
                '<div style="float: left; margin-bottom: 10px; margin-right: 5px;"><input type="text" id="kode_perkiraan_d'+i+'" class="kode_perkiraan_d form-control" style="width: 300px;" placeholder="Kode akun ..." /></div> '+
                '<input type="hidden" name="kode_perkiraan_d[]" class="hide_kode_perkiraan_d" id="hide_kode_perkiraan_d'+i+'" />'+
                '<div style="float: left; margin-bottom: 10px; margin-right: 5px;"><input type="text" name="jumlah_d[]" id="jumlah_d'+i+'" onkeyup="FormNum(this);" class="jumlah_d form-control" style="width: 150px;" placeholder="Nominal ..." /> </div>'+
                '<div style="float: left; margin-bottom: 10px; margin-right: 5px;"><button type="button" class="btn delete" onClick="removeDebet(this);"><i class="fa fa-trash-o"></i></button></div>'+
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
        var parent = el.parentNode.parentNode;
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
        str = '<div class="rows_kredit">'+
                '<div style="float: left; margin-bottom: 10px; margin-right: 5px;"><input type="text" id="kode_perkiraan_k'+i+'" style="width: 300px;" placeholder="Kode akun ..." class="form-control" /> </div>'+
                '<input type="hidden" name="kode_perkiraan_k[]" id="hide_kode_perkiraan_k'+i+'" />'+
                '<div style="float: left; margin-bottom: 10px; margin-right: 5px;"><input type="text" name="jumlah_k[]" id="jumlah_k'+i+'" onkeyup="FormNum(this);" style="width: 150px;" class="form-control" placeholder="Nominal ..." /> </div>'+
                '<div style="float: left; margin-bottom: 10px; margin-right: 5px;"><button type="button" class="btn delete" onClick="removeKredit(this);"><i class="fa fa-trash-o"></i></button></div>'+
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
        bootbox.dialog({
          message: "Anda yakin akan menghapus data ini?",
          title: "Hapus Data",
          buttons: {
            batal: {
              label: '<i class="fa fa-refresh"></i> Batal',
              className: "btn-default",
              callback: function() {
                
              }
            },
            hapus: {
              label: '<i class="fa fa-trash-o"></i>  Hapus',
              className: "btn-primary",
              callback: function() {
                $.ajax({
                    url: '<?= base_url('transaksi/manage_jurnal/delete') ?>?id='+id,
                    cache: false,
                    success: function() {
                        get_list_jurnal(page);
                        message_delete_success();
                    }
                });
              }
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
                <button class="btn" id="cari_verifikasi"><i class="fa fa-search"></i> Cari</button>
                <button class="btn" id="reload_verifikasi"><i class="fa fa-refresh"></i> Reload Data</button>
                <div id="result_verifikasi"></div>
            </div>
            <div id="tabs-2">
                <button class="btn btn-primary" id="add_jurnal"><i class="fa fa-plus-circle"></i> Tambah Data</button>
                <button class="btn" id="cari_jurnal"><i class="fa fa-search"></i> Cari</button>
                <button class="btn" id="reload"><i class="fa fa-refresh"></i> Reload Data</button>
                <div id="result"></div>
            </div>
        </div>
    </div>
    <div id="datamodal_tambah_jurnal" class="modal fade">
    <div class="modal-dialog" style="width: 800px;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="modal_title"></h4>
        </div>
        <div class="modal-body">
        <?= form_open('', 'id=form role="form" class="form-horizontal"') ?>
            <div class="form-group">
                <label class="col-lg-3 control-label">Tanggal:</label>
                <div class="col-lg-8">
                    <input type="text" name="tanggal" id="tanggal" value="<?= date("d/m/Y") ?>" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Kode BKK/BKM:</label>
                <div class="col-lg-8">
                    <?= form_input('kode_transaksi', NULL, 'id=kode_transaksi class="form-control"') ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label"></label>
                <div class="col-lg-8">
                    <button type="button" class="btn btn-default btn-xs delete" onclick="rek_debet_add_row();"><i class="fa fa-plus-circle"></i> Tambah Kode (D)</button> <button type="button" class="btn btn-default btn-xs delete" onclick="rek_kredit_add_row();"><i class="fa fa-plus-circle"></i> Tambah Kode (K)</button>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Kode Akun (D):</label>
                <div class="col-lg-8" id="rows_debet">
                    
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Kode Akun (K):</label>
                <div class="col-lg-8" id="rows_kredit">
                    
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Uraian:</label>
                <div class="col-lg-8">
                    <?= form_textarea('uraian', NULL, 'id=uraian rows=4 class="form-control"') ?>
                </div>
            </div>
        <?= form_close() ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
          <button type="button" class="btn btn-primary" id="save" onclick="konfirmasi_save_jurnal();"><i class="fa fa-save"></i> Simpan</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
        
    
    <div id="datamodal_search_jurnal" class="modal fade">
    <div class="modal-dialog" style="width: 600px;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="modal_title">Pencarian</h4>
        </div>
        <div class="modal-body">
        <form action="" id="search_jurnal" role="form" class="form-horizontal">
            <div class="form-group">
                <label class="col-lg-3 control-label">Range Tanggal:</label>
                <div class="col-lg-8">
                    <input type="text" name="awal" id="awal_jurnal" value="<?= date("01/m/Y") ?>" size="10" class="hasDatepicker form-control" /> <input type="text" name="akhir" id="akhir_jurnal" value="<?= date("d/m/Y") ?>" class="hasDatepicker form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">No. Rekening:</label>
                <div class="col-lg-8">
                    <input type="text" name="kode_rekening" id="kode_rekening" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">No. Bukti:</label>
                <div class="col-lg-8">
                    <input type="text" name="nobukti" id="nobukti" class="form-control" />
                </div>
            </div>
        </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
          <button type="button" class="btn btn-primary" id="save" onclick="get_list_jurnal(1);"><i class="fa fa-eye"></i> Tampilkan</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
<!--    <div id="form_kasir" class="nodisplay">
        <?= form_open('', 'id=formkasir') ?>
        <input type="hidden" name="id_kasir" id="id_kasir" />
        <table class="inputan" width="100%">
            <tr><td>Nama Transaksi:</td><td><?= form_dropdown('jenis', array('' => 'Pilih ...', 'bkk' => 'Kas Keluar', 'bkm' => 'Kas Masuk','mts' => 'Mutasi'), NULL, 'id=jenis style="width: 300px;"') ?></td></tr>
            <tr><td>Tanggal Kegiatan:</td><td><?= form_input('tanggal', date("d/m/Y"), 'size=15 id=tanggal') ?></td></tr>
            <tr><td>No.</td><td><?= form_input('no', NULL, 'id=no') ?></td></tr>
            <tr><td>Sumber Dana:</td><td><?= form_dropdown('sumberdana', array('' => 'Pilih ...', 'Kas' => 'Kas', 'Bank' => 'Bank'), NULL, 'id="sumberdana" style="width: 300px;"') ?></td></tr>
            <tr><td id="kdatas">Kode Perkiraan (D)*:</td><td><?= form_input('', NULL, 'id=kode_perkiraan size=60') ?><?= form_hidden('kode_perkiraan', NULL, 'id=hide_kode_perkiraan') ?></td></tr>
            <tr><td>Nomor Renbut:</td><td><?= form_input('kode_renbut', NULL, 'id=kode_renbut size=60') ?><small style="font-style: italic;">Mengacu ke bulan kegiatan</small><?= form_hidden('id_renbut', NULL, 'id=id_renbut') ?></td></tr>
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
            <tr><td></td><td><?= form_button('Simpan', 'id=simpan') ?> <?= form_button('Reset', 'id=reset') ?></td></tr>
        </table>
        <?= form_close() ?>
    </div>-->
    <div id="datamodal_kasir" class="modal fade">
    <div class="modal-dialog" style="width: 820px;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="modal_title">Tambah Transaksi Kasir</h4>
        </div>
        <div class="modal-body">
        <?= form_open('', 'id=formkasir role="form" class="form-horizontal"') ?>
            <input type="hidden" name="id_kasir" id="id_kasir" />
            <div class="form-group">
                <label class="col-lg-3 control-label">Nama Transaksi:</label>
                <div class="col-lg-8">
                    <?= form_dropdown('jenis', array('' => 'Pilih ...', 'bkk' => 'Kas Keluar', 'bkm' => 'Kas Masuk','mts' => 'Mutasi'), NULL, 'id=jenis class="form-control"') ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Tanggal Kegiatan:</label>
                <div class="col-lg-8">
                    <?= form_input('tanggal', date("d/m/Y"), 'size=15 id=tanggal class="form-control"') ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">No.:</label>
                <div class="col-lg-8">
                    <?= form_input('no', NULL, 'id=no class="form-control"') ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Sumber Dana:</label>
                <div class="col-lg-8">
                    <?= form_dropdown('sumberdana', array('' => 'Pilih ...', 'Kas' => 'Kas', 'Bank' => 'Bank'), NULL, 'id="sumberdana" class="form-control"') ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label" id="kdatas">Kode Perkiraan (D)*:</label>
                <div class="col-lg-8">
                    <div><?= form_input('', NULL, 'id=kode_perkiraan class="form-control"') ?></div>
                    <div><?= form_hidden('kode_perkiraan', NULL, 'id=hide_kode_perkiraan') ?></div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Nomor Renbut:</label>
                <div class="col-lg-8">
                    <div><?= form_input('kode_renbut', NULL, 'id=kode_renbut class="form-control"') ?></div>
                    <div><?= form_hidden('id_renbut', NULL, 'id=id_renbut') ?></div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Tahun Anggaran:</label>
                <div class="col-lg-8">
                    <select name="tahun" id="tahun" class="form-control">
                    <?php for ($i = date("Y"); $i >=2014 ; $i--) { ?>
                        <option value="<?= $i ?>" <?= (($i === date("Y"))?'selected':'') ?>><?= $i ?></option>
                    <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Kode MA/Proja:</label>
                <div class="col-lg-8">
                    <div><?= form_input('kode', NULL, 'id=kode class="form-control"') ?></div>
                    <div><?= form_hidden('id_kode', NULL, 'id=id_kode') ?></div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Pengguna Anggaran:</label>
                <div class="col-lg-8">
                    <?= form_input('pengguna', NULL, 'id=pengguna class="form-control"') ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Uraian:</label>
                <div class="col-lg-8" id="label_uraian">
                    
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Keterangan <i>Memorial</i>:</label>
                <div class="col-lg-8">
                    <?= form_textarea('uraian', NULL, 'id=uraian_kasir rows=10 class="form-control"') ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Nominal Rp.:</label>
                <div class="col-lg-8">
                    <?= form_input('jumlah', NULL, 'id=jumlah onblur="FormNum(this);" class="form-control"') ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label" id="pngjwb">-</label>
                <div class="col-lg-8">
                    <?= form_input('nama_user', NULL, 'id=nama_user class="form-control"') ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Jenis Transaksi:</label>
                <div class="col-lg-8">
                    <?= form_dropdown('perwabku', array('' => 'Pilih ...', 'Default' => 'Default', 'Belum' => 'Belum (DP)', 'Sudah' => 'Sudah (Pusat Biaya)'), NULL, 'id=perwabku class="form-control"') ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label"" id="kdbawah">Kode Perkiraan Lawan (K)*:</label>
                <div class="col-lg-8">
                    <?= form_input('', NULL, 'id=kode_perkiraan_pwk class="form-control"') ?><?= form_hidden('kode_perkiraan_pwk', NULL, 'id=hide_kode_perkiraan_pwk') ?>
                </div>
            </div>
        <?= form_close() ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
          <button type="button" class="btn btn-primary" id="save" onclick="konfirmasi_simpan();"><i class="fa fa-save"></i> Simpan</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div id="datamodal_search_verifikasi" class="modal fade">
    <div class="modal-dialog" style="width: 600px;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="modal_title">Pencarian</h4>
        </div>
        <div class="modal-body">
        <form action="" id="search_kasir_verifikasi" role="form" class="form-horizontal">
            <div class="form-group">
                <label class="col-lg-3 control-label">Range Tanggal:</label>
                <div class="col-lg-8">
                    <input type="text" name="awal" id="awal_verifikasi" value="<?= date("01/m/Y") ?>" size="10" class="hasDatepicker form-control" /> <input type="text" name="akhir" id="akhir_verifikasi" value="<?= date("d/m/Y") ?>" class="hasDatepicker form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Transaksi:</label>
                <div class="col-lg-8">
                    <?= form_dropdown('jenis', array('' => 'Semua Jenis ...', 'BKK' => 'Kas Keluar', 'BKM' => 'Kas Masuk','MTS' => 'Mutasi'), NULL, 'id=jenis_transaksi class="form-control"') ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Kegiatan:</label>
                <div class="col-lg-8">
                    <input type="text" name="kegiatan" id="kegiatan" class="form-control" />
                </div>
            </div>
        </table>
        </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
          <button type="button" class="btn btn-primary" id="save" onclick="get_list_verifikasi(1);"><i class="fa fa-eye"></i> Tampilkan</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    
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