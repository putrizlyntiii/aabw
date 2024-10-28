<?= $this->extend('layout/backend') ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="section-header">
        <a href="<?= site_url('transaksi') ?>" class="btn btn-primary"> Back</a>
    </div>

    <div class="section-body">     
        <div class="card">
            <div class="card-header">
                <h4>Edit Data Transaksi</h4>
            </div>
            <div class="card-body p-4">
                <form method="post" action="<?= site_url('transaksi/' . $dttransaksi->id_transaksi . '/edit') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="PUT">

                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" required value="<?= $dttransaksi->tanggal ?>">
                    </div>

                    <div class="form-group">
                        <label>Deskripsi</label>
                        <input type="text" class="form-control" name="deskripsi" required value="<?= $dttransaksi->deskripsi ?>">
                    </div>

                    <div class="form-group">
                        <label>Ket Jurnal</label>
                        <input type="text" class="form-control" name="ketjurnal" required value="<?= $dttransaksi->ketjurnal ?>">
                    </div>

                    <div class="box-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Akun</th>
                                    <th>Debit</th>
                                    <th>Kredit</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dtnilai as $i => $item) : ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td>
                                            <select class="form-control" name="kode_akun[]">
                                                <?php foreach ($dtakun3 as $value) : ?>
                                                    <option value="<?= $value->kode_akun3 ?>" <?= $item->kode_akun3 == $value->kode_akun3 ? 'selected' : '' ?>>
                                                        <?= $value->kode_akun3 ?> | <?= $value->nama_akun3 ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="debit[]" required value="<?= $item->debit ?>">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="kredit[]" required value="<?= $item->kredit ?>">
                                        </td>
                                        <td>
                                            <select class="form-control" name="status[]">
                                                <?php foreach ($dstatus as $value) : ?>
                                                    <option value="<?= $value->id_status ?>" <?= $item->id_status == $value->id_status ? 'selected' : '' ?>>
                                                        <?= $value->id_status ?> | <?= $value->status ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select> 
                                            <input type="hidden" name="id_nilai[]" required value="<?= $item->id_nilai ?>">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-paper-plane"></i> Update
                        </button>
                        <button type="reset" class="btn btn-secondary"> Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection(); ?>
