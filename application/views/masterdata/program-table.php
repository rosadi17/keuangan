<table cellspacing="0" width="100%" class="list-data">
    <tr>
        <th width="3%">No.</th>
        <th width="17%">Nama Satker</th>
        <th width="10%">Status</th>
        <th width="7%">Kode</th>
        <th width="70%">Nama Program</th>
        <th width="3%">Aksi</th>
    </tr>
    <?php 
    $satker = "";
    $status = "";
    foreach ($list_data as $key => $data) { 
        $str = $data->id.'#'.$data->kode.'#'.$data->id_satker.'#'.$data->satker.'#'.$data->nama_program.'#'.$data->status;
        ?>
    <tr class="<?= ($key%2==1)?'even':'odd' ?>">
        <td align="center"><?= ($satker !== $data->satker)?$auto++:NULL ?></td>
        <td><?= ($satker !== $data->satker)?$data->satker:NULL ?></td>
        <td><?= (($satker !== $data->satker) or ($status !== $data->status))?$data->status:NULL ?></td>
        <td align="center"><?= $data->kode ?></td>
        <td><?= $data->nama_program ?></td>
        <td class="aksi" align="center">
            <a class='edition' onclick="edit_program('<?= $str ?>');" title="Klik untuk edit unit">&nbsp;</a>
            <a class='deletion' onclick="delete_program('<?= $data->id ?>', '<?= $page ?>');" title="Klik untuk hapus unit">&nbsp;</a>
        </td>
    </tr>
    <?php 
    $satker = $data->satker;
    $status = $data->status;
    } ?>
</table>
<?= $paging ?><br/><br/>