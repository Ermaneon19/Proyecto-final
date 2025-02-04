<?php
session_start();
include('db/db_connection.php');

$errorMessage = ""; // Definir la variable por defecto

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consultar si el usuario existe
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Verificar que el usuario exista y la contraseña sea correcta
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['login_success_message'] = "Inicio de sesión exitoso.";

        if (isset($_POST['ajax']) && $_POST['ajax'] == true) {
            echo json_encode(['success' => true]);
            exit();
        } else {
            header("Location: index.php");
            exit();
        }
    } else {
        $errorMessage = "Usuario o contraseña incorrectos.";
        if (isset($_POST['ajax']) && $_POST['ajax'] == true) {
            echo json_encode(['success' => false, 'message' => $errorMessage, 'username' => $username]);
            exit();
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Iniciar sesion</title>
  <link rel="shortcut icon" type="image/png" href="../assets/images/logos/seodashlogo.png" />
  <link rel="stylesheet" href="src/assets/css/styles.min.css" />
  <script src="src/assets/libs/jquery/dist/jquery.min.js"></script> <!-- Asegúrate de que jQuery se cargue primero -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="src/assets/js/login.js"></script>
</head>

<body>
  <!--  Body Wrapper -->
  <div id="errorMessage" class="alert alert-danger" role="alert" style="display: none;"></div>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0"> 
              <div class="card-body">
                
                <p class="text-center">Iniciar Sesion</p>
                <?php if (!empty($errorMessage)): ?>
                  <div class="alert alert-danger" role="alert">
                    <?php echo $errorMessage; ?>
                  </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['success_message'])): ?>
                  <div class="alert alert-success" role="alert">
                    <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                  </div>
                <?php endif; ?>
                <form id="loginForm" method="POST" action="">
                    <div class="mb-3">
                <label for="username" class="form-label">Usuario</label>
                <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-4">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required minlength="8">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4">Iniciar Sesion</button>
                    <div class="d-flex align-items-center justify-content-center">
                <p class="fs-4 mb-0 fw-bold">Eres nuevo en nuestro sistema?</p>
                <a class="text-primary fw-bold ms-2" href="./authentication-register.php">Crear una cuenta</a>
                    </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="src/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
  <script src="src/assets/js/script.js"></script>
</body>

</html>

