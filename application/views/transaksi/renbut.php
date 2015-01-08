<title><?= $title ?></title>
<div class="titling"><h1><?= $title ?></h1></div>
<?= $this->load->view('message') ?>
<script type="text/javascript">
$(function() {
    $('#tabs').tabs();
    get_list_renbut(1);
    $('#add_renbut').button({
        icons: {
            secondary: 'ui-icon-newwin'
        }
    }).click(function() {
        form_renbut();
    });
    $('#cari_button').button({
        icons: {
            secondary: 'ui-icon-search'
        }
    }).click(function() {
        form_cari();
    });
    $('#reload_renbut').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        get_list_renbut();
    });
});
function get_list_renbut(page, src, id) {
    $.ajax({
        url: '<?= base_url('transaksi/manage_renbut') ?>/list/'+page,
        data: 'search='+src+'&id='+id+'&bulan='+$('#year').val()+'-'+$('#bln').val()+'&id_satker='+$('#id_satker').val(),
        cache: false,
        success: function(data) {
            $('#result').html(data);
        }
    });
}

function form_cari() {
    var str = '<div id="dialog_renbut"><form action="" id="save_renbut">'+
            '<?= form_hidden('id_renbut', NULL, 'id=id_renbut') ?>'+
            '<table width=100% cellpadding=0 cellspacing=0 class=inputan>'+
                '<tr><td width=25%>Bulan Tahun:</td><td><select name=bln id=bln style="width: 74px;"><?php foreach ($bulan as $bln) { ?> <option value="<?= $bln[0] ?>" <?= (($bln[0] === date("m"))?'selected':NULL) ?>><?= $bln[1] ?></option><?php } ?></select><select name="year" id="year" style="width: 74px;"><option value="">Select Year ....</option><?php for($i = 2010; $i <= date("Y"); $i++) { ?> <option value="<?= $i ?>" <?php if ($i == date("Y")) { echo "selected"; } ?>><?= $i ?></option><?php } ?></select></td></tr>'+
                '<tr><td>Satuan Kerja:</td><td><select name=id_satker id=id_satker><option value="">Pilih Satker ...</option><?php foreach ($satker as $data) { ?><option value="<?= $data->id ?>"><?= $data->nama ?></option><?php } ?></select></td></tr>'+
            '</table>'+
            '</form></div>';
    $(str).dialog({
        title: 'Cari Renbut',
        autoOpen: true,
        width: 480,
        autoResize:true,
        modal: true,
        hide: 'explode',
        show: 'blind',
        position: ['center',47],
        buttons: {
            "Cari": function() {
                get_list_renbut();
            }, "Cancel": function() {
                $(this).dialog().remove();
            }
        }, close: function() {
            $(this).dialog().remove();
        }, open: function() {
            $('#uraian').focus();
        }
    });
}

function get_nominal_renbut(id) {
    $.ajax({
        url: '<?= base_url('autocomplete/get_nominal_renbut') ?>/'+id,
        dataType: 'json',
        success: function(data) {
           $('#jml_renbut').val(numberToCurrency(data.total));
        }
    });
}

function form_renbut() {
    var str = '<div id="dialog_renbut"><form action="" id="save_renbut">'+
            '<?= form_hidden('id_renbut', NULL, 'id=id_renbut') ?>'+
            '<table width=100% cellpadding=0 cellspacing=0 class=inputan>'+
                '<tr><td width=40%>Nomor:</td><td><?= form_input('nomor', date("ym"), 'id=nomor size=60') ?></td></tr>'+
                '<tr><td width=40%>Tanggal Kegiatan:</td><td><?= form_input('tanggal', date("d/m/Y"), 'id=tanggal size=10') ?></td></tr>'+
                '<tr><td width=40%>MA Proja:</td><td><?= form_input('uraian', NULL, 'id=uraian size=60') ?><?= form_hidden('id_uraian', NULL, 'id=id_uraian') ?></td></tr>'+
                '<tr><td width=40%>Detail:</td><td id="detail"></td></tr>'+
                '<tr><td width=40%>Jumlah Renbut Rp.:</td><td><?= form_input('jml_renbut', NULL, 'id=jml_renbut size=60 onkeyup="FormNum(this);"') ?></td></tr>'+
                '<tr><td width=40%>Penerima / PngJawab:</td><td><?= form_input('penerima', NULL, 'id=penerima size=60') ?></td></tr>'+
                '<tr><td width=40% valign="top">Keterangan:</td><td><?= form_textarea('keterangan', NULL, 'id=keterangan rows="10"') ?></td></tr>'+
                /*'<tr><td width=40%>Nominal Rp.:</td><td><?= form_input('nominal', NULL, 'id=nominal size=60 onkeyup="FormNum(this);"') ?></td></tr>'+
                '<tr><td width=40%>Cash bon Rp.:</td><td><?= form_input('cashbon', NULL, 'id=cashbon size=60 onkeyup="FormNum(this);"') ?></td></tr>'+                
                '<tr><td width=40%>Penerima / PngJawab:</td><td><?= form_input('penerima', NULL, 'id=penerima size=60') ?></td></tr>'+*/
            '</table>'+
            '</form></div>';
    $(str).dialog({
        title: 'Tambah Rencana Kebutuhan',
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
                $('#save_renbut').submit();
            }
        }, close: function() {
            $(this).dialog().remove();
        }, open: function() {
            $('#nomor').focus();
        }
    });
    $('#tanggal').datepicker({
        changeYear: true,
        changeMonth: true
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
        $('#id_uraian').val(data.id);
        $('#detail').html(data.uraian+' / '+data.keterangan);
        get_nominal_renbut(data.id);
        $('#penerima').focus();
    });
    $('#save_renbut').submit(function() {
        if ($('#nomor').val().length < 8) {
            custom_message('Peringatan', 'Nomor yang anda masukkan harus dengan format yymmxxxx misal: 15010001 !', '#nomor');
            return false;
        }
        if ($('#id_uraian').val() === '') {
            custom_message('Peringatan', 'Kode MA proja belum dipilih !', '#uraian');
            return false;
        }
        if ($('#jml_renbut').val() === '') {
            custom_message('Peringatan', 'Jumlah renbut harus diisi !', '#jml_renbut');
            return false;
        }
        if ($('#penerima').val() === '') {
            custom_message('Peringatan', 'Penerima / penanggung jawab harus diisi !', '#uraian');
            return false;
        }
        var cek_id = $('#id_renbut').val();
        $.ajax({
            url: '<?= base_url('transaksi/manage_renbut/save') ?>',
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            cache: false,
            success: function(data) {
                if (data.status === true) {
                    $('#dialog_renbut').dialog().remove();
                    if (cek_id === '') {
                        alert_tambah();
                        $('input').val('');
                        get_list_renbut('1','',data.id_renbut);
                    } else {
                        alert_edit();
                        $('#form_add').dialog().remove();
                        get_list_renbut($('.noblock').html(),'');
                    }
                }
            }
        });
        return false;
    });
}

function edit_renbut(str) {
    var arr = str.split('#');
    form_renbut();
    $('#id_renbut').val(arr[0]);
    $('#uraian').val(arr[1]);
    $('#keterangan').val(arr[2]);
    $('#jml_renbut').val(arr[3]);
    $('#penerima').val(arr[4]);
    $('#id_uraian').val(arr[5]);
    $('#tanggal').val(arr[6]);
    $('#detail').html(arr[7]);
    $('#nomor').val(arr[8]);
    $('#dialog_renbut').dialog({ title: 'Edit renbut satuan kerja' });
}

function print_renbut(id) {
    var wWidth = $(window).width();
    var dWidth = wWidth * 1;
    var wHeight= $(window).height();
    var dHeight= wHeight * 1;
    var x = screen.width/2 - dWidth/2;
    var y = screen.height/2 - dHeight/2;
    window.open('<?= base_url('transaksi/manage_renbut') ?>/print?id='+id, 'Renbut Cetak', 'width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
}

function paging(page, tab, search) {
    get_list_renbut(page, search);
}

function delete_renbut(id, page) {
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
                        get_list_renbut(page);
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
            <li><a href="#tabs-1">Entri Rencana Kebutuhan</a></li>
        </ul>
        <div id="tabs-1">
            <button id="add_renbut">Tambah Data</button>
            <button id="cari_button">Cari Data</button>
            <button id="reload_renbut">Refresh</button>
            <div id="result">

            </div>
        </div>
</div>