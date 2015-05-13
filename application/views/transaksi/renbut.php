<title><?= $title ?></title>
<div class="titling"><h1><?= $title ?></h1></div>
<?= $this->load->view('message') ?>
<script type="text/javascript">
    $(function() {
        $('#tabs').tabs();
        get_list_renbut(1);
        $('#add_renbut').click(function() {
            //form_renbut();
            $('#datamodal_tambah').modal('show');
            get_nomor_renbut();
        });
        $('#cari_button').click(function() {
            $('#datamodal').modal('show');
        });
        $('#reload_renbut').click(function() {
            reset_form();
            get_list_renbut(1);
        });
        $('#awal, #akhir, #awal_keg, #akhir_keg').datepicker({
            format: 'dd/mm/yyyy'
        }).on('changeDate', function(){
            $(this).datepicker('hide');
        });
        
        $('#tanggal_renbut').datepicker({
            format: 'dd/mm/yyyy'
        }).on('changeDate', function(){
            $(this).datepicker('hide');
            get_nomor_renbut();
        });

        $('#tanggal').datepicker({
            format: 'dd/mm/yyyy'
        }).on('changeDate', function(){
            $(this).datepicker('hide');
        });
        $('#nomorbkk').autocomplete("<?= base_url('autocomplete/nomorbkk') ?>",
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
                var str = '<div class=result>'+data.kode+'</div>';
                return str;
            },
            width: 500, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
            dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
            cacheLength: 0,
            max: 100
        }).result(
        function(event,data,formated){
            $(this).val(data.kode);
            $('#uraian').val(data.kode_ma);
            $('#id_uraian').val(data.id_uraian);
            $('#detail').html(data.keterangan);
            $('#jml_renbut').val('');
            $('#nominalcashbon').html(numberToCurrency(data.cashbon));
            $('#penerima').val(data.penerima);
            $('#id_pengeluaran').val(data.id);
            $('#id_renbut').val(data.id_renbut);

        });
        $('#uraian').autocomplete("<?= base_url('autocomplete/ma_proja') ?>",
        {
            extraParams: { 
                tahun: function() { 
                    return $('#tanggal_renbut').val().substr(6,4);
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
            width: 500, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
            dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
            cacheLength: 0,
            max: 100
        }).result(
        function(event,data,formated){
            $(this).val(pad(data.ma_proja,5));
            $('#id_uraian').val(data.id);
            $('#detail').html(data.uraian+' / '+data.keterangan);
            get_nominal_renbut(data.id);
            $('#penerima').focus();
        });
    });
    
    function reset_form() {
        $('input[type=text], input[type=hidden], select').val('');
    }
    
    function get_list_renbut(page, id) {
        $.ajax({
            url: '<?= base_url('transaksi/manage_renbut') ?>/list/'+page,
            data: $('#search_renbut').serialize(),
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

    function get_nominal_renbut(id) {
        $.ajax({
            url: '<?= base_url('autocomplete/get_nominal_renbut') ?>/'+id,
            data: 'tanggal='+$('#tanggal').val(),
            dataType: 'json',
            success: function(data) {
                if (data.total === null) {
                    dinamic_alert('Belum ada data di master kegiatan');
                } else {
                    $('#jml_renbut').val(numberToCurrency(data.total));
                }
            }
        });
    }

    function get_nomor_renbut() {
        $.ajax({
            url: '<?= base_url('autocomplete/get_nomor_renbut') ?>',
            data: 'tanggal='+$('#tanggal_renbut').val(),
            dataType: 'json',
            success: function(data) {
               $('#nomor').val(data);
            }
        });
    }
    
    function save_renbut() {
        if ($('#tanggal_renbut').val() === '') {
            custom_message('Peringatan', 'Tanggal renbut tidak boleh kosong !','#tanggal_renbut'); return false;
        }
        if ($('#nomor').val().length < 8) {
            custom_message('Peringatan', 'Nomor yang anda masukkan harus dengan format yymmxxxx misal: 15010001 !', '#nomor');
            return false;
        }
        /*if ($('#id_uraian').val() === '') {
            custom_message('Peringatan', 'Kode MA proja belum dipilih !', '#uraian');
            return false;
        }*/
        if ($('#jml_renbut').val() === '') {
            custom_message('Peringatan', 'Jumlah renbut harus diisi !', '#jml_renbut');
            return false;
        }
        if ($('#penerima').val() === '') {
            custom_message('Peringatan', 'Penerima / penanggung jawab harus diisi !', '#uraian');
            return false;
        }
        var cek_id = $('#id_renbut').val();
        $.ajax({
            url: '<?= base_url('transaksi/manage_renbut/save') ?>',
            type: 'POST',
            dataType: 'json',
            data: $('#save_renbut').serialize(),
            cache: false,
            success: function(data) {
                if (data.status === true) {
                    $('#datamodal_tambah').modal('hide');
                    if (cek_id === '') {
                        message_add_success();
                        $('input').val('');
                        get_list_renbut('1','',data.id_renbut);
                    } else {
                        message_edit_success();
                        get_list_renbut($('.noblock').html(),'');
                    }
                }
            }
        });
    }

    function edit_renbut(str) {
        var arr = str.split('#');
        $('#datamodal_tambah').modal('show');
        $('#id_renbut').val(arr[0]);
        $('#uraian').val(arr[1]);
        $('#keterangan').val(arr[2]);
        $('#jml_renbut').val(arr[3]);
        $('#penerima').val(arr[4]);
        $('#id_uraian').val(arr[5]);
        $('#tanggal').val(arr[6]);
        $('#detail').html(arr[7]);
        $('#nomor').val(arr[8]);
        $('#nomorbkk').val(arr[9]);
        $('#id_pengeluaran').val(arr[10]);
        $('#tanggal_renbut').val(arr[11]);
        $('#dialog_renbut').dialog({ title: 'Edit renbut satuan kerja' });
    }

    function print_renbut(id) {
        var wWidth = $(window).width();
        var dWidth = wWidth * 1;
        var wHeight= $(window).height();
        var dHeight= wHeight * 1;
        var x = screen.width/2 - dWidth/2;
        var y = screen.height/2 - dHeight/2;
        window.open('<?= base_url('transaksi/manage_renbut') ?>/print?id='+id, 'Renbut Cetak', 'width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
    }

    function paging(page, tab, search) {
        get_list_renbut(page, search);
    }

    function delete_renbut(id, page) {
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
                    url: '<?= base_url('transaksi/manage_renbut/delete') ?>?id='+id,
                    cache: false,
                    success: function() {
                        get_list_renbut(page);
                        $('#alert').dialog().remove();
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
            <li><a href="#tabs-1">Entri Rencana Kebutuhan</a></li>
        </ul>
        <div id="tabs-1">
            <button class="btn" id="add_renbut"><i class="fa fa-plus-circle"></i> Tambah Data</button>
            <button class="btn" id="cari_button"><i class="fa fa-search"></i> Cari Data</button>
            <button class="btn" id="reload_renbut"><i class="fa fa-refresh"></i> Reload Data</button>
            <div id="result">

            </div>
        </div>
    </div>
    
    <div id="datamodal_tambah" class="modal fade">
    <div class="modal-dialog" style="width: 800px;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="modal_title">Tambah Renbut</h4>
        </div>
        <div class="modal-body">
        <form action="" id="save_renbut" role="form" class="form-horizontal">
            <?= form_hidden('id_renbut', NULL, 'id=id_renbut') ?>
            <div class="form-group">
                <label class="col-lg-3 control-label">Nomor:</label>
                <div class="col-lg-8">
                    <?= form_input('nomor', NULL, 'id=nomor class="form-control"') ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Tanggal Renbut:</label>
                <div class="col-lg-8">
                    <?= form_input('tanggal_renbut', date("d/m/Y"), 'id=tanggal_renbut class="hasDatepicker form-control"') ?> * <small style="font-style: italic;">Tahun renbut berkaitan dgn MA Proja</small>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Tanggal Kegiatan:</label>
                <div class="col-lg-8">
                    <?= form_input('tanggal', NULL, 'id=tanggal class="hasDatepicker form-control"') ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Nomor BKK Cashbon *:</label>
                <div class="col-lg-8">
                    <?= form_input('nomorbkk', '', 'id=nomorbkk class="form-control"') ?><?= form_hidden('id_pengeluaran', NULL, 'id=id_pengeluaran') ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">MA Proja:</label>
                <div class="col-lg-8">
                    <?= form_input('uraian', NULL, 'id=uraian class="form-control"') ?><?= form_hidden('id_uraian', NULL, 'id=id_uraian') ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Detail:</label>
                <div class="col-lg-8" id="detail">
                    
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Cashbon:</label>
                <div class="col-lg-8" id="nominalcashbon">
                    
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Jumlah Renbut Rp.:</label>
                <div class="col-lg-8">
                    <?= form_input('jml_renbut', NULL, 'id=jml_renbut class="form-control" onkeyup="FormNum(this);"') ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Penerima / PngJawab:</label>
                <div class="col-lg-8">
                    <?= form_input('penerima', NULL, 'id=penerima class="form-control"') ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Keterangan:</label>
                <div class="col-lg-8">
                    <?= form_textarea('keterangan', NULL, 'id=keterangan class="form-control"') ?>
                </div>
            </div>
            * No. BKK Cashbon diisikan jika Renbut berasal dari cashbon
        </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
          <button type="button" class="btn btn-primary" id="save" onclick="save_renbut();"><i class="fa fa-save"></i> Simpan</button>
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
        <form action="" id="search_renbut" role="form" class="form-horizontal">
            <div class="form-group">
                <label class="col-lg-3 control-label">Tanggal Renbut:</label>
                <div class="col-lg-8">
                    <input type="text" name="awal" id="awal" value="<?= date("01/m/Y") ?>" class="form-control hasDatepicker" /> <input type="text" name="akhir" id="akhir" value="<?= date("d/m/Y") ?>" class="form-control hasDatepicker" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Tanggal Kegiatan:</label>
                <div class="col-lg-8">
                    <input type="text" name="awal_keg" id="awal_keg" value="" size="10" class="form-control hasDatepicker"/> <input type="text" name="akhir_keg" id="akhir_keg" value="" class="form-control hasDatepicker"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Jenis Renbut:</label>
                <div class="col-lg-8">
                    <select name=jenis_renbut id="jenis_renbut" class="form-control"><option value="">Semua ...</option><option value="murni">Murni Renbut</option><option value="cashbon">Dari Cashbon</option></select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Kegiatan:</label>
                <div class="col-lg-8">
                    <input type="text" name="kegiatan" id="kegiatan" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Satuan Kerja:</label>
                <div class="col-lg-8">
                    <select name=id_satker id=id_satker class="form-control"><option value="">Pilih Satker ...</option><?php foreach ($satker as $data) { ?><option value="<?= $data->id ?>"><?= $data->nama ?></option><?php } ?></select>
                </div>
            </div>
        </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
          <button type="button" class="btn btn-primary" id="save" onclick="get_list_renbut(1);"><i class="fa fa-eye"></i> Tampilkan</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>