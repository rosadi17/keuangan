<?= header_excel('rekap-renbut-'.tampil_bulan($bulan).'.xls') ?>
<table width="100%">
    <tr>
        <td colspan="7">REKAP RENCANA KEBUTUHAN <?= strtoupper(tampil_bulan($bulan)).' '.$tahun ?></td>
    </tr>
</table>
<table cellspacing="0" width="100%" border="1">
    <tr>
        <th width="3%">No.</th>
        <th width="5%">Tanggal</th>
        <th width="7%">Kode</th>
        <th width="40%" class="left">Kegiatan</th>
        <th width="5%" class="left">Unit</th>
        <th width="10%" class="left">Penanggungjawab</th>
        <th width="10%" class="right">Jml Renbut</th>
    </tr>
    <?php foreach ($list_data as $key => $data) { 
        ?>
    <tr class="<?= ($key%2==1)?'even':'odd' ?>">
        <td align="center"><?= $auto++ ?></td>
        <td align="center"><?= datefmysql($data->tanggal) ?></td>
        <td align="center"><?= $data->kode ?></td>
        <td><?= $data->keterangan ?></td>
        <td><?= $data->satker ?></td>
        <td><?= $data->penerima ?></td>
        <td align="right"><?= rupiah($data->jml_renbut) ?></td>
        
    </tr>
    <?php } ?>
</table>