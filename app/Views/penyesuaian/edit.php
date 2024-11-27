<?= $this->extend('layout/backend') ?>

<?= $this->Section('content') ?>

<section class="section">
    <div class="section-header">
        <!-- <h6>Blank Page</h6> -->
        <a href="<?= site_url('penyesuaian') ?>" class="btn btn-primary">Back</a>
    </div>

    <div class="section-body">
        <!-- Dinamis  -->
        <div class="card"> 
            <div class="card-header">
                <h4>Tambah Data Penyesuaian</h4>
            </div>
            <div class="card-body p-4">
                <form method="post" action="<?= site_url('penyesuaian/' . $dtpenyesuaian->id_penyesuaian.'/edit') ?>">
                    <?= csrf_field() ?>
                    
                    <input type="hidden" name="_method" value="PUT">
                    
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" placeholder="Tanggal" required value="<?= $dtpenyesuaian->tanggal ?>">
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <input type="text" class="form-control" name="deskripsi" placeholder="Deskripsi" required value="<?= $dtpenyesuaian->deskripsi ?>">
                    </div>
                    <div class="form-group">
                        <label>Nilai</label>
                        <input type="text" class="form-control" onkeyup="hitung()" name="nilai" placeholder="Nilai" required value="<?= $dtpenyesuaian->nilai  ?>">
                    </div>
                    <div class="form-group">
                        <label>Waktu</label>
                        <input type="text" class="form-control" onkeyup="hitung()" name="waktu" placeholder="Waktu" required value="<?= $dtpenyesuaian->waktu  ?>">
                    </div>
                    <div class="form-group">
                        <label>Jumlah</label>
                        <input type="text" class="form-control" name="jumlah" placeholder="Jumlah" required value="<?= $dtpenyesuaian->jumlah  ?>">
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
                                <?php $i = 0 ?>
                                <?php foreach($dtnilaipenyesuaian as $item) : ?>
                                    <?php $i++ ?>
                                    <tr>
                                        <td>
                                            <?= $i ?>
                                        </td>
                                        <td>
                                            <select class="form-control" name="kode_akun3[]">
                                                <?php foreach($dtakun3 as $key => $value) :?>
                                                    <option value="<?=$value->kode_akun3 ?>" <?=$item->kode_akun3==$value->kode_akun3? 'selected' : null ?>><?= $value->kode_akun3 ?> | <?=$value->nama_akun3 ?></option>
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
                                            <select class="form-control" name="id_status[]">
                                                <?php foreach($dtstatus as $key => $value) :?>
                                                    <option value="<?=$value->id_status ?>" <?= $item->id_status==$value->id_status ? 'selected' : null ?>><?= $value->id_status ?> | <?= $value->status ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <input type="hidden" id="id" name="id[]" required value="<?= $item->id ?>">
                                <?php endforeach; ?>

    
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane"></i> Update</button>
                        <button type="reset" class="btn btn-secondary"> Reset</button>
                    </div>
                </form>
            </div>
            <!-- <div class="card-footer text-right">
                <nav class="d-inline-block">
                    <ul class="pagination mb-0">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1"><i class="fas fa-chevron-left"></i></a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1 <span class="sr-only">(current)</span></a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">2</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a>
                        </li>
                    </ul>
                </nav>
            </div> -->
        </div>
    </div>

</section>

<?= $this->endSection(); ?>