<?= $this->extend('layout/backend') ?>

<?= $this->section('content') ?>
<title>SIA-IPB &mdash; Neraca Lajur</title>
<?= $this->endSection(); ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="section-header">
        <h1>Neraca Lajur</h1>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-body">
                <form action="<?= site_url('neracalajur') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col">
                            <input type="date" class="form-control" name="tglawal" value="<?= $tglawal ?>">
                        </div>
                        <div class="col">
                            <input type="date" class="form-control" name="tglakhir" value="<?= $tglakhir ?>">
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-list"></i> Tampilkan
                            </button>
                            <input type="submit" class="btn btn-success" formtarget="_blank" formaction="neracalajur/neracalajurpdf" value="Cetak PDF">
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-striped table-md">
                        <thead class="judul">
                            <tr>
                                <td class="text-center" rowspan="2">Kode Akun</td>
                                <td class="text-center" rowspan="2">Deskripsi</td>
                                <td class="text-center" colspan="2">Neraca Saldo</td>
                                <td class="text-center" colspan="2">Jurnal Penyesuaian</td>
                                <td class="text-center" colspan="2">NS.yang Disesuaikan</td>
                                <td class="text-center" colspan="2">Laba Rugi</td>
                                <td class="text-center" colspan="2">Neraca</td>
                            </tr>
                            <tr>
                                <td class="text-right">Debit</td>
                                <td class="text-right">Kredit</td>
                                <td class="text-right">Debit</td>
                                <td class="text-right">Kredit</td>
                                <td class="text-right">Debit</td>
                                <td class="text-right">Kredit</td>
                                <td class="text-right">Debit</td>
                                <td class="text-right">Kredit</td>
                                <td class="text-right">Debit</td>
                                <td class="text-right">Kredit</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $td = $tk = $tdjp = $tkjp = $totd = $totk = $lb_td = $lb_tk = $totns = $totkd = 0; 
                            ?>
                            <?php foreach ($dttransaksi as $key => $value) : ?>
                                <?php
                                $d = $value->jumdebit;
                                $k = $value->jumkredit;
                                $neraca = $d - $k;

                                // Jurnal Penyesuaian
                                $djp = $value->jumdebits;
                                $kjp = $value->jumkredits;
                                $neracajp = $djp - $kjp;

                                $debitnewjp = $neracajp > 0 ? abs($neracajp) : 0;
                                $kreditnewjp = $neracajp < 0 ? abs($neracajp) : 0;
                                $tdjp += $debitnewjp;
                                $tkjp += $kreditnewjp;

                                $debitnew = $neraca > 0 ? abs($neraca) : 0;
                                $kreditnew = $neraca < 0 ? abs($neraca) : 0;
                                $td += $debitnew;
                                $tk += $kreditnew;

                                $ns = $debitnew - $kreditnew + $djp - $kjp;
                                $debs = $ns > 0 ? $ns : 0;
                                $kres = $ns < 0 ? abs($ns) : 0;
                                $totd += $debs;
                                $totk += $kres;

                                // laba rugi
                                $kode_akun = $value->kode_akun3;
                                $kode = substr($kode_akun, 0, 1);

                                if ($kode == '4') {
                                    $lb_db = $kres;
                                    $lb_td += $lb_db;
                                } else {
                                    $lb_db = 0;
                                }

                                if ($kode == '5') {
                                    $lb_kr = $debs;
                                    $lb_tk += $lb_kr;
                                } else {
                                    $lb_kr = 0;
                                }

                                // Neraca
                                if ($kode <= 4 && $ns > 0) {
                                    $nrbs = $debs;
                                    $totns += $nrbs;
                                } else {
                                    $nrbs = 0;
                                }

                                if ($kode <= 4 && $ns < 0) {
                                    $nrkd = abs($ns);
                                    $totkd += $nrkd;
                                } else {
                                    $nrkd = 0;
                                }

                                ?>


                                <tr>
                                    <td><?= $value->kode_akun3 ?></td>
                                    <td><?= $value->nama_akun3 ?></td>
                                    <td class="text-right"><?= number_format($debitnew, 0, ",", ",") ?></td>
                                    <td class="text-right"><?= number_format($kreditnew, 0, ",", ",") ?></td>
                                    <td class="text-right"><?= number_format($debitnewjp, 0, ",", ",") ?></td>
                                    <td class="text-right"><?= number_format($kreditnewjp, 0, ",", ",") ?></td>
                                    <td class="text-right"><?= number_format($debs, 0, ",", ",") ?></td>
                                    <td class="text-right"><?= number_format($kres, 0, ",", ",") ?></td>
                                    <td class="text-right"><?= number_format($lb_db, 0, ",", ",") ?></td>
                                    <td class="text-right"><?= number_format($lb_kr, 0, ",", ",") ?></td>
                                    <td class="text-right"><?= number_format($nrbs, 0, ",", ",") ?></td>
                                    <td class="text-right"><?= number_format($nrkd, 0, ",", ",") ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                        <tfoot class="judul">
                            <tr>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-right"><?= number_format($td, 0, ",", ",") ?></td>
                                <td class="text-right"><?= number_format($tk, 0, ",", ",") ?></td>
                                <td class="text-right"><?= number_format($tdjp, 0, ",", ",") ?></td>
                                <td class="text-right"><?= number_format($tkjp, 0, ",", ",") ?></td>
                                <td class="text-right"><?= number_format($totd, 0, ",", ",") ?></td>
                                <td class="text-right"><?= number_format($totk, 0, ",", ",") ?></td>
                                <td class="text-right"><?= number_format($lb_td, 0, ",", ",") ?></td>
                                <td class="text-right"><?= number_format($lb_tk, 0, ",", ",") ?></td>
                                <td class="text-right"><?= number_format($totns, 0, ",", ",") ?></td>
                                <td class="text-right"><?= number_format($totkd, 0, ",", ",") ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection(); ?>  
