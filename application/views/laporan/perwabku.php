<title><?= $title ?></title>
<div class="titling"><h1><?= $title ?></h1></div>
<?= $this->load->view('message') ?>
<script type="text/javascript">
$(function() {
    $('#tabs').tabs();
    get_list_perwabku(1);
    $('#export_excel').button({
        icons: {
            secondary: 'ui-icon-print'
        }
    }).click(function() {
        window.location='<?= base_url('laporan/manage_perwabku/export') ?>/?'+$('#search_perwabku').serialize();
    });
    $('#cari_button').button({
        icons: {
            secondary: 'ui-icon-search'
        }
    }).click(function() {
        $('#dialog_perwabku_search').dialog({
            title: 'Cari Data Perwabku',
            autoOpen: true,
            width: 480,
            autoResize:true,
            modal: true,
            hide: 'explode',
            show: 'blind',
            position: ['center',47],
            buttons: {
                "Cancel": function() {
                    $('#dialog_perwabku_search').dialog('close');
                },
                "Cari": function() {
                    get_list_perwabku(1);
                    $('#dialog_perwabku_search').dialog('close');
                } 
            }, close: function() {
                $('#dialog_perwabku_search').dialog('close');
            }, open: function() {
                $('#awal, #akhir').datepicker('hide');
                $('#id_satker').focus();
            }
        });
    });
    $('#awal, #akhir').datepicker({
        changeYear: true,
        changeMonth: true
    });
    $('#reload_perwabku').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        reset_form();
        get_list_perwabku(1);
    });
});

function reset_form() {
    $('input[type=text], input[type=hidden], select, textarea').val('');
    $('#awal').val('<?= date("01/m/Y") ?>');
    $('#akhir').val('<?= date("d/m/Y") ?>');
}

function get_list_perwabku(page, src, id) {
    $.ajax({
        url: '<?= base_url('laporan/manage_perwabku') ?>/list/'+page,
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
    el.parentNode.remove();
    var jml = $('.rows_bkk').length;
    var col = 0;
    for (i = 1; i <= jml; i++) {
        $('.rows_bkk:eq('+col+')').children('.nomorbkk').attr('id', 'nomorbkk'+i);
        $('.rows_bkk:eq('+col+')').children('.id_nomorbkk').attr('id', 'id_nomorbkk'+i);
        col++;
    }
}

function bkk_add_row() {
    var jml = $('.rows_bkk').length+1;
    str = '<div class="rows_bkk" style="margin-bottom: 3px;">'+
            '<input type="text" name="nomorbkk[]" id="nomorbkk'+jml+'" class="nomorbkk" /> '+
            '<input type="hidden" name="id_nomorbkk[]" id="id_nomorbkk'+jml+'" class="id_nomorbkk" />'+
            '<button class="btn btn-default btn-xs" onclick="removeEl(this);"><i class="fa fa-times"></i></button>'+
          '</div>';
    $('#nobkk').append(str);
    $('#nomorbkk'+jml).autocomplete("<?= base_url('autocomplete/nomorbkkdp') ?>",
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
            var str = '<div class=result>'+datefmysql(data.tanggal)+' '+data.kode+' '+data.keterangan+'<br/>Rp. '+numberToCurrency(data.pengeluaran)+'</div>';
            return str;
        },
        width: 300, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
        cacheLength: 0,
        max: 100
    }).result(
    function(event,data,formated){
        $(this).val(data.kode+' Rp. '+numberToCurrency(data.pengeluaran)+' '+data.keterangan);
        $('#id_nomorbkk'+jml).val(data.id);
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
                '<tr><td></td><td><button type="button" class="btn btn-default btn-xs delete" onclick="bkk_add_row();"><i class="fa fa-plus-circle"></i> Tambah Kode BKK</button></td></tr>'+
                '<tr><td width=40% valign="top">Nomor BKK (DP):</td><td id="nobkk"></td></tr>'+
                '<tr><td width=40% valign="top">Dana yang Digunakan Rp.:</td><td><input type="text" name="dana" id="dana" onkeyup="FormNum(this);" /></td></tr>'+
                '<tr><td>Kelengkapan:</td><td>'+
                    '<select name="kelengkapan" id="kelengkapan">'+
                        '<option value="">Pilih ...</option>'+
                        '<option value="Asli">Asli</option>'+
                        '<option value="Copy">Copy</option>'+
                        '<option value="Asli & Copy">Asli & Copy</option>'+
                    '</select>'+
                '</td></tr>'+
                '<tr><td>Catatan/Memorial:</td><td><textarea name="catatan" id="catatan" rows="4"></textarea></td></tr>'+
            '</table>'+
            '</form></div>';
    $(str).dialog({
        title: 'Tambah Perwabku',
        autoOpen: true,
        width: 510,
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
        var jml = $('.rows_bkk').length;
        for (i = 1; i <= jml; i++) {
            if ($('#id_nomorbkk'+i).val() === '') {
                custom_message('Peringatan', 'Nomor BKK harus dipilih', '#nomorbkk'+i); return false;
            }
        }
        if ($('#dana').val() === '') {
            custom_message('Peringatan','Dana yang digunakan harus diisikan !','#dana'); return false;
        }
        
        $.ajax({
            url: '<?= base_url('laporan/manage_perwabku/save') ?>',
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
            <button id="cari_button">Cari Data</button>
            <button id="export_excel">Export Excel</button>
            <button id="reload_perwabku">Reload Data</button>
            <div id="result">

            </div>
        </div>
    </div>
    <div id="dialog_perwabku_search" class="nodisplay">
        <form action="" id="search_perwabku">
        <table width=100% cellpadding=0 cellspacing=0 class=inputan>
            <tr><td>Tanggal Perwabku:</td><td><input type="text" name="awal" id="awal" value="<?= date("01/m/Y") ?>" size="10" /> s.d <input type="text" name="akhir" id="akhir" value="<?= date("d/m/Y") ?>" /></td></tr>
            <tr><td>Satuan Kerja:</td><td><select name=id_satker id=id_satker><option value="">Semua Satker ...</option><?php foreach ($satker as $data) { ?><option value="<?= $data->id ?>"><?= $data->nama ?></option><?php } ?></select></td></tr>
            <tr><td>No. Perwabku:</td><td><input type="text" name="nomorpwk" id="nomorpwk" /></td></tr>
            <tr><td>No. BKK:</td><td><input type="text" name="nomorbkk" id="nomorbkk" /></td></tr>
        </table>
        </form>
    </div>
</div>