<?= $this->extend('layout/backend') ?>

<?= $this->section('content') ?>
<title>SIA-IPB &mdash; akun2</title>
<section class="section">
  <div class="section-header">
    <a href="<?= site_url('akun2/new') ?>" class="btn btn-primary">Add New</a>
  </div>

  <!-- For capturing session success messages -->
  <?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible show fade">
      <div class="alert-body">
        <button class="close" data-dismiss="alert">x</button>
        <?= session()->getFlashdata('success'); ?>
      </div>
    </div>
  <?php endif; ?>

  <?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible show fade">
      <div class="alert-body">
        <button class="close" data-dismiss="alert">x</button>
        <?= session()->getFlashdata('error'); ?>
      </div>
    </div>
  <?php endif; ?>

  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Data Akun 2</h4>
      </div>
      <div class="card-body p-4">
        <div class="table-responsive">
          <table class="table table-striped table-md" id="myTable">
            <thead>
              <tr>
                <th>No</th>
                <th>Kode Akun 2</th>
                <th>Nama Akun 2</th>
                <th>Nama Akun 1</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($datakun2 as $key => $value) : ?>
                <tr>
                  <td><?= $key + 1 ?></td>
                  <td><?= $value->kode_akun2 ?></td>
                  <td><?= $value->nama_akun2 ?></td>
                  <td><?= isset($value->nama_akun1) ? $value->nama_akun1 : 'N/A' ?></td> <!-- Ensure it handles cases where nama_akun1 might be null -->
                  <td class="text-center" style="width:15%">
                    <a href="<?= site_url('akun2/' . $value->id_akun2) . '/edit' ?>" class="btn btn-warning" aria-label="Edit">
                      <i class="fas fa-pencil-alt btn-small"></i> Edit
                    </a>
                    <form action="<?= site_url('akun2/' . $value->id_akun2) ?>" method="post" id="del-<?= $value->id_akun2 ?>" class="d-inline">
                      <?= csrf_field() ?>
                      <input type="hidden" name="_method" value="DELETE">
                      <button class="btn btn-danger btn-small" data-confirm="Hapus Data....? | Apakah anda Yakin ....?" data-confirm-yes="hapus(<?= $value->id_akun2 ?>)" aria-label="Delete">
                        <i class="fas fa-trash"></i> Del
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
<?= $this->endSection() ?>
