<?php
$monthNames = array(
            array('01','Jan'),
            array('02','Feb'),
            array('03','Mar'),
            array('04', 'Apr'),
            array('05', 'Mei'),
            array('06', 'Jun'),
            array('07', 'Jul'),
            array('08', 'Agu'),
            array('09', 'Sep'),
            array('10', 'Okt'),
            array('11', 'Nov'),
            array('12', 'Des')
        );
?>
<table width=100% cellpadding=0 cellspacing=0 class=inputan>
    <tr><td>Nama Satker:</td><td><?= $nama ?></td></tr>
    <tr><td>Tahun:</td><td><?= $tahun ?></td></tr>
</table><br/>
<table cellspacing="0" width="150%" class="list-data">
    <tr>
        <th width="2%">No.</th>
        <th width="3%">Kode</th>
        <th width="18%" class="left">Nama MA</th>
        <th width="5%" class="right">Pagu</th>
        <?php foreach ($monthNames as $key => $bname) { ?>
        <th width="5%" class="right"><?= $bname[1] ?></th>
        <?php } ?>
        <th width="6%%" class="right"><small>Real <?= $tahun+1 ?></small></th>
        <th width="6%%" class="right">Total</th>
        <th width="6%%" class="right">Sisa</th>
        <th width="1%">&nbsp;</th>
    </tr>
    <?php 
    $total = 0; $pagu = 0; $sisa = 0; $next = 0;
    foreach ($list_data as $key => $data) { ?>
    <tr valign="top" class="<?= ($key%2==1)?'even':'odd' ?>">
        <td align="center"><?= ++$key ?></td>
        <td align="center"><a onclick="return false;" href="#"><?= $data->ma_proja ?></a></td>
        <td><a onclick="return false;" href="#"><?= $data->uraian ?></a></td>
        <td align="right"><?= rupiah($data->pagu) ?></td>
        <?php 
        $subtotal = 0;
        foreach ($data->rincian as $list) { 
            $subtotal+=$list;
            ?>
        <td align="right"><?= rupiah($list) ?></td>
        <?php } ?>
        <td align="right"><?= rupiah($data->next_year) ?></td>
        <td align="right"><?= rupiah($subtotal+$data->next_year) ?></td>
        <td align="right"><?= rupiah($data->pagu-($subtotal+$data->next_year)) ?></td>
        <td></td>
    </tr>
    <?php 
    $pagu +=$data->pagu;
    $total+=$subtotal;
    $sisa +=$data->pagu-($subtotal+$data->next_year);
    $next +=$data->next_year;
    } ?>
    <tr class="odd">
        <td colspan="3"  align="center">TOTAL</td>
        <td align="right"><?= rupiah($pagu) ?></td>
        <?php foreach ($monthNames as $key => $bname) { 
            $total_bln = $this->m_laporan->total_realisasi_perbulan_persatker($satker, $tahun.'-'.$bname[0])->row();
            ?>
        <td align="right"><?= rupiah($total_bln->realisasi) ?></td>
        <?php } ?>
        <td align="right"><b><?= rupiah($next) ?></b></td>
        <td align="right"><b><?= rupiah($total+$next) ?></b></td>
        <td align="right"><b><?= rupiah($sisa) ?></b></td>
        <td></td>
    </tr>
</table>