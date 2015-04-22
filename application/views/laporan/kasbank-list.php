<script type="text/javascript">
    $(function() {
        $('#tabs').tabs();
    });
</script>
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">Kas</a></li>
        <li><a href="#tabs-2">BTN Dropping</a></li>
        <li><a href="#tabs-3">BTN Kelas Internasional</a></li>
    </ul>
    <div id="tabs-1">
        <table class="list-data" width="100%">
            <tr>
                <th width="7%" rowspan="2" colspan="2">NO BUK</th>
                <th width="5%" rowspan="2">TGL</th>
                <th width="28%" rowspan="2">Uraian</th>
                <th width="20%" colspan="3">KAS</th>
            </tr>
            <tr>
                <th>DEBET</th>
                <th>KREDIT</th>
                <th>SALDO</th>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>SALDO AWAL</td>
                <td></td>
                <td></td>
                <td align="right"><?= rupiah($awal_kas->saldo_sisa) ?></td>
            </tr>

            <?php 
            $saldo = 0;
            foreach ($list_data as $key => $data) { 
                //$saldo = $saldo+$awal_kas->saldo_sisa+$data->pemasukkan-$data->pengeluaran;
                if ($data->sumberdana === 'Kas') {
                ?>
                <tr class="<?= ($key%2===0)?'odd':'even' ?>">
                    <td align="center"></td>
                    <td align="center"></td>
                    <td align="center"><?= substr($data->tanggal, 8, 2) ?></td>
                    <td><?= $data->uraian ?></td>
                    <td align="right"></td>
                    <td align="right"></td>
                    <td align="right"><?= rupiah($saldo) ?></td>
                </tr>
            <?php   } 
            } ?>
        </table>
    </div>
    <div id="tabs-2">
        <table class="list-data" width="100%">
            <tr>
                <th width="7%" rowspan="2" colspan="2">NO BUK</th>
                <th width="5%" rowspan="2">TGL</th>
                <th width="28%" rowspan="2">Uraian</th>
                <th width="40%" colspan="3">BTN DROPPING</th>
            </tr>
            <tr>
                <th>DEBET</th>
                <th>KREDIT</th>
                <th>SALDO</th>
            </tr>
<!--            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>SALDO AWAL</td>
                <td></td>
                <td></td>
                <td align="right"><?= rupiah($awal_kas->saldo_sisa) ?></td>
            </tr>-->

            <?php 
            $saldo = 0;
            foreach ($list_data_dropping as $key => $data) { 
                $saldo = $saldo+$awal_kas->saldo_sisa+$data->pemasukkan-$data->pengeluaran;
                if ($data->sumberdana === 'Bank') {
                ?>
                <tr class="<?= ($key%2===0)?'odd':'even' ?>">
                    <td align="center"><?= $data->kode_ket ?></td>
                    <td align="center"><?= $data->kode_auto ?></td>
                    <td align="center"><?= substr($data->tanggal, 8, 2) ?></td>
                    <td><?= $data->uraian ?></td>
                    <td align="right"><?= ($data->pemasukkan !== '0')?rupiah($data->pemasukkan):NULL ?></td>
                    <td align="right"><?= ($data->pengeluaran !== '0')?rupiah($data->pengeluaran):NULL ?></td>
                    <td align="right"><?= rupiah($saldo) ?></td>
                </tr>
        <?php   } 
            } ?>
        </table>
    </div>
    <div id="tabs-3">
        <table class="list-data" width="100%">
            <tr>
                <th width="7%" rowspan="2" colspan="2">NO BUK</th>
                <th width="5%" rowspan="2">TGL</th>
                <th width="28%" rowspan="2">Uraian</th>
                <th width="20%" colspan="3">BTN KELAS INTERNASIONAL</th>
            </tr>
            <tr>
                <th>DEBET</th>
                <th>KREDIT</th>
                <th>SALDO</th>
            </tr>
<!--            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>SALDO AWAL</td>
                <td></td>
                <td></td>
                <td align="right"><?= rupiah($awal_kas->saldo_sisa) ?></td>
            </tr>-->

            <?php 
            $saldo = 0;
            foreach ($list_data_kelas_int as $key => $data) { 
                $saldo = $saldo+$awal_kas->saldo_sisa+$data->pemasukkan-$data->pengeluaran;
                if ($data->sumberdana === 'Bank') {
                ?>
                <tr class="<?= ($key%2===0)?'odd':'even' ?>">
                    <td align="center"><?= $data->kode_ket ?></td>
                    <td align="center"><?= $data->kode_auto ?></td>
                    <td align="center"><?= substr($data->tanggal, 8, 2) ?></td>
                    <td><?= $data->uraian ?></td>
                    <td align="right"><?= ($data->pemasukkan !== '0')?rupiah($data->pemasukkan):NULL ?></td>
                    <td align="right"><?= ($data->pengeluaran !== '0')?rupiah($data->pengeluaran):NULL ?></td>
                    <td align="right"><?= rupiah($saldo) ?></td>
                </tr>
        <?php   } 
            } ?>
        </table>
    </div>
</div>