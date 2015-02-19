<table cellspacing="0" width="100%" class="list-data">
    <tr>
        <th width="3%">No.</th>
        <th width="7%">Tgl Masuk</th>
        <th width="10%">Tgl Kegiatan</th>
        <th width="10%">Thn Anggaran</th>
        <th width="10%">Unit Kerja</th>
        <th width="10%">Kode MA</th>
        <th width="10%">Jumlah Dana</th>
        <th width="15%">Penanggung Jwb</th>
        <th width="3%"></th>
    </tr>
    <?php foreach ($list_data as $key => $data) { ?>
    <tr class="<?= ($key%2==1)?'even':'odd' ?>">
        <td align="center"><?= $auto++ ?></td>
        <td align="center"><?= datefmysql($data->tanggal) ?></td>
        <td align="right">
            <button type="button" class="btn btn-default btn-xs" onclick="delete_jurnal('<?= $data->id ?>', '<?= $page ?>');" title="Klik untuk hapus"><i class="fa fa-trash-o"></i></button>
        </td>
    </tr>
    <?php } ?>
</table>
<?= $paging ?><br/><br/>