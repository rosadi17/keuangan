<table cellspacing="0" width="100%" class="list-data">
    <tr>
        <th width="5%">Tahun</th>
        <th width="5%">No.</th>
        <th width="75%">Nama Unit</th>
        <th width="10%">Pagu</th>
        <th width="5%">Aksi</th>
    </tr>
    <?php 
    $tahun = "";
    foreach ($list_data as $key => $data) { 
        $str = $data->id.'#'.$data->tahun.'#'.$data->id_satker.'#'.rupiah($data->pagu);
        ?>
    <tr class="<?= ($key%2==1)?'even':'odd' ?>">
        <td align="center"><?= ($tahun !== $data->tahun)?$data->tahun:NULL ?></td>
        <td align="center"><?= $auto++ ?></td>
        <td><?= $data->satker ?></td>
        <td align="right"><?= rupiah($data->pagu) ?></td>
        <td class="aksi" align="center">
            <a class='edition' onclick="edit_pagu('<?= $str ?>');" title="Klik untuk edit pagu">&nbsp;</a>
            <a class='deletion' onclick="delete_pagu('<?= $data->id ?>', '<?= $page ?>');" title="Klik untuk hapus pagu">&nbsp;</a>
        </td>
    </tr>
    <?php 
    $tahun = $data->tahun;
    } ?>
</table>
<?= $paging ?><br/><br/>