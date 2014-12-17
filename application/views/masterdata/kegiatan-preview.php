<script type="text/javascript">
$("#example-advanced").treetable({ expandable: true });
$('input:checkbox').click(function() {
    if ($(this).is(':checked') === true) {
        $('#example-advanced').treetable('expandAll');
    } else {
        $('#example-advanced').treetable('collapseAll');
    }
});
$('#reset-prev').button({
    icons: {
        secondary: 'ui-icon-print'
    }
}).click(function() {
    var satker = $('#id_satker').val();
    window.location='<?= base_url('masterdata/cetak_proja') ?>/'+satker;
});
function preview() {
    var satker = $('#id_satker').val();
    $('#tabs-7').load('<?= base_url('masterdata/kegiatan_preview') ?>/?id='+satker);
};
</script>
<style>
    .tabel-advance th { text-align: center; border-bottom: 1px solid #f4f4f4; border-right: 1px solid #f4f4f4; }
    .tabel-advance td {border-right: 1px solid #ccc; }
    .tabel-advance tr:last-child td { border-bottom: 1px solid #ccc; }
</style>
<div class="searching-box">
    <?php if ($sk !== NULL) { ?>
    <button id="reset-prev">Export Excel</button>
    <?php } ?>
</div>
<table><tr><td>Group By:</td><td><select name=id_satker id=id_satker onchange="preview();"><option value="">Semua Satker ...</option><?php foreach ($satker as $data) { ?><option value="<?= $data->id ?>" <?php if($data->id == $sk) { echo 'selected'; } ?>><?= $data->nama ?></option><?php } ?></select></td><td>&nbsp;</td><td>Expand Collapse:</td><td><input type="checkbox" id="exp" /></td></tr></table>

<div id="dialog_form"></div>
    <table class="tabel-advance list-data" width="100%" id="example-advanced" cellspacing="0">
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
            <th width="5%">&Sigma; Orang</th>
            <th width="10%">&Sigma; Hari/Bulan</th>
            <th width="7%">Harga Satuan</th>
        </tr>
        <?php 
        // Rekening
        $status = "";
        $satker = "";
        foreach ($program as $r1 => $data) { 
            $total_p = $this->db->query("select IFNULL(sum(ssu.sub_total), sum(su.sub_total)) as total from 
                    sub_sub_uraian ssu    
                    right join sub_uraian su on (ssu.id_sub_uraian = su.id)
                    join uraian u on (su.id_uraian = u.id)
                    join sub_kegiatan sk on (sk.id = u.id_sub_kegiatan)
                    join kegiatan k on (sk.id_kegiatan = k.id)
                    join program p on (k.id_program = p.id)
                    join satker s on (p.id_satker = s.id) where p.id = '".$data->id."'")->row();
            ?>
        <?php if (($satker !== $data->satker) or ($status !== $data->status)) { ?>
        <tr>
            <td><?= strtoupper($data->satker) ?> <?= ($status !== $data->status)?'( '.$data->status.' )':NULL ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <?php } ?>
        <tr data-tt-id='<?= $r1 ?>' class="<?= ($r1%2==1)?'even':'odd' ?>">
            <td><?= $data->kode ?></td>
            <td><?= $data->nama_program ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td align="right"><?= rupiah($total_p->total) ?></td>
        </tr>
        <?php
            $kegiatan = $this->db->query("select * from kegiatan where id_program = '".$data->id."'")->result();
            foreach ($kegiatan as $r2 => $rows) { 
                $total_k = $this->db->query("select IFNULL(sum(ssu.sub_total), sum(su.sub_total)) as total from 
                    sub_sub_uraian ssu    
                    right join sub_uraian su on (ssu.id_sub_uraian = su.id)
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
                    <td align="right"><?= rupiah($total_k->total) ?></td>
                </tr>
                    <?php 
                    $sub_kegiatan = $this->db->query("select * from sub_kegiatan where id_kegiatan = '".$rows->id."'")->result();
                    foreach ($sub_kegiatan as $r3 => $rowx) { 
                        $total_sk = $this->db->query("select IFNULL(sum(ssu.sub_total), sum(su.sub_total)) as total from 
                            sub_sub_uraian ssu    
                            right join sub_uraian su on (ssu.id_sub_uraian = su.id)
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
                            <td align="right"><?= rupiah($total_sk->total) ?></td>
                        </tr>
                        <?php
                        $uraian = $this->db->query("select * from uraian where id_sub_kegiatan = '".$rowx->id."'")->result();
                        foreach ($uraian as $r4 => $rowy) { 
                            $total_uraian = $this->db->query("select IFNULL(sum(ssu.sub_total), sum(su.sub_total)) as total from 
                            sub_sub_uraian ssu    
                            right join sub_uraian su on (ssu.id_sub_uraian = su.id)
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
                                <td align="right"><?= rupiah($total_uraian->total) ?></td>
                            </tr>
                        <?php
                            $sub_uraian = $this->db->query("select * from sub_uraian where id_uraian = '".$rowy->id."'")->result();
                            foreach ($sub_uraian as $r5 => $rowz) { 
                                $sub_sub_sub_sub_kode = $rowz->kode;
                                $total_sub_uraian = $this->db->query("select IFNULL(sum(ssu.sub_total), sum(su.sub_total)) as total from 
                                sub_sub_uraian ssu    
                                right join sub_uraian su on (ssu.id_sub_uraian = su.id)
                                join uraian u on (su.id_uraian = u.id)
                                join sub_kegiatan sk on (sk.id = u.id_sub_kegiatan)
                                join kegiatan k on (sk.id_kegiatan = k.id)
                                join program p on (k.id_program = p.id)
                                join satker s on (p.id_satker = s.id) where su.id = '".$rowz->id."'")->row();
                                ?>
                                <tr data-tt-id='<?= $r1 ?>-<?= $r2 ?>-<?= $r3 ?>-<?= $r4 ?>-<?= $r5 ?>' data-tt-parent-id='<?= $r1 ?>-<?= $r2 ?>-<?= $r3 ?>-<?= $r4 ?>' class="even">
                                    <td><?= $sub_sub_sub_sub_kode ?></td>
                                    <td style="padding-left: 60px;"><?= $rowz->keterangan ?></td>
                                    <td align="center"><?= $rowz->data_kuat_org ?></td>
                                    <td align="center"><?= ($rowz->vol_orang !== '0')?$rowz->vol_orang:NULL ?></td>
                                    <td align="center"><?= ($rowz->vol_hari_perbulan !== '0')?$rowz->vol_hari_perbulan:NULL ?></td>
                                    <td align="right"><?= ($rowz->harga_satuan !== '0')?rupiah($rowz->harga_satuan):NULL ?></td>
                                    <td align="right"><?= ($total_sub_uraian->total !== '0')?rupiah($total_sub_uraian->total):NULL ?></td>
                                </tr>
                            <?php
                                $sub_sub_uraian = $this->db->query("select * from sub_sub_uraian where id_sub_uraian = '".$rowz->id."'")->result();
                                foreach ($sub_sub_uraian as $r6 => $roww) {
                                    $sub_sub_sub_sub_sub_kode = $roww->kode;
                                    ?>
                                    <tr data-tt-id='<?= $r1 ?>-<?= $r2 ?>-<?= $r3 ?>-<?= $r4 ?>-<?= $r5 ?>-<?= $r6 ?>' data-tt-parent-id='<?= $r1 ?>-<?= $r2 ?>-<?= $r3 ?>-<?= $r4 ?>-<?= $r5 ?>' class="even">
                                        <td></td>
                                        <td style="padding-left: 75px;"><?= $roww->keterangan ?></td>
                                        <td align="center"><?= $roww->data_kuat_org ?></td>
                                        <td align="center"><?= $roww->vol_orang ?></td>
                                        <td align="center"><?= $roww->vol_hari_perbulan ?></td>
                                        <td align="right"><?= rupiah($roww->harga_satuan) ?></td>
                                        <td align="right"><?= rupiah($roww->sub_total) ?></td>
                                    </tr>
                               <?php }
                            }
                        }
                    }
            }
            $status = $data->status;
            $satker = $data->satker;
        } ?>
    </table>