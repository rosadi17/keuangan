<?php $this->load->view('message') ?>
<title><?= $title ?></title>
    <script type="text/javascript">
        function form_user_group() {
            var str = '<?= form_open('', 'id=form_group') ?>'+
                ' <?= form_hidden('id',NULL,'id=id') ?>'+
                '<table width="100%" cellspacing="0" cellpadding="0" class=inputan>'+
                    '<tr><td width="25%">Nama Group</td><td><?= form_input('nama', '','id=nama_group size=40') ?></td></tr>'+
                '</table>'+
                '<?= form_close() ?>';
            $(str).dialog({
                title: 'Tambah User Group',
                autoOpen: true,
                modal: true,
                width: 400,
                height: 150,
                buttons: {
                    "Simpan": function() {
                        $('#form_group').submit();
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
            $('#form_group').submit(function(){
                var Url = '<?= base_url("masterdata/manage_group") ?>/post/';
                var id = $('input[name=id]').val();
                if ($('#nama_group').val()===''){
                    custom_message('Peringatan','Nama tidak boleh kosong !','#nama_group');
                } else {
                    $.ajax({
                        type : 'POST',
                        url: Url+$('.noblock').html(),               
                        data: $(this).serialize(),
                        cache: false,
                        success: function(data) {
                            $('#group_list').html(data);
                            if (id === '') {
                                alert_tambah();
                                $('#nama_group').val('');
                            } else {
                                alert_edit();
                            }
                        }
                    });
              
                    return false;
                }
                return false;
            });
        }
        
        function form_privileges() {
            var str = '<div id="privform">'+
                        '<div id="privileges"></div>'+
                    '</div>';
            var wWidth = $(window).width();
            var dWidth = wWidth * 0.8;

            var wHeight= $(window).height();
            var dHeight= wHeight * 1;
            $(str).dialog({
                autoOpen: true,
                height: dHeight,
                width: dWidth,
                modal: true,
                close : function(){
                    reset_group();
                },
                buttons: { 
                    "Simpan": function() { 
                        form_submit();
                    }
                }
            });
        }
        
        function edit_privileges(id, nama) {
            var str = '<div id="privform">'+
                        '<div id="privileges"></div>'+
                    '</div>';
            var wWidth = $(window).width();
            var dWidth = wWidth * 0.5;

            var wHeight= $(window).height();
            var dHeight= wHeight * 1;
            $(str).dialog({
                title: 'User Account Permission',
                autoOpen: true,
                height: dHeight,
                width: dWidth,
                modal: true,
                close : function(){
                    $(this).dialog().remove();
                },
                open: function() {
                    $.ajax({
                        type : 'GET',
                        url: '<?= base_url("masterdata/manage_group") ?>/edit/'+1,
                        data :'id='+id+'&nama='+nama,
                        cache: false,
                        success: function(data) {                
                            $('#privileges').html(data);             
                        }
                    });
                },
                buttons: { 
                    "Simpan": function() { 
                        form_submit();
                    }
                }
            });
        }
        
        $(function() {
            get_group_list(1);
            $('#add-user-group').button({
                icons: {
                    secondary: 'ui-icon-newwin'
                }
            }).click(function() {
                form_user_group();
            });
            $('#reset-user-group').button({
                icons: {
                    secondary: 'ui-icon-refresh'
                }
            }).click(function() {
                get_group_list(1);
            });
                        
            

            $('#simpan').click(function(){
                $('#form_group').submit();
            });
        });
    
        function reset_group(){
            $('#loaddata').load('<?= base_url('masterdata/account') ?>');
        }
    
        function get_group_list(p){
            $.ajax({
                type : 'POST',
                url: '<?= base_url("masterdata/manage_group") ?>/list/'+p,
                data: $('#form_group').serialize(),
                cache: false,
                success: function(data) {
                    $('#group_list').html(data);
                }
            });
        }
    
        function get_privileges_list(){
            $.ajax({
                type : 'GET',
                url: '<?= base_url("masterdata/manage_privileges") ?>/list',
                data :'id='+$('input[name=id_group]').val(),
                cache: false,
                success: function(data) {
                    $('#list').html(data);
                    //reset_group();
                }
            });
        }
        function edit_group(id,nama){
            form_user_group();
            $('#id').val(id);
            $('#nama_group').val(nama);
        }
    
        function delete_group(id){
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
                                    url: '<?= base_url("masterdata/manage_group") ?>/delete/'+$('.noblock').html(),
                                    data :'id='+id,
                                    cache: false,
                                    success: function(data) {
                                        $('#group_list').html(data);
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
        
        
        
        
    </script>
<button id="add-user-group">Tambah User Group</button>
<button id="reset-user-group">Reset</button>
<div id="group_list"></div>
