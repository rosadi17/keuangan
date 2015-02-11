<table cellspacing="0" width="100%" class="list-data">
    <tr>
        <th width="3%" rowspan="2">No.</th>
        <th width="5%" rowspan="2">Tanggal</th>
        <th width="38%" rowspan="2">Kegiatan</th>
        <th width="5%" rowspan="2">Unit</th>
        <th width="5%" rowspan="2">MA<br/>Proja</th>
        <th width="5%" colspan="4">Jumlah</th>
        <th width="10%" rowspan="2">Penerima /<br/> Penanggungjawab</th>
        <th width="5%" rowspan="2">Status</th>
        <th width="2%" rowspan="2">Aksi</th>
    </tr>
    <tr>
        <th width="7%" style="border-top: 1px solid #6eb7ff;">Nominal</th>
        <th width="7%" style="border-top: 1px solid #6eb7ff;">Cash Bon</th>
        <th width="7%" style="border-top: 1px solid #6eb7ff;">Renbut</th>
        <th width="7%" style="border-top: 1px solid #6eb7ff;">Dropping</th>
    </tr>
    <?php foreach ($list_data as $key => $data) { 
        $alert = "<i class='blinker'>".$data->status."</i>";
        $button= "";
        if ($data->status === 'Disetujui') {
            $alert = '<span class="label label-success"><i class="fa fa-thumbs-up"></i> '.$data->status.'</span>';
            $button= 'disabled';
        }
        else if ($data->status === 'Ditolak') {
            $alert = '<span class="label label-warning"><i class="fa fa-ban"></i> '.$data->status.'</span>';
        }
        ?>
    <tr class="<?= ($key%2==1)?'even':'odd' ?>">
        <td align="center"><?= $auto++ ?></td>
        <td align="center"><?= datefmysql($data->tanggal) ?></td>
        <td><?= $data->keterangan ?></td>
        <td class="nowrap"><?= $data->satker ?></td>
        <td align="center"><?= $data->ma_proja ?></td>
        <td align="right"><?= rupiah($data->nominal) ?></td>
        <td align="right"><?= rupiah($data->cashbon) ?></td>
        <td align="right"><?= rupiah($data->jml_renbut) ?></td>
        <td align="right"><?= rupiah($data->jml_dropping) ?></td>
        <td><?= $data->penerima ?></td>
        <td align="center"><?= $alert ?></td>
        <td class="aksi" align="center">
            <button type="button" <?= $button ?> class="btn btn-default btn-xs" onclick="edit_dropping('<?= $data->id_renbut ?>','<?= $data->jml_renbut ?>');" title="Klik untuk persetujuan"><i class="fa fa-gear"></i></button>
            <button type="button" class="btn btn-default btn-xs" onclick="delete_dropping('<?= $data->id_renbut ?>', '<?= $page ?>');" title="Klik untuk hapus"><i class="fa fa-trash-o"></i></button>
        </td>
    </tr>
    <?php } ?>
</table>
<?= $paging ?><br/><br/>