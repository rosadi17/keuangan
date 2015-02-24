<table cellspacing="0" width="100%" class="list-data">
    <tr>
        <th width="3%">No.</th>
        <th width="7%">Tgl Masuk</th>
        <th width="10%">Tgl Kegiatan</th>
        <th width="10%">Thn Anggaran</th>
        <th width="10%" class="left">Unit Kerja</th>
        <th width="10%" class="left">Kode MA</th>
        <th width="10%" class="right">Jumlah Dana</th>
        <th width="15%" class="left">Penanggung Jwb</th>
        <th width="5%"></th>
    </tr>
    <?php foreach ($list_data as $key => $data) { ?>
    <tr class="<?= ($key%2==1)?'even':'odd' ?>">
        <td align="center"><?= $auto++ ?></td>
        <td align="center"><?= datetimefmysql($data->waktu) ?></td>
        <td align="center"><?= datefmysql($data->tanggal) ?></td>
        <td align="center"><?= substr($data->tanggal, 0, 4) ?></td>
        <td><?= $data->satker ?></td>
        <td><?= $data->kode_ma ?></td>
        <td align="right"><?= rupiah($data->dana) ?></td>
        <td><?= $data->penerima ?></td>
        <td align="right">
            <button type="button" class="btn btn-default btn-xs" onclick="print_perwabku('<?= $data->id ?>');" title="Klik untuk print"><i class="fa fa-print"></i></button>
            <button type="button" class="btn btn-default btn-xs" onclick="delete_perwabku('<?= $data->id ?>', '<?= $page ?>');" title="Klik untuk hapus"><i class="fa fa-trash-o"></i></button>
        </td>
    </tr>
    <?php } ?>
</table>
<?= $paging ?><br/><br/>