<table cellspacing="0" width="100%" class="list-data">
    <tr>
        <th width="3%">No.</th>
        <th width="5%">Tanggal</th>
        <th width="7%" class="left">Kode</th>
        <th width="40%" class="left">Kegiatan</th>
        <th width="10%" class="left">Penanggungjawab</th>
        <th width="10%" class="right">Jml Pembayaran</th>
        <th width="5%" class="left">Status</th>
        <th width="5%"></th>
    </tr>
    <?php foreach ($list_data as $key => $data) { 
        $status = "";
        if ($data->renbut === '' and $data->kode_trans === 'BKK') {
            $status = "<i>Cashbon</i>";
        }
        else if ($data->renbut === '' and $data->kode_trans === 'BKM') {
            $status = "<i>Kas Masuk</i>";
        }
        else if ($data->renbut !== '' and $data->kode_trans === 'BKK') {
            $status = "<i>Renbut</i>";
        }
        ?>
    <tr class="<?= ($key%2==1)?'even':'odd' ?>">
        <td align="center"><?= $auto++ ?></td>
        <td align="center"><?= datefmysql($data->tanggal) ?></td>
        <td><?= $data->kode ?></td>
        <td><?= $data->keterangan ?></td>
        <td><?= $data->penyetor ?></td>
        <td align="right"><?= rupiah($data->pemasukkan) ?></td>
        <td style="white-space: nowrap;"><?= $status ?></td>
        <td align="right"><a class='printing' onclick="print_kasir('<?= $data->id ?>', '<?= $data->kode_trans ?>');" title="Klik untuk print">&nbsp;</a></td>
    </tr>
    <?php } ?>
</table>
<?= $paging ?><br/><br/>