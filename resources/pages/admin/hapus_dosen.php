<?php  
session_start();  
include "../../../database/koneksi.php";
  
if (isset($_GET['nidn'])) {  
    $nidn = htmlspecialchars(trim($_GET['nidn']));  
  
    try {  
        $query = $pdo->prepare("DELETE FROM dosen WHERE nidn = :nidn");  
        $query->bindParam(':nidn', $nidn, PDO::PARAM_INT);  
        $query->execute();  
  
        $_SESSION['message'] = "Dosen berhasil dihapus.";  
    } catch (PDOException $e) {  
        $_SESSION['message'] = "Error: " . $e->getMessage();  
    }  
}  
  
header("Location: ../../../manage_dosen");  
exit();  

