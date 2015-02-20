<table cellspacing="0" width="100%" class="list-data">
    <tr>
        <th width="3%" rowspan="2">No.</th>
        <th width="5%" rowspan="2">Tanggal</th>
        <th width="5%" rowspan="2">No. Renbut</th>
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
        $str = $data->id_renbut.'#'.$data->ma_proja.'#'.$data->keterangan.'#'.$data->jml_renbut.'#'.$data->penerima.'#'.$data->id_uraian.'#'.datefmysql($data->tanggal_kegiatan).'#'.$data->detail.'#'.$data->kode.'#'.$data->kode_cashbon.'#'.$data->id_pengeluaran;
        ?>
    <tr class="<?= ($key%2==1)?'even':'odd' ?>">
        <td align="center"><?= $auto++ ?></td>
        <td align="center"><?= datefmysql($data->tanggal_kegiatan) ?></td>
        <td align="center"><?= $data->kode ?></td>
        <td><?= $data->keterangan ?></td>
        <td class="nowrap"><?= $data->satker ?></td>
        <td align="center"><?= $data->ma_proja ?></td>
        <td align="right"><?= rupiah($data->nominal) ?></td>
        <td align="right"><?= rupiah($data->cashbon) ?></td>
        <td align="right"><?= rupiah($data->jml_renbut) ?></td>
        <td><?= $data->penerima ?></td>
        <td class="aksi" align="center">
            <button type="button" class="btn btn-default btn-xs" onclick="print_renbut('<?= $data->id_renbut ?>');" title="Klik untuk print"><i class="fa fa-print"></i></button>
            <button type="button" class="btn btn-default btn-xs" onclick="edit_renbut('<?= $str ?>');" title="Klik untuk edit"><i class="fa fa-pencil"></i></button>
            <button type="button" class="btn btn-default btn-xs" onclick="delete_renbut('<?= $data->id_renbut ?>', '<?= $page ?>');" title="Klik untuk hapus"><i class="fa fa-trash-o"></i></button>
        </td>
    </tr>
    <?php } ?>
</table>
<?= $paging ?><br/><br/>