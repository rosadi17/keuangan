<table cellspacing="0" width="100%" class="list-data">
    <tr>
        <th width="3%" rowspan="2">No.</th>
        <th width="5%" rowspan="2">Tanggal</th>
        <th width="40%" rowspan="2">Kegiatan</th>
        <th width="5%" rowspan="2">Unit</th>
        <th width="5%" rowspan="2">MA<br/>Proja</th>
        <th width="5%" colspan="3">Jumlah</th>
        <th width="10%" rowspan="2">Penerima /<br/> Penanggungjawab</th>
        <th width="3%" rowspan="2">Aksi</th>
    </tr>
    <tr>
        <th width="7%" style="border-top: 1px solid #6eb7ff;">Nominal</th>
        <th width="7%" style="border-top: 1px solid #6eb7ff;">Cash Bon</th>
        <th width="7%" style="border-top: 1px solid #6eb7ff;">Jml Renbut</th>
    </tr>
    <?php foreach ($list_data as $key => $data) { 
        $str = $data->id_renbut.'#'.$data->ma_proja.'#'.$data->keterangan.'#'.$data->jml_renbut.'#'.$data->penerima.'#'.$data->id_uraian.'#'.datefmysql($data->tanggal_kegiatan);
        ?>
    <tr class="<?= ($key%2==1)?'even':'odd' ?>">
        <td align="center"><?= $auto++ ?></td>
        <td align="center"><?= datefmysql($data->tanggal) ?></td>
        <td><?= $data->keterangan ?></td>
        <td align="center"><?= $data->satker ?></td>
        <td align="center"><?= $data->ma_proja ?></td>
        <td align="right"><?= rupiah($data->nominal) ?></td>
        <td align="right"><?= rupiah($data->cashbon) ?></td>
        <td align="right"><?= rupiah($data->jml_renbut) ?></td>
        <td><?= $data->penerima ?></td>
        <td class="aksi" align="center">
            <a class='printing' onclick="print_renbut('<?= $data->id_renbut ?>');" title="Klik untuk print">&nbsp;</a>
            <a class='edition' onclick="edit_renbut('<?= $str ?>');" title="Klik untuk edit">&nbsp;</a>
            <a class='deletion' onclick="delete_renbut('<?= $data->id_renbut ?>', '<?= $page ?>');" title="Klik untuk hapus">&nbsp;</a>
        </td>
    </tr>
    <?php } ?>
</table>
<?= $paging ?><br/><br/>