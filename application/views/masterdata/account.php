<script type="text/javascript">
    $(function() {
        get_user_list(1);
        $('#add-user-account').button({
            icons: {
                secondary: 'ui-icon-newwin'
            }
        }).click(function() {
            form_user_account();
        });
        
        $('#reset-user-account').button({
            icons: {
                secondary: 'ui-icon-refresh'
            }
        }).click(function() {
            get_user_list(1);
        });
    });

    function form_user_account() {
        var str = '<div id=form-user><form id="save-user-account">'+
                    '<?= form_hidden('id_user_account', NULL, 'id=id_user_account') ?>'+
                    '<table width=100% class=inputan cellspacing=0>'+
                    '<tr><td width=20%>Nama:</td><td><?= form_input('nama', NULL, 'id=nama size=40') ?></td></tr>'+
                    '<tr><td>Username:</td><td><?= form_input('username', NULL, 'id=username size=40') ?></td></tr>'+
                    '<tr><td>User Group:</td><td><select name="group" id="group-user"><option value="">Pilih ...</option><?php foreach ($user_group as $data) { ?><option value="<?= $data->id ?>"><?= $data->nama ?></option><?php } ?></select></td></tr>'+
                    '</table>'+
                '</form></div>';
        $(str).dialog({
            title: 'Tambah User Group',
            autoOpen: true,
            modal: true,
            width: 400,
            height: 200,
            buttons: {
                "Simpan": function() {
                    $('#save-user-account').submit();
                    $(this).dialog().remove();
                },
                "Cancel": function() {
                    $(this).dialog().remove();
                }
            },
            close: function() {
                $(this).dialog().remove();  
            }
        });
        $('#save-user-account').submit(function() {
            $.ajax({
                url: '<?= base_url('masterdata/manage_user/save') ?>',
                data: $(this).serialize(),
                type: 'POST',
                dataType: 'json',
                success: function(data) {
                    if (data.status === true) {
                        if (data.act === 'add') {
                            alert_tambah();
                            $('#form-user').dialog().remove();
                            get_user_list(1);
                        } else {
                            alert_edit();
                            get_user_list(1);
                        }
                    }
                }
            });
            return false;
        });
    }
    
    function get_user_list(p){
        $.ajax({
            type : 'POST',
            url: '<?= base_url('masterdata/manage_user') ?>/list/'+p,
            data: $('#form').serialize(),
            cache: false,
            success: function(data) {
                $('#user_list').html(data);
            }
        });
    }

    function edit_user(str){
        var arr = str.split('#');
        form_user_account();
        $('#nama').val(arr[2]);
        $('#username').val(arr[1]);
        $('#group-user').val(arr[3]);
        $('#id_user_account').val(arr[0]);
    }

    function delete_user(id){
            $('<div></div>')
              .html("Anda yakin akan menghapus data ini ?")
              .dialog({
                 title : "Hapus Data",
                 modal: true,
                 buttons: [ 
                    { 
                        text: "Ok", 
                        click: function() { 
                            $.ajax({
                                type : 'GET',
                                url: '<?= base_url('masterdata/manage_user') ?>/delete/'+$('.noblock').html(),
                                data :'id='+id,
                                cache: false,
                                success: function(data) {
                                    get_user_list($('.noblock').html());
                                    alert_delete();
                                }
                            });
                            $( this ).dialog( "close" ); 
                        } 
                    }, 
                    { text: "Batal", click: function() { $( this ).dialog( "close" );}} 
                ]
            });     

    }

    function resetpassword(id, str) {
        $('<div>Anda yakin akan mereset password untuk username <b>'+str+'</b> ?</div>').dialog({
            autoOpen: true,
            title : "Konfirmasi",
            modal: true,
            buttons: { 
                "Ok": function() { 
                    $.ajax({
                        type : 'GET',
                        url: '<?= base_url('masterdata/manage_user') ?>/reset_password/'+$('.noblock').html(),
                        data :'id='+id,
                        success: function(data) {
                            get_user_list($('.noblock').html());
                            custom_message('Informasi','Reset password <b>'+str+'</b> sukses dilakukan');
                        }
                    });
                    $(this).dialog().remove(); 
                }, 
                "Batal": function() { 
                    $(this).dialog().remove(); 
                }
            }
      });  
    }


</script>
<button id="add-user-account">Tambah User Account</button>
<button id="reset-user-account">Reset</button>
<div id="user_list"></div>