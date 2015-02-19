<title><?= $title ?></title>
<div class="titling"><h1><?= $title ?></h1></div>
<?= $this->load->view('message') ?>
<script type="text/javascript">
$(function() {
    $('#tabs').tabs();
    get_list_perwabku(1);
    $('#add_perwabku').button({
        icons: {
            secondary: 'ui-icon-newwin'
        }
    }).click(function() {
        form_perwabku();
    });
    $('#cari_button').button({
        icons: {
            secondary: 'ui-icon-search'
        }
    }).click(function() {
        $('#dialog_perwabku_search').dialog({
            title: 'Cari Data Rencana Kebutuhan',
            autoOpen: true,
            width: 480,
            autoResize:true,
            modal: true,
            hide: 'explode',
            show: 'blind',
            position: ['center',47],
            buttons: {
                "Cancel": function() {
                    $('#dialog_perwabku_search').dialog('destroy');
                },
                "Cari": function() {
                    get_list_perwabku(1);
                } 
            }, close: function() {
                $('#dialog_perwabku_search').dialog('destroy');
            }, open: function() {
                $('#uraian').focus();
            }
        });
    });
    $('#reload_perwabku').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        get_list_perwabku(1);
    });
});
function get_list_perwabku(page, src, id) {
    $.ajax({
        url: '<?= base_url('transaksi/manage_perwabku') ?>/list/'+page,
        data: $('#search_perwabku').serialize(),
        cache: false,
        success: function(data) {
            $('#result').html(data);
        }
    });
}

function get_nominal_perwabku(id) {
    $.ajax({
        url: '<?= base_url('autocomplete/get_nominal_perwabku') ?>/'+id,
        dataType: 'json',
        success: function(data) {
           //$('#jml_perwabku').val(numberToCurrency(data.total));
        }
    });
}

function get_nomor_perwabku() {
    $.ajax({
        url: '<?= base_url('autocomplete/get_nomor_perwabku') ?>',
        dataType: 'json',
        success: function(data) {
           $('#nomor').val(data);
        }
    });
}

function removeEl(el) {
    
}

function bkk_add_row() {
    var jml = $('.rows_bkk').length+1;
    str = '<div class="rows_bkk" style="margin-bottom: 3px;"><input type="text" name="nomorbkk" id="nomorbkk" /> <input type="hidden" name="id_nomorbkk" id="id_nomorbkk" /></div>';
            //'<button type="button" class="btn btn-default btn-xs" onclick="removeEl(this);" id=""><i class="fa fa-times"></i></button>';
    $('#nobkk').append(str);
    $('#nomorbkk').autocomplete("<?= base_url('autocomplete/nomorbkkdp') ?>",
    {
        parse: function(data){
            var parsed = [];
            for (var i=0; i < data.length; i++) {
                parsed[i] = {
                    data: data[i],
                    value: data[i].kode // nama field yang dicari
                };
            }
            return parsed;
        },
        formatItem: function(data,i,max){
            var str = '<div class=result>'+data.kode+' '+data.keterangan+'<br/>Rp. '+numberToCurrency(data.pengeluaran)+'</div>';
            return str;
        },
        width: 300, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
        cacheLength: 0,
        max: 100
    }).result(
    function(event,data,formated){
        $(this).val(data.kode);
        $('#id_nomorbkk').val(data.id);
        $('#keterangan').html(data.keterangan);
        $('#nominal').html('Rp. '+numberToCurrency(data.pengeluaran));
        $('#penerima').html(data.penerima);
    });
}

function get_nomor_perwabku() {
    $.ajax({
        url: '<?= base_url('autocomplete/get_nomor_perwabku') ?>',
        data: 'tanggal='+$('#tanggal').val(),
        dataType: 'json',
        success: function(data) {
           $('#nomor').val(data);
        }
    });
}

function form_perwabku() {
    var str = '<div id="dialog_perwabku"><form action="" id="save_perwabku">'+
            '<?= form_hidden('id_perwabku', NULL, 'id=id_perwabku') ?>'+
            '<table width=100% cellpadding=0 cellspacing=0 class=inputan>'+
                '<tr><td width=40%>Nomor:</td><td><?= form_input('nomor', '', 'id=nomor size=60') ?></td></tr>'+
                '<tr><td width=40%>Tanggal Perwabku:</td><td><?= form_input('tanggal', date("d/m/Y"), 'id=tanggal size=10') ?></td></tr>'+
                //'<tr><td></td><td><button type="button" class="btn btn-default btn-xs delete" onclick="bkk_add_row();"><i class="fa fa-plus-circle"></i> Tambah Kode BKK</button></td></tr>'+
                '<tr><td width=40% valign="top">Nomor BKK (DP):</td><td id="nobkk"></td></tr>'+
                '<tr><td width=40% valign="top">Keterangan:</td><td id="keterangan"></td></tr>'+
                '<tr><td width=40% valign="top">Nominal:</td><td id="nominal"></td></tr>'+
                '<tr><td width=40% valign="top">Penerima:</td><td id="penerima"></td></tr>'+
            '</table>'+
            '</form></div>';
    $(str).dialog({
        title: 'Tambah Perwabku',
        autoOpen: true,
        width: 480,
        autoResize:true,
        modal: true,
        hide: 'explode',
        show: 'blind',
        position: ['center',47],
        buttons: {
            "Cancel": function() {
                $(this).dialog().remove();
            },
            "Simpan": function() {
                $('#save_perwabku').submit();
            }
        }, close: function() {
            $(this).dialog().remove();
        }, open: function() {
            $('#nomor').focus();
            bkk_add_row();
            get_nomor_perwabku();
        }
    });
    $('#tanggal').datepicker({
        changeYear: true,
        changeMonth: true,
        onSelect: function() {
            get_nomor_perwabku();
        }
    });
    $('#save_perwabku').submit(function() {
        if ($('#nomorbkk').val() === '') {
            custom_message('Peringatan', 'Nomor BKK harus dipilih', '#nomorbkk');
            return false;
        }
        $.ajax({
            url: '<?= base_url('transaksi/manage_perwabku/save') ?>',
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            cache: false,
            success: function(data) {
                if (data.status === true) {
                    $('#dialog_perwabku').dialog().remove();
                    alert_tambah();
                    get_list_perwabku(1);
                }
            }
        });
        return false;
    });
}

function print_perwabku(id) {
    var wWidth = $(window).width();
    var dWidth = wWidth * 1;
    var wHeight= $(window).height();
    var dHeight= wHeight * 1;
    var x = screen.width/2 - dWidth/2;
    var y = screen.height/2 - dHeight/2;
    window.open('<?= base_url('transaksi/manage_perwabku') ?>/print?id='+id, 'perwabku Cetak', 'width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
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
                    url: '<?= base_url('transaksi/manage_perwabku/delete') ?>?id='+id,
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
            <li><a href="#tabs-1">Entri Perwabku</a></li>
        </ul>
        <div id="tabs-1">
            <button id="add_perwabku">Tambah Data</button>
            <button id="cari_button">Cari Data</button>
            <button id="reload_perwabku">Refresh</button>
            <div id="result">

            </div>
        </div>
    </div>
    <div id="dialog_perwabku_search" class="nodisplay">
        <form action="" id="search_perwabku">
        <table width=100% cellpadding=0 cellspacing=0 class=inputan>
            <tr><td width=25%>Bulan Tahun:</td><td><select name=bln id=bln style="width: 74px;"><?php foreach ($bulan as $bln) { ?> <option value="<?= $bln[0] ?>" <?= (($bln[0] === date("m"))?'selected':NULL) ?>><?= $bln[1] ?></option><?php } ?></select><select name="year" id="year" style="width: 74px;"><option value="">Select Year ....</option><?php for($i = 2010; $i <= date("Y"); $i++) { ?> <option value="<?= $i ?>" <?php if ($i == date("Y")) { echo "selected"; } ?>><?= $i ?></option><?php } ?></select></td></tr>
            <tr><td>Satuan Kerja:</td><td><select name=id_satker id=id_satker><option value="">Pilih Satker ...</option><?php foreach ($satker as $data) { ?><option value="<?= $data->id ?>"><?= $data->nama ?></option><?php } ?></select></td></tr>
        </table>
        </form>
    </div>
</div>