<title><?= $title ?></title>
<div class="titling"><h1><?= $title ?></h1></div>
<?= $this->load->view('message') ?>
<script type="text/javascript">
    $(function() {
        $('#tabs').tabs();
        get_list_perwabku(1);
        $('#add_perwabku').click(function() {
            $('#datamodal_tambah').modal('show');
            $('#nobkk').empty();
            bkk_add_row();
            get_nomor_perwabku();
        });
        $('#cari_button').click(function() {
            $('#datamodal').modal('show');
        });
        $('#awal_pwk, #akhir_pwk').datepicker({
            format: 'dd/mm/yyyy'
        }).on('changeDate', function(){
            $(this).datepicker('hide');
        });

        $('#reload_perwabku').click(function() {
            reset_form();
            get_list_perwabku(1);
        });
        
        $('#tanggal').datepicker({
            format: 'dd/mm/yyyy'
        }).on('changeDate', function(){
            $(this).datepicker('hide');
            get_nomor_perwabku();
        });
        
        $('.form-control').change(function() {
            if ($(this).val() !== '') {
                dc_validation_remove(this);
            }
        });
    });

    function reset_form() {
        $('input[type=text], input[type=hidden], select, textarea').val('');
        $('#awal_pwk').val('<?= date("01/m/Y") ?>');
        $('#akhir_pwk').val('<?= date("d/m/Y") ?>');
        $('#tanggal').val('<?= date("d/m/Y") ?>');
    }

    function get_list_perwabku(page, src, id) {
        $('#datamodal').modal('hide');
        $.ajax({
            url: '<?= base_url('transaksi/manage_perwabku') ?>/list/'+page,
            data: $('#search_perwabku').serialize(),
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

    function get_nominal_perwabku(id) {
        $.ajax({
            url: '<?= base_url('autocomplete/get_nominal_perwabku') ?>/'+id,
            dataType: 'json',
            success: function(data) {
               //$('#jml_perwabku').val(numberToCurrency(data.total));
            }
        });
    }

    function get_nomor_perwabku() {
        $.ajax({
            url: '<?= base_url('autocomplete/get_nomor_perwabku') ?>',
            dataType: 'json',
            success: function(data) {
               $('#nomor').val(data);
            }
        });
    }

    function removeEl(el) {
        el.parentNode.remove();
        var jml = $('.rows_bkk').length;
        var col = 0;
        for (i = 1; i <= jml; i++) {
            $('.rows_bkk:eq('+col+')').children('.nomorbkk').attr('id', 'nomorbkk'+i);
            $('.rows_bkk:eq('+col+')').children('.id_nomorbkk').attr('id', 'id_nomorbkk'+i);
            col++;
        }
    }

    function bkk_add_row() {
        dc_validation_remove('#alert_jumlah');
        var jml = $('.rows_bkk').length+1;
        str = '<div class="rows_bkk">'+
                '<div style="float: left; width: 92%; margin-bottom: 10px;"><input type="text" name="nomorbkk[]" id="nomorbkk'+jml+'" class="nomorbkk form-control" /> </div>'+
                '<div><input type="hidden" name="id_nomorbkk[]" id="id_nomorbkk'+jml+'" class="id_nomorbkk" /> </div>'+
                '<button class="btn" onclick="removeEl(this);" style="float: right;"><i class="fa fa-trash-o"></i></button>'+
              '</div>';
        $('#nobkk').append(str);
        $('#nomorbkk'+jml).autocomplete("<?= base_url('autocomplete/nomorbkkdp') ?>",
        {
            parse: function(data){
                var parsed = [];
                for (var i=0; i < data.length; i++) {
                    parsed[i] = {
                        data: data[i],
                        value: data[i].kode // nama field yang dicari
                    };
                }
                return parsed;
            },
            formatItem: function(data,i,max){
                var str = '<div class=result>'+datefmysql(data.tanggal)+' '+data.kode+' '+data.keterangan+'<br/>Rp. '+numberToCurrency(data.pengeluaran)+'</div>';
                return str;
            },
            width: 473, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
            dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
            cacheLength: 0,
            max: 100
        }).result(
        function(event,data,formated){
            $(this).val(data.kode+' Rp. '+numberToCurrency(data.pengeluaran)+' '+data.keterangan);
            $('#id_nomorbkk'+jml).val(data.id);
            $('#keterangan').html(data.keterangan);
            $('#nominal').html('Rp. '+numberToCurrency(data.pengeluaran));
            $('#penerima').html(data.penerima);
        });
    }

    function get_nomor_perwabku() {
        $.ajax({
            url: '<?= base_url('autocomplete/get_nomor_perwabku') ?>',
            data: 'tanggal='+$('#tanggal').val(),
            dataType: 'json',
            success: function(data) {
               $('#nomor').val(data);
            }
        });
    }
    
    function konfirmasi_simpan() {
        var jml = $('.rows_bkk').length;
        if (jml === 0) {
            dc_validation('#alert_jumlah','Nomor BKK harus ada minimal 1 data !'); return false;
        }
        for (i = 1; i <= jml; i++) {
            if ($('#id_nomorbkk'+i).val() === '') {
                dc_validation('#nomorbkk'+i, 'Nomor BKK harus dipilih'); return false;
            }
        }
        if ($('#dana').val() === '') {
            dc_validation('#dana','Dana yang digunakan harus diisikan !');  return false;
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
                save_perwabku();
              }
            }
          }
        });
    }
    
    function save_perwabku() {
        $.ajax({
            url: '<?= base_url('transaksi/manage_perwabku/save') ?>',
            type: 'POST',
            dataType: 'json',
            data: $('#save_perwabku').serialize(),
            cache: false,
            success: function(data) {
                $('#datamodal_tambah').modal('hide');
                if (data.status === true) {
                    $('#dialog_perwabku').dialog().remove();
                    message_add_success();
                    get_list_perwabku(1);
                }
            }
        });
    }

    function print_perwabku(id) {
        var wWidth = $(window).width();
        var dWidth = wWidth * 1;
        var wHeight= $(window).height();
        var dHeight= wHeight * 1;
        var x = screen.width/2 - dWidth/2;
        var y = screen.height/2 - dHeight/2;
        window.open('<?= base_url('transaksi/manage_perwabku/print') ?>?id='+id, 'perwabku Cetak', 'width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
    }

    function paging(page) {
        get_list_perwabku(page);
    }

    function delete_perwabku(id, page) {
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
                    url: '<?= base_url('transaksi/manage_perwabku/delete') ?>?id='+id,
                    cache: false,
                    success: function() {
                        get_list_perwabku(page);
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
            <li><a href="#tabs-1">Entri Perwabku</a></li>
        </ul>
        <div id="tabs-1">
            <button class="btn btn-primary" id="add_perwabku"><i class="fa fa-plus-circle"></i> Tambah</button>
            <button class="btn" id="cari_button"><i class="fa fa-search"></i> Cari</button>
            <button class="btn" id="reload_perwabku"><i class="fa fa-refresh"></i> Reload Data</button>
            <div id="result">

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
        <form action="" id="save_perwabku" role="form" class="form-horizontal">
            <?= form_hidden('id_perwabku', NULL, 'id=id_perwabku') ?>
            <div class="form-group">
                <label class="col-lg-3 control-label">Nomor:</label>
                <div class="col-lg-8">
                    <?= form_input('nomor', '', 'id=nomor class="form-control"') ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Tanggal Perwabku:</label>
                <div class="col-lg-8">
                    <?= form_input('tanggal', date("d/m/Y"), 'id=tanggal class="hasDatepicker form-control"') ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label"></label>
                <div class="col-lg-8">
                    <button type="button" class="btn btn-default btn-xs delete" onclick="bkk_add_row();"><i class="fa fa-plus-circle"></i> Tambah Kode BKK</button>
                    <span id="alert_jumlah"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Nomor BKK (DP):</label>
                <div class="col-lg-8" id="nobkk">
                    
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Dana yang Digunakan Rp.:</label>
                <div class="col-lg-8">
                    <input type="text" name="dana" id="dana" onblur="FormNum(this);" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Kelengkapan:</label>
                <div class="col-lg-8">
                    <select name="kelengkapan" id="kelengkapan" class="form-control">
                        <option value="">Pilih ...</option>
                        <option value="Asli">Asli</option>
                        <option value="Copy">Copy</option>
                        <option value="Asli & Copy">Asli & Copy</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Catatan/Memorial:</label>
                <div class="col-lg-8">
                    <textarea name="catatan" id="catatan" rows="4" class="form-control"></textarea>
                </div>
            </div>
        </form>
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
        <div class="modal-body" role="form" class="form-horizontal">
        <form action="" id="search_perwabku" role="form" class="form-horizontal">
            <div class="form-group">
                <label class="col-lg-3 control-label">Tanggal Perwabku:</label>
                <div class="col-lg-8">
                    <div><input type="text" name="awal" id="awal_pwk" value="<?= date("01/m/Y") ?>" size="10" class="hasDatepicker form-control" /> </div>
                    <div><input type="text" name="akhir" id="akhir_pwk" value="<?= date("d/m/Y") ?>" class="hasDatepicker form-control" /></div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">No. Perwabku:</label>
                <div class="col-lg-8">
                    <input type="text" name="nomorpwk" id="nomorpwk" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">No. BKK:</label>
                <div class="col-lg-8">
                    <input type="text" name="nomorbkk" id="nomorbkk" class="form-control" />
                </div>
            </div>
        </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
          <button type="button" class="btn btn-primary" id="save" onclick="get_list_perwabku(1);"><i class="fa fa-eye"></i> Tampilkan</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>