<?php
// modules/user/edit.php
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    echo '<p>Id tidak valid.</p>';
    return;
}

// fetch data
$stmt = mysqli_prepare($conn, "SELECT id_barang,nama,kategori,harga_beli,harga_jual,stok,gambar FROM data_barang WHERE id_barang = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

if (!$data) {
    echo '<p>Data tidak ditemukan.</p>';
    return;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');
    $harga_beli = (int)($_POST['harga_beli'] ?? 0);
    $harga_jual = (int)($_POST['harga_jual'] ?? 0);
    $stok = (int)($_POST['stok'] ?? 0);
    $gambar_path = $data['gambar'];

    if ($nama === '') $errors[] = 'Nama wajib diisi';
    if ($harga_beli <= 0) $errors[] = 'Harga beli harus lebih besar dari 0';
    if ($harga_jual <= 0) $errors[] = 'Harga jual harus lebih besar dari 0';

    // upload gambar jikaa ada
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg','jpeg','png','gif'];
        if (!in_array(strtolower($ext), $allowed)) {
            $errors[] = 'Jenis file gambar tidak diizinkan';
        } else {
            
            $upload_dir = 'gambar/'; 
            
            if (!is_dir($upload_dir)) {
                if (!mkdir($upload_dir, 0777, true)) { 
                    $errors[] = 'Gagal membuat direktori upload: ' . $upload_dir;
                }
            }
            
           
            if (empty($errors)) {
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $_FILES['gambar']['name']);
                $target = $upload_dir . $filename;
                
               
                if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $target)) { 
                    $errors[] = 'Gagal mengupload gambar';
                } else {
                    
                    if (!empty($gambar_path) && file_exists($gambar_path)) {
                        @unlink($gambar_path);
                    }
                    $gambar_path = $target;
                }
            }

        }
    }

    if (empty($errors)) {
        $stmt = mysqli_prepare($conn, "UPDATE data_barang SET nama=?, kategori=?, harga_beli=?, harga_jual=?, stok=?, gambar=? WHERE id_barang=?");
        mysqli_stmt_bind_param($stmt, 'ssiiisi', $nama, $kategori, $harga_beli, $harga_jual, $stok, $gambar_path, $id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            echo "<script>alert('Data berhasil diupdate');window.location='index.php?page=user/list';</script>";
            exit;
        } else {
            $errors[] = 'Query gagal: ' . mysqli_error($conn);
        }
    }
} 
?>

<h2>Edit Barang</h2>
<?php if (!empty($errors)): ?>
    <div class="error"><ul><?php foreach($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul></div>
<?php endif; ?>

<form method="post" action="index.php?page=user/edit&id=<?= $data['id_barang'] ?>" enctype="multipart/form-data">
    <label>Nama</label><br>
    <input type="text" name="nama" value="<?= htmlspecialchars($_POST['nama'] ?? $data['nama']) ?>" required><br><br>

    <label>Kategori</label><br>
    <select name="kategori">
        <option value="Komputer" <?= (($data['kategori']=='Komputer')?'selected':'') ?>>Komputer</option>
        <option value="Elektronik" <?= (($data['kategori']=='Elektronik')?'selected':'') ?>>Elektronik</option>
        <option value="Hand Phone" <?= (($data['kategori']=='Hand Phone')?'selected':'') ?>>Hand Phone</option>
    </select><br><br>

    <label>Harga Beli</label><br>
    <input type="number" name="harga_beli" value="<?= htmlspecialchars($_POST['harga_beli'] ?? $data['harga_beli']) ?>" required><br><br>

    <label>Harga Jual</label><br>
    <input type="number" name="harga_jual" value="<?= htmlspecialchars($_POST['harga_jual'] ?? $data['harga_jual']) ?>" required><br><br>

    <label>Stok</label><br>
    <input type="number" name="stok" value="<?= htmlspecialchars($_POST['stok'] ?? $data['stok']) ?>" required><br><br>

    <label>Gambar saat ini</label><br>
    <?php if (!empty($data['gambar'])): ?>
        <img src="<?= htmlspecialchars($data['gambar']) ?>" width="120" alt=""><br>
    <?php else: ?>
        <em>Tidak ada gambar</em><br>
    <?php endif; ?>
    <br>
    <label>Ganti Gambar (opsional)</label><br>
    <input type="file" name="gambar" accept="image/*"><br><br>

    <button type="submit">Update</button>
</form>