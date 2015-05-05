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
    
    $('#cari_button').button({
        icons: {
            secondary: 'ui-icon-search'
        }
    }).click(function() {
        $('#dialog_rekap_realisasi_search').dialog({
            title: 'Cari Rekap Realisasi',
            autoOpen: true,
            width: 480,
            autoResize:true,
            modal: true,
            hide: 'explode',
            show: 'blind',
            position: ['center',47],
            buttons: {
                "Cancel": function() {
                    $('#dialog_rekap_realisasi_search').dialog('close');
                },
                "Cari": function() {
                    $('#dialog_rekap_realisasi_search').dialog('close');
                    get_list_rekap_rekap_realisasi(1);
                } 
            }, close: function() {
                $('#dialog_rekap_realisasi_search').dialog('close');
            }, open: function() {
                $('#awal_rekap_realisasi, #akhir_rekap_realisasi').datepicker('hide');
                $('#jenis_laporan').focus();
            }
        });
    });
    
    $('#reload_rekap_realisasi_data').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        reset_form();
        get_list_rekap_rekap_realisasi(1);
    });
    $('#excel_rekap_realisasi').button({
        icons: {
            secondary: 'ui-icon-print'
        }
    }).click(function() {
        location.href='<?= base_url('laporan/manage_rekap_realisasi/export_excel') ?>/?'+$('#search_rekap_realisasi').serialize();
    });
    $('#tahun_anggaran').change(function() {
        $('#kodema, #id_kodema').val('');
    });
    $('#awal_rekap_realisasi, #akhir_rekap_realisasi').datepicker({
        changeYear: true,
        changeMonth: true
    });
    $('#tanggal').datepicker({
        changeYear: true,
        changeMonth: true,
        onSelect: function() {
            var jenis = $('#jenis').val();
            if ($('#id_rekap_realisasi').val() === '') {
                get_last_code_rekap_realisasi(jenis, $(this).val());
                get_nominal_renbut($('#id_kode').val(), $('#tahun').val());
            }
        }
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
            <button id="cari_button">Cari</button>
            <button id="excel_rekap_realisasi">Export Excel</button>
            <button id="reload_rekap_realisasi_data">Reload Data</button>
            <div id="result-rekap_realisasi">

            </div>
        </div>
    </div>
    <div id="dialog_rekap_realisasi_search" class="nodisplay">
        <form action="" id="search_rekap_realisasi">
            <table width=100% cellpadding=0 cellspacing=0 class=inputan>
                <tr><td>Range Tanggal:</td><td><input type="text" name="awal" id="awal_rekap_realisasi" value="<?= date("01/m/Y") ?>" size="10" /> s.d <input type="text" name="akhir" id="akhir_rekap_realisasi" value="<?= date("d/m/Y") ?>" /></td></tr>
                <tr><td>Jenis:</td><td><?= form_dropdown('jenis', array('' => 'Semua Jenis ...', 'SPP' => 'SPP', 'NON SPP' => 'Non SPP'), NULL, 'id=jenis_laporan style="width: 300px;"') ?></td></tr>
                <tr><td>Satuan Kerja:</td><td><select name="id_satker" id="id_satker_rekap"><option value="">Pilih Satker ...</option><?php foreach ($satker as $data) { ?><option value="<?= $data->id ?>"><?= $data->kode ?> <?= $data->nama ?></option><?php } ?></select></td></tr>
                <tr><td>Tahun Anggaran:</td><td>
                <select name="tahun" id="tahun_anggaran">
                <?php for ($i = date("Y"); $i >=2014 ; $i--) { ?>
                    <option value="<?= $i ?>" <?= (($i === date("Y"))?'selected':'') ?>><?= $i ?></option>
                <?php } ?>
                </select></td></tr>
                <tr><td>Kode MA/Proja:</td><td><?= form_input('kode', NULL, 'id=kodema') ?><?= form_hidden('id_kode', NULL, 'id=id_kodema') ?></td></tr>
            </table>
        </form>
    </div>
</div>