<?php
    header_excel('rekap-kasir-'.date2mysql(get_safe('awal')).'_sd_'.  date2mysql(get_safe('akhir')).'.xls');
?>
<table>
    <tr>
        <td colspan="7">REKAP KASIR <?= get_safe('awal') ?> S.D <?= get_safe('akhir') ?></td>
    </tr>
</table>
<table cellspacing="0" width="100%" border="1">
    <tr>
        <th width="3%">No.</th>
        <th width="5%">Tanggal</th>
        <th width="7%" class="left">Kode</th>
        <th width="40%" class="left">Kegiatan</th>
        <th width="10%" class="left">Penanggungjawab</th>
        <th width="10%" class="right">Jml Pembayaran</th>
        <th width="5%" class="left">Status</th>
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
        ?>
    <tr class="<?= ($key%2==1)?'even':'odd' ?>">
        <td align="center"><?= ++$key ?></td>
        <td align="center"><?= datefmysql($data->tanggal) ?></td>
        <td><?= $data->kode ?></td>
        <td><?= $keterangan ?></td>
        <td class="nowrap"><?= $data->penanggung_jwb ?></td>
        <td align="right"><?= ($data->nominal) ?></td>
        <td style="white-space: nowrap;"><?= $status ?></td>
    </tr>
    <?php } ?>
</table>