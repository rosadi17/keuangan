<?= $this->load->view('message') ?>
<script type="text/javascript">
$(function() {
    $('#tabs').tabs();
    get_list_cashbon(1);
    $('#add_cashbon').button({
        icons: {
            secondary: 'ui-icon-newwin'
        }
    }).click(function() {
        form_cashbon();
    });
    $('#cari_cashbon').button({
        icons: {
            secondary: 'ui-icon-search'
        }
    }).click(function() {
        form_cari_cashbon();
    });
    $('#reload_cashbon').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        get_list_cashbon();
    });
});
function get_nominal_renbut(id) {
    $.ajax({
        url: '<?= base_url('autocomplete/get_nominal_renbut') ?>/'+id,
        dataType: 'json',
        success: function(data) {
           $('#jml_cashbon').val(numberToCurrency(data.total));
        }
    });
}
function get_list_cashbon(page, src, id) {
    $.ajax({
        url: '<?= base_url('transaksi/manage_cashbon') ?>/list/'+page,
        data: 'search='+src+'&id='+id+'&bulan='+$('#year').val()+'-'+$('#bln').val()+'&id_satker='+$('#id_satker').val()+'&proja='+$('#uraian').val()+'&pjawab='+$('#png_jawab').val(),
        cache: false,
        success: function(data) {
            $('#result').html(data);
        }
    });
}

function form_cari_cashbon() {
    var str = '<div id="dialog_cashbon"><form action="" id="save_cashbon">'+
            '<?= form_hidden('id_cashbon', NULL, 'id=id_cashbon') ?>'+
            '<table width=100% cellpadding=0 cellspacing=0 class=inputan>'+
                '<tr><td width=25%>Bulan Tahun:</td><td><select name=bln id=bln style="width: 74px;"><?php foreach ($bulan as $bln) { ?> <option value="<?= $bln[0] ?>" <?= (($bln[0] === date("m"))?'selected':NULL) ?>><?= $bln[1] ?></option><?php } ?></select><select name="year" id="year" style="width: 74px;"><option value="">Select Year ....</option><?php for($i = 2010; $i <= date("Y"); $i++) { ?> <option value="<?= $i ?>" <?php if ($i === date("Y")) echo "selected";  ?>><?= $i ?></option><?php } ?></select></td></tr>'+
                '<tr><td>Satuan Kerja:</td><td><select name=id_satker id=id_satker><option value="">Pilih Satker ...</option><?php foreach ($satker as $data) { ?><option value="<?= $data->id ?>"><?= $data->nama ?></option><?php } ?></select></td></tr>'+
                '<tr><td width=40%>MA Proja:</td><td><?= form_input('uraian', NULL, 'id=uraian size=60') ?><?= form_hidden('id_uraian', NULL, 'id=id_uraian') ?></td></tr>'+
                '<tr><td width=40%>Keterangan:</td><td><?= form_input('keterangan', NULL, 'id=keterangan size=60') ?></td></tr>'+
                '<tr><td>Penanggung Jawab:</td><td><?= form_input('png_jawab', NULL, 'id=png_jawab size=60') ?></td></tr>'+
            '</table>'+
            '</form></div>';
    $(str).dialog({
        title: 'Cari cashbon',
        autoOpen: true,
        width: 480,
        autoResize:true,
        modal: true,
        hide: 'explode',
        show: 'blind',
        position: ['center',47],
        buttons: {
            "Simpan": function() {
                get_list_cashbon();
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

function form_cashbon() {
    var str = '<div id="dialog_cashbon"><form action="" id="save_cashbon">'+
            '<?= form_hidden('id_cashbon', NULL, 'id=id_cashbon') ?>'+
            '<table width=100% cellpadding=0 cellspacing=0 class=inputan>'+
                '<tr><td width=40%>Tanggal:</td><td><?= form_input('tanggal', date("d/m/Y"), 'id=tanggal size=10') ?></td></tr>'+
                '<tr><td width=40%>MA Proja:</td><td><?= form_input('uraian', NULL, 'id=uraian size=60') ?><?= form_hidden('id_uraian', NULL, 'id=id_uraian') ?></td></tr>'+
                '<tr><td width=40%>Keterangan:</td><td><?= form_input('keterangan', NULL, 'id=keterangan size=60') ?></td></tr>'+
                '<tr><td width=40%>Jumlah cashbon Rp.:</td><td><?= form_input('jml_cashbon', NULL, 'id=jml_cashbon size=60 onkeyup="FormNum(this);"') ?></td></tr>'+
                '<tr><td width=40%>Penerima / PngJawab:</td><td><?= form_input('penerima', NULL, 'id=penerima size=60') ?></td></tr>'+
                /*'<tr><td width=40%>Nominal Rp.:</td><td><?= form_input('nominal', NULL, 'id=nominal size=60 onkeyup="FormNum(this);"') ?></td></tr>'+
                '<tr><td width=40%>Cash bon Rp.:</td><td><?= form_input('cashbon', NULL, 'id=cashbon size=60 onkeyup="FormNum(this);"') ?></td></tr>'+                
                '<tr><td width=40%>Penerima / PngJawab:</td><td><?= form_input('penerima', NULL, 'id=penerima size=60') ?></td></tr>'+*/
            '</table>'+
            '</form></div>';
    $(str).dialog({
        title: 'Tambah cashbon',
        autoOpen: true,
        width: 480,
        autoResize:true,
        modal: true,
        hide: 'explode',
        show: 'blind',
        position: ['center',47],
        buttons: {
            "Simpan": function() {
                $('#save_cashbon').submit();
            }, "Cancel": function() {
                $(this).dialog().remove();
            }
        }, close: function() {
            $(this).dialog().remove();
        }, open: function() {
            $('#uraian').focus();
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
        get_nominal_renbut(data.id);
    });
    $('#save_cashbon').submit(function() {
        if ($('#nama').val() === '') {
            alert('Nama bank tidak boleh kosong !');
            $('#nama').focus(); return false;
        }
        var cek_id = $('#id_cashbon').val();
        $.ajax({
            url: '<?= base_url('transaksi/manage_cashbon/save') ?>',
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            cache: false,
            success: function(data) {
                if (data.status === true) {
                    if (cek_id === '') {
                        alert_tambah();
                        $('input').val('');
                        get_list_cashbon('1','',data.id_cashbon);
                    } else {
                        alert_edit();
                        $('#form_add').dialog().remove();
                        get_list_cashbon($('.noblock').html(),'');
                    }
                }
            }
        });
        return false;
    });
}

function edit_cashbon(str) {
    var arr = str.split('#');
    form_cashbon();
    $('#id_cashbon').val(arr[0]);
    $('#uraian').val(arr[1]);
    $('#keterangan').val(arr[2]);
    $('#jml_cashbon').val(arr[3]);
    $('#penerima').val(arr[4]);
    $('#id_uraian').val(arr[5]);
    $('#dialog_cashbon').dialog({ title: 'Edit cashbon satuan kerja' });
}

function print_cashbon(id) {
    var wWidth = $(window).width();
    var dWidth = wWidth * 1;
    var wHeight= $(window).height();
    var dHeight= wHeight * 1;
    var x = screen.width/2 - dWidth/2;
    var y = screen.height/2 - dHeight/2;
    window.open('<?= base_url('transaksi/manage_cashbon') ?>/print?id='+id, 'cashbon Cetak', 'width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
}

function paging(page, tab, search) {
    get_list_cashbon(page, search);
}

function delete_cashbon(id, page) {
    $('<div id=alert>Anda yakin akan menghapus data ini?</div>').dialog({
        title: 'Konfirmasi Penghapusan',
        autoOpen: true,
        modal: true,
        buttons: {
            "OK": function() {
                
                $.ajax({
                    url: '<?= base_url('transaksi/manage_cashbon/delete') ?>?id='+id,
                    cache: false,
                    success: function() {
                        get_list_cashbon(page);
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
<button id="add_cashbon">Tambah Data</button>
<button id="cari_cashbon">Cari Data</button>
<button id="reload_cashbon">Refresh</button>
<div id="result"></div>