<title><?= $title ?></title>
<div class="titling"><h1><?= $title ?></h1></div>
<?= $this->load->view('message') ?>
<script type="text/javascript">
$(function() {
    $('#tabs').tabs();
    get_list_dropping(1);
    $('#add_dropping').button({
        icons: {
            secondary: 'ui-icon-newwin'
        }
    }).click(function() {
        form_dropping();
    });
    
    $('#awal_dropping, #akhir_dropping').datepicker({
        changeYear: true,
        changeMonth: true
    });
    
    $('#cari_button').button({
        icons: {
            secondary: 'ui-icon-search'
        }
    }).click(function() {
        $('#dialog_dropping').dialog({
            title: 'Cari dropping',
            autoOpen: true,
            width: 480,
            autoResize:true,
            modal: true,
            hide: 'explode',
            show: 'blind',
            position: ['center',47],
            buttons: {
                "Cancel": function() {
                    $('#dialog_dropping').dialog('destroy');
                },
                "Search": function() {
                    get_list_dropping(1);
                    $('#dialog_dropping').dialog('destroy');
                } 
            }, close: function() {
                $('#dialog_dropping').dialog('destroy');
            }, open: function() {
                $('#awal_dropping, #akhir_dropping').datepicker('hide');
                $('#id_satker').focus();
            }
        });
        $('#uraian').autocomplete("<?= base_url('autocomplete/ma_proja') ?>",
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
            width: 400, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
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
        get_list_dropping(1);
    });
});
function get_list_dropping(page, src, id) {
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
    var str = '<div id=alert>'+
            '<table width=100% class=inputan>'+
                '<tr><td>Status:</td><td><select name=status id=status><option value="">Pilih ...</option><option value="Disetujui">Disetujui</option><option value="Ditolak">Ditolak</option></select></td></tr>'+
                '<tr><td>Jumlah:</td><td><input type="text" name="jumlah" onblur="FormNum(this);" id="jumlah" value="'+numberToCurrency(nominal)+'" /></td></tr>'+
            '</table>'+
            '</div>';
    $(str).dialog({
        title: 'Konfirmasi Persetujuan',
        autoOpen: true,
        width: 480,
        autoResize:true,
        modal: true,
        hide: 'explode',
        show: 'blind',
        position: ['center',47],
        buttons: {
            "Save": function() {
                if ($('#status').val() === '') {
                    custom_message('Peringatan','Status konfirmasi harus dipilih !','#status'); return false;
                }
                $.ajax({
                    type: 'POST',
                    url: '<?= base_url('transaksi/manage_dropping/approve') ?>',
                    data: 'id='+id+'&status='+$('#status').val()+'&jumlah='+$('#jumlah').val(),
                    cache: false,
                    dataType: 'json',
                    success: function(data) {
                        $('#alert').dialog().remove();
                        if (data.status === 'Disetujui') {
                            custom_message('Informasi','Data rencana kebutuhan sudah <b>disetujui</b>');
                        } else {
                            custom_message('Informasi','Data rencana kebutuhan <b>ditolak</b>');
                        }
                        get_list_dropping($('.noblock').html());
                    }
                });
            },
            "Cancel": function() {
                $(this).dialog().remove();
            }
        }
    });
}

function delete_dropping(id, page) {
    $('<div id=alert>Anda yakin akan menghapus data ini?</div>').dialog({
        title: 'Konfirmasi Penghapusan',
        autoOpen: true,
        modal: true,
        buttons: {
            "OK": function() {
                
                $.ajax({
                    url: '<?= base_url('transaksi/manage_renbut/delete') ?>?id='+id,
                    cache: false,
                    success: function() {
                        get_list_dropping(page);
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
            <button id="cari_button">Cari Data</button>
            <button id="reload_dropping">Refresh</button>
            <div id="result">

            </div>
        </div>
    </div>
    <div class="nodisplay" id="dialog_dropping"><form action="" id="search_dropping">
        <?= form_hidden('id_dropping', NULL, 'id=id_dropping') ?>
        <table width=100% cellpadding=0 cellspacing=0 class=inputan>
            <tr><td>Tanggal Renbut:</td><td><input type="text" name="awal" id="awal_dropping" value="<?= date("01/m/Y") ?>" size="10" /> s.d <input type="text" name="akhir" id="akhir_dropping" value="<?= date("d/m/Y") ?>" /></td></tr>
            <tr><td>Satuan Kerja:</td><td><select name=id_satker id=id_satker><option value="">Semua Satker ...</option><?php foreach ($satker as $data) { ?><option value="<?= $data->id ?>"><?= $data->nama ?></option><?php } ?></select></td></tr>
            <tr><td width=40%>MA Proja:</td><td><?= form_input('uraian', NULL, 'id=uraian size=60') ?><?= form_hidden('id_uraian', NULL, 'id=id_uraian') ?></td></tr>
            <tr><td width=40%>Keterangan:</td><td><?= form_input('keterangan', NULL, 'id=keterangan size=60') ?></td></tr>
            <tr><td>Penanggung Jawab:</td><td><?= form_input('png_jawab', NULL, 'id=png_jawab size=60') ?></td></tr>
        </table>
        </form>
    </div>
</div>