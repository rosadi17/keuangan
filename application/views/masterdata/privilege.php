<script type="text/javascript">
    $(function() {
        get_privileges_list();
        $('#all').button({
           icons: {
               secondary: 'ui-icon-circlesmall-plus'
           } 
        }).click(function(){
            $(".check").each( function() {
                $(this).attr("checked",'checked');
            });
        }).click(function() {
            $('input[type=checkbox]').attr('checked','checked');
        });
        $('#uncek').button({
           icons: {
               secondary: 'ui-icon-circlesmall-minus'
           } 
        }).click(function(){
            $('input[type=checkbox]').removeAttr('checked');
        });
        $('#batal').click(function(){
            $('#privform').dialog("close");
        });
    });

    function form_submit(){
        var Url = '<?= base_url("masterdata/manage_privileges") ?>/add/';
        $.ajax({
            type : 'POST',
            url: Url,              
            data: $('#form_priv').serialize(),
            cache: false,
            success: function(data) {
                $('#list').html(data);
                alert_edit();
            }
        });   
    }
    
    
</script>
<?= form_open('', 'id = form_priv') ?>
<div class='msg' id="pesan"></div>
<table width="100%" class="data-input">
    <tr><td width="15%">ID:</td><td><?= $id ?><?= form_hidden('id_group', $id) ?></td> </tr>
    <tr><td>Nama Profesi:</td><td><?= $nama ?></td> </tr>
</table>


<?= form_button('Check All', 'id=all') ?>
<?= form_button('Uncheck All', 'id=uncek') ?><br/>
<div id="list"></div>

<?= form_hidden('id_penduduk') ?>

<?= form_close() ?>
<div id="edit_akun" style="display: none" title="Information Alert">
    <p>
        <span class="ui-icon ui-icon-circle-check" style="float: left; margin: 0 7px 50px 0;"></span>
        Data Telah Berhasil di Update
    </p>
</div>

