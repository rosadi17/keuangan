<table cellspacing="0" width="100%" class="list-data">
    <tr>
        <th width="3%">No.</th>
        <?php if ($cari['satker'] === '') { ?>
        <th width="15%" class="left">Satker</th>
        <?php } ?>
        <th width="7%" class="left">No. BKK</th>
        <th width="7%">Tgl BKK</th>
        <th width="30%" class="left">Uraian Kegiatan</th>
        <th width="7%" class="right">Jumlah</th>
        <th width="7%" class="left">Png Jawab</th>
        <?php if ($cari['satker'] !== '') { ?>
        <th width="15%" class="left">Pengguna Ang.</th>
        <?php } ?>
        <th width="10%">No. Perwabku</th>
        <th width="5%">Tgl PWK</th>
        <th width="7%" class="right">Jumlah Pwk</th>
        <th width="7%" class="right">Selisih</th>
        <th width="1%"></th>
    </tr>
    <?php foreach ($list_data as $key => $data) { ?>
    <tr class="<?= ($key%2==1)?'even':'odd' ?>">
        <td align="center"><?= $auto++ ?></td>
        <?php if ($cari['satker'] === '') { ?>
        <td><?= $data->satker ?></td>
        <?php } ?>
        <td>
            <?php 
            $value = array();
            foreach ($data->perwabku as $rows) {
                $value[] = $rows->kodes;    
            } 
            echo implode(', ', $value);
            ?>
        </td>
        <td align="center"><?= datefmysql($data->tanggal_pengeluaran) ?></td>
        <td><?= $data->uraian ?> <i><?= $data->keterangan ?></i></td>
        <td align="right"><?= rupiah($data->dana) ?></td>
        <td><?= $data->penerima ?></td>
        <?php if ($cari['satker'] !== '') { ?>
            <td>
               <?php
               $nilai = array();
               foreach ($data->png_jwb as $pwk) {
                   $nilai[] = $pwk->png_jwb;
               }
               echo implode(', ', $nilai);
               ?>
            </td>
        <?php } ?>
        <td align="center"><?= $data->kode_pwk ?></td>
        <td align="center"><?= datefmysql($data->tanggal) ?></td>
        <td align="right"><?= rupiah($data->dana_digunakan) ?></td>
        <td align="right"><?= rupiah($data->dana-$data->dana_digunakan) ?></td>
        
        <td></td>
    </tr>
    <?php } ?>
</table>
<?= $infopage ?>
<?= $paging ?><br/><br/>