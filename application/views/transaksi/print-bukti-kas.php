<link rel="stylesheet" href="<?= base_url('assets/css/print-A4-half.css') ?>" media="all" />
<script type="text/javascript">
    function cetak() {
        window.print();
        setTimeout(function(){ window.close();},300);
    }
</script>
<style type="text/css" media="print">
    *, body { background: #fff; font-family: Arial, "Trebuchet MS"; font-size: 10px; }
</style>
<?php
foreach ($list_data as $detail);
?>
<body onload="cetak();">
    <div class="page">
    <table width="100%" cellspacing="0" style="margin-bottom: 2px;">
        <tr>
            <td width="15%"></td>
            <td align="center" width="57%">&nbsp;</td><td width="25%" valign="top" align="right">
                <table>
                    <tr><td style="border: none;">&nbsp;</td><td style="border: none; text-align: right;"><?= $detail->kode ?></td></tr>
                    <tr><td style="border: none;"></td><td style="border: none;"><?= datefmysql($detail->tanggal) ?></td></tr>
                </table>
            </td>
            <td width="3%">&nbsp;</td>
        </tr>
    </table>
    <table width="100%" cellspacing="0">
        <tr>
            <th width="15%">&nbsp;</th>
            <th width="15%">&nbsp;</th>
            <th width="40%">&nbsp;</th>
            <th width="20%">&nbsp;</th>
            <th width="10%">&nbsp;</th>
        </tr>
        <?php
        $total = 0;
        foreach ($list_data as $key => $data) { 
            ?>
        <tr valign="top">
            <td align="center"><?= ($data->jenis === 'BKK')?$data->id_rekening_pwk:$data->id_akun_rekening ?></td>
            <td><?= $data->ma_proja ?></td>
            <td><?= $data->uraian ?></td>
            <td align="right"><?= rupiah($data->nominal) ?></td>
        </tr>
        <tr valign="top">
            <td align="center"></td>
            <td></td>
            <td><?= $data->keterangan ?></td>
            <td align="right"></td>
        </tr>
        <tr valign="top">
            <td align="center"></td>
            <td><?= $data->id_rekening ?> <?= ($data->jenis === 'BKK')?'(K)':'(D)' ?></td>
            <td><?= $data->rekening ?></td>
            <td align="right"></td>
        </tr>
        <tr valign="top">
            <td align="center"></td>
            <td><?= $data->id_rekening_pwk ?> <?= ($data->jenis === 'BKK')?'(D)':'(K)' ?></td>
            <td><?= $data->rekening_pwk ?></td>
            <td align="right"></td>
        </tr>
        <?php 
        $total = $total + $data->nominal;
        } 
        for ($i = 1; $i <= (5-$key); $i++) { ?>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <?php }
        ?>
        <tr>
            <td colspan="3" align="right">&nbsp;</td>
            <td align="right"><?= rupiah($total) ?></td>
        </tr>
        <tr>
            <td rowspan="2" valign="top">&nbsp;</td><td colspan="3"><?= ucwords(toTerbilang($total)) ?> rupiah</td>
        </tr>
        <tr>
            <td rowspan="2" valign="top">&nbsp;</td><td colspan="3"></td>
        </tr>
    </table>
    <table width="100%" cellspacing="0">
        <tr>
            <th width="30%">&nbsp;</th>
            <th width="15%">&nbsp;</th>
            <th width="15%">&nbsp;</th>
            <th width="20%">&nbsp;</th>
            <th width="20%">&nbsp;</th>
        </tr>
        <?php for ($i = 1; $i <= 2; $i++) { ?>
        <tr>
            <td colspan="6">&nbsp;</td>
        </tr>
        <?php } ?>
        <tr>
            <td align="right">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td align="center"> <?= $this->session->userdata('nama') ?></td>
            <td align="center"> <?= $detail->penerima ?></td>
        </tr>
    </table>
    </div>
</body>