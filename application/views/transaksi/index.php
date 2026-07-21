<?php $this->load->view('templates/header', get_defined_vars()); ?>

<!-- FILTER BAR -->
<form method="get" class="card mb-3">
    <div class="card-body row g-2 align-items-end">
        <div class="col-md-2">
            <label class="form-label small fw-semibold mb-1">Bulan</label>
            <select name="bulan" class="form-select form-select-sm">
                <option value="">Semua</option>
                <?php for ($m = 1; $m <= 12; $m++): ?>
                    <option value="<?= $m ?>" <?= $filters['month'] == $m ? 'selected' : '' ?>><?= nama_bulan($m) ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold mb-1">Tahun</label>
            <select name="tahun" class="form-select form-select-sm">
                <option value="">Semua</option>
                <?php for ($y = date('Y'); $y >= date('Y') - 4; $y--): ?>
                    <option value="<?= $y ?>" <?= $filters['year'] == $y ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold mb-1">Tipe</label>
            <select name="tipe" class="form-select form-select-sm">
                <option value="">Semua</option>
                <option value="income" <?= $filters['type'] == 'income' ? 'selected' : '' ?>>Pemasukan</option>
                <option value="expense" <?= $filters['type'] == 'expense' ? 'selected' : '' ?>>Pengeluaran</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold mb-1">Kategori</label>
            <select name="kategori" class="form-select form-select-sm">
                <option value="">Semua</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= $c->id ?>" <?= $filters['category_id'] == $c->id ? 'selected' : '' ?>><?= htmlspecialchars($c->name) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold mb-1">Cari</label>
            <input type="text" name="q" value="<?= htmlspecialchars($filters['keyword'] ?? '') ?>" class="form-control form-control-sm" placeholder="Keterangan...">
        </div>
        <div class="col-md-2 d-flex gap-1">
            <button class="btn btn-sm btn-primary w-100"><i class="fa-solid fa-filter"></i> Filter</button>
            <a href="<?= site_url('transaksi') ?>" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-rotate"></i></a>
        </div>
    </div>
</form>

<div class="d-flex justify-content-between align-items-center mb-3">
    <span class="text-muted small">Total <?= $total_rows ?> transaksi</span>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAdd">
        <i class="fa-solid fa-plus"></i> Tambah Transaksi
    </button>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kategori</th>
                    <th>Keterangan</th>
                    <th>Tipe</th>
                    <th class="text-end">Jumlah</th>
                    <th class="text-center" style="width:100px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($list)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada data transaksi.</td></tr>
                <?php endif; ?>
                <?php foreach ($list as $r): ?>
                <tr>
                    <td><?= tgl_indo($r->trx_date) ?></td>
                    <td><span class="cat-dot" style="background:<?= $r->category_color ?>"></span><?= htmlspecialchars($r->category_name) ?></td>
                    <td><?= htmlspecialchars($r->description ?: '-') ?></td>
                    <td><?= badge_type($r->type) ?></td>
                    <td class="text-end fw-semibold <?= $r->type == 'income' ? 'text-success' : 'text-danger' ?>">
                        <?= $r->type == 'income' ? '+' : '-' ?> <?= rupiah($r->amount) ?>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $r->id ?>"><i class="fa-solid fa-pen"></i></button>
                        <a href="<?= site_url('transaksi/delete/' . $r->id) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus transaksi ini?')"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php if (!empty($pagination)): ?>
    <div class="card-footer bg-white">
        <?= $pagination ?>
    </div>
    <?php endif; ?>
</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalAdd" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" action="<?= site_url('transaksi/create') ?>" class="modal-content">
            <div class="modal-header"><h6 class="modal-title fw-bold">Tambah Transaksi</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Kategori</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        <optgroup label="Pemasukan">
                            <?php foreach ($categories as $c): if ($c->type != 'income') continue; ?>
                                <option value="<?= $c->id ?>"><?= htmlspecialchars($c->name) ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                        <optgroup label="Pengeluaran">
                            <?php foreach ($categories as $c): if ($c->type != 'expense') continue; ?>
                                <option value="<?= $c->id ?>"><?= htmlspecialchars($c->name) ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                    </select>
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Jumlah (Rp)</label>
                        <input type="text" name="amount" class="form-control input-rupiah" placeholder="0" required inputmode="numeric">
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Tanggal</label>
                        <input type="date" name="trx_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
                <div class="mt-3">
                    <label class="form-label small fw-semibold">Keterangan</label>
                    <input type="text" name="description" class="form-control" placeholder="Opsional">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT (per transaksi) -->
<?php foreach ($list as $r): ?>
<div class="modal fade" id="modalEdit<?= $r->id ?>" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" action="<?= site_url('transaksi/update/' . $r->id) ?>" class="modal-content">
            <div class="modal-header"><h6 class="modal-title fw-bold">Edit Transaksi</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Kategori</label>
                    <select name="category_id" class="form-select" required>
                        <optgroup label="Pemasukan">
                            <?php foreach ($categories as $c): if ($c->type != 'income') continue; ?>
                                <option value="<?= $c->id ?>" <?= $c->id == $r->category_id ? 'selected' : '' ?>><?= htmlspecialchars($c->name) ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                        <optgroup label="Pengeluaran">
                            <?php foreach ($categories as $c): if ($c->type != 'expense') continue; ?>
                                <option value="<?= $c->id ?>" <?= $c->id == $r->category_id ? 'selected' : '' ?>><?= htmlspecialchars($c->name) ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                    </select>
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Jumlah (Rp)</label>
                        <input type="text" name="amount" class="form-control input-rupiah" value="<?= number_format($r->amount, 0, ',', '.') ?>" required inputmode="numeric">
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Tanggal</label>
                        <input type="date" name="trx_date" class="form-control" value="<?= $r->trx_date ?>" required>
                    </div>
                </div>
                <div class="mt-3">
                    <label class="form-label small fw-semibold">Keterangan</label>
                    <input type="text" name="description" class="form-control" value="<?= htmlspecialchars($r->description) ?>">
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
