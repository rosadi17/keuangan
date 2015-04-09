<table cellspacing="0" width="100%" class="list-data">
    <tr>
        <th width="3%">No.</th>
        <th width="20%" class="left">Sakter</th>
        <th width="5%">Tahun</th>
        <th width="5%">Uraian</th>
        <th width="50%" class="left">Sub Uraian</th>
        <th width="10%" class="right">Jumlah Biaya</th>
        <th width="3%">Aksi</th>
    </tr>
    <?php 
    $satker = "";
    $program = "";
    $status = "";
    foreach ($list_data as $key => $data) { 
        $str = $data->id.'#'.$data->id_satker.'#'.$data->status.'#'.$data->id_uraian.'#'.
               $data->uraian.'#'.$data->keterangan.'#'.$data->data_kuat_org.'#'.$data->vol_orang.'#'.$data->vol_hari_perbulan.'#'.rupiah($data->harga_satuan).'#'.$data->code.'#'.$data->tahun;
        ?>
    <tr class="<?= ($key%2==1)?'even':'odd' ?>">
        <td align="center"><?= ($satker !== $data->satker)?$data->kode_satker:NULL ?></td>
        <td><?= ($satker !== $data->satker)?$data->satker:NULL ?></td>
        <td align="center"><?= ($satker !== $data->satker)?$data->tahun:NULL ?></td>
        <td align="center"><?= $data->kode ?></td>
        <td><?= ucwords(strtolower($data->keterangan)) ?></td>
        <td align="right"><?= ($data->sub_total !== '0')?rupiah($data->sub_total):NULL ?></td>
        <td class="aksi" align="center">
            <button type="button" class="btn btn-default btn-xs" onclick="edit_sub_uraian('<?= $str ?>');" title="Klik untuk edit"><i class="fa fa-pencil"></i></button>
            <button type="button" class="btn btn-default btn-xs" onclick="delete_sub_uraian('<?= $data->id ?>', '<?= $page ?>');" title="Klik untuk hapus"><i class="fa fa-trash-o"></i></button>
        </td>
    </tr>
    <?php 
    $satker = $data->satker;
    $program= $data->id_program;
    $status = $data->status;
    } ?>
</table>
<?= $paging ?><br/><br/>