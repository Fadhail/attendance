<?php
$errors = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email Salah';
    }

    if (empty($password)) {
        $errors['password'] = 'Password tidak boleh kosong';
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        exit();
    }
    if ($role == "admin") {
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE email = :email");
    } elseif ($role == "dosen") {
        $stmt = $pdo->prepare("SELECT * FROM dosen WHERE email = :email");
    }
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();


    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user'] = [
            'id' => $user['Id'],
            'email' => $user['emailAddress'],
            'name' => $user['firstName'],
            'role' => $role,
        ];

        header('Location: home');
        exit();
    } else {
        $errors['login'] = 'Password atau Email Salah';
        $_SESSION['errors'] = $errors;
    }
}
if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
}


function display_error($error, $is_main = false)
{
    global $errors;
    if (isset($errors["{$error}"])) {

        echo '<div class="' . ($is_main ? 'error-main' : 'error') . '">
                  <p>' . $errors["{$error}"] . '</p>
           </div>';
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Login </title>
</head>

<body>
    <div class="flex items-center justify-center min-h-screen bg-gray-100" id="signIn">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Login</h2>

            <?php display_error('login', true); ?>

            <form action="" method="POST">
                <!-- User Role Selection -->
                <div class="mb-6">
                    <select name="role" required
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-700">
                        <option value="">Pilih Pengguna</option>
                        <option value="dosen">Dosen</option>
                        <option value="admin">Admin</option>
                    </select>
                    <?php display_error('role'); ?>
                </div>

                <!-- Email Input -->
                <div class="mb-6">
                    <input type="email" id="email" name="email"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-700"
                        placeholder="Email" required>
                    <?php display_error('email'); ?>
                </div>

                <!-- Password Input -->
                <div class="mb-6">
                    <input type="password" id="password" name="password"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-700"
                        placeholder="Password" required>
                    <?php display_error('password'); ?>
                </div>

                <!-- Submit Button -->
                <div class="mb-4">
                    <input type="submit"
                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer transition duration-300 ease-in-out hover:bg-blue-700"
                        value="Sign In" name="login">
                </div>

                <!-- Forgot Password Link -->
                <div class="text-center mt-4">
                    <a href="#" class="text-blue-600 hover:text-blue-800">Forgot Password?</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>