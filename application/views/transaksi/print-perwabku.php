<link rel="stylesheet" href="<?= base_url('assets/css/printing-A4-half.css') ?>" media="all" />
<script type="text/javascript">
    function cetak() {
        window.print();
        setTimeout(function(){ window.close();},300);
    }
</script>
<body onload="cetak();">
    <?php foreach ($list_data as $data); ?>
    <div class="page">
    <h3 style="text-align: center; border-bottom: 1px solid #000;">PERTANGGUNG JAWABAN KEUANGAN<br/>UNIVERSITAS BHAYANGKARA SURABAYA</h3>
    <table width="100%" style="line-height: 10px;">
        <tr><td width="20%">No.</td><td width="1%">:</td><td width="79%"><?= $data->kode_pwk ?>
            (<?php 
            $value = array();
            foreach ($data->perwabku as $rows) {
                $value[] = $rows->kodes;    
            } 
            echo implode(', ', $value);
            ?>)
            </td></tr>
        <!--<tr><td>Tanggal Masuk</td><td>:</td><td><?= indo_tgl(date2mysql(datetimefmysql($data->waktu))) ?></td></tr>-->
        <tr><td>Tanggal Perwabku</td><td>:</td><td><?= indo_tgl($data->tanggal) ?></td></tr>
        <tr><td>Tahun Anggaran</td><td>:</td><td><?= $data->thn_anggaran ?></td></tr>
        <tr><td>Kode Unit Kerja</td><td>:</td><td><?= $data->kode_satker ?></td></tr>
        <tr><td>Unit Kerja</td><td>:</td><td><?= $data->satker ?></td></tr>
        <!--<tr><td>Jenis Pengeluaran</td><td>:</td><td><?= $data->status_pengeluaran ?></td></tr>-->
        <tr><td>MA</td><td>:</td><td><?= $data->kode_ma ?></td></tr>
        <tr><td>Program</td><td>:</td><td><?= $data->nama_program ?></td></tr>
        <tr><td>Kegiatan</td><td>:</td><td><?= $data->nama_kegiatan ?></td></tr>
        <tr><td>Sub Kegiatan</td><td>:</td><td><?= $data->nama_sub_kegiatan ?></td></tr>
        <tr><td>Uraian Kegiatan</td><td>:</td><td><?= $data->uraian ?></td></tr>
        <tr><td>Keterangan</td><td>:</td><td></td></tr>
        <tr><td>Jumlah Dana Rp.</td><td>:</td><td><?= rupiah($data->dana) ?></td></tr>
        <tr><td>Dana yang Digunakan Rp.</td><td>:</td><td><?= rupiah($data->dana_digunakan) ?>, Selisih Rp. <?= rupiah($data->dana-$data->dana_digunakan) ?></td></tr>
        <tr><td>Penanggung Jawab</td><td>:</td><td>
            <?php 
            $values = array();
            foreach ($data->png_jwb as $rows) {
                $values[] = $rows->png_jwb;    
            } 
            echo implode(', ', $values);
            ?>
            </td></tr>
        <tr><td>Kelengkapan</td><td>:</td><td><?= $data->kelengkapan ?></td></tr>
        <tr><td>Catatan</td><td>:</td><td><?= $data->catatan ?></td></tr>
    </table>
    <br/>
    <table width="100%" style="float: left;">
        <tr><td width="25%" align="center"><b>Yang Menerima</b></td><td align="center" width="25%"><b>Yang Menyerahkan</b></td><td width="25%" align="center"><b>Pemeriksa</b></td><td width="50%" align="center"><b>Telah Dibukukan</b></td></tr>
        <tr><td style="height: 50px;">&nbsp;</td><td style="height: 50px;">&nbsp;</td><td style="height: 50px;">&nbsp;</td><td align="right" style="height: 50px;">&nbsp;</td></tr>
        <tr><td align="center">( ...................... )</td><td align="center">( ...................... )</td><td align="center">( Ni Luh Ketut NI )</td><td align="center">( ...................... )</td></tr>
    </table>
    </div>
</body>