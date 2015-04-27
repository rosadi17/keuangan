        <table class="list-data" width="100%">
            <thead>
            <tr>
                <th width="3%" rowspan="2">NO.</th>
                <th width="7%" rowspan="2">NO. BUKTI</th>
                <th width="5%" rowspan="2">TGL</th>
                <th width="28%" rowspan="2">Uraian</th>
                <th width="30%" colspan="3">KAS</th>
            </tr>
            <tr>
                <th>DEBET</th>
                <th>KREDIT</th>
                <th>SALDO</th>
            </tr>
            </thead>
            <tbody>
<!--            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>SALDO AWAL</td>
                <td></td>
                <td></td>
                <td align="right"></td>
            </tr>-->

            <?php 
            $saldo = 0;
            foreach ($list_data as $key => $data) { 
                ?>
                <tr class="<?= ($key%2===0)?'odd':'even' ?>">
                    <td align="center"><?= ++$key ?></td>
                    <td align="center"><?= $data->kode ?></td>
                    <td align="center"><?= datefmysql($data->tanggal) ?></td>
                    <td><?= $data->uraian ?><i><?= $data->keterangan ?></i></td>
                    <td align="right"></td>
                    <td align="right"></td>
                    <td align="right"></td>
                </tr>
            <?php
            } ?>
                </tbody>
        </table>