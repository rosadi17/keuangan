<title><?= $title ?></title>
<div class="titling"><h1><?= $title ?></h1></div>
<?= $this->load->view('message') ?>
<script type="text/javascript">
$(function() {
    $('#tabs').tabs();
    get_list_perwabku(1);
    $('#export_excel').click(function() {
        var status = $('#status_pwk').val();
        if (status === 'sudah') {
            window.location='<?= base_url('laporan/manage_perwabku/export') ?>/?'+$('#search_perwabku').serialize();
        } else {
            window.location='<?= base_url('laporan/manage_perwabku/export2') ?>/?'+$('#search_perwabku').serialize();
        }
    });
    $('#cari_button').click(function() {
        $('#datamodal').modal('show');
    });
    $('#awal_lpwk, #akhir_lpwk').datepicker({
        format: 'dd/mm/yyyy'
    }).on('changeDate', function(){
        $(this).datepicker('hide');
    });
    $('#reload_perwabku').click(function() {
        reset_form();
        get_list_perwabku(1);
    });
    $('#awal_lpwk').val('<?= date("01/m/Y") ?>');
    $('#akhir_lpwk').val('<?= date("d/m/Y") ?>');
    $('#nomorpwk, #awal_lpwk, #akhir_lpwk').removeAttr('disabled');
    $('#status_pwk').change(function() {
        var val = $(this).val();
        if (val === 'belum') {
            $('#awal_lpwk, #akhir_lpwk').val('');
            $('#nomorpwk, #awal_lpwk, #akhir_lpwk').attr('disabled','disabled');
        } else {
            $('#awal_lpwk').val('<?= date("01/m/Y") ?>');
            $('#akhir_lpwk').val('<?= date("d/m/Y") ?>');
            $('#nomorpwk, #awal_lpwk, #akhir_lpwk').removeAttr('disabled');
        }
    });
});

function reset_form() {
    $('input[type=text], input[type=hidden], select, textarea').val('');
    $('#awal_lpwk').val('<?= date("01/m/Y") ?>');
    $('#akhir_lpwk').val('<?= date("d/m/Y") ?>');
}

function get_list_perwabku(page) {
    $('#datamodal').modal('hide');
    var status = $('#status_pwk').val();
    var url = '<?= base_url('laporan/manage_perwabku') ?>/list2/'+page;
    if (status === 'sudah') {
        url = '<?= base_url('laporan/manage_perwabku') ?>/list/'+page;
    }
    $.ajax({
        url: url,
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

function print_perwabku(id) {
    var wWidth = $(window).width();
    var dWidth = wWidth * 1;
    var wHeight= $(window).height();
    var dHeight= wHeight * 1;
    var x = screen.width/2 - dWidth/2;
    var y = screen.height/2 - dHeight/2;
    window.open('<?= base_url('laporan/manage_perwabku/print') ?>?id='+id, 'perwabku Cetak', 'width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
}

function paging(page, tab, search) {
    get_list_perwabku(page, search);
}

function delete_perwabku(id, page) {
    $('<div id=alert>Anda yakin akan menghapus data ini?</div>').dialog({
        title: 'Konfirmasi Penghapusan',
        autoOpen: true,
        modal: true,
        buttons: {
            "OK": function() {
                
                $.ajax({
                    url: '<?= base_url('laporan/manage_perwabku/delete') ?>?id='+id,
                    cache: false,
                    success: function() {
                        get_list_perwabku(page);
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
            <li><a href="#tabs-1"><?= $title ?></a></li>
        </ul>
        <div id="tabs-1">
            <button class="btn" id="cari_button"><i class="fa fa-search"></i> Cari</button>
            <button class="btn" id="export_excel"><i class="fa fa-file-text-o"></i> Export Excel</button>
            <button class="btn" id="reload_perwabku"><i class="fa fa-refresh"></i> Reload Data</button>
            <div id="result">

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
        <form action="" id="search_perwabku" role="form" class="form-horizontal">
            <div class="form-group">
                <label class="col-lg-3 control-label">Tanggal Perwabku:</label>
                <div class="col-lg-8">
                    <input type="text" name="awal" id="awal_lpwk" class="hasDatepicker form-control" value="<?= date("01/m/Y") ?>" size="10" /> <input type="text" name="akhir" id="akhir_lpwk" value="<?= date("d/m/Y") ?>" class="hasDatepicker form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Satuan Kerja:</label>
                <div class="col-lg-8">
                    <select name=id_satker id=id_satker class="form-control"><option value="">Semua Satker ...</option><?php foreach ($satker as $data) { ?><option value="<?= $data->id ?>"><?= $data->nama ?></option><?php } ?></select>
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
            <div class="form-group">
                <label class="col-lg-3 control-label">Status Perwabku:</label>
                <div class="col-lg-8">
                    <select name="status" id="status_pwk" class="form-control"><option value="sudah">Sudah Perwabku</option><option value="belum">Belum Perwabku</option></select>
                </div>
            </div>
        </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
          <button type="button" class="btn btn-primary" id="tampilkan" onclick="get_list_perwabku(1);"><i class="fa fa-eye"></i> Tampilkan</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>