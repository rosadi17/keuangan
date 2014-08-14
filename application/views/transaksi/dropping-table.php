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
        <th width="2%" rowspan="2">Aksi</th>
    </tr>
    <tr>
        <th width="7%" style="border-top: 1px solid #6eb7ff;">Nominal</th>
        <th width="7%" style="border-top: 1px solid #6eb7ff;">Cash Bon</th>
        <th width="7%" style="border-top: 1px solid #6eb7ff;">Jml Renbut</th>
    </tr>
    <?php foreach ($list_data as $key => $data) { 
        $alert = "";
        if ($data->status === 'Disetujui') {
            $alert = "style='background:green;color:#fff;'";
        }
        ?>
    <tr class="<?= ($key%2==1)?'even':'odd' ?>" <?= $alert ?>>
        <td align="center"><?= $auto++ ?></td>
        <td align="center"><?= datefmysql($data->tanggal) ?></td>
        <td><?= $data->keterangan ?></td>
        <td align="center"><?= $data->satker ?></td>
        <td align="center"><?= $data->ma_proja ?></td>
        <td align="right"><?= rupiah($data->nominal) ?></td>
        <td align="right"><?= rupiah($data->cashbon) ?></td>
        <td align="right"><?= rupiah($data->jml_renbut) ?></td>
        <td><?= $data->penerima ?></td>
        <td align="center"><?= $data->status ?></td>
        <td class="aksi" align="center">
            <a class='process' onclick="edit_dropping('<?= $data->id_renbut ?>');" title="Klik untuk persetujuan">&nbsp;</a>
        </td>
    </tr>
    <?php } ?>
</table>
<?= $paging ?><br/><br/>