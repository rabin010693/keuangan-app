<?php $this->load->view('templates/header', get_defined_vars()); ?>

<div class="d-flex justify-content-end mb-3">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAdd">
        <i class="fa-solid fa-plus"></i> Tambah Kategori
    </button>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header text-success"><i class="fa-solid fa-arrow-down"></i> Kategori Pemasukan</div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <tbody>
                    <?php $has_income = false; foreach ($categories as $c): if ($c->type !== 'income') continue; $has_income = true; ?>
                        <tr>
                            <td style="width:40px"><i class="fa-solid <?= $c->icon ?>" style="color:<?= $c->color ?>"></i></td>
                            <td><?= htmlspecialchars($c->name) ?></td>
                            <td class="text-end" style="width:90px">
                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                    data-bs-target="#modalEdit<?= $c->id ?>"><i class="fa-solid fa-pen"></i></button>
                                <a href="<?= site_url('kategori/delete/' . $c->id) ?>" class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Hapus kategori ini?')"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; if (!$has_income): ?>
                        <tr><td class="text-center text-muted py-3">Belum ada kategori pemasukan.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header text-danger"><i class="fa-solid fa-arrow-up"></i> Kategori Pengeluaran</div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <tbody>
                    <?php $has_expense = false; foreach ($categories as $c): if ($c->type !== 'expense') continue; $has_expense = true; ?>
                        <tr>
                            <td style="width:40px"><i class="fa-solid <?= $c->icon ?>" style="color:<?= $c->color ?>"></i></td>
                            <td><?= htmlspecialchars($c->name) ?></td>
                            <td class="text-end" style="width:90px">
                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                    data-bs-target="#modalEdit<?= $c->id ?>"><i class="fa-solid fa-pen"></i></button>
                                <a href="<?= site_url('kategori/delete/' . $c->id) ?>" class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Hapus kategori ini?')"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; if (!$has_expense): ?>
                        <tr><td class="text-center text-muted py-3">Belum ada kategori pengeluaran.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalAdd" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" action="<?= site_url('kategori/create') ?>" class="modal-content">
            <div class="modal-header"><h6 class="modal-title fw-bold">Tambah Kategori</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Nama Kategori</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Tipe</label>
                    <select name="type" class="form-select" required>
                        <option value="income">Pemasukan</option>
                        <option value="expense">Pengeluaran</option>
                    </select>
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Icon (Font Awesome)</label>
                        <input type="text" name="icon" class="form-control" placeholder="fa-wallet" value="fa-wallet">
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Warna</label>
                        <input type="color" name="color" class="form-control form-control-color w-100" value="#0d6efd">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT (per kategori) -->
<?php foreach ($categories as $c): ?>
<div class="modal fade" id="modalEdit<?= $c->id ?>" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" action="<?= site_url('kategori/update/' . $c->id) ?>" class="modal-content">
            <div class="modal-header"><h6 class="modal-title fw-bold">Edit Kategori</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Nama Kategori</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($c->name) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Tipe</label>
                    <select name="type" class="form-select" required>
                        <option value="income" <?= $c->type == 'income' ? 'selected' : '' ?>>Pemasukan</option>
                        <option value="expense" <?= $c->type == 'expense' ? 'selected' : '' ?>>Pengeluaran</option>
                    </select>
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Icon</label>
                        <input type="text" name="icon" class="form-control" value="<?= $c->icon ?>">
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Warna</label>
                        <input type="color" name="color" class="form-control form-control-color w-100" value="<?= $c->color ?>">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
<?php endforeach; ?>

<?php $this->load->view('templates/footer'); ?>
