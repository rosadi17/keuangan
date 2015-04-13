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
        $('#cari_button').button({
            icons: {
                secondary: 'ui-icon-search'
            }
        }).click(function() {
            $('#dialog_realisasi').dialog({
                title: 'Form Pencarian',
                autoOpen: true,
                width: 480,
                autoResize:true,
                modal: true,
                hide: 'explode',
                show: 'blind',
                position: ['center',47],
                buttons: {
                    "Cancel": function() {
                        $('#dialog_realisasi').dialog('close');
                    },
                    "Tampilkan": function() {
                        get_list_realisasi(1);
                        $('#dialog_realisasi').dialog('close');
                    }
                }, close: function() {
                    $('#dialog_realisasi').dialog('close');
                }, open: function() {
                    $('#uraian').focus();
                }
            });
        });
        $('#reload_realisasi').button({
            icons: {
                secondary: 'ui-icon-refresh'
            }
        }).click(function() {
            reset_form();
            get_list_realisasi(1);
        });
    });

    function reset_form() {
        $('input[type=text], input[type=hidden], select, textarea').val('');
        $('#year').val('<?= date("Y") ?>');
    }

    function get_list_realisasi(page) {
        $.ajax({
            url: '<?= base_url('laporan/manage_realisasi') ?>/list/'+page,
            data: $('#form_cari_realisasi').serialize(),
            cache: false,
            success: function(data) {
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
            success: function(data) {
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
            <button id="cari_button">Cari Data</button>
            <button id="reload_realisasi">Reload Data</button>
            <div id="result" style="overflow-x: auto;">

            </div>
        </div>
    </div>
    <div class="nodisplay" id="dialog_realisasi">
        <form action="" id="form_cari_realisasi">
            <table width=100% cellpadding=0 cellspacing=0 class=inputan>
                <tr><td width=30%>Tahun:</td><td><select name="year" id="year" style="width: 74px;"><?php for($i = 2014; $i <= date("Y"); $i++) { ?> <option value="<?= $i ?>" <?php if ($i == date("Y")) { echo "selected"; } ?>><?= $i ?></option><?php } ?></select></td></tr>
                <tr><td>Satuan Kerja:</td><td><select name="id_satker" id="id_satker"><option value="">Pilih Satker ...</option><?php foreach ($satker as $data) { ?><option value="<?= $data->id ?>"><?= $data->nama ?></option><?php } ?></select></td></tr>
            </table>
        </form>
    </div>
    <div id="detail_ma">
        
    </div>
</div>