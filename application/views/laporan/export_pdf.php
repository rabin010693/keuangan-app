<h2 style="color:#312e81;">LAPORAN KEUANGAN BULANAN</h2>
<p style="font-size:11px;">
    Periode: <b><?= nama_bulan($bulan) ?> <?= $tahun ?></b><br>
    Nama: <b><?= htmlspecialchars($user->name) ?></b><br>
    Dicetak: <?= date('d-m-Y H:i') ?>
</p>

<table border="1" cellpadding="5" style="width:100%; font-size:10px; border-collapse:collapse;">
    <tr style="background-color:#e0e7ff;">
        <td width="15%"><b>Tanggal</b></td>
        <td width="20%"><b>Kategori</b></td>
        <td width="30%"><b>Keterangan</b></td>
        <td width="15%"><b>Tipe</b></td>
        <td width="20%" align="right"><b>Jumlah (Rp)</b></td>
    </tr>
    <?php if (empty($list)): ?>
    <tr><td colspan="5" align="center">Tidak ada transaksi pada periode ini.</td></tr>
    <?php endif; ?>
    <?php foreach ($list as $r): ?>
    <tr>
        <td><?= tgl_indo($r->trx_date) ?></td>
        <td><?= htmlspecialchars($r->category_name) ?></td>
        <td><?= htmlspecialchars($r->description ?: '-') ?></td>
        <td><?= $r->type == 'income' ? 'Pemasukan' : 'Pengeluaran' ?></td>
        <td align="right"><?= $r->type == 'income' ? '' : '-' ?><?= number_format($r->amount, 0, ',', '.') ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<br>

<table border="1" cellpadding="5" style="width:60%; font-size:10px; border-collapse:collapse;">
    <tr>
        <td width="60%">Total Pemasukan</td>
        <td width="40%" align="right"><?= number_format($summary['total_income'], 0, ',', '.') ?></td>
    </tr>
    <tr>
        <td>Total Pengeluaran</td>
        <td align="right"><?= number_format($summary['total_expense'], 0, ',', '.') ?></td>
    </tr>
    <tr style="background-color:#e0e7ff;">
        <td><b>Selisih (Net)</b></td>
        <td align="right"><b><?= number_format($summary['total_income'] - $summary['total_expense'], 0, ',', '.') ?></b></td>
    </tr>
</table>

<?php if (!empty($by_cat)): ?>
<br>
<h4 style="color:#312e81;">Rincian Pengeluaran per Kategori</h4>
<table border="1" cellpadding="5" style="width:60%; font-size:10px; border-collapse:collapse;">
    <tr style="background-color:#e0e7ff;">
        <td width="60%"><b>Kategori</b></td>
        <td width="40%" align="right"><b>Jumlah (Rp)</b></td>
    </tr>
    <?php foreach ($by_cat as $c): ?>
    <tr>
        <td><?= htmlspecialchars($c->name) ?></td>
        <td align="right"><?= number_format($c->total, 0, ',', '.') ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>
