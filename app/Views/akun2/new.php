<?= $this->extend('layout/backend') ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="section-header">
        <a href="<?= site_url('akun2') ?>" class="btn btn-primary">Back</a>
    </div>
    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Tambah Data Akun 2</h4>
            </div>
            <div class="card-body p-4">
                <form method="post" action="<?= site_url('akun2') ?>">
                    <?= csrf_field() ?>
                    
                    <div class="form-group">
                        <label for="kode_akun1"></label>
                            <label>Kode Akun1</label>
                            <select class="form-control" name="kode_akun1" required>
                                <?php foreach ($dtakun1 as $key => $value) : ?>
                            <option value="<?= $value->kode_akun1 ?>"><?= $value->nama_akun1 ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Kode Akun 2</label>
                        <input type="text" class="form-control" name="kode_akun2" placeholder="Kode Akun" required>
                    </div>

                    <div class="form-group">
                        <label>Nama Akun 2</label>
                        <input type="text" class="form-control" name="nama_akun2" placeholder="Nama Akun" required>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success" aria-label="Save">
                            <i class="fas fa-paper-plane"></i> Save
                        </button>
                        <button type="reset" class="btn btn-secondary" aria-label="Reset">
                            Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?> 