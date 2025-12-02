<?php


// modules/user/delete.php
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: index.php?page=user/list');
    exit;
}
// get gambar path to delete file
$stmt = mysqli_prepare($conn, "SELECT gambar FROM data_barang WHERE id_barang = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

if ($row && !empty($row['gambar']) && file_exists($row['gambar'])) {
    @unlink($row['gambar']);
}

// delete record
$stmt = mysqli_prepare($conn, "DELETE FROM data_barang WHERE id_barang = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header('Location: index.php?page=user/list');
exit;
?>