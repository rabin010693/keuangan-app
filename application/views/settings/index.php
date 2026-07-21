<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 p-3">
            <div class="card-body">
                <h5 class="fw-bold mb-4"><i class="fa-solid fa-user-gear me-2"></i> Pengaturan Profil</h5>

                <?= form_open_multipart('settings') ?>
                    <div class="row mb-4 align-items-center">
                        <div class="col-auto">
                            <?php if (!empty($user->photo) && file_exists(FCPATH . 'uploads/profile/' . $user->photo)): ?>
                                <img src="<?= base_url('uploads/profile/' . $user->photo) ?>" 
                                    class="rounded-circle img-thumbnail" 
                                    style="width: 90px; height: 90px; object-fit: cover; object-position: center;">
                            <?php else: ?>
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                    style="width: 90px; height: 90px; font-size: 2rem;">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col">
                            <label class="form-label small fw-semibold">Ubah Foto Profil</label>
                            <input type="file" name="photo" class="form-control form-control-sm" accept="image/*">
                            <small class="text-muted">Format: JPG, PNG, WEBP (Max 2MB)</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="<?= set_value('name', $user->name) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Username</label>
                        <input type="text" name="username" class="form-control" value="<?= set_value('username', $user->username) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Email</label>
                        <input type="email" class="form-control bg-light" value="<?= $user->email ?>" readonly>
                        <small class="text-muted">Email tidak dapat diubah.</small>
                    </div>

                    <hr class="my-4">
                    <h6 class="fw-bold text-danger mb-1"><i class="fa-solid fa-key me-2"></i> Ubah Password (Opsional)</h6>
                    <p class="text-muted small mb-3">Kosongkan jika tidak ingin mengubah password. Jika diisi, minimal 8 karakter (kombinasi huruf besar, huruf kecil, dan angka).</p>

                    <div class="row">
                        <!-- FIELD PASSWORD BARU -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold">Password Baru</label>
                            <div class="input-group">
                                <input type="password" 
                                    name="new_password" 
                                    id="new_password" 
                                    class="form-control <?= form_error('new_password') ? 'is-invalid' : '' ?>" 
                                    placeholder="Min. 8 karakter">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password', this)">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                            <!-- Pesan error di bawah input -->
                            <?php if (form_error('new_password')): ?>
                                <div class="text-danger small mt-1">
                                    <i class="fa-solid fa-circle-exclamation me-1"></i><?= strip_tags(form_error('new_password')) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- FIELD KONFIRMASI PASSWORD -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <input type="password" 
                                    name="confirm_password" 
                                    id="confirm_password" 
                                    class="form-control <?= form_error('confirm_password') ? 'is-invalid' : '' ?>" 
                                    placeholder="Ulangi password baru">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirm_password', this)">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                            <!-- Pesan error di bawah input -->
                            <?php if (form_error('confirm_password')): ?>
                                <div class="text-danger small mt-1">
                                    <i class="fa-solid fa-circle-exclamation me-1"></i><?= strip_tags(form_error('confirm_password')) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Script sederhana untuk Show/Hide Password -->
                    <script>
                    function togglePassword(inputId, btn) {
                        const input = document.getElementById(inputId);
                        const icon = btn.querySelector('i');
                        if (input.type === 'password') {
                            input.type = 'text';
                            icon.classList.replace('fa-eye', 'fa-eye-slash');
                        } else {
                            input.type = 'password';
                            icon.classList.replace('fa-eye-slash', 'fa-eye');
                        }
                    }
                    </script>

                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <button type="submit" class="btn btn-primary px-4 fw-semibold">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>