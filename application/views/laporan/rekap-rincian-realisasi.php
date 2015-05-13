<title><?= $title ?></title>
<?= $this->load->view('message') ?>
<div class="titling"><h1><?= $title ?></h1></div>
<script type="text/javascript">

$(function() {
    get_list_rekap_rekap_realisasi(1);
    $('#tabs').tabs();
    $('#reset').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        $('#loaddata').load('<?= base_url('laporan/rekap_realisasi') ?>');
    });
    
    $('#cari_button_realisasi').click(function() {
        $('#datamodal').modal('show');
    });
    
    $('#reload_rekap_realisasi_data').click(function() {
        reset_form();
        get_list_rekap_rekap_realisasi(1);
    });
    $('#excel_rekap_realisasi').click(function() {
        location.href='<?= base_url('laporan/manage_rekap_realisasi/export_excel') ?>/?'+$('#search_rekap_realisasi').serialize();
    });
    $('#tahun_anggaran').change(function() {
        $('#kodema, #id_kodema').val('');
    });
    $('#awal_rekap_realisasi, #akhir_rekap_realisasi').datepicker({
        format: 'dd/mm/yyyy'
    }).on('changeDate', function(){
        $(this).datepicker('hide');
    });
    
    $('#kodema').autocomplete("<?= base_url('autocomplete/ma_proja') ?>",
    {
        extraParams: { 
            satker: function() { 
                return $('#id_satker_rekap').val();
            },
            tahun: function() {
                return $('#tahun_anggaran').val();
            }
        },
        parse: function(data){
            var parsed = [];
            for (var i=0; i < data.length; i++) {
                parsed[i] = {
                    data: data[i],
                    value: data[i].ma_proja // nama field yang dicari
                };
            }
            $('#id_kodema').val('');
            return parsed;
        },
        formatItem: function(data,i,max){
            var str = '<div class=result>'+pad(data.ma_proja,5)+' / '+data.uraian+' &Rightarrow; <i>'+data.keterangan+'</i></div>';
            return str;
        },
        width: 400, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
        cacheLength: 0,
        max: 100
    }).result(
    function(event,data,formated){
        $(this).val(pad(data.ma_proja,5));
        $('#id_kodema').val(data.id);
        $('#keterangan_ma').html(data.keterangan);
    });
});

function reset_form() {
    $('input[type=text], input[type=hidden], select, textarea').val('');
    $('#s2id_supplier_auto a .select2-chosen, #label_uraian').html('');
    $('#tanggal').val('<?= date("d/m/Y") ?>');
    $('#awal_rekap_realisasi').val('<?= date("01/m/Y") ?>');
    $('#akhir_rekap_realisasi').val('<?= date("d/m/Y") ?>');
    $('#perwabku, #kode_renbut').removeAttr('disabled');
    $('#keterangan_ma').html('');
}

function delete_rekap_realisasi(id, page, kode) {
    $('<div id=alert>Anda yakin akan menghapus data ini?</div>').dialog({
        title: 'Konfirmasi Penghapusan',
        autoOpen: true,
        modal: true,
        buttons: {
            "OK": function() {
                
                $.ajax({
                    url: '<?= base_url('laporan/manage_rekap_realisasi/delete') ?>?id='+id+'&kode='+kode,
                    cache: false,
                    success: function() {
                        get_list_rekap_rekap_realisasi(page);
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

function get_list_rekap_rekap_realisasi(page) {
    $('#datamodal').modal('hide');
    $.ajax({
        url: '<?= base_url('laporan/manage_rekap_realisasi') ?>/list/'+page,
        data: $('#search_rekap_realisasi').serialize(),
        cache: false,
        beforeSend: function() {
            show_ajax_indicator();
        },
        success: function(data) {
            hide_ajax_indicator();
            $('#result-rekap_realisasi').html(data);
        }
    });
}

function paging(p) {
    get_list_rekap_rekap_realisasi(p);
}
</script>
<div class="kegiatan">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Parameter</a></li>
        </ul>
        <div id="tabs-1">
            <button class="btn" id="cari_button_realisasi"><i class="fa fa-search"></i> Cari</button>
            <button class="btn" id="excel_rekap_realisasi"><i class="fa fa-file-text-o"></i> Export Excel</button>
            <button class="btn" id="reload_rekap_realisasi_data"><i class="fa fa-refresh"></i> Reload Data</button>
            <div id="result-rekap_realisasi">

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
        <form action="" id="search_rekap_realisasi" role="form" class="form-horizontal">
            <div class="form-group">
                <label class="col-lg-3 control-label">Range Tanggal:</label>
                <div class="col-lg-8">
                    <input type="text" name="awal" id="awal_rekap_realisasi" value="<?= date("01/m/Y") ?>" size="10" class="hasDatepicker form-control" /> 
                    <input type="text" name="akhir" id="akhir_rekap_realisasi" value="<?= date("d/m/Y") ?>" class="hasDatepicker form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Jenis:</label>
                <div class="col-lg-8">
                    <?= form_dropdown('jenis', array('' => 'Semua Jenis ...', 'SPP' => 'SPP', 'NON SPP' => 'Non SPP'), NULL, 'id=jenis_laporan class="form-control"') ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Satuan Kerja:</label>
                <div class="col-lg-8">
                    <select name="id_satker" id="id_satker_rekap" class="form-control"><option value="">Pilih Satker ...</option><?php foreach ($satker as $data) { ?><option value="<?= $data->id ?>"><?= $data->kode ?> <?= $data->nama ?></option><?php } ?></select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Tahun Anggaran:</label>
                <div class="col-lg-8">
                    <select name="tahun" id="tahun_anggaran" class="form-control">
                    <?php for ($i = date("Y"); $i >=2014 ; $i--) { ?>
                        <option value="<?= $i ?>" <?= (($i === date("Y"))?'selected':'') ?>><?= $i ?></option>
                    <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Kode MA/Proja:</label>
                <div class="col-lg-8">
                    <?= form_input('kode', NULL, 'id=kodema class="form-control"') ?><?= form_hidden('id_kode', NULL, 'id=id_kodema') ?>
                </div>
            </div>
        </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
          <button type="button" class="btn btn-primary" id="tampilkan" onclick="get_list_rekap_rekap_realisasi(1);"><i class="fa fa-eye"></i> Tampilkan</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>