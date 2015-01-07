<table class="list-data" width="100%" cellspacing="0">
    <thead>
    <tr>
        <th width="3%">No.</th>
        <th width="15%">ID / Username</th>
        <th width="65%">Nama</th>
        <th width="15%">User Group</th>
        <th width="2%">Aksi</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($user as $key => $rows) : 
        $str = $rows->id.'#'.$rows->username.'#'.$rows->nama.'#'.$rows->id_user_group;
        ?>
        <tr class="<?= ($key % 2 == 1) ? 'even' : 'odd' ?>" ondblclick="edit_user('<?= $rows->id ?>')">
            <td align="center"><?= ++$key ?></td>
            <td><?= $rows->username ?></td>
            <td><?= $rows->nama ?></td>
            <td><?= $rows->user_group ?></td>
            <td class="aksi"> 
                <a title="Reset Password" class="resetpass" onclick="resetpassword('<?= $rows->id ?>', '<?= $rows->username ?>');"></a>
                <a title="Reset Password" class="edition" onclick="edit_user('<?= $str ?>');"></a>
                <a title="Hapus user account" class="deletion" onclick="delete_user('<?= $rows->id ?>')"></a>
            </td>  
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<br/>
<div id="paging"><?= $paging ?></div>
