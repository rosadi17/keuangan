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
    .tabel-advance td { }
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
            <th width="15%">Kode</th>
            <th width="40%">URAIAN Program, Kegiatan, Sub Kegiatan, dan Uraian MAK</th>
            <th width="5%">Jumlah Biaya</th>
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
                    join satker s on (p.id_satker = s.id) where p.id = '".$data->id."' and su.tahun = '".date("Y")."'")->row();
            ?>
        <?php if (($satker !== $data->satker) or ($status !== $data->status)) { ?>
        <tr>
            <td><?= strtoupper($data->satker) ?> <?= ($status !== $data->status)?'( '.$data->status.' )':NULL ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <?php } ?>
        <tr data-tt-id='<?= $r1 ?>' class="<?= ($r1%2==1)?'even':'odd' ?>">
            <td><?= $data->kode ?></td>
            <td><?= $data->nama_program ?></td>
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
                    join satker s on (p.id_satker = s.id) where k.id = '".$rows->id."' and su.tahun = '".date("Y")."'")->row();
                $subkode = $rows->kode;
                ?>
                <tr data-tt-id='<?= $r1 ?>-<?= $r2 ?>' data-tt-parent-id='<?= $r1 ?>' class="even">
                    <td><?= $subkode ?></td>
                    <td style="padding-left: 15px;"><?= $rows->nama_kegiatan ?></td>
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
                            join satker s on (p.id_satker = s.id) where sk.id = '".$rowx->id."' and su.tahun = '".date("Y")."'")->row();
                        $sub_sub_kode = $rowx->kode;
                        ?>
                        <tr data-tt-id='<?= $r1 ?>-<?= $r2 ?>-<?= $r3 ?>' data-tt-parent-id='<?= $r1 ?>-<?= $r2 ?>' class="even">
                            <td><?= $sub_sub_kode ?></td>
                            <td style="padding-left: 30px;"><?= $rowx->nama_sub_kegiatan ?></td>
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
                            join satker s on (p.id_satker = s.id) where u.id = '".$rowy->id."' and su.tahun = '".date("Y")."'")->row();
                            $sub_sub_sub_kode = $rowy->kode;
                            ?>
                            <tr data-tt-id='<?= $r1 ?>-<?= $r2 ?>-<?= $r3 ?>-<?= $r4 ?>' data-tt-parent-id='<?= $r1 ?>-<?= $r2 ?>-<?= $r3 ?>' class="even">
                                <td><?= $sub_sub_sub_kode ?></td>
                                <td style="padding-left: 45px;"><?= $rowy->uraian ?></td>
                                <td align="right"><?= rupiah($total_uraian->total) ?></td>
                            </tr>
                            <?php
                        }
                    }
            }
            $status = $data->status;
            $satker = $data->satker;
        } ?>
    </table>