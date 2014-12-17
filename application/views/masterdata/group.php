<?php $this->load->view('message') ?>
<title><?= $title ?></title>
<div class="titling"><h1><?= $title ?></h1></div>
<div class="kegiatan">
    <script type="text/javascript">
        var data = '';
        $(function() {
            $('#tabs').tabs();
            my_ajax('<?= base_url() ?>masterdata/user_group','#group');
            $('.group').click(function(){
                if($('#group').html()=== ''){
                    my_ajax('<?= base_url('masterdata/user_group') ?>','#group');
                }
                
            });
            $('.user').click(function(){
                if($('#user').html()=== ''){
                    my_ajax('<?= base_url('masterdata/user_account') ?>','#user');
                }
            });
        });
    
        function my_ajax(url,element){
            $.ajax({
                url: url,
                dataType: '',
                success: function( response ) {
                    $(element).html(response);
                }
            });
        }
        
        function paging(page, tab, search) {
            var active = $('#tabs').tabs('option','active');
            paginate(page, tab, search, active);
            //load_data_barang(page, search);
        }
        
        function paginate(page, tab, search, active) {
            if (active === 0) {
                get_group_list(page, search);
            }
            if (active === 1) {
                get_user_list(page, search);
            }
        }
    </script>

    <div id="tabs">
        <ul>
            <li><a class="group" href="#group">User Group</a></li>
            <li><a class="user" href="#user">User Account</a></li>
        </ul>

        <div id="group"></div>
        <div id="user"></div>
    </div>


</div>