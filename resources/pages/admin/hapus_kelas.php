<?php  
session_start();  
include "../../../database/koneksi.php";
  
if (isset($_GET['id'])) {  
    $id_kelas = htmlspecialchars(trim($_GET['id']));  
  
    try {    
        $query = $pdo->prepare("DELETE FROM kelas WHERE id_kelas = :id_kelas");  
        $query->bindParam(':id_kelas', $id_kelas, PDO::PARAM_INT);  
        $query->execute();  
  
        $_SESSION['message'] = "Kelas berhasil dihapus.";  
    } catch (PDOException $e) {  
        $_SESSION['message'] = "Error: " . $e->getMessage();  
    }  
}  
  
header("Location: ../../../manage_kelas");  
exit();  

