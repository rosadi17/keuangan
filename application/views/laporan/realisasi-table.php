<?php
$month1 = (get_safe('awal'))?get_safe('awal'):'1';
$month2 = date("m");
if (get_safe('year') < date("Y")) {
    $month2 = 12;
}

$monthNames = array( 
  1 => 'Jan', 
  2 => 'Feb', 
  3 => 'Mar', 
  4 => 'Apr', 
  5 => 'May', 
  6 => 'Jun', 
  7 => 'Jul', 
  8 => 'Aug', 
  9 => 'Sep', 
  10 => 'Oct', 
  11 => 'Nov', 
  12 => 'Dec' 
);


?>
<!--<b style="float: right; position: absolute;">LAPORAN ANGGARAN DAN REALISASI BULAN JANUARI - DESEMBER TAHUN <?= get_safe('tahun') ?><br />UNIVERSITAS BHAYANGKARA SURABAYA</b>-->
<table cellspacing="0" width="150%" class="list-data-border">
    <tr>
        <th width="3%"></th>
        <th width="5%">&nbsp;Kode.&nbsp;</th>
        <th width="15%">Satker</th>
        <th width="7%">Pagu Angg.</th>
        <?php for($i = $month1; $i <= $month2; $i++) { ?>
        <th width="7%" class="right"><?= $monthNames[$i] ?></th>
        <?php } ?>
        <th width="7%"><small>Real <?= $tahun+1 ?></small></th>
        <th width="7%">Jumlah</th>
        <th width="7%">Sisa</th>
    </tr>
    <?php 
    $total_pagu = 0;
    $total_terpakai = 0;
    if (count($list_data) > 0) {
    foreach ($list_data as $key => $data) {
        $total_pagu = $total_pagu + $data->pagu;
        ?>
    <tr class="<?= ($key%2==1)?'even':'odd' ?>">
        <td align="center"><button class="btn btn-default btn-xs" onclick="detail_kode_ma('<?= $data->id_satker ?>','<?= $data->nama ?>', '<?= $tahun ?>');"><i class="fa fa-eye"></i></button></td>
        <td align="center"><?= $data->kode ?></td>
        <td class="nowrap"><small><?= $data->nama ?></small></td>
        <td align="right"><?= rupiah($data->pagu) ?></td>
        <?php 
        $total_kanan = 0;
        for($i = $month1; $i <= $month2; $i++) { 
            $real = $this->m_laporan->load_realisasi_total_satker($tahun."-".pad($i, 2), $data->id_satker)->row();
            $total_kanan = $total_kanan+$real->total;
            ?>
            
        <td align="right" style="min-width: 90px;"><?= isset($real->total)?rupiah($real->total):'-' ?></td>
        <?php } ?>
        <td align="right" style="min-width: 90px;"><?= rupiah($data->next_year) ?></td>
        <td align="right"><?= rupiah($total_kanan+$data->next_year) ?></td>
        <td align="right"><?= rupiah($data->pagu-($total_kanan+$data->next_year)) ?></td>
    </tr>
    <?php 
    $total_terpakai = $total_terpakai+$total_kanan;
        } ?>
<!--    <tr>
        <td colspan="2">Jumlah (Rp.)</td>
        <td align="right"><b><?= rupiah($total_pagu) ?></b></td>
        <?php for($i = $month1; $i <= $month2; $i++) { 
            $real_perbulan = $this->m_laporan->load_realisasi_total_satker($tahun."-".pad($i, 2))->row(); ?>
        <td align="right"><?= isset($real_perbulan->total)?'<b>'.rupiah($real_perbulan->total).'</b>':'-' ?></td>
        <?php } ?>
        <td align="right"><?= rupiah($total_terpakai) ?></td>
        <td align="right"><?= rupiah($total_pagu-$total_terpakai) ?></td>
    </tr>
    <tr>
        <td colspan="2">Jumlah (%)</td>
        <td></td>
        <?php 
        $total_persen = 0;
        for($i = $month1; $i <= $month2; $i++) { 
            $real_perbulan = $this->m_laporan->load_realisasi_total_satker($tahun."-".pad($i, 2))->row(); 
            $persen = ($real_perbulan->total/$total_pagu)*100;
            ?>
        <td align="center"><?= round($persen, 1) ?>%</td>
        <?php 
        $total_persen = $total_persen+round($persen, 1);
        } ?>
        <td align="center"><?= $total_persen ?>%</td>
        <td align="center"><?= (100-$total_persen) ?>%</td>
    </tr>
    <tr>
        <td colspan="3" rowspan="2" align="center">Pengeluaran Rata-rata Seharusnya (Pagu dibagi 12 bulan)</td>
        <td colspan="14"></td>
    </tr>
    <tr>
        <?php for($i = $month1; $i <= $month2; $i++) { ?>
        <td align="right"><?= rupiah($total_pagu/12) ?></td>
        <?php } ?>
        <td align="right"><?= rupiah($total_pagu) ?></td>
        <td></td>
    </tr>
    <tr>
        <td rowspan="2" colspan="2">Efisiensi Anggaran</td>
        <td>Dalam (Rp.)</td>
        <?php 
        $total_efisiensi = 0;
        for($i = $month1; $i <= $month2; $i++) { 
            $real_perbulan = $this->m_laporan->load_realisasi_total_satker($tahun."-".pad($i, 2))->row(); 
            $efisiensi_anggaran = ($real_perbulan->total-($total_pagu/12)); ?>
        <td align="right"><?= ($efisiensi_anggaran < 0)?$efisiensi_anggaran:  rupiah($efisiensi_anggaran) ?></td>
        <?php 
        $total_efisiensi = $total_efisiensi+$efisiensi_anggaran;
        } ?>
        <td align="right"><?= rupiah($total_efisiensi) ?></td>
        <td></td>
    </tr>
    <tr>
        <td>Dalam (%)</td>
        <?php 
        $total_persen_efisiensi = 0;
        for($i = $month1; $i <= $month2; $i++) { 
            $real_perbulan = $this->m_laporan->load_realisasi_total_satker($tahun."-".pad($i, 2))->row(); 
            $efisiensi_angg = ($real_perbulan->total-($total_pagu/12));
            ?>
        <td align="center"><?= round($efisiensi_angg/$total_pagu,4) ?>%</td>
        <?php 
        $total_persen_efisiensi = $total_persen_efisiensi+round($efisiensi_angg/$total_pagu,4);
        } ?>
        <td align="center"><?=$total_persen_efisiensi ?> %</td>
        <td></td>
    </tr>-->
    <?php } else { ?>
    CHECK KEMBALI DATA ENTRI PAGU
    <?php } ?>
</table>