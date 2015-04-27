<?php
header_excel('perwabku-'.$cari['awal'].'-sd-'.$cari['akhir'].'.xls');
?>
<table width="100%">
    <tr><td colspan="10">REKAP PERWABKU <?= isset($satker->nama)?'SATKER '.$satker->nama:NULL ?></td></tr>
    <tr><td colspan="10"><?= datefmysql($cari['awal']) ?> s.d <?= datefmysql($cari['akhir']) ?></td></tr>
</table>
<table cellspacing="0" width="100%" border="1">
    <tr>
        <th width="3%">No.</th>
        <?php if ($cari['satker'] === '') { ?>
        <th width="15%" class="left">Satker</th>
        <?php } ?>
        <th width="15%" class="left">No. BKK</th>
        <th width="7%">Tgl BKK</th>
        <th width="30%" class="left">Uraian Kegiatan</th>
        <th width="7%" class="right">Jumlah</th>
        <?php if ($cari['satker'] !== '') { ?>
        <th width="15%" class="left">Pengguna Ang.</th>
        <?php } ?>
        <th width="10%">No. Perwabku</th>
        <th width="5%">Tgl PWK</th>
        <th width="7%" class="right">Jumlah Pwk</th>
        <th width="7%" class="right">Selisih</th>
    </tr>
    <?php foreach ($list_data as $key => $data) { ?>
    <tr class="<?= ($key%2==1)?'even':'odd' ?>">
        <td align="center"><?= ++$key ?></td>
        <?php if ($cari['satker'] === '') { ?>
        <td><?= $data->satker ?></td>
        <?php } ?>
        <td>
            <?php 
            $value = array();
            foreach ($data->perwabku as $rows) {
                $value[] = $rows->kodes;    
            } 
            echo implode(', ', $value);
            ?>
        </td>
        <td align="center"><?= datefmysql($data->tanggal_pengeluaran) ?></td>
        <td><?= $data->uraian ?></td>
        <td align="right"><?= ($data->dana) ?></td>
        <?php if ($cari['satker'] !== '') { ?>
            <td>
               <?php
               $nilai = array();
               foreach ($data->png_jwb as $pwk) {
                   $nilai[] = $pwk->png_jwb;
               }
               echo implode(', ', $nilai);
               ?>
            </td>
        <?php } ?>
        <td align="center"><?= $data->kode_pwk ?></td>
        <td align="center"><?= datefmysql($data->tanggal) ?></td>
        <td align="right"><?= ($data->dana_digunakan) ?></td>
        <td align="right"><?= ($data->dana-$data->dana_digunakan) ?></td>
    </tr>
    <?php } ?>
</table>