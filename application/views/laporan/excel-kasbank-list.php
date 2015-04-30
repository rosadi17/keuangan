<?php
    header_excel('rekap_kas_bank_'.date2mysql($awal).'_sd_'.  date2mysql($akhir).'.xls');
?>
<table>
    <tr>
        <td colspan="7">REKAP TANSAKSI KAS & BANK TANGGAL <?= $awal ?> S.D <?= $akhir ?></td>
    </tr>
</table>
<table border="1" width="100%">
    <thead>
    <tr>
        <th width="3%">NO.</th>
        <th width="7%">NO. BUKTI</th>
        <th width="5%">TGL</th>
        <th width="50%" class="left">Keterangan</th>
        <th width="10%" class="right">DEBET</th>
        <th width="10%" class="right">KREDIT</th>
        <th width="10%" class="right">SALDO</th>
    </tr>
    </thead>
    <tbody>
    <tr class="odd">
        <td></td>
        <td></td>
        <td></td>
        <td>SALDO AWAL</td>
        <td></td>
        <td></td>
        <td align="right"><?= ($saldo->awal) ?></td>
    </tr>

    <?php 
    $saldo = 0;
    $sisa  = $what->awal;

    foreach ($list_data as $key => $data) { 
        if ($data->jenis === 'BKK') {
            $sisa-=$data->pengeluaran;
        } else {
            $sisa+=$data->pengeluaran;
        }
        ?>
        <tr class="<?= ($key%2===0)?'odd':'even' ?>">
            <td align="center"><?= ++$key ?></td>
            <td align="center"><?= $data->kode ?></td>
            <td align="center"><?= datefmysql($data->tanggal) ?></td>
            <td><?= ($data->keterangan !== '')?$data->keterangan:$data->uraian ?></td>
            <td align="right"><?= ($data->jenis !== 'BKK')?($data->pengeluaran):'-' ?></td>
            <td align="right"><?= ($data->jenis === 'BKK')?($data->pengeluaran):'-' ?></td>
            <td align="right"><?= ($sisa) ?></td>
        </tr>
    <?php
    } ?>
        </tbody>
</table>