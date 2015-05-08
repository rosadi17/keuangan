<table cellspacing="0" width="100%" class="list-data">
    <tr>
        <th width="3%">No.</th>
        <th width="5%">Tanggal</th>
        <th width="7%" class="left">Kode</th>
        <th width="40%" class="left">Kegiatan</th>
        <th width="10%" class="left">Penanggungjawab</th>
        <th width="10%" class="right">Jml Pembayaran</th>
        <th width="5%" class="left">Status</th>
        <th width="8%"></th>
    </tr>
    <?php foreach ($list_data as $key => $data) { 
        $status = "";
        if ($data->renbut === '' and $data->kode_trans === 'BKK') {
            $status = "<i class='fa fa-sign-out'></i> Cashbon";
        }
        else if ($data->renbut === '' and $data->kode_trans === 'BKM') {
            $status = "<i class='fa fa-sign-in'></i> Kas Masuk";
        }
        else if ($data->renbut !== '' and $data->kode_trans === 'BKK') {
            $status = "<i class='fa fa-check-square-o'></i> Renbut";
        }
        else {
            $status = "<i class='fa fa-retweet'></i> Mutasi";
        }
        $keterangan = $data->keterangan;
        if ($data->keterangan === NULL) {
            $keterangan = '';
        }
        
        $tombol = NULL;
        if ($data->posted === '1') {
            $tombol = "disabled";
        }
        ?>
    <tr class="<?= ($key%2==1)?'even':'odd' ?>">
        <td align="center"><?= $auto++ ?></td>
        <td align="center"><?= datefmysql($data->tanggal) ?></td>
        <td><?= $data->kode ?></td>
        <td><?= $keterangan ?> <i><?= $data->keterangan_kasir ?></i></td>
        <td class="nowrap"><?= $data->penanggung_jwb ?></td>
        <td align="right"><?= rupiah($data->nominal) ?></td>
        <td style="white-space: nowrap;"><?= $status ?></td>
        <td align="right">
            <button type="button" class="btn btn-default btn-xs" onclick="print_kasir('<?= $data->id ?>', '<?= $data->kode_trans ?>');" title="Klik untuk print"><i class="fa fa-print"></i></button>
            <button type="button" class="btn btn-default btn-xs" <?= $tombol ?> onclick="edit_kasir('<?= $data->id ?>','<?= $data->kode_trans ?>');" title="Klik untuk edit"><i class="fa fa-pencil"></i></button>
            <button type="button" class="btn btn-default btn-xs" <?= $tombol ?> onclick="delete_kasir('<?= $data->id ?>', '<?= $page ?>','<?= $data->kode_trans ?>');" title="Klik untuk delete"><i class="fa fa-trash-o"></i></button>
        </td>
    </tr>
    <?php } ?>
</table>
<?= $paging ?>
<?= $infopage ?>
<br/><br/>