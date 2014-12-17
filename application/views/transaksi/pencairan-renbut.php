<script type="text/javascript">
$(function() {
    get_list_pencairan_renbut();
    $('#cari_button').button({
        icons: {
            secondary: 'ui-icon-newwin'
        }
    }).click(function() {
        form_cari();
    });
    $('#reload_pencairan_renbut').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        get_list_pencairan_renbut();
    });
});

function form_cari() {
    var str = '<div id="dialog_dropping"><form action="" id="save_dropping">'+
            '<table width=100% cellpadding=0 cellspacing=0 class=data-input>'+
                '<tr><td width=25%>Bulan Tahun:</td><td><select name=bln id=bln style="width: 74px;"><?php foreach ($bulan as $bln) { ?> <option value="<?= $bln[0] ?>" <?= (($bln[0] == date("m"))?'selected':NULL) ?>><?= $bln[1] ?></option><?php } ?></select><select name="year" id="year" style="width: 72px;"><option value="">Select Year ....</option><?php for($i = 2010; $i <= date("Y"); $i++) { ?> <option value="<?= $i ?>" <?php if ($i == date("Y")) echo "selected"; else echo ""; ?>><?= $i ?></option><?php } ?></select></td></tr>'+
                '<tr><td>Satuan Kerja:</td><td><select name=id_satker id=id_satker><option value="">Pilih Satker ...</option><?php foreach ($satker as $data) { ?><option value="<?= $data->id ?>"><?= $data->nama ?></option><?php } ?></select></td></tr>'+
                '<tr><td width=40%>MA Proja:</td><td><?= form_input('uraian', NULL, 'id=uraian size=60') ?><?= form_hidden('id_uraian', NULL, 'id=id_uraian') ?></td></tr>'+
                '<tr><td width=40%>Keterangan:</td><td><?= form_input('keterangan', NULL, 'id=keterangan size=60') ?></td></tr>'+
                '<tr><td>Penanggung Jawab:</td><td><?= form_input('png_jawab', NULL, 'id=png_jawab size=60') ?></td></tr>'+
            '</table>'+
            '</form></div>';
    $(str).dialog({
        title: 'Cari dropping',
        autoOpen: true,
        width: 480,
        height: 220,
        modal: true,
        hide: 'clip',
        show: 'blind',
        buttons: {
            "Search": function() {
                get_list_dropping();
            }, "Cancel": function() {
                $(this).dialog().remove();
            }
        }, close: function() {
            $(this).dialog().remove();
        }, open: function() {
            $('#uraian').focus();
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
}

function get_list_pencairan_renbut(page, src, id) {
    $.ajax({
        url: '<?= base_url('transaksi/manage_pencairan_renbut') ?>/list/'+page,
        data: 'search='+src+'&id='+id+'&bulan='+$('#year').val()+'-'+$('#bln').val()+'&id_satker='+$('#id_satker').val()+'&proja='+$('#uraian').val()+'&pjawab='+$('#png_jawab').val(),
        cache: false,
        success: function(data) {
            $('#result-pencairan').html(data);
        }
    });
}

function edit_pencairan(arr) {
    var str = '<div id="dialog_renbut"><form action="" id="save_renbut">'+
            '<?= form_hidden('id_renbut', NULL, 'id=id_renbut') ?>'+
            '<table width=100% cellpadding=0 cellspacing=0 class=data-input>'+
                '<tr><td width=40%>Tanggal Pencairan:</td><td><?= form_input('tanggal', date("d/m/Y"), 'id=tanggal size=10') ?></td></tr>'+
                '<tr><td width=40%>MA Proja:</td><td><?= form_input('uraian', NULL, 'id=uraian size=60') ?><?= form_hidden('id_uraian', NULL, 'id=id_uraian') ?></td></tr>'+
                '<tr><td width=40%>Keterangan:</td><td><?= form_input('keterangan', NULL, 'id=keterangan size=60') ?></td></tr>'+
                '<tr><td width=40%>Jumlah Renbut Rp.:</td><td><?= form_input('jml_renbut', NULL, 'id=jml_renbut size=60 onkeyup="FormNum(this);"') ?></td></tr>'+
                '<tr><td width=40%>Jumlah Pencairan Rp.:</td><td><?= form_input('nominal', NULL, 'id=nominal size=60 onkeyup="FormNum(this);"') ?></td></tr>'+
                '<tr><td width=40%>Penerima / PngJawab:</td><td><?= form_input('penerima', NULL, 'id=penerima size=60') ?></td></tr>'+
                '<tr><td>Kode Perkiraan:</td><td><?= form_input('kode_perkiraan', NULL, 'id=kode_perkiraan size=60') ?></td></tr>'+
            '</table>'+
            '</form></div>';
    $(str).dialog({
        title: 'Form Pencairan Dana',
        autoOpen: true,
        width: 480,
        height: 253,
        modal: true,
        hide: 'clip',
        show: 'blind',
        buttons: {
            "Simpan": function() {
                $('#save_renbut').submit();
            }, "Cancel": function() {
                $(this).dialog().remove();
            }
        }, close: function() {
            $(this).dialog().remove();
        }, open: function() {
            $('#uraian').focus();
            var val = arr.split('#');        
            $('#id_renbut').val(val[0]);
            $('#uraian').val(val[1]);
            $('#keterangan').val(val[2]);
            $('#jml_renbut').val(val[3]);
            $('#nominal').val(val[3]);
            $('#penerima').val(val[4]);
            $('input[type=text]').attr('readonly','readonly');
            $('#kode_perkiraan').removeAttr('readonly');
        }
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
        $(this).val(data.id_akun);
    });
    $('#save_renbut').submit(function() {
        $('<div id=alert>Anda yakin akan melakukan transaksi pencairan ini ?</div>').dialog({
            title: 'Konfirmasi Penghapusan',
            autoOpen: true,
            modal: true,
            buttons: {
                "OK": function() {
                    if ($('#kode_perkiraan').val() === '') {
                        custom_message('Peringatan','Kode akun perkiraan tidak boleh kosong !','#kode_perkiraan'); return false;
                    }
                    $('#alert').dialog().remove();
                    $.ajax({
                        url: '<?= base_url('transaksi/manage_pencairan_renbut/save') ?>',
                        type: 'POST',
                        dataType: 'json',
                        data: $('#save_renbut').serialize(),
                        success: function(data) {
                            if (data.status === true) {
                                custom_message('Informasi','Pencairan dana berhasil dilakukan !');
                                get_list_pencairan_renbut('1','',data.id);
                                $('#dialog_renbut').dialog().remove();
                            }
                        }
                    });
                }, 
                "Cancel": function() {
                    $('#alert').dialog().remove();
                }
            },
            close: function() {
                $('#alert').dialog().remove();
            }
        });
        return false;
    });
}

function cetak_bukti_kas(id) {
    var wWidth = $(window).width();
    var dWidth = wWidth * 1;
    var wHeight= $(window).height();
    var dHeight= wHeight * 1;
    var x = screen.width/2 - dWidth/2;
    var y = screen.height/2 - dHeight/2;
    window.open('<?= base_url('transaksi/manage_pencairan_renbut') ?>/print_bukti_kas?id='+id, 'Renbut Cetak', 'width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
}

</script>
<button id="cari_button">Cari Data</button>
<button id="reload_pencairan_renbut">Refresh</button>
<div id="result-pencairan">
    
</div>