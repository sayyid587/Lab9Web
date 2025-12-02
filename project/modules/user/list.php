<?php

$query = "SELECT * FROM data_barang ORDER BY id_barang DESC";
$res = mysqli_query($conn, $query);
?>
<h2>Daftar Barang</h2>
<a class="btn" href="index.php?page=user/add">+ Tambah Barang</a>

<table class="table">
    <thead>
        <tr>
            <th>Gambar</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Harga Beli</th>
            <th>Harga Jual</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    <?php if ($res && mysqli_num_rows($res) > 0): ?>
        <?php while ($r = mysqli_fetch_assoc($res)): ?>
        <tr>
            <td>
                <?php if (!empty($r['gambar']) && file_exists(__DIR__ . '/../../' . $r['gambar'])): ?>
                    <img src="<?= htmlspecialchars($r['gambar']) ?>" width="80" alt="">
                <?php elseif (!empty($r['gambar'])): ?>
                    <img src="<?= htmlspecialchars($r['gambar']) ?>" width="80" alt="">
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($r['nama']) ?></td>
            <td><?= htmlspecialchars($r['kategori']) ?></td>
            <td><?= number_format($r['harga_beli'], 0, ',', '.') ?></td>
            <td><?= number_format($r['harga_jual'], 0, ',', '.') ?></td>
            <td><?= (int)$r['stok'] ?></td>
            <td>
                <a href="index.php?page=user/edit&id=<?= $r['id_barang'] ?>">Edit</a> |
                <a href="index.php?page=user/delete&id=<?= $r['id_barang'] ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="7">Belum ada data</td></tr>
    <?php endif; ?>
    </tbody>
</table>
