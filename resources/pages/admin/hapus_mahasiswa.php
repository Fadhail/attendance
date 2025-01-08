<?php  
session_start();  
include "../../../database/koneksi.php";
  
if (isset($_GET['id'])) {  
    $npm = htmlspecialchars(trim($_GET['id']));  
  
    try {    
        $query = $pdo->prepare("DELETE FROM mahasiswa WHERE npm = :npm");  
        $query->bindParam(':npm', $npm, PDO::PARAM_INT);  
        $query->execute();  
  
        $_SESSION['message'] = "Mahasiswa berhasil dihapus.";  
    } catch (PDOException $e) {  
        $_SESSION['message'] = "Error: " . $e->getMessage();  
    }  
}  
  
header("Location: ../../../manage_mahasiswa");  
exit();  
