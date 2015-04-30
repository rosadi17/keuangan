<table class="list-data" width="100%">
    <thead>
    <tr>
        <th width="3%">NO.</th>
        <th width="7%">NO. BUKTI</th>
        <th width="5%">TGL</th>
        <th width="50%" class="left">Keterangan</th>
        <th width="10%" class="right">DEBET</th>
        <th width="10%" class="right">KREDIT</th>
        <th width="10%" class="right">SALDO</th>
        <th width="1%"></th>
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
        <td align="right"><?= rupiah($saldo->awal) ?></td>
        <td></td>
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
            <td align="right"><?= ($data->jenis !== 'BKK')?rupiah($data->pengeluaran):'-' ?></td>
            <td align="right"><?= ($data->jenis === 'BKK')?rupiah($data->pengeluaran):'-' ?></td>
            <td align="right"><?= rupiah($sisa) ?></td>
            <td align="right"></td>
        </tr>
    <?php
    } ?>
        </tbody>
</table>