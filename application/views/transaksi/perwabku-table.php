<table cellspacing="0" width="100%" class="list-data">
    <tr>
        <th width="3%">No.</th>
        <th width="10%">No. Perwabku</th>
        <th width="10%" class="right">Jumlah Dana</th>
        <th width="10%" class="right">Dana Terpakai</th>
        <th width="7%">Tgl BKK</th>
        <th width="7%">Tgl PWK</th>
        <th width="7%">Thn Agr</th>
        <!--<th width="10%" class="left">Unit Kerja</th>-->
        <th width="13%" class="left">Nomor BKK</th>
        <th width="20%" class="left">Keterangan</th>
        <th width="7%" class="left">User</th>
        <th width="5%"></th>
    </tr>
    <?php foreach ($list_data as $key => $data) { ?>
    <tr class="<?= ($key%2==1)?'even':'odd' ?>">
        <td align="center"><?= $auto++ ?></td>
        <td align="center"><?= $data->kode_pwk ?></td>
        <td align="right"><?= rupiah($data->dana) ?></td>
        <td align="right"><?= rupiah($data->dana_digunakan) ?></td>
        <td align="center"><?= datefmysql($data->tanggal_pengeluaran) ?></td>
        <td align="center"><?= datefmysql($data->tanggal) ?></td>
        <td align="center"><?= substr($data->tanggal, 0, 4) ?></td>
        <!--<td><?= $data->satker ?></td>-->
        <td>
            <?php 
            $value = array();
            foreach ($data->perwabku as $rows) {
                $value[] = $rows->kodes;    
            } 
            echo implode(', ', $value);
            ?>
        </td>
        <td><?= $data->catatan ?></td>
        <td><i><?= $data->username ?></i></td>
        <td align="right" class="nowrap">
            <button type="button" class="btn btn-default btn-xs" onclick="print_perwabku('<?= $data->id ?>');" title="Klik untuk print"><i class="fa fa-print"></i></button>
            <button type="button" class="btn btn-default btn-xs" onclick="delete_perwabku('<?= $data->id ?>', '<?= $page ?>');" title="Klik untuk hapus"><i class="fa fa-trash-o"></i></button>
        </td>
    </tr>
    <?php } ?>
</table>
<?= $infopage ?>
<?= $paging ?><br/><br/>