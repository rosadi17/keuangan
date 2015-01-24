<table cellspacing="0" width="100%" class="list-data">
    <tr>
        <th width="3%">No.</th>
        <th width="10%">No. Bukti</th>
        <th width="7%">Tanggal</th>
        <th width="20%">Kode Akun</th>
        <th width="10%">Debet</th>
        <th width="10%">Kredit</th>
        <th width="40%">Keterangan</th>
    </tr>
    <?php foreach ($list_data as $key => $data) { ?>
    <tr class="<?= ($key%2==1)?'even':'odd' ?>">
        <td align="center"><?= $auto++ ?></td>
        <td align="center"><?= $data->kode_nota ?></td>
        <td align="center"><?= datetimefmysql($data->tanggal) ?></td>
        <td><?= $data->id_rekening ?></td>
        <td align="right"><?= rupiah($data->debet) ?></td>
        <td align="right"><?= rupiah($data->kredit) ?></td>
        <td><?= $data->keterangan ?></td>
    </tr>
    <?php } ?>
</table>
<?= $paging ?><br/><br/>