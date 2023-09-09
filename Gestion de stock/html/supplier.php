<?php 
include_once("connection.php");
// display Supplier from db 
$query = "SELECT * FROM supplier";
$result = mysqli_query($conn, $query);
// insert into db (add client)
if (isset($_POST['AddSup'])) {
    
    
    
    

  $Name = $_POST['smiya'];
  $ladrissa = $_POST['Ladrissa'];
  $City = $_POST['city'];
  $email = $_POST['email'];
  
  

  // Insert the new user into the database
  $stmt = $conn->prepare('INSERT INTO supplier (SUPPLIER_NAME,ADDRESS_supp,city,Email) VALUES (?, ?, ?, ?)');
  $stmt->bind_param('ssss',$Name,$ladrissa,$City,$email);
 
  if ($stmt->execute()) {
    header("Location: supplier.php?insertMessage=success");
    exit();
} else {
    header("Location: supplier.php?insertMessage=error");
    exit();
}
};
// update supplier
// delete supplier
if (isset($_GET['deleteSupp'])) {
  $SuppId = $_GET['deleteSupp'];

  // Delete the user from the database
  $stmt = $conn->prepare('DELETE FROM supplier WHERE ID_SUPPLIER = ?');
  $stmt->bind_param('i', $SuppId );
  if ($stmt->execute()) {
    header("Location: supplier.php?deleteMessage=success");
    exit();
} else {
    header("Location: supplier.php?deleteMessage=error");
    exit();
}
}



?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>supplier</title>
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
          <h4>Page des fournisseurs</h4>
          </div>


          <div class="d-flex">
           <div class="p-3">
              <button class="btn" data-bs-toggle="modal" data-bs-target="#addSup"><i class="bi bi-plus-lg"></i></button>
           </div>
           <div class="p-4">
           <h5 class="mb-0">Ajouter fournisseur</h5>
           </div>
          </div>

          <?php
$insertMessage = isset($_GET['insertMessage']) ? $_GET['insertMessage'] : "";

if ($insertMessage === "success") {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        Fournisseur ajouté avec succès.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>';
} elseif ($insertMessage === "error") {
    echo '<div class="alert alert-danger" role="alert">
        Erreur lors de l\'ajout du fournisseur.
    </div>';
}
?>
<?php
$deleteMessage = isset($_GET['deleteMessage']) ? $_GET['deleteMessage'] : "";

if ($deleteMessage === "success") {
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        Fournisseur supprimé avec succès.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>';
} elseif ($deleteMessage === "error") {
    echo '<div class="alert alert-danger" role="alert">
        Erreur lors de la suppression du fournisseur.
    </div>';
}
?>
 <table class="table mt-4 ">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Nom</th>
      <th scope="col">Adresse</th>
      <th scope="col">Ville</th>
      <th scope="col">Email</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
  <tbody>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<th scope="row">' . $row['ID_SUPPLIER'] . '</th>';
            echo '<td>' . $row['SUPPLIER_NAME'] . '</td>';
            echo '<td>' . $row['ADDRESS_supp'] . '</td>';
            echo '<td>' . $row['city'] . '</td>';
            echo '<td>' . $row['Email'] . '</td>';
            echo '<td>';
            echo '<a href="supplier.php?deleteSupp=' . $row['ID_SUPPLIER'] . '" onclick="return confirm(\'Are you sure you want to delete this Supplier?\')"><button class="btn"><i class="bi bi-trash"></i></button></a>';
            echo '&nbsp;';
            echo '<button class="btn" data-bs-toggle="modal" data-bs-target="#editt" ><i class="bi bi-pencil-square"></i></button>';
            echo '</td>';
            echo '</tr>';
        }
        ?>
    </tbody>
    
  </tbody>
</table>

<!-- add Supplier -->
<div class="modal fade" id="addSup" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title " id="exampleModalLabel">Ajouter fournisseur</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" Action="supplier.php">
          <div class="mb-3">
            <label for="Name" class="form-label">Nom</label>
            <input type="text" class="form-control" id="Name" name="smiya" placeholder="Enter Supplier Name">
          </div>
          <div class="mb-3">
            <label for="address" class="form-label">address</label>
            <input type="text" class="form-control" id="addresss" name="Ladrissa" placeholder="Enter Supplier address">
          </div>
          <div class="mb-3">
            <label for="City" class="form-label">Ville</label>
            <input type="text" class="form-control" id="City" name="city" placeholder="Enter Supplier City Name">
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email"  name="email"  placeholder="Enter Supplier email">
          </div>
           <div class="modal-footer">
             <button type="submit" class="btn" name="AddSup"><i class="bi bi-check-lg"></i></button>
             <button type="button" class="btn" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- end -->
<!-- edit Supp -->
<div class="modal fade" id="editt" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title " id="exampleModalLabel"> Edit Supplier</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" Action="supplier.php">
          <div class="mb-3">
            <label for="Name" class="form-label">Name</label>
            <input type="text" class="form-control" id="Name" name="smiya" placeholder="Enter Supplier Name">
          </div>
          <div class="mb-3">
            <label for="address" class="form-label">address</label>
            <input type="text" class="form-control" id="addresss" name="Ladrissa" placeholder="Enter Supplier address">
          </div>
          <div class="mb-3">
            <label for="City" class="form-label">City</label>
            <input type="text" class="form-control" id="City" name="city" placeholder="Enter Supplier City Name">
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email"  name="email"  placeholder="Enter Supplier email">
          </div>
           <div class="modal-footer">
             <button type="submit" class="btn" name="AddSup"><i class="bi bi-check-lg"></i></button>
             <button type="button" class="btn" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- end -->







        </section>
      </main>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
   <script sre="jsc.js"></script>
</body>
</html>