<?php
session_start();
include_once("connection.php");
$error = "";
// Check if the login form was submitted
if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Replace this with your authentication logic using a database query
  $query = "SELECT * FROM admin WHERE email = '$email' AND motpasse = '$password'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) === 1) {
      // If a row is found, the credentials are correct
      $adminData = mysqli_fetch_assoc($result);
      $_SESSION['firstname'] = $adminData['Nom'];
      $_SESSION['lastname'] = $adminData['Prenom'];
      $_SESSION['email'] = $adminData['email'];
      header('Location: Dashbord.php'); // Redirect to the dashboard or another page
      exit();
  } else {
      $error = "Invalid email or password. Please try again.";
  }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/ae360af17e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style >
    .divider:after,
.divider:before {
content: "";
flex: 1;
height: 1px;
background:#749BC2;
}
.h-custom {
height: calc(100% - 73px);
}
@media (max-width: 450px) {
.h-custom {
height: 100%;
}
}
</style>
</head>
<body>
<section class="vh-100">
  <div class="container-fluid h-custom">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-md-9 col-lg-6 col-xl-5">
        <img src="https://img.freepik.com/vecteurs-libre/illustration-concept-mot-passe-oublie_114360-1123.jpg?w=740&t=st=1692548614~exp=1692549214~hmac=1a57916a36f926337c10c4a00ed23f9ebbd04ccade26d572c6fd42407afc3049"
          class="img-fluid" alt="Sample image">
      </div>
      <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
        <form method="post" action="login.php">

          <div class="divider d-flex align-items-center my-4">
            <p class="text-center fw-bold mx-3 mb-0">se connecter</p>
          </div>

          <!-- Email input -->
          <div class="form-outline mb-4">
          <label class="form-label" for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control form-control-lg"
              placeholder="Entrez une adresse mail valide" />
          </div>

          <!-- Password input -->
          <div class="form-outline mb-3">
          <label class="form-label" for="password">Mot de passe</label>
            <input type="password" id="password" name="password" class="form-control form-control-lg"
              placeholder="Entrer le mot de passe" />
          </div>

          <div class="d-flex justify-content-between align-items-center">
          <div class="text-center text-lg-start mt-4 pt-2">
            <button type="submit" name="login" class="btn"
              style="background-color: #67bae1; color: white;">se connecter</button>
          </div>
          <?php if (!empty($error)) : ?>
            <div class="alert alert-danger mt-3" role="alert">
                <?php echo $error; ?>
            </div>
         <?php endif; ?>
        </form>
      </div>
    </div>
  </div>
</section>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
      <script src="jsc.js"></script>
</body>
</html>