<style type="text/css" media="print">
    *, body { background: #fff; font-family: Calibri, Arial, "Trebuchet MS"; font-size: 12px }
    body { width: 21cm; height: 29.7cm; }
    h2 {  }
    @page {
        size: auto;   /* auto is the initial value */
        margin: 0mm 1cm;  /* this affects the margin in the printer settings */
    }
</style>
<script type="text/javascript">
    window.print();
    setTimeout(function(){ window.close();},300);
</script>
<body>
    <h3 style="text-align: center; border-bottom: 1px solid #000;">RENCANA ANGGARAN<br/>UNIVERSITAS BHAYANGKARA SURABAYA</h3>
    <table width="100%">
        <tr><td width="20%">No.</td><td width="1%">:</td><td width="79%"><?= $data->id_renbut ?></td></tr>
        <tr><td>Tanggal Masuk</td><td>:</td><td><?= indo_tgl($data->tanggal) ?></td></tr>
        <tr><td>Tanggal Kegiatan</td><td>:</td><td><?= indo_tgl($data->tanggal_kegiatan) ?></td></tr>
        <tr><td>Tahun Anggaran</td><td>:</td><td><?= $data->tahun_anggaran ?></td></tr>
        <tr><td>Kode Unit Kerja</td><td>:</td><td><?= $data->id_satker ?></td></tr>
        <tr><td>Unit Kerja</td><td>:</td><td><?= $data->satker ?></td></tr>
        <tr><td>Jenis Pengeluaran</td><td>:</td><td></td></tr>
        <tr><td>MA</td><td>:</td><td><?= $data->ma_proja ?></td></tr>
        <tr><td>Program</td><td>:</td><td><?= $data->nama_program ?></td></tr>
        <tr><td>Kegiatan</td><td>:</td><td><?= $data->nama_kegiatan ?></td></tr>
        <tr><td>Sub Kegiatan</td><td>:</td><td><?= $data->nama_sub_kegiatan ?></td></tr>
        <tr><td>Uraian Kegiatan</td><td>:</td><td><?= $data->uraian ?></td></tr>
        <tr><td>Jumlah Dana</td><td>:</td><td><?= rupiah($data->jml_renbut) ?></td></tr>
        <tr><td>Penanggung Jawab</td><td>:</td><td><?= $data->penerima ?></td></tr>
        <tr><td>Kelengkapan</td><td>:</td><td></td></tr>
        <tr><td>Catatan</td><td>:</td><td></td></tr>
    </table>
    <br/><br/>
    <table width="100%">
        <tr><td align="right"><b>Pemeriksa</b></td></tr>
        <tr><td align="right" style="height: 50px;">&nbsp;</td></tr>
        <tr><td align="right"><?= $this->session->userdata('nama') ?></td></tr>
    </table>
</body>