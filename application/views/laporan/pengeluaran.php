<title><?= $title ?></title>
<?= $this->load->view('message') ?>
<div class="titling"><h1><?= $title ?></h1></div>
<script type="text/javascript">
load_my_fucking_page('<?= base_url('laporan/pencairan_normal') ?>','#tabs-1');
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
    
}
$(function() {
    $('#tabs').tabs();
});
</script>
<div class="kegiatan">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1" onclick="load_my_fucking_page('<?= base_url('laporan/pencairan_normal') ?>','#tabs-1');">Laporan Pencairan</a></li>
            <li><a href="#tabs-2" onclick="load_my_fucking_page('<?= base_url('laporan/cashbon') ?>','#tabs-2');">Laporan Cashbon</a></li>
        </ul>
        <div id="tabs-1"></div>
        <div id="tabs-2"></div>
    </div>
</div>