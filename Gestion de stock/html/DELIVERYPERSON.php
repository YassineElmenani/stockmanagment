<?php 

include_once("connection.php");


// display client from db 
$query = "SELECT * FROM delivery_person";
$result = mysqli_query($conn, $query);



 // Start the session



// insert into db (add client)
// if (isset($_POST['Addlvreur'])) {
//   $First = $_POST['frstname'];
//   $Last = $_POST['lstname'];
//   $Address = $_POST['address'];
//   $phone = $_POST['number'];

//   $stmt = $conn->prepare('INSERT INTO delivery_person (DELIVERYPERSON_First_Name,DELIVERYPERSON_Last_Name,DELIVERYPERSON_Adresse,TELEPHONE_NUMBER) VALUES (?, ?, ?, ?)');
//   $stmt->bind_param('sssi', $First, $Last, $Address, $phone);

//   if ($stmt->execute()) {
//     header("Location: DELIVERYPERSON.php?success=true");
//     $add = "Livreur ajouté avec succès.";
//     exit();
// } else {
//     header("Location: DELIVERYPERSON.php?");
//     exit();
// }
// }


  // modify client 
   

  //delet client 
//   if (isset($_GET['deletelevreur'])) {
//     $lvrId = $_GET['deletelevreur'];

//     // Delete the user from the database
//     $stmt = $conn->prepare('DELETE FROM delivery_person WHERE ID_DELIVERY_PERSON  = ?');
//     $stmt->bind_param('i', $lvrId );
//     if ($stmt->execute()) {
       
//         header("Location: DELIVERYPERSON.php");
//         exit();
//     } else {
        
//         header("Location: DELIVERYPERSON.php");
//         exit();
//     }
// }
// Edit a specific delivery person




error_reporting(E_ALL);
ini_set('display_errors', 1);
?>



















<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DELIVERYPERSON</title>
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
          <h4>Page des livrueurs</h4>
          </div>


          <div class="d-flex">
           <div class="p-3">
              <button class="btn" data-bs-toggle="modal" data-bs-target="#addDlv"><i class="bi bi-plus-lg" ></i></button>
           </div>
           <div class="p-4">
           <h5 class="mb-0">Ajouter Livreur</h5>
           </div>
          </div>
          <?php
if (isset($_GET['addMessage'])) {
    $addMessage = ($_GET['addMessage'] === 'success') ? "Livreur ajouté avec succès." : "Erreur lors de l'ajout du livreur.";
}
?>

<!-- Place this where you want the alert to be displayed -->
<?php if (isset($addMessage)): ?>
<div class="alert alert-<?php echo ($_GET['addMessage'] === 'success') ? "success" : "danger"; ?> alert-dismissible fade show" role="alert">
    <?php echo $addMessage; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>
<?php
if (isset($_GET['deleteMessage'])) {
    $deleteMessage = ($_GET['deleteMessage'] === 'success') ? "Livreur supprimé avec succès." : "Erreur lors de la suppression du livreur.";
}
?>

<!-- Place this where you want the alert to be displayed -->
<?php if (isset($deleteMessage)): ?>
<div class="alert alert-<?php echo ($_GET['deleteMessage'] === 'success') ? "warning" : "danger"; ?> alert-dismissible fade show" role="alert">
    <?php echo $deleteMessage; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>  

<!-- Place this where you want the alert to be displayed -->
<?php if (isset($_GET['updateMessage'])): ?>
<div class="alert alert-<?php echo ($_GET['updateMessage'] === 'success') ? "success" : "danger"; ?> alert-dismissible fade show" role="alert">
    <?php echo ($_GET['updateMessage'] === 'success') ? "Livreur mis à jour avec succès." : "Erreur lors de la mise à jour du livreur."; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

          

  
 <table class="table mt-4">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Prenom</th>
      <th scope="col">Nom de la famille</th>
      <th scope="col">Adresse</th>
      <th scope="col">numéro de téléphone</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
  <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<th scope="row">' . $row['ID_DELIVERY_PERSON'] . '</th>';
            echo '<td>' . $row['DELIVERYPERSON_First_Name'] . '</td>';
            echo '<td>' . $row['DELIVERYPERSON_Last_Name'] . '</td>';
            echo '<td>' . $row['DELIVERYPERSON_Adresse'] . '</td>';
            echo '<td>' . $row['TELEPHONE_NUMBER'] . '</td>';
            echo '<td>';
            echo '<a href="delete_delivery_person.php?deletelevreur=' . $row['ID_DELIVERY_PERSON'] . '" onclick="return confirm(\'Are you sure you want to delete this DELIVERY PERSON? \')"><button class="btn"><i class="bi bi-trash"></i></button></a>';
            echo '&nbsp;';
            
            echo '<a href="editLEVR.php?editlevreur=' . $row['ID_DELIVERY_PERSON'] . '" data-bs-toggle="modal" data-bs-target="#edit" ><button class="btn edit"><i class="bi bi-pencil-square"></i></button></a>'; 
            
            echo '</td>';
            echo '</tr>'; 
          }
            ?>   
  </tbody>
</table>

<!-- add DELIVERYPERSON -->
<div class="modal fade" id="addDlv" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Ajouter Livreur</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form method="post" action="add_delivery_person.php">
        
          <div class="mb-3">
            <label for="firstName" class="form-label">Prenom</label>
            <input type="text" class="form-control" id="firstName" name="frstname" placeholder="Enter first name">
          </div>
          <div class="mb-3">
            <label for="lastName" class="form-label">Nom de la famille</label>
            <input type="text" class="form-control" id="lastName" name="lstname" placeholder="Enter last name">
          </div>
          <div class="mb-3">
            <label for="address" class="form-label">address</label>
            <input type="text" class="form-control" id="address" name="address" placeholder="Enter address">
          </div>
          <div class="mb-3">
            <label for="phone" class="form-label">numéro de téléphone</label>
            <input type="number" class="form-control" id="phone" name="number" placeholder="Enter phone number">
          </div>
        <div class="modal-footer">
         <button type="submit" class="btn" name="Addlvreur"><i class="bi bi-check-lg"></i></button>
         <button type="button" class="btn" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
         </div>

        </form>
      </div>
     
    </div>
  </div>
</div>
<!-- end -->

<!-- ... (other HTML code) ... -->
<?php
while ($rowdata = mysqli_fetch_assoc($result)) {
    // Modal for each row
    echo '<div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
    echo '<div class="modal-dialog modal-dialog-centered">';
    echo '<div class="modal-content">';
    echo '<div class="modal-header">';
    echo '<h4 class="modal-title" id="exampleModalLabel">UPDATE DELIVERY PERSON</h4>';
    echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
    echo '</div>';
    echo '<div class="modal-body">';
    echo '<form method="post" action="editLEVR.php">';
    
    // Populate modal fields with the corresponding data
    echo '<div class="mb-3">';
    echo '<label for="firstName" class="form-label">First Name</label>';
    echo '<input type="text" class="form-control" id="firstName" name="first" value="' . $row['DELIVERYPERSON_First_Name'] . '">';
    echo '</div>';
    
    echo '<div class="mb-3">';
    echo '<label for="lastName" class="form-label">Last Name</label>';
    echo '<input type="text" class="form-control" id="lastName" name="last" value="' . $row['DELIVERYPERSON_Last_Name'] . '">';
    echo '</div>';
    
    echo '<div class="mb-3">';
    echo '<label for="address" class="form-label">Address</label>';
    echo '<input type="text" class="form-control" id="address" name="addres" value="' . $row['DELIVERYPERSON_Adresse'] . '">';
    echo '</div>';
    
    echo '<div class="mb-3">';
    echo '<label for="phone" class="form-label">Phone Number</label>';
    echo '<input type="number" class="form-control" id="phone" name="nimiro" value="' . $row['TELEPHONE_NUMBER'] . '">';
    echo '</div>';
    
    echo '<div class="modal-footer">';
    echo '<button type="submit" class="btn" name="UpdateDeliveryPerson"><i class="bi bi-check-lg"></i></button>';
    echo '<button type="button" class="btn" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>';
    echo '</div>';
    
    echo '</form>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';

}
?>


<!-- ... (other HTML code) ... -->




        </section>
      </main>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
      <script src="jsc.js"></script>
</body>
</html>