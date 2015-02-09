<title><?= $title ?></title>
<div class="titling"><h1><?= $title ?></h1></div>
<?= $this->load->view('message') ?>
<script type="text/javascript">
$(function() {
    get_list_rekening();
    $('#tabs').tabs();
    $('#add-rekening').button({
        icons: {
            secondary: 'ui-icon-newwin'
        }
    }).click(function() {
        dialog_rekening('Tambah Rekening');
        get_last_code('rekening', 'id', null,'#kode_rek');
    });;
    $('#reset').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        get_list_rekening(1);
    });
});
function get_list_rekening(page) {
    $.ajax({
        url: '<?= base_url('masterdata/manage_rekening') ?>/list/'+page,
        cache: false,
        success: function(data) {
            $('#list_rekening').html(data);
        }
    });
}
</script>
<div class="kegiatan">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Kode Rekening</a></li>
        </ul>
        <div id="tabs-1">
            <button id="add-rekening">Tambah Rekening</button>
            <button id="reset">Reload Data</button>
            <div id="list_rekening"></div>
            </div>
        </div>
</div>