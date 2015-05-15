<title><?= $title ?></title>
<div class="titling"><h1><?= $title ?></h1></div>
<?= $this->load->view('message') ?>
<script type="text/javascript">
$(function() {
    $('#tabs').tabs();
    get_list_dropping(1);
    $('#add_dropping').click(function() {
        form_dropping();
    });
    
    $('#awal_dropping, #akhir_dropping').datepicker({
        format: 'dd/mm/yyyy'
    }).on('changeDate', function(){
        $(this).datepicker('hide');
    });
    
    $('#excel_dropping').button({
        icons: {
            secondary: 'ui-icon-print'
        }
    }).click(function() {
        location.href='<?= base_url('transaksi/manage_dropping/export_excel') ?>/?'+$('#search_dropping').serialize();
    });
    
    $('#uraian').autocomplete("<?= base_url('autocomplete/ma_proja_dropping') ?>",
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
            width: 370, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
            dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
            cacheLength: 0,
            max: 100
        }).result(
        function(event,data,formated){
            $(this).val(pad(data.ma_proja,5));
            $('#id_uraian').val(data.id);
            $('#keterangan').val(data.uraian);
        });
    });
    
    $('#reload_dropping').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        //reset_form();
        get_list_dropping(1);
    });
    
    $('#cari_button').click(function() {
        $('#datamodal').modal('show');    
    });
    
    $('.form-control').change(function() {
        if ($(this).val() !== '') {
            dc_validation_remove(this);
        }
    });
    
    function get_list_dropping(page, src, id) {
        $('#datamodal').modal('hide');
        $.ajax({
            url: '<?= base_url('transaksi/manage_dropping') ?>/list/'+page,
            data: $('#search_dropping').serialize(),
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

    function edit_dropping(id, nominal) {
        $('#datamodal_tambah').modal('show');
        $('#id_dropping_x').val(id);
        $('#jumlah').val(numberToCurrency(nominal));
    }
    
    function save_dropping() {
        if ($('#status').val() === '') {
            dc_validation('#status', 'Status konfirmasi harus dipilih !'); return false;
        }
        $.ajax({
            type: 'POST',
            url: '<?= base_url('transaksi/manage_dropping/approve') ?>',
            data: 'id='+$('#id_dropping_x').val()+'&status='+$('#status').val()+'&jumlah='+$('#jumlah').val(),
            cache: false,
            dataType: 'json',
            success: function(data) {
                $('#datamodal_tambah').modal('hide');
                if (data.status === 'Disetujui') {
                    message_edit_success();
                } else {
                    message_edit_success();
                }
                get_list_dropping($('.noblock').html());
            }
        });
    }

    function delete_dropping(id, page) {
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
                        get_list_dropping(page);
                        $('#alert').dialog().remove();
                    }
                });
              }
            }
          }
        });
    }

    function paging(page, tab, search) {
        get_list_dropping(page, search);
    }

</script>
<div class="kegiatan">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Data Dropping</a></li>
        </ul>
        <div id="tabs-1">
            <button class="btn" id="cari_button"><i class="fa fa-search"></i> Cari</button>
            <button class="btn" id="excel_dropping"><i class="fa fa-file-text-o"></i> Export Excel</button>
            <button class="btn" id="reload_dropping"><i class="fa fa-refresh"></i> Reload Data</button>
            <div id="result">

            </div>
        </div>
    </div>
    <div id="datamodal_tambah" class="modal fade">
    <div class="modal-dialog" style="width: 600px;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="modal_title">Konfirmasi Persetujuan</h4>
        </div>
        <div class="modal-body">
            <form role="form" class="form-horizontal">
            <input type="hidden" name="id" id="id_dropping_x" />
            <div class="form-group">
                <label class="col-lg-3 control-label">Status:</label>
                <div class="col-lg-8">
                    <select name=status id=status class="form-control"><option value="">Pilih ...</option><option value="Disetujui">Disetujui</option><option value="Ditolak">Ditolak</option></select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Jumlah:</label>
                <div class="col-lg-8">
                    <input type="text" name="jumlah" onblur="FormNum(this);" id="jumlah" class="form-control" />
                </div>
            </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
          <button type="button" class="btn btn-primary" id="save" onclick="save_dropping();"><i class="fa fa-save"></i> Simpan</button>
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
        <form action="" id="search_dropping" role="form" class="form-horizontal">
            <?= form_hidden('id_dropping', NULL, 'id=id_dropping') ?>
            <div class="form-group">
                <label class="col-lg-3 control-label">Tanggal Renbut:</label>
                <div class="col-lg-8">
                    <div><input type="text" name="awal" id="awal_dropping" value="<?= date("01/m/Y") ?>" size="10" class="form-control hasDatepicker" /></div>
                    <div><input type="text" name="akhir" id="akhir_dropping" value="<?= date("d/m/Y") ?>" class="hasDatepicker form-control" /></div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Satuan Kerja:</label>
                <div class="col-lg-8">
                    <select name=id_satker id=id_satker class="form-control"><option value="">Semua Satker ...</option><?php foreach ($satker as $data) { ?><option value="<?= $data->id ?>"><?= $data->nama ?></option><?php } ?></select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">MA Proja:</label>
                <div class="col-lg-8">
                    <?= form_input('uraian', NULL, 'id=uraian class="form-control"') ?>
                    <?= form_hidden('id_uraian', NULL, 'id=id_uraian') ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Keterangan:</label>
                <div class="col-lg-8">
                    <?= form_input('keterangan', NULL, 'id=keterangan class="form-control"') ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Penanggung Jwb:</label>
                <div class="col-lg-8">
                    <?= form_input('png_jawab', NULL, 'id=png_jawab class="form-control"') ?>
                </div>
            </div>
        </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
          <button type="button" class="btn btn-primary" id="save" onclick="get_list_dropping(1);"><i class="fa fa-eye"></i> Tampilkan</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>