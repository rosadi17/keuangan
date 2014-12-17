<?= header_excel('proja-'.$proja->nama.'.xls') ?>
<style>
    .tabel-advance th { text-align: center; border: 1px solid #000; }
    .tabel-advance td {border: 1px solid #000; }
    .tabel-advance tr:last-child td { border-bottom: 1px solid #ccc; }
</style>

<div id="dialog_form"></div>
    <table border="1" class="tabel-advance list-data" width="100%" id="example-advanced" cellspacing="0">
        <tr>
            <th width="15%" rowspan="3">Kode</th>
            <th width="40%" rowspan="3">URAIAN<br/>Program, Kegiatan, Sub Kegiatan, <br/>dan Uraian MAK</th>
            <th width="10%" rowspan="3">Data Kuat<br/>Organisasi</th>
            <th colspan="4">Perhitungan Anggaran</th>
        </tr>
        <tr>
            <th width="5%" colspan="2">Volume</th>
            <th width="5%">Harga Satuan</th>
            <th width="5%" rowspan="2">Jumlah Biaya</th>
        </tr>
        <tr>
            <th width="5%">&Sigma; Orang</th>
            <th width="10%">&Sigma; Hari/Bulan</th>
            <th width="7%">Harga Satuan</th>
        </tr>
        <tr><td><b><?= strtoupper($proja->nama) ?></b></td><td colspan="6"></td></tr>
        <?php 
        // Rekening
        $status = "";
        foreach ($program as $r1 => $data) { 
            $total_p = $this->db->query("select sum(sub_total) as total from sub_uraian su
                    join uraian u on (su.id_uraian = u.id)
                    join sub_kegiatan sk on (sk.id = u.id_sub_kegiatan)
                    join kegiatan k on (sk.id_kegiatan = k.id)
                    join program p on (k.id_program = p.id)
                    join satker s on (p.id_satker = s.id) where p.id = '".$data->id."'")->row();
            ?>
        <tr>
            <td><?= ($status !== $data->status)?$data->status:NULL ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr data-tt-id='<?= $r1 ?>' class="<?= ($r1%2==1)?'even':'odd' ?>">
            <td><?= $data->kode ?></td>
            <td><?= $data->nama_program ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td align="right"><?= ($total_p->total) ?></td>
        </tr>
        <?php
            $kegiatan = $this->db->query("select * from kegiatan where id_program = '".$data->id."'")->result();
            foreach ($kegiatan as $r2 => $rows) { 
                $total_k = $this->db->query("select sum(sub_total) as total from sub_uraian su
                    join uraian u on (su.id_uraian = u.id)
                    join sub_kegiatan sk on (sk.id = u.id_sub_kegiatan)
                    join kegiatan k on (sk.id_kegiatan = k.id)
                    join program p on (k.id_program = p.id)
                    join satker s on (p.id_satker = s.id) where k.id = '".$rows->id."'")->row();
                $subkode = $rows->kode;
                ?>
                <tr data-tt-id='<?= $r1 ?>-<?= $r2 ?>' data-tt-parent-id='<?= $r1 ?>' class="even">
                    <td><?= $subkode ?></td>
                    <td style="padding-left: 15px;"><?= $rows->nama_kegiatan ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td align="right"><?= ($total_k->total) ?></td>
                </tr>
                    <?php 
                    $sub_kegiatan = $this->db->query("select * from sub_kegiatan where id_kegiatan = '".$rows->id."'")->result();
                    foreach ($sub_kegiatan as $r3 => $rowx) { 
                        $total_sk = $this->db->query("select sum(sub_total) as total from sub_uraian su
                            join uraian u on (su.id_uraian = u.id)
                            join sub_kegiatan sk on (sk.id = u.id_sub_kegiatan)
                            join kegiatan k on (sk.id_kegiatan = k.id)
                            join program p on (k.id_program = p.id)
                            join satker s on (p.id_satker = s.id) where sk.id = '".$rowx->id."'")->row();
                        $sub_sub_kode = $rowx->kode;
                        ?>
                        <tr data-tt-id='<?= $r1 ?>-<?= $r2 ?>-<?= $r3 ?>' data-tt-parent-id='<?= $r1 ?>-<?= $r2 ?>' class="even">
                            <td><?= $sub_sub_kode ?></td>
                            <td style="padding-left: 30px;"><?= $rowx->nama_sub_kegiatan ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td align="right"><?= ($total_sk->total) ?></td>
                        </tr>
                        <?php
                        $uraian = $this->db->query("select * from uraian where id_sub_kegiatan = '".$rowx->id."'")->result();
                        foreach ($uraian as $r4 => $rowy) { 
                            $total_uraian = $this->db->query("select sum(sub_total) as total from sub_uraian su
                            join uraian u on (su.id_uraian = u.id)
                            join sub_kegiatan sk on (sk.id = u.id_sub_kegiatan)
                            join kegiatan k on (sk.id_kegiatan = k.id)
                            join program p on (k.id_program = p.id)
                            join satker s on (p.id_satker = s.id) where u.id = '".$rowy->id."'")->row();
                            $sub_sub_sub_kode = $rowy->kode;
                            ?>
                            <tr data-tt-id='<?= $r1 ?>-<?= $r2 ?>-<?= $r3 ?>-<?= $r4 ?>' data-tt-parent-id='<?= $r1 ?>-<?= $r2 ?>-<?= $r3 ?>' class="even">
                                <td><?= $sub_sub_sub_kode ?></td>
                                <td style="padding-left: 45px;"><?= $rowy->uraian ?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td align="right"><?= ($total_uraian->total) ?></td>
                            </tr>
                        <?php
                            $sub_uraian = $this->db->query("select * from sub_uraian where id_uraian = '".$rowy->id."'")->result();
                            foreach ($sub_uraian as $r5 => $rowz) { 
                                $sub_sub_sub_sub_kode = $rowz->kode;
                                ?>
                                <tr data-tt-id='<?= $r1 ?>-<?= $r2 ?>-<?= $r3 ?>-<?= $r4 ?>-<?= $r5 ?>' data-tt-parent-id='<?= $r1 ?>-<?= $r2 ?>-<?= $r3 ?>-<?= $r4 ?>' class="even">
                                    <td><?= $sub_sub_sub_sub_kode ?></td>
                                    <td style="padding-left: 60px;"><?= $rowz->keterangan ?></td>
                                    <td align="center"><?= $rowz->data_kuat_org ?></td>
                                    <td align="center"><?= $rowz->vol_orang ?></td>
                                    <td align="center"><?= $rowz->vol_hari_perbulan ?></td>
                                    <td align="right"><?= ($rowz->harga_satuan) ?></td>
                                    <td align="right"><?= ($rowz->sub_total) ?></td>
                                </tr>
                            <?php

                            }
                        }
                    }
            }
            $status = $data->status;
        } ?>
    </table>