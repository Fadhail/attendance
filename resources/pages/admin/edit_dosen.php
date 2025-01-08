<?php          
session_start();          
include "../../../database/koneksi.php";
$nidn = $firstName = $lastName = $phone_no = $email = "";        
         
if (isset($_GET['nidn'])) {          
    $nidn = htmlspecialchars(trim($_GET['nidn']));          
  
    try {          
        $query = $pdo->prepare("SELECT * FROM dosen WHERE nidn = :nidn");          
        $query->bindParam(':nidn', $nidn, PDO::PARAM_STR);          
        $query->execute();          
        $dosen = $query->fetch(PDO::FETCH_ASSOC);          
  
        if ($dosen) {          
            $firstName = $dosen['first_name'];          
            $lastName = $dosen['last_name'];          
            $phone_no = $dosen['phone_no'];          
            $email = $dosen['email'];          
        } else {        
            $_SESSION['message'] = "Dosen tidak ditemukan.";        
            header("Location: ../../../manage_dosen");
            exit();      
        }          
    } catch (PDOException $e) {          
        $_SESSION['message'] = "Error loading data dosen: " . $e->getMessage();          
        header("Location: ../../../manage_dosen");    
        exit();      
    }          
}          
  
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["updateDosen"])) {          
    $nidn = htmlspecialchars(trim($_POST["nidn"] ?? ""));          
    $firstName = htmlspecialchars(trim($_POST["first_name"] ?? ""));          
    $lastName = htmlspecialchars(trim($_POST["last_name"] ?? ""));          
    $phone_no = htmlspecialchars(trim($_POST["phone_no"] ?? ""));          
    $email = htmlspecialchars(trim($_POST["email"] ?? ""));          
  
    if (!empty($nidn) && !empty($firstName) && !empty($lastName) && !empty($phone_no) && !empty($email)) {          
        try {          
            $query = $pdo->prepare("UPDATE dosen           
                                    SET first_name = :first_name,     
                                        last_name = :last_name,     
                                        phone_no = :phone_no,     
                                        email = :email     
                                    WHERE nidn = :nidn");          
            $query->bindParam(':nidn', $nidn, PDO::PARAM_STR);          
            $query->bindParam(':first_name', $firstName, PDO::PARAM_STR);          
            $query->bindParam(':last_name', $lastName, PDO::PARAM_STR);          
            $query->bindParam(':phone_no', $phone_no, PDO::PARAM_STR);          
            $query->bindParam(':email', $email, PDO::PARAM_STR);          
  
            $query->execute();          
  
            $_SESSION['message'] = "Dosen berhasil diperbarui.";          
            header("Location: ../../../manage_dosen");          
            exit();          
        } catch (PDOException $e) {          
            $_SESSION['message'] = "Error: " . $e->getMessage();          
            header("Location: ../../../manage_dosen");      
            exit();      
        }          
    } else {          
        $_SESSION['message'] = "Data tidak lengkap. Mohon lengkapi semua field.";          
        header("Location: ../../../manage_dosen");
        exit();      
    }          
}          
?>          
  
<!DOCTYPE html>          
<html lang="en">          
<head>          
    <meta charset="UTF-8">          
    <meta name="viewport" content="width=device-width, initial-scale=1.0">          
    <title>Edit Dosen</title>          
    <link rel="stylesheet" href="resources/assets/css/output.css">          
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />          
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>          
</head>          
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">Edit Dosen</h2>

        <form id="updateDosen" method="POST" action="" class="space-y-4">
            <div>
                <label for="nidn" class="block text-sm font-medium text-gray-700">NIDN</label>
                <input type="text" id="nidn" name="nidn" value="<?= htmlspecialchars($nidn); ?>" required readonly
                    class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($firstName); ?>" required
                    class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($lastName); ?>" required
                    class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label for="phone_no" class="block text-sm font-medium text-gray-700">Phone Number</label>
                <input type="text" id="phone_no" name="phone_no" value="<?= htmlspecialchars($phone_no); ?>" required
                    class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email); ?>" required
                    class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="flex space-x-4">
                <button type="submit" name="updateDosen"
                        class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                    Update Dosen
                </button>
                <a href="../../../manage_dosen"
                   class="w-full bg-gray-400 text-white py-2 px-4 rounded-md hover:bg-gray-500 text-center focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">
                    Back
                </a>
            </div>
        </form>
    </div>
</body>
</html>          
