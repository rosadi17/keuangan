<title><?= $title ?></title>
<div class="titling"><h1><?= $title ?></h1></div>
<?= $this->load->view('message') ?>
<script type="text/javascript">
$(function() {
    $('#tabx').tabs();
    get_list_rekap_renbut(1);
    $('#cari_rekap_button').click(function() {
        $('#datamodal').modal('show');
    });
    $('#awal_lrenbut, #akhir_lrenbut, #awal_keg, #akhir_keg').datepicker({
        format: 'dd/mm/yyyy'
    }).on('changeDate', function(){
        $(this).datepicker('hide');
    });
    $('#reload_rekap_renbut').click(function() {
        get_list_rekap_renbut(1);
    });
    $('#print_excel').click(function() {
        window.location='<?= base_url('laporan/export_excel_renbut') ?>/?'+$('#search_renbut').serialize();
    });
});
function get_list_rekap_renbut(page, src) {
    $('#datamodal').modal('hide');
    $.ajax({
        url: '<?= base_url('laporan/manage_renbut') ?>/list/'+page,
        data: $('#search_renbut').serialize(),
        cache: false,
        beforeSend: function() {
            show_ajax_indicator();
        },
        success: function(data) {
            hide_ajax_indicator();
            $('#result-rekap').html(data);
        }
    });
}


//function print_renbut(id) {
//    var wWidth = $(window).width();
//    var dWidth = wWidth * 1;
//    var wHeight= $(window).height();
//    var dHeight= wHeight * 1;
//    var x = screen.width/2 - dWidth/2;
//    var y = screen.height/2 - dHeight/2;
//    window.open('<?= base_url('transaksi/manage_renbut') ?>/print?id='+id, 'Renbut Cetak', 'width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
//}

function paging(page) {
    get_list_rekap_renbut(page);
}
</script>
<div class="kegiatan">
    <div id="tabx">
        <ul>
            <li><a href="#tabs-1"><?= $title ?></a></li>
        </ul>
        <div id="tabs-1">
            <button class="btn" id="cari_rekap_button"><i class="fa fa-search"></i> Cari</button>
            <button class="btn" id="print_excel"><i class="fa fa-file-text-o"></i> Export Excel</button>
            <button class="btn" id="reload_rekap_renbut"><i class="fa fa-refresh"></i> Reload Data</button>
            <div id="result-rekap">

            </div>
        </div>
    </div>
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
                    <input type="text" name="awal" id="awal_lrenbut" class="hasDatepicker form-control" value="<?= date("01/m/Y") ?>" size="10" /> <input type="text" name="akhir" id="akhir_lrenbut" value="<?= date("d/m/Y") ?>" class="hasDatepicker form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Tanggal Kegiatan:</label>
                <div class="col-lg-8">
                    <input type="text" name="awal_keg" id="awal_keg" class="form-control hasDatepicker" /> <input type="text" name="akhir_keg" id="akhir_keg" class="form-control hasDatepicker" />
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
          <button type="button" class="btn btn-primary" id="tampilkan" onclick="get_list_rekap_renbut(1);"><i class="fa fa-eye"></i> Tampilkan</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>