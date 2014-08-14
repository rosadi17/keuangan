<title><?= $title ?></title>
<div class="titling"><h1><?= $title ?></h1></div>
<?= $this->load->view('message') ?>
<script type="text/javascript">
$(function() {
    get_list_rekening();
    $('#add-rekening').button({
        icons: {
            secondary: 'ui-icon-newwin'
        }
    }).click(function() {
        dialog_rekening();
        get_last_code('rekening', 'id', null,'#kode_rek');
    });;
    $('#reset').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        get_list_rekening();
    });
});
function get_list_rekening(page, search) {
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
    <button id="add-rekening">Tambah Rekening</button>
    <button id="reset">Reset</button>
    <div id="list_rekening"></div>
</div>