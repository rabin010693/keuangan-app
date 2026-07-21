<html>
<head><meta charset="UTF-8"></head>
<body>
<table border="1">
    <tr>
        <td colspan="5" style="font-size:16px; font-weight:bold;">
            LAPORAN KEUANGAN - <?= strtoupper(nama_bulan($bulan)) ?> <?= $tahun ?>
        </td>
    </tr>
    <tr><td colspan="5">Nama: <?= htmlspecialchars($user->name) ?></td></tr>
    <tr><td colspan="5">&nbsp;</td></tr>
    <tr>
        <td><b>Tanggal</b></td>
        <td><b>Kategori</b></td>
        <td><b>Keterangan</b></td>
        <td><b>Tipe</b></td>
        <td><b>Jumlah (Rp)</b></td>
    </tr>
    <?php foreach ($list as $r): ?>
    <tr>
        <td><?= tgl_indo($r->trx_date) ?></td>
        <td><?= htmlspecialchars($r->category_name) ?></td>
        <td><?= htmlspecialchars($r->description ?: '-') ?></td>
        <td><?= $r->type == 'income' ? 'Pemasukan' : 'Pengeluaran' ?></td>
        <td style="mso-number-format:'#,##0';"><?= $r->type == 'income' ? '' : '-' ?><?= number_format($r->amount, 0, ',', '.') ?></td>
    </tr>
    <?php endforeach; ?>
    <tr><td colspan="5">&nbsp;</td></tr>
    <tr>
        <td colspan="4"><b>Total Pemasukan</b></td>
        <td><b><?= number_format($summary['total_income'], 0, ',', '.') ?></b></td>
    </tr>
    <tr>
        <td colspan="4"><b>Total Pengeluaran</b></td>
        <td><b><?= number_format($summary['total_expense'], 0, ',', '.') ?></b></td>
    </tr>
    <tr>
        <td colspan="4"><b>Selisih (Net)</b></td>
        <td><b><?= number_format($summary['total_income'] - $summary['total_expense'], 0, ',', '.') ?></b></td>
    </tr>
</table>
</body>
</html>
