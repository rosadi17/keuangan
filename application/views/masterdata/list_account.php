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
                <button class="btn btn-xs" onclick="resetpassword('<?= $rows->id ?>', '<?= $rows->username ?>');"><i class="fa fa-refresh"></i></button>
                <button class="btn btn-xs" onclick="edit_user('<?= $str ?>');"><i class="fa fa-pencil"></i></button>
                <button class="btn btn-xs" onclick="delete_user('<?= $rows->id ?>')"><i class="fa fa-trash-o"></i></button>
            </td>  
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?= $infopage ?>
<?= $paging ?>
<br/>
<br/>
