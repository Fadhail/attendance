<?php  
session_start();  
include "../../../database/koneksi.php"; // Pastikan file ini menginisialisasi koneksi $pdo  
  
if (isset($_GET['id'])) {  
    $id_kelas = htmlspecialchars(trim($_GET['id']));  
  
    try {  
        // Query untuk menghapus kelas berdasarkan id_kelas  
        $query = $pdo->prepare("DELETE FROM kelas WHERE id_kelas = :id_kelas");  
        $query->bindParam(':id_kelas', $id_kelas, PDO::PARAM_INT);  
        $query->execute();  
  
        $_SESSION['message'] = "Kelas berhasil dihapus.";  
    } catch (PDOException $e) {  
        $_SESSION['message'] = "Error: " . $e->getMessage();  
    }  
}  
  
// Redirect kembali ke halaman manage kelas  
header("Location: ../../../manage_kelas");  
exit();  
?>  
