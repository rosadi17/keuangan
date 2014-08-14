<table class="list-data" width="50%" cellspacing="0">
    <thead>
    <tr>
        <th width="8%">No.</th>
        <th width="82%">Nama</th>
        <th width="10%">Aksi</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($user as $key => $rows) : ?>
        <tr class="<?= ($key % 2 == 1) ? 'even' : 'odd' ?>" ondblclick="edit_user('<?= $rows->id ?>')">
            <td align="center"><?= (++$key + (($page - 1) * $limit)) ?></td>
            <td><?= $rows->nama ?></td>
            <td class="aksi" align="center"> 
                <a title="Ubah group privileges" class="edition" onclick="edit_privileges('<?= $rows->id ?>', '<?= $rows->nama ?>');">&nbsp;</a>
                <a title="Ubah user group" class="edition" onclick="edit_group('<?= $rows->id ?>', '<?= $rows->nama ?>');">&nbsp;</a>
                <a title="Hapus user group" class="deletion" onclick="delete_group('<?= $rows->id ?>');">&nbsp;</a>
            </td>  
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<br/>
<div id="paging"><?= $paging ?></div>
