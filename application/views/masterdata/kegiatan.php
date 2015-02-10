<title><?= $title ?></title>
<?= $this->load->view('message') ?>
<div class="titling">
    <h1>Entri Data <?= $title ?></h1>
</div>
<script type="text/javascript">
$(function() {
    $('#tabs').tabs();
    load_my_fucking_page('<?= base_url('masterdata/program') ?>','#tabs-1');
});
function load_my_fucking_page(url, el) {
    if ($(el).html() === '') {
        $.ajax({
            url: url,
            success: function(data) {
                $(el).html(data);
            }
        });
    }
}


function paging(page, tab, search) {
    var active = $('#tabs-instansi').tabs('option','active');
    paginate(page, tab, search, active);
    //load_data_barang(page, search);
}

function paginate(page, tab, search, active) {
    if (active === 0) {
        load_data_pabrik(page, search);
    }
    if (active === 1) {
        load_data_supplier(page, search);
    }
    if (active === 2) {
        load_data_instansi(page, search);
    }
    if (active === 3) {
        load_data_asuransi(page, search);
    }
    if (active === 4) {
        load_data_bank(page, search);
    }
}
</script>
<div class="kegiatan">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1" onclick="load_my_fucking_page('<?= base_url('masterdata/program') ?>','#tabs-1');">Program</a></li>
            <li><a href="#tabs-2" onclick="load_my_fucking_page('<?= base_url('masterdata/keg_program') ?>','#tabs-2');">Kegiatan</a></li>
            <li><a href="#tabs-3" onclick="load_my_fucking_page('<?= base_url('masterdata/sub_kegiatan') ?>','#tabs-3');">Sub Kegiatan</a></li>
            <li><a href="#tabs-4" onclick="load_my_fucking_page('<?= base_url('masterdata/uraian') ?>','#tabs-4');">Uraian</a></li>
            <li><a href="#tabs-5" onclick="load_my_fucking_page('<?= base_url('masterdata/sub_uraian') ?>','#tabs-5');">Sub Uraian</a></li>
<!--            <li><a href="#tabs-6" onclick="load_my_fucking_page('<?= base_url('masterdata/sub_sub_uraian') ?>','#tabs-6');">Sub Sub Uraian</a></li>-->
            <li><a href="#tabs-7" onclick="load_my_fucking_page('<?= base_url('masterdata/kegiatan_preview') ?>','#tabs-7');">Preview</a></li>
        </ul>
        <div id="tabs-1"></div>
        <div id="tabs-2"></div>
        <div id="tabs-3"></div>
        <div id="tabs-4"></div>
        <div id="tabs-5"></div>
<!--        <div id="tabs-6"></div>-->
        <div id="tabs-7"></div>
    </div>
</div>