<table cellspacing="0" width="100%" class="list-data">
    <tr>
        <th width="3%">No.</th>
        <th width="7%">No. Satker</th>
        <th width="20%" class="left">Satker</th>
        <th width="7%" class="left">No. BKK</th>
        <th width="7%">Tgl BKK</th>
        <th width="30%" class="left">Uraian Kegiatan</th>
        <th width="7%" class="right">Jumlah</th>
        
        <th width="20%" class="left">Pengguna Ang.</th>
        
        
        <th width="1%"></th>
    </tr>
    <?php foreach ($list_data as $key => $data) { ?>
    <tr class="<?= ($key%2==1)?'even':'odd' ?>">
        <td align="center"><?= $auto++ ?></td>
        <td align="center"><?= $data->kode_satker ?></td>
        <td><?= $data->satker ?></td>
        <td><?= $data->kode_bkk ?></td>
        <td align="center"><?= datetimefmysql($data->waktu) ?></td>
        <td><?= $data->uraian ?></td>
        <td align="right"><?= rupiah($data->dana) ?></td>
        <td><?= $data->penerima ?></td>
        <td></td>
    </tr>
    <?php } ?>
</table>
<?= $infopage ?>
<?= $paging ?><br/><br/>