<title><?= $title ?></title>
<div class="titling"><h1><?= $title ?></h1></div>
<?= $this->load->view('message') ?>
<script type="text/javascript">
    $(function() {
        $('#tabs').tabs();
        $('#save').button({
            icons: {
                secondary: 'ui-icon-disk'
            }
        });
    });
    
    function save_data() {
        var passlama = $('#passlama').val();
        var passbaru = $('#passbaru').val();
        var passconf = $('#passconfirm').val();
        if (passlama === '' || passbaru === '' || passconf === '') {
            custom_message('Peringatan','Data tidak boleh ada yang kosong !','#passlama'); return false;
        }
        if (passbaru !== passconf) {
            custom_message('Peringatan','Password baru dan password konfirmasi harus sama!','#passbaru'); return false;
        }
        $.ajax({
            url: '<?= base_url('masterdata/save_ubah_password') ?>',
            type: 'POST',
            dataType: 'json',
            data: $('#formedit').serialize(),
            success: function(data) {
                if (data.status === true) {
                    alert_edit();
                    $('input[type=password]').val('');
                } else {
                    alert_edit_failed();
                }
            }
        });
        return false;
    }

</script>
<div class="kegiatan">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Ubah Password</a></li>
        </ul>
        <div id="tabs-1">
            <div id="result">
                <form id="formedit">
                <table width=100% cellpadding=0 cellspacing=0 class="inputan">
                    <tr>
                        <td width="15%">Password Lama:</td>
                        <td><input type="password" name="passlama" id="passlama" size="30" /></td>
                    </tr>
                    <tr>
                        <td>Password Baru:</td>
                        <td><input type="password" name="passbaru" id="passbaru" size="30" /></td>
                    </tr>
                    <tr>
                        <td>Password Confirm:</td>
                        <td><input type="password" name="passconfirm" id="passconfirm" size="30" /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type="button" id="save" onclick="save_data();" value="Simpan">
                        </td>
                    </tr>
                </table>
                </form>
            </div>
        </div>
</div>