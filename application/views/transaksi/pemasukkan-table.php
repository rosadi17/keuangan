<table cellspacing="0" width="100%" class="list-data">
    <tr>
        <th width="5%">No.</th>
        <th width="10%">tanggal</th>
        <th width="10%">MA Proja</th>
        <th width="50%">Uraian</th>
        <th width="10%">Penerimaan</th>
        <th width="5%">Aksi</th>
    </tr>
    <?php foreach ($list_data as $key => $data) { 
        $str = $data->id.'#'.$data->kode.'#'.$data->id_uraian.'#'.$data->uraian.'#'.rupiah($data->pemasukkan);
        ?>
    <tr class="<?= ($key%2==1)?'even':'odd' ?>">
        <td align="center"><?= $auto++ ?></td>
        <td align="center"><?= datefmysql($data->tanggal) ?></td>
        <td align="center"><?= $data->kode ?></td>
        <td><?= $data->uraian ?></td>
        <td align="right"><?= rupiah($data->pemasukkan) ?></td>
        <td class="aksi" align="center">
            <a class='printing' onclick="cetak_bukti_kas('<?= $data->id ?>')" title="Klik untuk cetak bukti kas Masuk">&nbsp;</a>
            <a class='edition' onclick="edit_penerimaan('<?= $str ?>');" title="Klik untuk edit">&nbsp;</a>
            <a class='deletion' onclick="delete_penerimaan('<?= $data->id ?>', '<?= $page ?>');" title="Klik untuk hapus">&nbsp;</a>
        </td>
    </tr>
    <?php } ?>
</table>
<?= $paging ?><br/><br/>