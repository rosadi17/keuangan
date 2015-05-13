<title><?= $title ?></title>
<div class="titling"><h1><?= $title ?></h1></div>
<?= $this->load->view('message') ?>
<script type="text/javascript">
    $(function() {
        $('#tabs').tabs();
        get_list_sub_uraian(1);
        $('#add_sub_uraian').click(function() {
            $('#datamodal_tambah').modal('show');
        });

        $('#reload_sub_uraian').click(function() {
            reset_form();
            get_list_sub_uraian(1);
        });

        $('#cari_button').click(function() {
            $('#datamodal').modal('show');
        });
        
        $('#tahun').datepicker({
            format: 'yyyy'
        }).on('changeDate', function(){
            $(this).datepicker('hide');
        });
        $("#tahun").focus(function () {
            $(".ui-datepicker-month").hide();
        });
        $('#id_satker').change(function() {
            var id = $(this).val();
            if (id !== '') {
                $('#program').removeAttr('disabled');
            } else {
                $('#program').attr('disabled','disabled');
            }
        });
        $('.form-control').change(function() {
            if ($(this).val() !== '') {
                dc_validation_remove(this);
            }
        });
        $('#uraian').autocomplete("<?= base_url('autocomplete/uraian') ?>",
        {
            extraParams: { 
                id_satker: function() { 
                    return $('#id_satker').val();
                },
                status: function() {
                    return $('#status').val();
                }
            },
            parse: function(data){
                var parsed = [];
                for (var i=0; i < data.length; i++) {
                    parsed[i] = {
                        data: data[i],
                        value: data[i].uraian // nama field yang dicari
                    };
                }
                return parsed;
            },
            formatItem: function(data,i,max){
                if ($('#id_satker').val() === '') {
                    var str = '<div class=result>'+pad(data.code,5)+' - '+data.uraian+'<br/>'+data.satker+' ('+data.status+')</div>';
                } else {
                    var str = '<div class=result>'+pad(data.code,5)+' - '+data.uraian+'</div>';
                }
                return str;
            },
            width: 400, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
            dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
            cacheLength: 0,
            max: 100
        }).result(
        function(event,data,formated){
            $(this).val(pad(data.code,5)+' '+data.uraian);
            $('#sub_uraian').val(data.uraian);
            $('#id_uraian').val(data.id);
            $('#id_satker').val(data.id_satker);
            $('#status').val(data.status);
        });
    });

    function reset_form() {
        $('input[type=text], select, textarea').val('');
        $('#year').val('<?= date("Y") ?>');
    }

    function get_list_sub_uraian(page, id) {
        $('#datamodal').modal('hide');
        var idx = '';
        if (id !== undefined) {
            idx = id;
        }
        $.ajax({
            url: '<?= base_url('masterdata/manage_sub_uraian') ?>/list/'+page+'/'+idx,
            data: $('#search_anggaran').serialize(),
            cache: false,
            beforeSend: function() {
                show_ajax_indicator();
            },
            success: function(data) {
                hide_ajax_indicator();
                $('#result-sub_uraian').html(data);
            }
        });
    }

    function save_anggaran() {
        
        if ($('#id_satker').val() === '') {
            dc_validation('#id_satker','Nama satker tidak boleh kosong !'); return false;
        }
        if ($('#tahun').val() === '') {
            dc_validation('#tahun','tahun tidak boleh kosong !'); return false;
        }
        if ($('#id_uraian').val() === '') {
            dc_validation('#uraian','Uraian tidak boleh kosong !'); return false;
        }
//        if ($('#sub_uraian').val() === '') {
//            dc_validation('#sub_uraian','Uraian tidak boleh kosong !'); return false;
//        }
        if ($('#harga').val() === '') {
            dc_validation('#harga','Harga tidak boleh kosong !'); return false;
        }
        
        var cek_id = $('#id_sub_uraian').val();
        $.ajax({
            url: '<?= base_url('masterdata/manage_sub_uraian/save') ?>',
            type: 'POST',
            dataType: 'json',
            data: $('#save_sub_uraian').serialize(),
            cache: false,
            success: function(data) {
                if (data.status === true) {
                    $('#datamodal_tambah').modal('hide');
                    if (cek_id === '') {
                        message_add_success();
                        $('input, select').val('');
                        get_list_sub_uraian('1','',data.id_sub_uraian);
                    } else {
                        message_edit_success();
                        $('#dialog_sub_uraian').dialog().remove();
                        get_list_sub_uraian($('.noblock').html(),'');
                    }
                }
            }
        });
    }

    function edit_sub_uraian(str) {

        var arr = str.split('#');
        $('#datamodal_tambah').modal('show');
        $('#datamodal_tambah h4').html('Edit Kegiatan');
        $('#id_satker, #status').attr('disabled','disabled');
        $('#id_sub_uraian').val(arr[0]);
        $('#id_satker').val(arr[1]);
        $('#status').val(arr[2]);
        $('#uraian').val(arr[10]+' '+arr[4]);
        $('#id_uraian').val(arr[3]);
        $('#sub_uraian').val(arr[5]);
        $('#kuat').val(arr[6]);
        $('#vol_orang').val(arr[7]);
        $('#haribulan').val(arr[8]);
        $('#harga').val(arr[9]);
        $('#tahun').val(arr[11]);
        $('#dialog_sub_uraian').dialog({ title: 'Edit Anggaran Kegiatan' });
    }

    function paging(page, tab, search) {
        get_list_sub_uraian(page, search);
    }

    function delete_sub_uraian(id, page) {
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
                    url: '<?= base_url('masterdata/manage_sub_uraian/delete') ?>?id='+id,
                    cache: false,
                    success: function() {
                        get_list_sub_uraian(page);
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
            <li><a href="#tabs-1">Entri <?= $title ?></a></li>
        </ul>
        <div id="tabs-1">
            <button class="btn btn-primary" id="add_sub_uraian"><i class="fa fa-plus-circle"></i> Tambah</button>
            <button class="btn" id="cari_button"><i class="fa fa-search"></i> Cari</button>
            <button class="btn" id="reload_sub_uraian"><i class="fa fa-refresh"></i> Reload Data</button>
            <div id="result-sub_uraian"></div>
        </div>
    </div>
    <div id="datamodal_tambah" class="modal fade">
    <div class="modal-dialog" style="width: 700px;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="modal_title">Tambah Data</h4>
        </div>
        <div class="modal-body">
        <form action="" id="save_sub_uraian" role="form" class="form-horizontal">
            <?= form_hidden('id_sub_uraian', NULL, 'id=id_sub_uraian') ?>
            <div class="form-group">
                <label class="col-lg-3 control-label">Satuan Kerja:</label>
                <div class="col-lg-8">
                    <select name=id_satker id=id_satker class="form-control"><option value="">Pilih Satker ...</option><?php foreach ($satker as $data) { ?><option value="<?= $data->id ?>"><?= $data->nama ?></option><?php } ?></select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Tahun:</label>
                <div class="col-lg-8">
                    <?= form_input('tahun', NULL, 'id=tahun class="form-control"') ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Status:</label>
                <div class="col-lg-8">
                    <select name=status id=status class="form-control"><option value="SPP">SPP</option><option value="NON SPP">NON SPP</option></select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Kode / Nama Uraian:</label>
                <div class="col-lg-8">
                    <div><?= form_input('uraian', NULL, 'id=uraian class="form-control"') ?></div>
                    <div><?= form_hidden('id_uraian', NULL, 'id=id_uraian') ?></div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Keterangan:</label>
                <div class="col-lg-8">
                    <?= form_input('sub_uraian', NULL, 'id=sub_uraian class="form-control"') ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Besar Anggaran:</label>
                <div class="col-lg-8">
                    <?= form_input('harga', NULL, 'id=harga onkeyup="FormNum(this);" class="form-control" size=60') ?>
                </div>
            </div>
        </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
          <button type="button" class="btn btn-primary" id="save" onclick="save_anggaran();"><i class="fa fa-save"></i> Simpan</button>
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
        <form action="" id="search_anggaran" role="form" class="form-horizontal">
            <div class="form-group">
                <label class="col-lg-3 control-label">Tahun:</label>
                <div class="col-lg-8">
                    <select name="year" id="year" class="form-control"><option value="">Select Year ....</option><?php for($i = 2014; $i <= date("Y"); $i++) { ?> <option value="<?= $i ?>" <?php if ($i == date("Y")) { echo "selected"; } ?>><?= $i ?></option><?php } ?></select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Satuan Kerja:</label>
                <div class="col-lg-8">
                    <select name=id_satker class="form-control"><option value="">Pilih Satker ...</option><?php foreach ($satker as $data) { ?><option value="<?= $data->id ?>"><?= $data->kode ?> <?= $data->nama ?></option><?php } ?></select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Status:</label>
                <div class="col-lg-8">
                    <select name="status" class="form-control"><option value="">Semua ...</option><option value="SPP">SPP</option><option value="NON SPP">NON SPP</option></select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Sub Uraian:</label>
                <div class="col-lg-8">
                    <input type="text" name="suburaian" id="suburaian" class="form-control" />
                </div>
            </div>
        </table>
        </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
          <button type="button" class="btn btn-primary" id="save" onclick="get_list_sub_uraian(1);"><i class="fa fa-eye"></i> Tampilkan</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>