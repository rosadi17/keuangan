<title><?= $title ?></title>
<?= $this->load->view('message') ?>
<div class="titling"><h1><?= $title ?></h1></div>
<script type="text/javascript">

    $(function() {
        get_list_rekap_kasir(1);
        $('#tabs').tabs();
        $('#reset').click(function() {
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
        $('#cari_button').click(function() {
            $('#datamodal').modal('show');
        });
        $('#tambah_button').click(function() {
            $('#datamodal_tambah').modal('show');
        });
        $('#reload_kasir_data').click(function() {
            reset_form();
            get_list_rekap_kasir(1);
        });
        $('#excel_kasir').click(function() {
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
             format: 'dd/mm/yyyy'
        }).on('changeDate', function(){
            $(this).datepicker('hide');
        });

        $('#tanggal').datepicker({
            format: 'dd/mm/yyyy'
        }).on('changeDate', function(){
            $(this).datepicker('hide');
            var jenis = $('#jenis').val();
            if ($('#id_kasir').val() === '') {
                get_last_code_kasir(jenis, $(this).val());
                get_nominal_renbut($('#id_kode').val(), $('#tahun').val());
            }
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
        $('#kode').blur(function() {
            if ($('#kode').val() === '' || $('#kode').val() === ' ') {
                $('#id_kode').val('');
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
            width: 515, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
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
       
        $('.form-control').change(function() {
            if ($(this).val() !== '') {
                dc_validation_remove(this);
            }
        });
    });
    
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
        $('#datamodal_tambah').modal('show');
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
                $('#tahun').val(data.tahun_anggaran);
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

    function delete_kasir(id, page, kode) {
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
                    url: '<?= base_url('transaksi/manage_kasir/delete') ?>?id='+id+'&kode='+kode,
                    cache: false,
                    success: function() {
                        get_list_rekap_kasir(page);
                        message_delete_success();
                    },
                    error: function() {
                        message_delete_failed();
                    }
                });
              }
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
                save_kasir();
              }
            }
          }
        });
    }
    
    function save_kasir() {
        $.ajax({
            url: '<?= base_url('transaksi/kasir_save') ?>',
            type: 'POST',
            data: $('#form').serialize(),
            dataType: 'json',
            success: function(data) {
                $('#datamodal_tambah').modal('hide');
                var page = $('.noblock').html();
                if (data.status === true) {
                    if (data.act === 'add') {
                        get_list_rekap_kasir(1);
                        message_add_success();
                        //cetak_bukti_kas(data.id, 'bkm');
                    } else {
                        get_list_rekap_kasir(page);
                        message_edit_success();
                        //cetak_bukti_kas(data.id, 'bkk');
                    }
                    $('#form_kasir').dialog('destroy');
                }
                reset_form();
            }
        });
    }

    function get_list_rekap_kasir(page) {
        $('#datamodal').modal('hide');
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
            <button class="btn btn-primary" id="tambah_button"><i class="fa fa-plus-circle"></i> Tambah</button>
            <button class="btn" id="cari_button"><i class="fa fa-search"></i> Cari</button>
            <button class="btn" id="excel_kasir"><i class="fa fa-file-text-o"></i> Export Excel</button>
            <button class="btn" id="reload_kasir_data"><i class="fa fa-refresh"></i> Reload Data</button>
            <div id="result-kasir">

            </div>
        </div>
    </div>
    <div id="datamodal_tambah" class="modal fade">
    <div class="modal-dialog" style="width: 820px;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="modal_title">Tambah Transaksi Kasir</h4>
        </div>
        <div class="modal-body">
        <?= form_open('', 'id=form role="form" class="form-horizontal"') ?>
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
                    <?= form_textarea('uraian', NULL, 'id=uraian rows=10 class="form-control"') ?>
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
    
    
    <div id="datamodal" class="modal fade">
    <div class="modal-dialog" style="width: 600px;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="modal_title">Pencarian</h4>
        </div>
        <div class="modal-body">
        <form action="" id="search_kasir" role="form" class="form-horizontal">
            <div class="form-group">
                <label class="col-lg-3 control-label">Range Tanggal:</label>
                <div class="col-lg-8">
                    <div><input type="text" name="awal" id="awal_kasir" class="hasDatepicker form-control" value="<?= date("01/m/Y") ?>" size="10" /> </div>
                    <div><input type="text" name="akhir" id="akhir_kasir" value="<?= date("d/m/Y") ?>" class="hasDatepicker form-control" /></div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Transaksi:</label>
                <div class="col-lg-8">
                    <?= form_dropdown('jenis', array('' => 'Semua Jenis ...', 'BKK' => 'Kas Keluar', 'BKM' => 'Kas Masuk','MTS' => 'Mutasi'), NULL, 'id=jenis_transaksi class="form-control"') ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Nomor Bukti:</label>
                <div class="col-lg-8">
                    <input type="text" name="nomorbukti" id="nomorbukti" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Kode MA/Proja:</label>
                <div class="col-lg-8">
                    <?= form_input('kode', NULL, 'id=kodema class="form-control"') ?><?= form_hidden('id_kode', NULL, 'id=id_kodema') ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Keterangan MA:</label>
                <div class="col-lg-8" id="keterangan_ma">
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Kegiatan:</label>
                <div class="col-lg-8">
                    <input type="text" name="kegiatan" id="kegiatan" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Penanggung Jwb:</label>
                <div class="col-lg-8">
                    <input type="text" name="png_jwb" id="png_jwb" class="form-control" />
                </div>
            </div>
        </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
          <button type="button" class="btn btn-primary" id="save" onclick="get_list_rekap_kasir(1);"><i class="fa fa-eye"></i> Tampilkan</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>