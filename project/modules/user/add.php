<?php
// modules/user/add.php
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');
    $harga_beli = (int)($_POST['harga_beli'] ?? 0);
    $harga_jual = (int)($_POST['harga_jual'] ?? 0);
    $stok = (int)($_POST['stok'] ?? 0);
    $gambar_path = '';

    if ($nama === '') $errors[] = 'Nama wajib diisi';
    if ($harga_beli <= 0) $errors[] = 'Harga beli harus lebih besar dari 0';
    if ($harga_jual <= 0) $errors[] = 'Harga jual harus lebih besar dari 0';

    // handle upload
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg','jpeg','png','gif'];
        if (!in_array(strtolower($ext), $allowed)) {
            $errors[] = 'Jenis file gambar tidak diizinkan';
        } else {
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $_FILES['gambar']['name']);
            $target = 'gambar/' . $filename;
            if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $target)) {
                $errors[] = 'Gagal mengupload gambar';
            } else {
                $gambar_path = $target;
            }
        }
    }

    if (empty($errors)) {
        $stmt = mysqli_prepare($conn, "INSERT INTO data_barang (nama,kategori,harga_beli,harga_jual,stok,gambar) VALUES (?,?,?,?,?,?)");
        mysqli_stmt_bind_param($stmt, 'ssiiis', $nama, $kategori, $harga_beli, $harga_jual, $stok, $gambar_path);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            echo "<script>alert('Data berhasil ditambah');window.location='index.php?page=user/list';</script>";
            exit;
        } else {
            $errors[] = 'Query gagal: ' . mysqli_error($conn);
        }
    }
}
?>

<h2>Tambah Barang</h2>
<?php if (!empty($errors)): ?>
    <div class="error">
        <ul><?php foreach($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul>
    </div>
<?php endif; ?>

<form method="post" action="index.php?page=user/add" enctype="multipart/form-data">
    <label>Nama</label><br>
    <input type="text" name="nama" value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" required><br><br>

    <label>Kategori</label><br>
    <select name="kategori">
        <option value="Komputer" <?= (($_POST['kategori'] ?? '')=='Komputer')?'selected':'' ?>>Komputer</option>
        <option value="Elektronik" <?= (($_POST['kategori'] ?? '')=='Elektronik')?'selected':'' ?>>Elektronik</option>
        <option value="Hand Phone" <?= (($_POST['kategori'] ?? '')=='Hand Phone')?'selected':'' ?>>Hand Phone</option>
    </select><br><br>

    <label>Harga Beli</label><br>
    <input type="number" name="harga_beli" value="<?= htmlspecialchars($_POST['harga_beli'] ?? '') ?>" required><br><br>

    <label>Harga Jual</label><br>
    <input type="number" name="harga_jual" value="<?= htmlspecialchars($_POST['harga_jual'] ?? '') ?>" required><br><br>

    <label>Stok</label><br>
    <input type="number" name="stok" value="<?= htmlspecialchars($_POST['stok'] ?? '') ?>" required><br><br>

    <label>Gambar</label><br>
    <input type="file" name="gambar" accept="image/*"><br><br>

    <button type="submit">Simpan</button>
</form>
