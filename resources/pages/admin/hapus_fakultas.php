<?php  
session_start();  
include "../../../database/koneksi.php";
  
if (isset($_GET['id'])) {  
    $id_fakultas = htmlspecialchars(trim($_GET['id']));  
  
    try {  
        $query = $pdo->prepare("DELETE FROM fakultas WHERE id_fakultas = :id_fakultas");  
        $query->bindParam(':id_fakultas', $id_fakultas, PDO::PARAM_INT);  
        $query->execute();  
  
        $_SESSION['message'] = "Fakultas berhasil dihapus.";  
    } catch (PDOException $e) {  
        $_SESSION['message'] = "Error: " . $e->getMessage();  
    }  
}  
  
header("Location: ../../../manage_fakultas");  
exit();  
