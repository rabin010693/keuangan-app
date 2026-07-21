<?php $this->load->view('templates/header', get_defined_vars()); ?>

<form method="get" class="row g-2 mb-3 align-items-end">
    <div class="col-auto">
        <label class="form-label small fw-semibold mb-1">Bulan</label>
        <select name="bulan" class="form-select form-select-sm" onchange="this.form.submit()">
            <?php for ($m = 1; $m <= 12; $m++): ?>
                <option value="<?= $m ?>" <?= $m == $bulan ? 'selected' : '' ?>><?= nama_bulan($m) ?></option>
            <?php endfor; ?>
        </select>
    </div>
    <div class="col-auto">
        <label class="form-label small fw-semibold mb-1">Tahun</label>
        <select name="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
            <?php for ($y = date('Y'); $y >= date('Y') - 4; $y--): ?>
                <option value="<?= $y ?>" <?= $y == $tahun ? 'selected' : '' ?>><?= $y ?></option>
            <?php endfor; ?>
        </select>
    </div>
    <div class="col-auto ms-auto d-flex gap-2">
        <a href="<?= site_url('laporan/export_excel?bulan=' . $bulan . '&tahun=' . $tahun) ?>" class="btn btn-success">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </a>
        <a href="<?= site_url('laporan/export_pdf?bulan=' . $bulan . '&tahun=' . $tahun) ?>" class="btn btn-danger">
            <i class="fa-solid fa-file-pdf"></i> Export PDF
        </a>
    </div>
</form>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card card-income">
            <i class="fa-solid fa-arrow-trend-up bg-icon"></i>
            <div class="label">Total Pemasukan</div>
            <div class="value"><?= rupiah($summary['total_income']) ?></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card card-expense">
            <i class="fa-solid fa-arrow-trend-down bg-icon"></i>
            <div class="label">Total Pengeluaran</div>
            <div class="value"><?= rupiah($summary['total_expense']) ?></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card card-net">
            <i class="fa-solid fa-scale-balanced bg-icon"></i>
            <div class="label">Selisih (Net)</div>
            <div class="value"><?= rupiah($summary['total_income'] - $summary['total_expense']) ?></div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">Detail Transaksi - <?= nama_bulan($bulan) ?> <?= $tahun ?></div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr><th>Tanggal</th><th>Kategori</th><th>Keterangan</th><th>Tipe</th><th class="text-end">Jumlah</th></tr>
                    </thead>
                    <tbody>
                        <?php if (empty($list)): ?>
                            <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada transaksi pada periode ini.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($list as $r): ?>
                        <tr>
                            <td><?= tgl_indo($r->trx_date) ?></td>
                            <td><?= htmlspecialchars($r->category_name) ?></td>
                            <td><?= htmlspecialchars($r->description ?: '-') ?></td>
                            <td><?= badge_type($r->type) ?></td>
                            <td class="text-end fw-semibold <?= $r->type == 'income' ? 'text-success' : 'text-danger' ?>">
                                <?= $r->type == 'income' ? '+' : '-' ?> <?= rupiah($r->amount) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">Rincian Pengeluaran per Kategori</div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <tbody>
                    <?php if (empty($by_cat)): ?>
                        <tr><td class="text-center text-muted py-3">Belum ada data.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($by_cat as $c): ?>
                        <tr>
                            <td><span class="cat-dot" style="background:<?= $c->color ?>"></span><?= htmlspecialchars($c->name) ?></td>
                            <td class="text-end"><?= rupiah($c->total) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>
