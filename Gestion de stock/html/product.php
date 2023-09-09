<?php
include_once("connection.php");


// display client from db 
$query = "SELECT * FROM product";
$result = mysqli_query($conn, $query);

// insert into db (add client)
if (isset($_POST['AddProductt'])) {
    
    
    
    

    $ProductName = $_POST['Productname'];
    $Desc = $_POST['Description'];
    $Taille = $_POST['taille'];
   
    
  
    // Insert the new user into the database
    $stmt = $conn->prepare('INSERT INTO product (PRODUCT_NAME,descrp,taille) VALUES (?, ?, ?)');
    $stmt->bind_param('sss',$ProductName,$Desc,$Taille);
   
    if ($stmt->execute()) {
      header("Location: product.php?addMessage=success");
      exit();
  } else {
      header("Location: product.php?addMessage=error");
      exit();
  }
  };

  // modify client 
   

  //delet client 
  if (isset($_GET['deleteProduct'])) {
    $prodId = $_GET['deleteProduct'];

    // Delete the user from the database
    $stmt = $conn->prepare('DELETE FROM product WHERE ID_PRODUCT = ?');
    $stmt->bind_param('i', $prodId );
    if ($stmt->execute()) {
      header("Location: product.php?deleteMessage=success");
      exit();
  } else {
      header("Location: product.php?deleteMessage=error");
      exit();
  }
}





?>










<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
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
          <h4>page des produits</h4>
          </div>


          <div class="d-flex">
           <div class="p-3">
              <button class="btn" data-bs-toggle="modal" data-bs-target="#newProduct"><i class="bi bi-plus-lg"></i></button>
           </div>
           <div class="p-4">
           <h5 class="mb-0">Ajouter produit</h5>
           </div>
          </div>

          <?php
$addMessage = isset($_GET['addMessage']) ? $_GET['addMessage'] : "";

if ($addMessage === "success") {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        Produit ajouté avec succès.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>';
} elseif ($addMessage === "error") {
    echo '<div class="alert alert-danger" role="alert">
        Erreur lors de l\'ajout du produit.
    </div>';
}
?>
<?php
$deleteMessage = isset($_GET['deleteMessage']) ? $_GET['deleteMessage'] : "";

if ($deleteMessage === "success") {
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        Produit supprimé avec succès.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>';
} elseif ($deleteMessage === "error") {
    echo '<div class="alert alert-danger" role="alert">
        Erreur lors de la suppression du produit.
    </div>';
}
?>
 <table class="table mt-4 ">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Nom du produit</th>
      <th scope="col">Description</th>
      <th scope="col">taille</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
  <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<th scope="row">' . $row['ID_PRODUCT'] . '</th>';
            echo '<td>' . $row['PRODUCT_NAME'] . '</td>';
            echo '<td>' . $row['descrp'] . '</td>';
            echo '<td>' . $row['taille'] . '</td>';
            echo '<td>';
            echo '<a href="Product.php?deleteProduct=' . $row['ID_PRODUCT'] . '" onclick="return confirm(\'Are you sure you want to delete this Product?\')"><button class="btn"><i class="bi bi-trash"></i></button></a>';
            echo '&nbsp;';
            echo '<button class="btn"  data-bs-toggle="modal" data-bs-target="#UpdateCli"><i class="bi bi-pencil-square"></i></button>';
            echo '</td>';
            echo '</tr>';
        }
        ?>
  </tbody>
</table>

<!-- add Client -->
<div class="modal fade" id="newProduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Ajouter produit</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="product.php"> 
          <div class="mb-3">
            <label for="Productname" class="form-label">Nom du produit</label>
            <input type="text" class="form-control" id="Productname" name="Productname" placeholder="Enter Product name" required>
          </div>
          <div class="mb-3">
            <label for="Description" class="form-label">Description</label>
            <input type="text" class="form-control" id="Description" name="Description" placeholder="Description" required>
          </div>
          <div class="mb-3">
            <label for="taille" class="form-label">taille</label>
            <input type="text" class="form-control" id="taille" name="taille" placeholder="taille" required>
          </div>
         
        
        <div class="modal-footer">
        <button type="submit" class="btn" name="AddProductt"><i class="bi bi-check-lg"></i></button>
        <button type="button" class="btn" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
      </div>
    </form>
  </div>
</div>

<!-- end -->







        </section>
      </main>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
   <script sre="jsc.js"></script>
</body>
</html>