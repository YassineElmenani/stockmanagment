
<?php 
// session_start();
include_once("connection.php");


// display client from db 
$query = "SELECT * FROM client";
$result = mysqli_query($conn, $query);

// insert into db (add client)
if (isset($_POST['AddClient'])) {
    
    
    $Firstname = $_POST['firstNamee'];
    $Lastname = $_POST['lastNamee'];
    $Address = $_POST['addressss'];
    $phonenumber = $_POST['phonee'];
    
  
    // Insert the new user into the database
    $stmt = $conn->prepare('INSERT INTO client (first_name,Last_name,client_adresse,TELEPHONE_NUMBER) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('sssi',$Firstname, $Lastname,$Address,$phonenumber);
   
    if ($stmt->execute()) {
      $redirectUrl = "client.php?Message=success";
  } else {
      $redirectUrl = "client.php?Message=error";
  }
  header("Location: $redirectUrl");
  exit();
}

  // modify client 
   

  //delet client 
  if (isset($_GET['deleteClient'])) {
    $clientId = $_GET['deleteClient'];

    // Delete the user from the database
    $stmt = $conn->prepare('DELETE FROM client WHERE ID_CLIENT = ?');
    $stmt->bind_param('i', $clientId );
    if ($stmt->execute()) {
      header("Location: client.php?deleteMessage=success");
      exit();
  } else {
      header("Location: client.php?deleteMessage=error");
      exit();
  }
}
  
  
  









?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client</title>
    <!-- CSS only -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/ae360af17e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>




    <?php 
    include_once("header.php");
    ?>
      <section class="w-100 p-3">
          <div class="d-flex justify-content-center">  
          <h4>page des clients</h4>
          </div>


          <div class="d-flex">
           <div class="p-3">
              <button class="btn" data-bs-toggle="modal" data-bs-target="#AddCli"><i class="bi bi-plus-lg"></i></button>
           </div>
           <div class="p-4">
           <h5 class="mb-0">Ajouter client</h5>
           </div>
          </div>

 <?php
$message = isset($_GET['Message']) ? $_GET['Message'] : "";

if ($message === "success") {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        Client ajouté avec succès.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>';
} elseif ($message === "error") {
    echo '<div class="alert alert-danger" role="alert">
        Erreur lors de l\'ajout du client.
    </div>';
}
?>
<?php
$deleteMessage = isset($_GET['deleteMessage']) ? $_GET['deleteMessage'] : "";

if ($deleteMessage === "success") {
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        Client supprimé avec succès.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>';
} elseif ($deleteMessage === "error") {
    echo '<div class="alert alert-danger" role="alert">
        Erreur lors de la suppression du client.
    </div>';
}
?>
<!-- Rest of your HTML content -->
       
  <table class="table mt-4 ">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Prénom</th>
      <th scope="col">Nom de famille</th>
      <th scope="col">Adresse</th>
      <th scope="col">numéro de téléphone</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<th scope="row">' . $row['ID_CLIENT'] . '</th>';
            echo '<td>' . $row['first_name'] . '</td>';
            echo '<td>' . $row['Last_name'] . '</td>';
            echo '<td>' . $row['client_adresse'] . '</td>';
            echo '<td>' . $row['TELEPHONE_NUMBER'] . '</td>';
            echo '<td>';
            echo '<a href="client.php?deleteClient=' . $row['ID_CLIENT'] . '" onclick="return confirm(\'Are you sure you want to delete this client?\')"><button class="btn"><i class="bi bi-trash"></i></button></a>';
            echo '&nbsp;';
            echo '<button class="btn" data-bs-toggle="modal" data-bs-target="#id"><i class="bi bi-pencil-square"></i></button>';
            echo '</td>';
            echo '</tr>';
        }
        ?>
    </tbody>
</table>

<!-- add Client -->
<div class="modal fade" id="AddCli" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Ajouter client</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="client.php"> 
          <div class="mb-3">
            <label for="firstNamee" class="form-label">Prénom</label>
            <input type="text" class="form-control" id="firstNamee" name="firstNamee" placeholder="Enter first name" required>
          </div>
          <div class="mb-3">
            <label for="lastNamee" class="form-label">Nom de famille</label>
            <input type="text" class="form-control" id="lastNamee" name="lastNamee" placeholder="Enter last name" required>
          </div>
          <div class="mb-3">
            <label for="addressss" class="form-label">Address</label>
            <input type="text" class="form-control" id="addressss" name="addressss" placeholder="Enter address" required>
          </div>
          <div class="mb-3">
            <label for="phonee" class="form-label">numéro de téléphone</label>
            <input type="number" class="form-control" id="phonee" name="phonee" placeholder="Enter phone number" required>
          </div>
        
        <div class="modal-footer">
        <button type="submit" class="btn" name="AddClient"><i class="bi bi-check-lg"></i></button>
        <button type="button" class="btn" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
      </div>
    </form>
  </div>
</div>

<!-- end -->

<div class="modal fade" id="id" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Update Client</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="client.php"> 
          <div class="mb-3">
            <label for="firstNamee" class="form-label">First Name</label>
            <input type="text" class="form-control" id="firstNamee" name="firstNamee" placeholder="Enter first name" required>
          </div>
          <div class="mb-3">
            <label for="lastNamee" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="lastNamee" name="lastNamee" placeholder="Enter last name" required>
          </div>
          <div class="mb-3">
            <label for="addressss" class="form-label">Address</label>
            <input type="text" class="form-control" id="addressss" name="addressss" placeholder="Enter address" required>
          </div>
          <div class="mb-3">
            <label for="phonee" class="form-label">Phone Number</label>
            <input type="number" class="form-control" id="phonee" name="phonee" placeholder="Enter phone number" required>
          </div>
        
        <div class="modal-footer">
        <button type="submit" class="btn" name="AddClient"><i class="bi bi-check-lg"></i></button>
        <button type="button" class="btn" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
      </div>
    </form>
  </div>
</div>







        </section>
     
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
   <script src="jsc.js"></script>
</body>
</html>