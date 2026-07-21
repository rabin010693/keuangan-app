        </main>

        <footer class="page-footer">
            &copy; <?= date('Y') ?> KeuanganApp &mdash; Kelola keuanganmu dengan lebih baik.
        </footer>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

<script>
document.getElementById('sidebarToggle')?.addEventListener('click', function () {
    document.getElementById('sidebar').classList.toggle('show');
});

// Format input angka ribuan otomatis (untuk field jumlah/amount)
document.querySelectorAll('.input-rupiah').forEach(function (el) {
    el.addEventListener('input', function () {
        let val = el.value.replace(/[^0-9]/g, '');
        el.value = val ? new Intl.NumberFormat('id-ID').format(val) : '';
    });
});
</script>
</body>
</html>
