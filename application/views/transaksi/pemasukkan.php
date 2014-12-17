<title><?= $title ?></title>
<div class="titling"><h1><?= $title ?></h1></div>
<?= $this->load->view('message') ?>
<script type="text/javascript">
$(function() {
    $('#tabs').tabs();
    get_list_pemasukkan(1);
    $('#add_pemasukkan').button({
        icons: {
            secondary: 'ui-icon-newwin'
        }
    }).click(function() {
        form_pemasukkan();
    });
    $('#tambah_button').button({
        icons: {
            secondary: 'ui-icon-search'
        }
    }).click(function() {
        form_add();
    });
    $('#reload_pemasukkan').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        get_list_pemasukkan();
    });
});
function get_list_pemasukkan(page, src, id) {
    $.ajax({
        url: '<?= base_url('transaksi/manage_pemasukkan') ?>/list/'+page,
        data: 'search='+src+'&id='+id+'&bulan='+$('#year').val()+'-'+$('#bln').val()+'&id_satker='+$('#id_satker').val()+'&proja='+$('#uraian').val()+'&pjawab='+$('#png_jawab').val(),
        cache: false,
        success: function(data) {
            $('#result').html(data);
        }
    });
}

function form_add() {
    var str = '<div id="dialog_pemasukkan"><form action="" id="save_pemasukkan">'+
            '<?= form_hidden('id_pemasukkan', NULL, 'id=id_pemasukkan') ?>'+
            '<table width=100% cellpadding=0 cellspacing=0 class=data-input>'+
                '<tr><td width=40%>MA Proja:</td><td><?= form_input('uraian', NULL, 'id=uraian size=60') ?><?= form_hidden('id_uraian', NULL, 'id=id_uraian') ?></td></tr>'+
                '<tr><td width=40%>Keterangan:</td><td><?= form_input('keterangan', NULL, 'id=keterangan size=60') ?></td></tr>'+
                '<tr><td>Kode Perkiraan:</td><td><?= form_input('kode_perkiraan', NULL, 'id=kode_perkiraan size=60') ?></td></tr>'+
                '<tr><td width=40%>Jumlah Penerimaan Rp.:</td><td><?= form_input('nominal', NULL, 'id=nominal size=60 onkeyup="FormNum(this);"') ?></td></tr>'+
            '</table>'+
            '</form></div>';
    $(str).dialog({
        title: 'Entri Kas Masuk',
        autoOpen: true,
        width: 480,
        height: 220,
        modal: true,
        hide: 'clip',
        show: 'blind',
        buttons: {
            "Simpan": function() {
                $('#save_pemasukkan').submit();
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
    $('#save_pemasukkan').submit(function() {
        if ($('#id_uraian').val() === '') {
            custom_message('Peringatan','Kode MA Proja tidak boleh kosong !','#uraian'); return false;
        }
        if ($('#kode_perkiraan').val() === '') {
            custom_message('Peringatan','Kode akun perkiraan tidak boleh kosong !','#uraian'); return false;
        }
        $.ajax({
            url: '<?= base_url('transaksi/manage_pemasukkan/save') ?>',
            dataType: 'json',
            type: 'POST',
            data: $('#save_pemasukkan').serialize(),
            success: function(data) {
                if (data.status === true) {
                    $('#kode_perkiraan, #nominal').val('').focus();
                    alert_tambah('#kode_perkiraan');
                    get_list_pemasukkan('undefined','',data.id_penerimaan);
                }
            }
        });
        return false;
    });
}

function edit_pemasukkan(id) {
    var str = '<div id=alert>'+
            '<table width=100% class=data-input>'+
                '<tr><td>Status:</td><td><select name=status id=status><option value="Disetujui">Disetujui</option><option value="Ditolak">Ditolak</option></select></td></tr>'+
            '</table>'+
            '</div>';
    $(str).dialog({
        title: 'Konfirmasi Persetujuan',
        autoOpen: true,
        modal: true,
        buttons: {
            "Save": function() {
                $.ajax({
                    url: '<?= base_url('transaksi/manage_pemasukkan/approve') ?>?id='+id+'&status='+$('#status').val(),
                    cache: false,
                    dataType: 'json',
                    success: function(data) {
                        $('#alert').dialog().remove();
                        if (data.status === 'Disetujui') {
                            custom_message('Informasi','Data rencana kebutuhan sudah <b>disetujui</b>');
                        } else {
                            custom_message('Informasi','Data rencana kebutuhan <b>ditolak</b>');
                        }
                        get_list_pemasukkan($('.noblock').html());
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
    get_list_pemasukkan(page, search);
}

function cetak_bukti_kas(id) {
    var wWidth = $(window).width();
    var dWidth = wWidth * 1;
    var wHeight= $(window).height();
    var dHeight= wHeight * 1;
    var x = screen.width/2 - dWidth/2;
    var y = screen.height/2 - dHeight/2;
    window.open('<?= base_url('transaksi/manage_pemasukkan') ?>/print_bukti_kas?id='+id, 'Renbut Cetak', 'width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
}

</script>
<div class="kegiatan">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Entri pemasukkan</a></li>
        </ul>
        <div id="tabs-1">
            <button id="tambah_button">Tambah Pemasukkan</button>
            <button id="reload_pemasukkan">Refresh</button>
            <div id="result">

            </div>
        </div>
</div>