<title><?= $title ?></title>
<div class="titling"><h1><?= $title ?></h1></div>
<?= $this->load->view('message') ?>
<script type="text/javascript">
    $(function() {
        $('#tabs').tabs();
        get_list_realisasi(1);
        $('#add_realisasi').button({
            icons: {
                secondary: 'ui-icon-newwin'
            }
        }).click(function() {
            form_realisasi();
        });
        
        $('#excel_realisasi').button({
            icons: {
                secondary: 'ui-icon-print'
            }
        }).click(function() {
            location.href='<?= base_url('laporan/manage_realisasi/export_excel') ?>/?'+$('#form_cari_realisasi').serialize();
        });
        
        $('#cari_button').click(function() {
            $('#datamodal').modal('show');
        });
        $('#reload_realisasi').click(function() {
            reset_form();
            get_list_realisasi(1);
        });
    });

    function reset_form() {
        $('input[type=text], input[type=hidden], select, textarea').val('');
        $('#year').val('<?= date("Y") ?>');
    }

    function get_list_realisasi(page) {
        $('#datamodal').modal('hide');
        $.ajax({
            url: '<?= base_url('laporan/manage_realisasi') ?>/list/'+page,
            data: $('#form_cari_realisasi').serialize(),
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

    function edit_realisasi(str) {
        var arr = str.split('#');
        form_realisasi();
        $('#id_realisasi').val(arr[0]);
        $('#uraian').val(arr[1]);
        $('#keterangan').val(arr[2]);
        $('#jml_realisasi').val(arr[3]);
        $('#penerima').val(arr[4]);
        $('#id_uraian').val(arr[5]);
        $('#tanggal').val(arr[6]);
        $('#dialog_realisasi').dialog({ title: 'Edit realisasi satuan kerja' });
    }

    function print_realisasi(id_satker, nama, tahun) {
        location.href='<?= base_url('laporan/manage_realisasi/print') ?>/?satker='+id_satker+'&tahun='+tahun+'&nama_satker='+nama;
    }

    function detail_kode_ma(id_satker, nama, tahun) {
        $('#detail_ma').dialog({
            title: 'Detail Kode MA',
            autoOpen: true,
            width: '100%',
            autoResize:true,
            modal: true,
            hide: 'fadeOut',
            show: 'blind',
            position: ['center',0],
            buttons: {
                "Export Excel": function() {
                    print_realisasi(id_satker, nama, tahun);
                },
                "Close": function() {
                    $('#detail_ma').dialog('close');
                }
            }, close: function() {
                $('#detail_ma').dialog('close');
            },
            open: function() {
                load_detail_kode_ma(id_satker, nama, tahun);
            }
        });
    }

    function load_detail_kode_ma(id_satker, nama_satker, tahun) {
        $.ajax({
            url: '<?= base_url('laporan/manage_realisasi') ?>/detail_ma/',
            data: 'satker='+id_satker+'&tahun='+tahun+'&nama_satker='+nama_satker,
            cache: false,
            beforeSend: function() {
                show_ajax_indicator();
            },
            success: function(data) {
                hide_ajax_indicator();
                $('#detail_ma').html(data);
            }
        });
    }

    function paging(page, tab, search) {
        get_list_realisasi(page, search);
    }
</script>
<div class="kegiatan">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1"><?= $title ?></a></li>
        </ul>
        <div id="tabs-1">
            <button class="btn" id="cari_button"><i class="fa fa-search"></i> Cari</button>
            <button class="btn" id="excel_realisasi"><i class="fa fa-file-text-o"></i> Export Excel</button>
            <button class="btn" id="reload_realisasi"><i class="fa fa-refresh"></i> Reload Data</button>
            <div id="result" style="overflow-x: auto;">

            </div>
        </div>
    </div>
    <div id="datamodal" class="modal fade">
    <div class="modal-dialog" style="width: 600px;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="modal_title">Pencarian Realisasi</h4>
        </div>
        <div class="modal-body">
        <form action="" id="form_cari_realisasi" role="form" class="form-horizontal">
            <div class="form-group">
                <label class="col-lg-3 control-label">Tahun Anggaran:</label>
                <div class="col-lg-8">
                    <select name="year" id="year" class="form-control"><?php for($i = 2014; $i <= date("Y"); $i++) { ?> <option value="<?= $i ?>" <?php if ($i == date("Y")) { echo "selected"; } ?>><?= $i ?></option><?php } ?></select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Satuan Kerja:</label>
                <div class="col-lg-8">
                    <select name="id_satker" id="id_satker" class="form-control"><option value="">Pilih Satker ...</option><?php foreach ($satker as $data) { ?><option value="<?= $data->id ?>"><?= $data->nama ?></option><?php } ?></select>
                </div>
            </div>
        </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
          <button type="button" class="btn btn-primary" id="tampilkan" onclick="get_list_realisasi(1);"><i class="fa fa-eye"></i> Tampilkan</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div id="detail_ma">
        
    </div>
</div>