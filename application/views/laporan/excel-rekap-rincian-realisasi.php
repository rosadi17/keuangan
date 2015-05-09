<?php
    header_excel('rincian_realisasi_'.$cari['awal'].'_sd_'.$cari['akhir'].'.xls');
?>
<table width="100%">
    <tr><td colspan="8">REKAP REALISASI ANGGARAN</td></tr>
    <tr><td colspan="8">PERIODE <?= datefmysql($cari['awal']) ?> S.D <?= datefmysql($cari['akhir']) ?></td></tr>
</table>
<table cellspacing="0" width="100%" border="1">
    <tr>
        <th width="3%">No.</th>
        <th width="5%">Tanggal</th>
        <th width="7%">Kode</th>
        <th width="7%">Satker</th>
        <th width="5%">MA</th>
        <th width="48%" class="left">Keterangan MA</th>
        <th width="15%" class="left">Penanggung Jawab</th>
        <th width="10%" class="right">Nominal</th>
    </tr>
    <?php foreach ($list_data as $key => $data) { 
        $status = "";
        if ($data->renbut === '' and $data->kode_trans === 'BKK') {
            $status = "<i class='fa fa-sign-out'></i> Cashbon";
        }
        else if ($data->renbut === '' and $data->kode_trans === 'BKM') {
            $status = "<i class='fa fa-sign-in'></i> Kas Masuk";
        }
        else if ($data->renbut !== '' and $data->kode_trans === 'BKK') {
            $status = "<i class='fa fa-check-square-o'></i> Renbut";
        }
        else {
            $status = "<i class='fa fa-retweet'></i> Mutasi";
        }
        $keterangan = $data->keterangan;
        if ($data->keterangan === NULL) {
            $keterangan = 'Mutasi';
        }
        
        $tombol = NULL;
        if ($data->posted === '1') {
            $tombol = "disabled";
        }
        ?>
    <tr class="<?= ($key%2==1)?'even':'odd' ?>">
        <td align="center"><?= ++$key ?></td>
        <td align="center"><?= datefmysql($data->tanggal) ?></td>
        <td><?= $data->kode ?></td>
        <td><small><?= $data->satker ?></small></td>
        <td align="center"><?= $data->kode_ma ?></td>
        <td><?= $data->keterangan ?>, <i><?= $data->keterangan_kasir ?></i></td>
        <td><?= $data->penanggung_jwb ?></td>
        <td align="right"><?= ($data->jenis !== 'BKM')?($data->nominal):-($data->nominal) ?></td>
        
    </tr>
    <?php } ?>
</table>