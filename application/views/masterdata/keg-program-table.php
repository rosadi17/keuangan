<table cellspacing="0" width="100%" class="list-data">
    <tr>
        <th width="10%">Sakter</th>
        <th width="5%">Program</th>
        <th width="42%">Program</th>
        <th width="5%">Kode</th>
        <th width="30%">Nama Kegiatan</th>
        <th width="3%">Aksi</th>
    </tr>
    <?php 
    $satker = "";
    $program = "";
    foreach ($list_data as $key => $data) { 
        $str = $data->id.'#'.$data->id_satker.'#'.$data->status.'#'.$data->nama_program.'#'.$data->id_program.'#'.$data->kode_kegiatan.'#'.$data->nama_kegiatan;
        ?>
    <tr class="<?= ($key%2==1)?'even':'odd' ?>">
        
        <td><?= ($satker !== $data->satker)?$data->satker:NULL ?></td>
        <td align="center"><?= (($satker !== $data->satker) or ($program !== $data->id_program))?number_pad($data->kode_program,5):NULL ?></td>
        <td><?= (($satker !== $data->satker) or ($program !== $data->id_program))?$data->nama_program:NULL ?></td>
        <td align="center"><?= $data->kode ?></td>
        <td><?= ucwords(strtolower($data->nama_kegiatan)) ?></td>
        <td class="aksi" align="center">
            <button class="btn btn-default btn-xs" onclick="edit_keg_program('<?= $str ?>');" title="Klik untuk edit unit"><i class="fa fa-pencil"></i></button>
            <button class="btn btn-default btn-xs" onclick="delete_keg_program('<?= $data->id ?>', '<?= $page ?>');" title="Klik untuk hapus unit"><i class="fa fa-trash-o"></i></button>
        </td>
    </tr>
    <?php 
    $satker = $data->satker;
    $program= $data->id_program;
    } ?>
</table>
<?= $paging ?><br/><br/>