<table cellspacing="0" width="100%" class="list-data">
    <tr>
        <th width="10%">Sakter</th>
        <th width="8%">Program</th>
        <th width="8%">Kegiatan</th>
        <th width="8%">Kode Sub. Keg.</th>
        <th width="8%">Kode Uraian</th>
        <th width="50%">Keterangan</th>
        <th width="3%">Aksi</th>
    </tr>
    <?php 
    $satker = "";
    $program = "";
    $kegiatan= "";
    foreach ($list_data as $key => $data) { 
        $str = $data->id.'#'.$data->id_satker.'#'.$data->status.'#'.$data->id_sub_kegiatan.'#'.
               $data->kode_sub_kegiatan.'#'.$data->nama_sub_kegiatan.'#'.$data->kode_uraian.'#'.$data->uraian;
        ?>
    <tr class="<?= ($key%2==1)?'even':'odd' ?>">
        
        <td><?= ($satker !== $data->satker)?$data->satker.' ('.$data->status.')':NULL ?></td>
        <td align="center" title="<?= $data->nama_program ?>"><?= (($satker !== $data->satker) or ($program !== $data->id_program) or (($program !== $data->id_program) and ($kegiatan !== $data->kode_kegiatan)))?number_pad($data->kode_program,5):NULL ?></td>
        <td align="center" title="<?= $data->nama_kegiatan ?>"><?= $data->kode_kegiatan ?></td>
        <td align="center" title="<?= $data->nama_sub_kegiatan ?>"><?= $data->kode_sub_kegiatan ?></td>
        <td align="center"><?= $data->kode ?></td>
        <td><?= ucwords(strtolower($data->uraian)) ?></td>
        <td class="aksi" align="center">
            <a class='edition' onclick="edit_uraian('<?= $str ?>');" title="Klik untuk edit unit">&nbsp;</a>
            <a class='deletion' onclick="delete_uraian('<?= $data->id ?>', '<?= $page ?>');" title="Klik untuk hapus unit">&nbsp;</a>
        </td>
    </tr>
    <?php 
    $satker = $data->satker;
    $program= $data->id_program;
    $kegiatan = $data->kode_kegiatan;
    } ?>
</table>
<?= $paging ?><br/><br/>