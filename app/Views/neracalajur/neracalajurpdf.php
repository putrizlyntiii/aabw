<html>

<head>
    <style type="text/css">
        .aturkiri {
            text-align: left;
        }

        .aturkanan {
            text-align: right;
        }

        .aturtengah {
            text-align: center;
        }

        .spesifik {
            form-style: italic;
            word-spacing: 30px;
        }

        .judul {
            font-style: italic;
            font-size: 20px;
        }
    </style>
</head>

<body>

<p class="judul">Neraca Lajur</p>
    Periode: <?= date('d F Y', strtotime($tglawal)) . " s/d " . date('d F Y', strtotime($tglakhir)) ?>
    <br />
    <br />


                    <table border="0.1px" class="table table-striped table-md">
                        <thead class="judul">
                            <tr>
                                <td class="aturtengah" rowspan="2" width="25">Kode Akun</td>
                                <td class="aturtengah" rowspan="2" width="150">Deskripsi</td>
                                <td class="aturtengah" colspan="2" width="100">Neraca Saldo</td>
                                <td class="aturtengah" colspan="2" width="100">Jurnal Penyesuaian</td>
                                <td class="aturtengah" colspan="2" width="100">NS.yang Disesuaikan</td>
                                <td class="aturtengah" colspan="2" width="100">Laba Rugi</td>
                                <td class="aturtengah" colspan="2" width="100">Neraca</td>
                            </tr>
                            <tr>
                                <td class="aturtengah" width="50">Debit</td>
                                <td class="aturtengah" width="50">Kredit</td>
                                <td class="aturtengah" width="50">Debit</td>
                                <td class="aturtengah" width="50">Kredit</td>
                                <td class="aturtengah" width="50">Debit</td>
                                <td class="aturtengah" width="50">Kredit</td>
                                <td class="aturtengah" width="50">Debit</td>
                                <td class="aturtengah" width="50">Kredit</td>
                                <td class="aturtengah" width="50">Debit</td>
                                <td class="aturtengah" width="50">Kredit</td>
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
                                    <td class="aturtengah" width="25"><?= $value->kode_akun3 ?></td>
                                    <td class="aturkiri" width="150"><?= $value->nama_akun3 ?></td>
                                    <td class="aturkanan" width="50"><?= number_format($debitnew, 0, ",", ",") ?></td>
                                    <td class="aturkanan" width="50"><?= number_format($kreditnew, 0, ",", ",") ?></td>
                                    <td class="aturkanan" width="50"><?= number_format($debitnewjp, 0, ",", ",") ?></td>
                                    <td class="aturkanan" width="50"><?= number_format($kreditnewjp, 0, ",", ",") ?></td>
                                    <td class="aturkanan" width="50"><?= number_format($debs, 0, ",", ",") ?></td>
                                    <td class="aturkanan" width="50"><?= number_format($kres, 0, ",", ",") ?></td>
                                    <td class="aturkanan" width="50"><?= number_format($lb_db, 0, ",", ",") ?></td>
                                    <td class="aturkanan" width="50"><?= number_format($lb_kr, 0, ",", ",") ?></td>
                                    <td class="aturkanan" width="50"><?= number_format($nrbs, 0, ",", ",") ?></td>
                                    <td class="aturkanan" width="50"><?= number_format($nrkd, 0, ",", ",") ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                        <tfoot class="judul">
                            <tr>
                                <td class="aturtengah"></td>
                                <td class="aturtengah"></td>
                                <td class="aturkanan"><?= number_format($td, 0, ",", ",") ?></td>
                                <td class="aturkanan"><?= number_format($tk, 0, ",", ",") ?></td>
                                <td class="aturkanan"><?= number_format($tdjp, 0, ",", ",") ?></td>
                                <td class="aturkanan"><?= number_format($tkjp, 0, ",", ",") ?></td>
                                <td class="aturkanan"><?= number_format($totd, 0, ",", ",") ?></td>
                                <td class="aturkanan"><?= number_format($totk, 0, ",", ",") ?></td>
                                <td class="aturkanan"><?= number_format($lb_td, 0, ",", ",") ?></td>
                                <td class="aturkanan"><?= number_format($lb_tk, 0, ",", ",") ?></td>
                                <td class="aturkanan"><?= number_format($totns, 0, ",", ",") ?></td>
                                <td class="aturkanan"><?= number_format($totkd, 0, ",", ",") ?></td>
                            </tr>
                        </tfoot>
                    </table>
                
                    <br />
    <?php 
        $tgl = date('l, d-m-y');
        echo $tgl;
    ?>
    <br />
    <br />
    PIMPINAN AKN
    <br />
    ____

</body>

</html>
