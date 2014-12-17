<table cellspacing="0" width="100%" class="list-data">
    <tr>
        <th width="3%" rowspan="2">No.</th>
        <th width="5%" rowspan="2">Tanggal</th>
        <th width="38%" rowspan="2">Kegiatan</th>
        <th width="5%" rowspan="2">Unit</th>
        <th width="5%" rowspan="2">MA<br/>Proja</th>
        <th width="5%" colspan="3">Jumlah</th>
        <th width="10%" rowspan="2">Penerima /<br/> Penanggungjawab</th>
        <th width="5%" rowspan="2">Status</th>
    </tr>
    <tr>
        <th width="7%" style="border-top: 1px solid #6eb7ff;">Nominal</th>
        <th width="7%" style="border-top: 1px solid #6eb7ff;">Cash Bon</th>
        <th width="7%" style="border-top: 1px solid #6eb7ff;">Jml Renbut</th>
    </tr>
    <?php foreach ($list_data as $key => $data) {
        $str = $data->id_renbut.'#'.$data->ma_proja.'#'.$data->keterangan.'#'.rupiah($data->jml_renbut).'#'.$data->penerima;
        $status = $data->status;
        if (($data->status === 'Disetujui') and ($data->tanggal_cair !== NULL)) {
            $status = "&checkmark;";
        } else if (($data->status === 'Disetujui') and ($data->tanggal_cair === NULL)) {
            $status = $data->status;
        }
        ?>
    <tr class="<?= ($key%2==1)?'even':'odd' ?> <?= ($data->nominal > $data->jml_renbut)?'warning':NULL ?>">
        <td align="center"><?= $auto++ ?></td>
        <td align="center"><?= datefmysql($data->tanggal) ?></td>
        <td><?= $data->keterangan ?></td>
        <td align="center"><?= $data->satker ?></td>
        <td align="center"><?= $data->ma_proja ?></td>
        <td align="right"><?= rupiah($data->nominal) ?></td>
        <td align="right"><?= rupiah($data->cashbon) ?></td>
        <td align="right"><?= rupiah($data->jml_renbut) ?></td>
        <td><?= $data->penerima ?></td>
        <td align="center"><?= ($status === 'Disetujui')?'<i style="color: blue">'.$status.'</i>':$status ?></td>
    </tr>
    <?php } ?>
</table>
<?= $paging ?><br/><br/>