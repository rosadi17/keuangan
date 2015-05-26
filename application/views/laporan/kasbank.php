<title><?= $title ?></title>
<div class="titling"><h1><?= $title ?></h1></div>
<script type="text/javascript">
    $(function() {
        //get_data_kas_bank(1);
        
        $('#tabs_kasbank').tabs();
        $('#awal_kasbank, #akhir_kasbank').datepicker({
                format: 'dd/mm/yyyy'
        }).on('changeDate', function(){
            $(this).datepicker('hide');
        });
        $('#cari_kasbank').click(function() {
            $('#datamodal').modal('show');
        });
        $('#reload_kasbank').click(function() {
            $('#loaddata').load('<?= base_url('laporan/kasbank') ?>');
        });
        
        $('#excel_kasbank').click(function() {
            location.href='<?= base_url('laporan/excel_kas_bank') ?>/?'+$('#search_kasbank').serialize();
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
            width: 400, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
            dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
            cacheLength: 0,
            max: 100
        }).result(
        function(event,data,formated){
            $(this).val(data.id_akun+' '+data.perkiraan);
            $('#hide_kode_perkiraan').val(data.id_akun);
        });
    });
    
    function get_data_kas_bank() {
        dc_validation_remove('#error_kode_rekening');
        if ($('#hide_kode_perkiraan').val() === '') {
            //dc_validation('#error_kode_rekening','Kode rekening harus dipilih !'); return false;
        }
        $.ajax({
            url: '<?= base_url('laporan/get_data_kas_bank') ?>',
            data: $('#search_kasbank').serialize(),
            success: function(data) {
                $('#datamodal').modal('hide');
                $('#result').html(data);
            }
        });
    }
</script>
<div class="kegiatan">
    <div id="tabs_kasbank">
        <ul>
            <li><a href="#tabss-1">Parameter</a></li>
        </ul>
        <div id="tabss-1">
            <button class="btn" id="cari_kasbank"><i class="fa fa-search"></i> Cari</button>
            <button class="btn" id="excel_kasbank"><i class="fa fa-file-text-o"></i> Export Excel</button>
            <!--<button id="reload_kasbank">Reload Data</button>-->
        <div id="result">
            <table class="list-data" width="100%">
                <thead>
                <tr>
                    <th width="3%">NO.</th>
                    <th width="7%">NO. BUKTI</th>
                    <th width="5%">TGL</th>
                    <th width="5%">No.Rek</th>
                    <th width="20%" class="left">Nama Rekening</th>
                    <th width="32%" class="left">Keterangan</th>
                    <th width="9%" class="right">DEBET</th>
                    <th width="9%" class="right">KREDIT</th>
                    <th width="9%" class="right">SALDO</th>
                </tr>
                </thead>
            </table>
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
        <form action="" id="search_kasbank" role="form" class="form-horizontal">
            <div class="form-group">
                <label class="col-lg-3 control-label">Range Tanggal:</label>
                <div class="col-lg-8">
                    <input type="text" name="awal" id="awal_kasbank" class="hasDatepicker form-control" value="<?= date("01/m/Y") ?>" size="10" /> 
                    <input type="text" name="akhir" id="akhir_kasbank" value="<?= date("d/m/Y") ?>" class="hasDatepicker form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">Kode Rekening*:</label>
                <div class="col-lg-8">
                    <?= form_input('', NULL, 'id=kode_perkiraan class="form-control"') ?><input type="hidden" name="kode_perkiraan" id="hide_kode_perkiraan" />
                    <span id="error_kode_rekening"></span>
                </div>
            </div>
        </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
          <button type="button" class="btn btn-primary" id="tampilkan" onclick="get_data_kas_bank(1);"><i class="fa fa-eye"></i> Tampilkan</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>