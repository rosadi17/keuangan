<?php
    header_excel('rekap_kas_bank_'.date2mysql($awal).'_sd_'.  date2mysql($akhir).'.xls');
?>
<table>
    <tr>
        <td colspan="7">REKAP TANSAKSI KAS & BANK TANGGAL <?= $awal ?> S.D <?= $akhir ?></td>
    </tr>
</table>
<br/>
<?php foreach ($list_data as $key => $value) { ?>
<b>Kode Rekening: <?= $value->id.' '.$value->rekening ?></b>
<table border="1" width="100%">
    <thead>
    <tr>
        <th width="3%">NO.</th>
        <th width="7%">NO. BUKTI</th>
        <th width="5%">TGL</th>
        <th width="32%" class="left">Keterangan</th>
        <th width="9%" class="right">DEBET</th>
        <th width="9%" class="right">KREDIT</th>
        <th width="9%" class="right">SALDO</th>
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
        <td align="right"><?= ($value->saldo) ?></td>
    </tr>

    <?php 
    $saldo = 0;
    $sisa  = $value->what;
    $debet = ''; $kredit = '';
    foreach ($value->detail as $key => $data) { 
        if ($data->jenis === 'BKK' and $data->id_rekening_pwk === $value->id) {
            $sisa += $data->pengeluaran;
            $debet = ($data->pengeluaran); // bertambah
            $kredit= '';
        } 
        else if ($data->jenis === 'BKK' and $data->id_rekening === $value->id) {
            $sisa -= $data->pengeluaran;
            $debet = '';
            $kredit= ($data->pengeluaran);
        }
        else if ($data->jenis === 'MTS' and $data->id_rekening_pwk === $value->id) {
            $sisa -= $data->pengeluaran;
            $debet = '';
            $kredit= ($data->pengeluaran); // bertambah
        }
        else if ($data->jenis === 'MTS' and $data->id_rekening === $value->id) {
            $sisa += $data->pengeluaran;
            $debet = ($data->pengeluaran); // bertambah
            $kredit= '';
        }
        else if ($data->jenis === 'BKM' and $data->id_rekening_pwk === $value->id) {
            $sisa -= $data->pengeluaran;
            $debet = '';
            $kredit= ($data->pengeluaran); // bertambah
        }
        else if ($data->jenis === 'BKM' and $data->id_rekening === $value->id) {
            $sisa += $data->pengeluaran;
            $debet = ($data->pengeluaran); // bertambah
            $kredit= '';
        }
//        else {
//            $sisa+=$data->pengeluaran;
//        }
        ?>
        <tr class="<?= ($key%2===0)?'odd':'even' ?>">
            <td align="center"><?= ++$key ?></td>
            <td align="center"><?= $data->kode ?></td>
            <td align="center"><?= datefmysql($data->tanggal) ?></td>
            <td><?= ($data->keterangan !== '')?'<i>'.$data->keterangan.'</i>':$data->uraian ?></td>
            <td align="right"><?= $debet ?></td>
            <td align="right"><?= $kredit ?></td>
            <td align="right"><?= ($sisa) ?></td>
        </tr>
    <?php
    } ?>
        </tbody>
</table>
<?php } ?>