<table cellspacing="0" width="50%" class="list-data">
    <tr>
        <th width="5%">No.</th>
        <th width="10%">Kode</th>
        <th width="80%">Nama Unit</th>
        <th width="5%">Aksi</th>
    </tr>
    <?php foreach ($list_data as $key => $data) { 
        $str = $data->id.'#'.$data->nama.'#'.$data->kode;
        ?>
    <tr class="<?= ($key%2==1)?'even':'odd' ?>">
        <td align="center"><?= $auto++ ?></td>
        <td align="center"><?= $data->kode ?></td>
        <td><?= $data->nama ?></td>
        <td class="aksi" align="center">
            <a class='edition' onclick="edit_unit('<?= $str ?>');" title="Klik untuk edit unit">&nbsp;</a>
            <a class='deletion' onclick="delete_unit('<?= $data->id ?>', '<?= $page ?>');" title="Klik untuk hapus unit">&nbsp;</a>
        </td>
    </tr>
    <?php } ?>
</table>
<?= $paging ?><br/><br/>