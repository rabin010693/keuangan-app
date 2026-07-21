<?php $this->load->view('templates/header', get_defined_vars()); ?>

<!-- FILTER PERIODE -->
<form method="get" class="row g-2 mb-4 align-items-end">
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
</form>

<!-- STAT CARDS -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card card-balance">
            <i class="fa-solid fa-scale-balanced bg-icon"></i>
            <div class="label">Saldo Total</div>
            <div class="value"><?= rupiah($saldo_total) ?></div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card card-income">
            <i class="fa-solid fa-arrow-trend-up bg-icon"></i>
            <div class="label">Pemasukan (<?= nama_bulan($bulan) ?>)</div>
            <div class="value"><?= rupiah($total_income) ?></div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card card-expense">
            <i class="fa-solid fa-arrow-trend-down bg-icon"></i>
            <div class="label">Pengeluaran (<?= nama_bulan($bulan) ?>)</div>
            <div class="value"><?= rupiah($total_expense) ?></div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card card-net">
            <i class="fa-solid fa-piggy-bank bg-icon"></i>
            <div class="label">Selisih Bulan Ini</div>
            <div class="value"><?= rupiah($saldo_bulan) ?></div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <!-- CASHFLOW CHART -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header">Alur Cash Flow Tahun <?= $tahun ?></div>
            <div class="card-body">
                <canvas id="cashflowChart" height="110"></canvas>
            </div>
        </div>
    </div>
    <!-- PIE CHART -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">Pengeluaran per Kategori</div>
            <div class="card-body">
                <?php if (count($expense_by_cat) > 0): ?>
                    <canvas id="categoryChart" height="200"></canvas>
                <?php else: ?>
                    <p class="text-muted text-center mb-0 mt-4">Belum ada data pengeluaran bulan ini.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- RECENT TRANSACTIONS -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Transaksi Terbaru</span>
        <a href="<?= site_url('transaksi') ?>" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kategori</th>
                    <th>Keterangan</th>
                    <th>Tipe</th>
                    <th class="text-end">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recent)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada transaksi.</td></tr>
                <?php endif; ?>
                <?php foreach ($recent as $r): ?>
                    <tr>
                        <td><?= tgl_indo($r->trx_date) ?></td>
                        <td><span class="cat-dot" style="background:<?= $r->category_color ?>"></span><?= htmlspecialchars($r->category_name) ?></td>
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

<?php $this->load->view('templates/footer'); ?>

<script>
const cashflowData = <?= json_encode($cashflow) ?>;
const labels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
const incomeArr = labels.map((_, i) => cashflowData[i+1] ? cashflowData[i+1].income : 0);
const expenseArr = labels.map((_, i) => cashflowData[i+1] ? cashflowData[i+1].expense : 0);

new Chart(document.getElementById('cashflowChart'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [
            { label: 'Pemasukan', data: incomeArr, backgroundColor: '#0d9488', borderRadius: 6 },
            { label: 'Pengeluaran', data: expenseArr, backgroundColor: '#f43f5e', borderRadius: 6 }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } },
        scales: { y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID') } } }
    }
});

<?php if (count($expense_by_cat) > 0): ?>
new Chart(document.getElementById('categoryChart'), {
    type: 'doughnut',
    data: {
        labels: [<?php foreach ($expense_by_cat as $c) echo "'" . addslashes($c->name) . "',"; ?>],
        datasets: [{
            data: [<?php foreach ($expense_by_cat as $c) echo $c->total . ','; ?>],
            backgroundColor: [<?php foreach ($expense_by_cat as $c) echo "'" . $c->color . "',"; ?>]
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { boxWidth: 12 } } } }
});
<?php endif; ?>
</script>
