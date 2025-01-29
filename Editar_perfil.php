<?php
// Incluir la conexión a la base de datos
include('db/db_connection.php');

// Iniciar sesión para verificar si el usuario está logueado
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    echo "Por favor, inicie sesión para editar su perfil.";
    exit();
}

// Obtener los datos actuales del usuario
$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

// Obtener los intereses del usuario
$stmt = $pdo->prepare("SELECT interest_id FROM user_interests WHERE user_id = ?");
$stmt->execute([$userId]);
$userInterests = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Actualizar los datos si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['delete_account'])) {
    $newUsername = $_POST['username'];
    $newEmail = $_POST['email'];
    $newPassword = $_POST['password'];
    $newInterests = $_POST['interests'];

    // Verificar si el nombre de usuario o el correo ya están en uso
    $stmt = $pdo->prepare("SELECT * FROM users WHERE (username = ? OR email = ?) AND id != ?");
    $stmt->execute([$newUsername, $newEmail, $userId]);
    $existingUser = $stmt->fetch();

    if ($existingUser) {
        echo "El nombre de usuario o el correo ya están en uso.";
    } else {
        // Actualizar la contraseña si se proporcionó una nueva
        if (!empty($newPassword) && strlen($newPassword) >= 8) {
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
            $stmt->execute([$newUsername, $newEmail, $newPasswordHash, $userId]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
            $stmt->execute([$newUsername, $newEmail, $userId]);
        }

        // Eliminar los intereses anteriores del usuario
        $stmt = $pdo->prepare("DELETE FROM user_interests WHERE user_id = ?");
        $stmt->execute([$userId]);

        // Insertar los nuevos intereses
        try {
            foreach ($newInterests as $interestId) {
                // Comprobar si el interés ya existe en la tabla `interests`
                $stmt = $pdo->prepare("SELECT id FROM interests WHERE id = ?");
                $stmt->execute([$interestId]);
                $interest = $stmt->fetch();

                if ($interest) {
                    $stmt = $pdo->prepare("INSERT INTO user_interests (user_id, interest_id) VALUES (?, ?)");
                    $stmt->execute([$userId, $interestId]);
                } else {
                    echo "Error: El interés con ID $interestId no existe.";
                    exit();
                }
            }
            echo "Perfil actualizado con éxito.";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Editar Perfil</title>
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
                <p class="text-center">Editar Perfil</p>
                <form method="POST" action="">
                  <div class="mb-3">
                    <label for="exampleInputtext1" class="form-label">Nombre de Usuario</label>
                    <input type="text" class="form-control" id="exampleInputtext1" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                  </div>
                  <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                  </div>
                  <div class="mb-4">
                    <label for="exampleInputPassword1" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" name="password" minlength="8">
                  </div>
                  <div class="mb-3">
                    <label for="interests" class="form-label">Intereses</label>
                    <select class="form-control" id="interests" name="interests[]" multiple required>
                      <option value="3" <?php echo in_array(3, $userInterests) ? 'selected' : ''; ?>>Arte</option>
                      <option value="1" <?php echo in_array(1, $userInterests) ? 'selected' : ''; ?>>Deportes</option>
                      <option value="2" <?php echo in_array(2, $userInterests) ? 'selected' : ''; ?>>Música</option>
                      <option value="4" <?php echo in_array(4, $userInterests) ? 'selected' : ''; ?>>Tecnología</option>
                    </select>
                  </div>
                  <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4">Actualizar Perfil</button>
                </form>
                <form method="POST" action="deleted_account.php">
                  <button type="submit" class="btn btn-danger w-100 py-8 fs-4 mb-4" name="delete_account">Borrar Cuenta</button>
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
  <script src="src/assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="src/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

</html>