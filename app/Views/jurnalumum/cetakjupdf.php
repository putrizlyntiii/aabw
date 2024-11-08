<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jurnal Umum</title>
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
            font-style: italic;
            word-spacing: 30px;
        }

        .judul {
            text-align: center;
            font-weight: bold;
            font-size: 20px;
            font-style: italic;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 5px;
        }
    </style>
</head>
<body>

    <p class="judul">Jurnal Umum</p>
    <p>Periode: <?= date('d F Y', strtotime($tglawal)) . " s/d " . date('d F Y', strtotime($tglakhir)) ?></p>

    <table border="0.1px" class="table tab-bordered">
        <thead>
            <tr>
                <th class="aturkiri" width="50">Tanggal</th>
                <th class="aturkiri" width="150">Keterangan</th>
                <th class="aturtengah" width="50">Ref</th>
                <th class="aturkanan">Debit</th>
                <th class="aturkanan">Kredit</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dttransaksi as $value) : ?>
                <tr>
                    <td class="aturkiri" width="50"><?= $value->tanggal ?></td>
                    <?php 
                    if ($value->debit <> 0) { ?>
                        <td class="aturkiri" width="150"><?= $value->nama_akun3 ?></td>
                    <?php
                    } else { ?>
                        <td class="spesifik"><?= " " . $value->nama_akun3 ?></td>
                    <?php } ?>
                    <td class="aturtengah" width ="50"><?= $value->kode_akun3 ?></td>
                    <td class="aturkanan"><?= number_format($value->debit, 0, ",", ".") ?></td>
                    <td class="aturkanan"><?= number_format($value->kredit, 0, ",", ".") ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    </br>
    <?php
    $tgl = date('1, d-m-y');
    echo $tgl;
    ?>
    <br/>
    <br/>
    Pimpinan AKN
    ______________


</body>
</html>