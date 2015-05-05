<?php
header_excel('realisasi-all-'.$tahun.'.xls');
$month1 = isset($_GET['awal'])?$_GET['awal']:'1';
$month2 = isset($_GET['akhir'])?$_GET['akhir']:date("m");
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
<table>
    <tr><td colspan="<?= (7+$month2) ?>"><b>LAPORAN ANGGARAN DAN REALISASI TAHUN <?= $tahun ?><br />UNIVERSITAS BHAYANGKARA SURABAYA</b></td></tr>
</table>
<table cellspacing="0" width="100%" border="1">
    <tr>
        <th width="3%">No.</th>
        <th width="3%">Kode</th>
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
    $total_pagu     = 0;
    $total_terpakai = 0;
    $total_next     = 0;
    $totallica      = 0;
    $total_sisa     = 0;
    if (count($list_data) > 0) {
    foreach ($list_data as $key => $data) {
        $total_pagu = $total_pagu + $data->pagu;
        $total_next+=$data->next_year;
        ?>
    <tr class="<?= ($key%2==1)?'even':'odd' ?>">
        <td align="center"><?= ++$key ?></td>
        <td align="center"><?= $data->kode ?></td>
        <td class="nowrap"><small><?= $data->nama ?></small></td>
        <td align="right"><?= ($data->pagu) ?></td>
        <?php 
        $total_kanan = 0;
        for($i = $month1; $i <= $month2; $i++) { 
            $real = $this->m_laporan->load_realisasi_total_satker($tahun."-".pad($i, 2), $data->id_satker)->row();
            $total_kanan = $total_kanan+$real->total;
            ?>
            
        <td align="right" style="min-width: 90px;"><?= isset($real->total)?($real->total):'-' ?></td>
        <?php } ?>
        <td align="right" style="min-width: 90px;"><?= ($data->next_year) ?></td>
        <td align="right"><?= ($total_kanan+$data->next_year) ?></td>
        <td align="right"><?= ($data->pagu-($total_kanan+$data->next_year)) ?></td>
    </tr>
    <?php 
        $totallica+=$total_kanan+$data->next_year;
        $total_sisa+=$data->pagu-($total_kanan+$data->next_year);
        } 
    } ?>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td align="right"><?= ($total_pagu) ?></td>
        <?php
        for($i = $month1; $i <= $month2; $i++) { 
        $real = $this->m_laporan->load_realisasi_total_satker($tahun."-".pad($i, 2))->row();
        ?>
        <td align="right" style="min-width: 90px;"><?= isset($real->total)?($real->total):'-' ?></td>
        <?php } ?>
        <td align="right"><?= ($total_next) ?></td>
        <td align="right"><?= ($totallica) ?></td>
        <td align="right"><?= ($total_sisa) ?></td>
    </tr>
</table>