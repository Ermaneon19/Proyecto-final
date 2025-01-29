<?php
// Incluir la conexión a la base de datos
include('db/db_connection.php');
session_start();

// Si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $interests = $_POST['interests'];

    // Validar que el nombre de usuario y el correo sean únicos
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    $user = $stmt->fetch();

    if ($user) {
        if ($user['username'] == $username) {
            $_SESSION['error_message'] = "El nombre de usuario ya está en uso.";
        } else {
            $_SESSION['error_message'] = "El correo ya está en uso.";
        }
    } else {
        // Validar la longitud de la contraseña
        if (strlen($password) < 8) {
            $_SESSION['error_message'] = "La contraseña debe tener al menos 8 caracteres.";
        } else {
            // Insertar usuario
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT)]);

            // Obtener el ID del usuario recién creado
            $userId = $pdo->lastInsertId();

            // Insertar los intereses
            foreach ($interests as $interestId) {
                // Asociar el interés con el usuario en la tabla `user_interests`
                $stmt = $pdo->prepare("INSERT INTO user_interests (user_id, interest_id) VALUES (?, ?)");
                $stmt->execute([$userId, $interestId]);
            }

            // Establecer mensaje de éxito y redirigir a la página de inicio de sesión
            $_SESSION['success_message'] = "Usuario registrado exitosamente.";
            header('Location: authentication-login.php');
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
  <title>Register</title>
  <link rel="shortcut icon" type="image/png" href="../assets/images/logos/seodashlogo.png" />
  <link rel="stylesheet" href="src/assets/css/styles.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
               
                <p class="text-center">Crear cuenta</p>
                <form method="POST" action="">
                  <div class="mb-3">
                    <label for="exampleInputtext1" class="form-label">Nombre de Usuario</label>
                    <input type="text" class="form-control" id="exampleInputtext1" name="username" required>
                  </div>
                  <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" name="email" required>
                  </div>
                  <div class="mb-4">
                    <label for="exampleInputPassword1" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" name="password" required minlength="8">
                  </div>
                  <div class="mb-3">
                    <label for="interests" class="form-label">Intereses</label>
                    <select class="form-control" id="interests" name="interests[]" multiple required>
                      <option value="3">Arte</option>
                      <option value="1">Deportes</option>
                      <option value="2">Música</option>
                      <option value="4">Tecnología</option>
                    </select>
                  </div>
                  <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger" role="alert">
                      <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                    </div>
                  <?php endif; ?>
                  <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4">Crear cuenta</button>
                </form>
                <div class="d-flex align-items-center justify-content-center">
                  <p class="fs-4 mb-0 fw-bold">Ya tienes una cuenta?</p>
                  <a class="text-primary fw-bold ms-2" href="./authentication-login.php">iniciar sesion</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="errorMessages"></div>

  <script src="src/assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="src/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
  <script src="src/assets/js/script.js"></script>
</body>

</html>


</body>
</html>

