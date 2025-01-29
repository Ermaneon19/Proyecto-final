<?php
// Incluir la conexión a la base de datos
include('db/db_connection.php');

// Iniciar sesión para verificar si el usuario está logueado
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    echo "Por favor, inicie sesión para eliminar su cuenta.";
    exit();
}

// Obtener los datos del usuario
$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

// Procesar la eliminación de la cuenta
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['password'])) {
        $password = $_POST['password'];

        // Verificar que la contraseña proporcionada sea correcta
        if (password_verify($password, $user['password'])) {
            // Eliminar los intereses del usuario
            $stmt = $pdo->prepare("DELETE FROM user_interests WHERE user_id = ?");
            $stmt->execute([$userId]);

            // Eliminar al usuario de la base de datos
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$userId]);

            // Destruir la sesión
            session_destroy();
            // Establecer mensaje de éxito y redirigir a la página de inicio de sesión
            session_start();
            $_SESSION['success_message'] = "Cuenta eliminada exitosamente.";
            header("Location: authentication-login.php");
            exit();
        } else {
            echo "La contraseña es incorrecta.";
        }
    } else {
        echo "Por favor, ingrese su contraseña.";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Eliminar cuenta</title>
  <link rel="shortcut icon" type="image/png" href="../assets/images/logos/seodashlogo.png" />
  <link rel="stylesheet" href="src/assets/css/styles.min.css" />
</head>
<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <p class="text-center">Eliminar cuenta</p>
                <form method="POST" action="">
                  <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" name="password" required>
                  </div>
                  <button type="submit" class="btn btn-danger w-100 py-8 fs-4 mb-4">Eliminar cuenta</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="src/assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="src/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>
</html>