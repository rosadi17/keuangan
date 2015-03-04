<title><?= $title ?></title>
<?= $this->load->view('message') ?>
<script type="text/javascript">
$(function() {
    get_list_cashbon(1);
    $('#add_cashbon').button({
        icons: {
            secondary: 'ui-icon-newwin'
        }
    }).click(function() {
        form_cashbon();
    });
    $('#cari_button_cashbon').button({
        icons: {
            secondary: 'ui-icon-search'
        }
    }).click(function() {
        form_cari();
    });
    $('#reload_cashbon').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        get_list_cashbon();
    });
});
function get_list_cashbon(page, src, id) {
    $.ajax({
        url: '<?= base_url('laporan/manage_cashbon') ?>/list/'+page,
        data: 'search='+src+'&id='+id+'&bulan='+$('#year').val()+'-'+$('#bln').val()+'&id_satker='+$('#id_satker').val()+'&proja='+$('#uraian').val()+'&pjawab='+$('#png_jawab').val(),
        cache: false,
        success: function(data) {
            $('#result-cashbon').html(data);
        }
    });
}

function form_cari() {
    var str = '<div id="dialog_dropping"><form action="" id="save_dropping">'+
            '<table width=100% cellpadding=0 cellspacing=0 class=inputan>'+
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
        autoResize:true,
        hide: 'explode',
        show: 'blind',
        position: ['center',47],
        buttons: {
            "Cancel": function() {
                $(this).dialog().remove();
            },
            "Cari": function() {
                get_list_cashbon(1);
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

function edit_cashbon(str) {
    var arr = str.split('#');
    form_cashbon();
    $('#id_cashbon').val(arr[0]);
    $('#uraian').val(arr[1]);
    $('#keterangan').val(arr[2]);
    $('#jml_cashbon').val(arr[3]);
    $('#penerima').val(arr[4]);
    $('#id_uraian').val(arr[5]);
    $('#tanggal').val(arr[6]);
    $('#dialog_cashbon').dialog({ title: 'Edit cashbon satuan kerja' });
}

function print_cashbon(id) {
    var wWidth = $(window).width();
    var dWidth = wWidth * 1;
    var wHeight= $(window).height();
    var dHeight= wHeight * 1;
    var x = screen.width/2 - dWidth/2;
    var y = screen.height/2 - dHeight/2;
    window.open('<?= base_url('laporan/manage_cashbon') ?>/print?id='+id, 'cashbon Cetak', 'width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
}

function paging(page, tab, search) {
    get_list_cashbon(page, search);
}
</script>
<div class="kegiatan">
    <button id="cari_button_cashbon">Cari Data</button>
    <button id="reload_cashbon">Refresh</button>
    <div id="result-cashbon" style="overflow-x: auto;">

    </div>
</div>