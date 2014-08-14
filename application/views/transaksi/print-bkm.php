<link rel="stylesheet" href="<?= base_url('assets/css/base.css') ?>" />
<script type="text/javascript">
    window.print();
    setTimeout(function(){ window.close();},300);
</script>
<?php
foreach ($list_data as $detail);
?>
<style type="text/css" media="print">
    *, body { background: #fff; font-family: Calibri, Arial, "Trebuchet MS"; font-size: 12px; }
    body { width: 21cm; height: 29.7cm; }
    h1, h2 { clear: both; }
    table { background: #fff; } 
    @page {
        size: auto;   /* auto is the initial value */
        margin: 0mm 1cm;  /* this affects the margin in the printer settings */
    }
    .warning { color: yellowgreen; }
</style>
<body>
    <div style="height: 0.5cm; background: #fff;">
        &nbsp;
    </div>
<table width="100%" class="list-data-print" cellspacing="0">
    <tr>
        <td width="15%"></td>
        <td align="center" width="60%">
            <h2>UNIVERSITAS BHAYANGKARA</h2><br/><h1>BUKTI KAS MASUK</h1><br/>Kampus: Jl. A. Yani 114 Wonocolo Surabaya Telp. 031 - 8285602, 8291055 Fax. 031 - 8285601</td><td width="25%" valign="top">
            <table style="border: none;"><tr><td style="border: none;">No. BKM:</td><td style="border: none;"><?= $detail->id ?></td></tr><tr><td style="border: none;">Tgl.:</td><td style="border: none;"><?= date("d F Y") ?></td></tr></table></td>
    </tr>
</table>
<table width="100%" class="list-data-print" cellspacing="0">
    <tr>
        <th width="15%">KODE<br/>PERKIRAAN</th>
        <th width="15%">KODE<br/>MA</th>
        <th width="45%">URAIAN</th>
        <th width="25%">JUMLAH</th>
    </tr>
    <?php
    $total = 0;
    foreach ($list_data as $key => $data) { 
        ?>
    <tr>
        <td align="center"><?= $data->id_rekening ?></td>
        <td><?= $data->kode ?></td>
        <td><?= $data->uraian ?></td>
        <td align="right"><?= rupiah($data->pemasukkan) ?></td>
    </tr>
    <?php 
    $total = $total + $data->pemasukkan;
    } 
    for ($i = 1; $i <= (9-$key); $i++) { ?>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <?php }
    ?>
    <tr>
        <td colspan="3" align="right">TOTAL:</td>
        <td align="right"><?= rupiah($total) ?></td>
    </tr>
    <tr>
        <td rowspan="2" valign="top">Terbilang:</td><td colspan="3"><?= toTerbilang($total) ?> rupiah</td>
    </tr>
</table>
<table width="100%" class="list-data-print" cellspacing="0">
    <tr>
        <th width="15%" rowspan="2">CATATAN:</th>
        <th width="15%">Kepala Biro Keuangan</th>
        <th width="25%">Kepala Bagian Pembukuan</th>
        <th width="20%">Kasir</th>
        <th width="25%">Penyetor</th>
    </tr>
    <tr>
        <td style="height: 50px;"></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td align="right">NAMA</td>
        <td></td>
        <td></td>
        <td align="center"> ( <?= $this->session->userdata('nama') ?> )</td>
        <td align="center"> ( <?= $detail->penyetor ?> ) </td>
    </tr>
</table>
</body>