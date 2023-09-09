<?php 
include_once("connection.php");

function getSupplier() {
    global $conn; // Use the database connection
    
    // Fetch suppliers from the database
    $suppliers = array();
    $query = "SELECT ID_SUPPLIER, SUPPLIER_NAME FROM supplier"; // Replace with your actual table name
    
    $result = $conn->query($query); // Use the correct mysqli query function
    
    while ($row = $result->fetch_assoc()) { // Use fetch_assoc() for mysqli
        $suppliers[] = $row;
    }
    
    return $suppliers;
}
$suppliers = getSupplier();
function getProducts() {
    global $conn; // Use the database connection
    
    // Fetch suppliers from the database
    $products = array();
    $query = "SELECT ID_PRODUCT, taille  FROM product"; // Replace with your actual table name
    
    $result = $conn->query($query); // Use the correct mysqli query function
    
    while ($row = $result->fetch_assoc()) { // Use fetch_assoc() for mysqli
        $products[] = $row;
    }
    
    return $products;
}
$products = getProducts();


// make order 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["makeorder"])) {
    $supplier = $_POST["supplier"];
    // $cmmndname = $_POST["commandname"];
    $products = $_POST["products"];
    $quantities = array();

    // Insert the order into the database
    $insertOrderQuery = "INSERT INTO `command` (ID_SUPPLIER, COMMAND_DATE, STATUS) VALUES (?, NOW(), 'Pending')";
    $stmt = $conn->prepare($insertOrderQuery);
    $stmt->bind_param("i", $supplier);
    $stmt->execute();

    // Get the auto-generated order ID
    $orderID = $stmt->insert_id;

    // Insert order items into the database
    $insertOrderItemQuery = "INSERT INTO `order_item` (ID_COMMAND, ID_PRODUCT, QUANTITY) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insertOrderItemQuery);

    // Loop through the products and insert order items
    foreach ($products as $product) {
        $quantity = $_POST["quantity_" . $product];
        $quantities[$product] = $quantity;

        // Insert order item
        $stmt->bind_param("iii", $orderID, $product, $quantity);
        $stmt->execute();

        // Update the product table with ordered quantity
        $updateProductQuery = "UPDATE product SET QUANTITY = QUANTITY + ? WHERE ID_PRODUCT = ?";
        $updateProductStmt = $conn->prepare($updateProductQuery);
        $updateProductStmt->bind_param("ii", $quantity, $product);
        $updateProductStmt->execute();
    }

    // Close the statement
    $stmt->close();

    // Redirect to a success page or show a success message
    header("Location: order.php");
    exit();
}

$query = "SELECT c.ID_COMMAND, c.COMMAND_NAME, c.COMMAND_DATE, SUM(oi.QUANTITY) AS total_quantity, s.SUPPLIER_NAME
          FROM command c
          JOIN order_item oi ON c.ID_COMMAND = oi.ID_COMMAND
          JOIN supplier s ON c.ID_SUPPLIER = s.ID_SUPPLIER
          WHERE c.STATUS = 'Pending'
          GROUP BY c.ID_COMMAND";

$result = mysqli_query($conn, $query);

if (isset($_GET['deleteorder'])) {
    $commandID = $_GET['deleteorder'];

    // Fetch the order items associated with the command
    $fetchOrderItemsQuery = "SELECT ID_PRODUCT, QUANTITY FROM `order_item` WHERE ID_COMMAND = ?";
    $fetchOrderItemsStmt = $conn->prepare($fetchOrderItemsQuery);
    $fetchOrderItemsStmt->bind_param("i", $commandID);
    $fetchOrderItemsStmt->execute();
    $orderItemsResult = $fetchOrderItemsStmt->get_result();

    // Loop through order items and update product quantities
    while ($orderItem = $orderItemsResult->fetch_assoc()) {
        $productID = $orderItem['ID_PRODUCT'];
        $quantity = $orderItem['QUANTITY'];

        // Update product quantity
        $updateProductQuery = "UPDATE `product` SET QUANTITY = QUANTITY - ? WHERE ID_PRODUCT = ?";
        $updateProductStmt = $conn->prepare($updateProductQuery);
        $updateProductStmt->bind_param("ii", $quantity, $productID);
        $updateProductStmt->execute();
    }

    // Delete associated order items
    $deleteItemsQuery = "DELETE FROM `order_item` WHERE ID_COMMAND = ?";
    $deleteItemsStmt = $conn->prepare($deleteItemsQuery);
    $deleteItemsStmt->bind_param("i", $commandID);
    $deleteItemsStmt->execute();

    // Delete the command itself
    $deleteCommandQuery = "DELETE FROM `command` WHERE ID_COMMAND = ?";
    $deleteCommandStmt = $conn->prepare($deleteCommandQuery);
    $deleteCommandStmt->bind_param("i", $commandID);
    $deleteCommandStmt->execute();

    // Redirect back to the orders page or wherever you want
    header("Location: order.php");
    exit();
}

if (isset($_GET['updatstatu'])) {
    $setstatu = $_GET['updatstatu'];

    // Delete the user from the database
    $stmt = $conn->prepare("UPDATE `command` SET STATUS = 'Validated' WHERE ID_COMMAND = ?");
    $stmt->bind_param('i', $setstatu );
    if ($stmt->execute()) {
       
        header("Location: order.php?update=success");
        exit();
    } else {
        
        header("Location: order.php?update=error");
        
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>order</title>
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
          <h4>Page des achats</h4>
          </div>
 
           
          <div class="d-flex">
           <div class="p-3">
              <button class="btn" data-bs-toggle="modal" data-bs-target="#Order"><i class="bi bi-plus-lg"></i></button>
           </div>
           <div class="p-4">
           <h5 class="mb-0">fair achat</h5>
           </div>
          </div>




           
  <h4  class="d-flex justify-content-center mt-4 mb-4"><i class="bi bi-card-checklist"></i></h4> 
 <?php if (isset($_GET['update'])) {
    $addMessage = ($_GET['update'] === 'success') ? "Achat validé avec succès. Le stock a été actualisé." : "La validation de l'achat a échoué. Veuillez réessayer.";
}
?>
<?php if (isset($addMessage)): ?>
<div class="alert alert-<?php echo ($_GET['update'] === 'success') ? "success" : "danger"; ?> alert-dismissible fade show" role="alert">
    <?php echo $addMessage; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>



  <!-- jnuixs -->
<table class="table mt-4 ">
  <thead>
    <tr>
    <th scope="col">ID</th>
    <!-- <th scope="col">Nom</th> -->
    <th scope="col">Date</th>
    <th scope="col">Quantité totale</th>
    <th scope="col">Nom du fournisseur</th>
    <th scope="col">action</th>
    <th scope="col">acheter</th>
    </tr>
  </thead>
  <tbody>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<th scope="row">' . $row['ID_COMMAND'] . '</th>';
            // echo '<td>' . $row['COMMAND_NAME'] . '</td>';
            echo '<td>' . $row['COMMAND_DATE'] . '</td>';
            echo '<td>' . $row['total_quantity'] . '</td>';
            echo '<td>' . $row['SUPPLIER_NAME'] . '</td>';
            echo '<td>';
            echo '<a href="order.php?deleteorder=' . $row['ID_COMMAND'] . '" onclick="return confirm(\'Are you sure you want to delete this commend?\')"><button class="btn"><i class="bi bi-trash"></i></button></a>';
            echo '&nbsp;';
            echo '<button class="btn"><i class="bi bi-pencil-square"></i></button>';
            echo '</td>';
            echo '<td>';
            echo '<a href="order.php?updatstatu=' . $row['ID_COMMAND'] . '"><button class="btn"><i class="bi bi-check-circle-fill"></i></button></a>';
            echo '</td>';
            echo '</tr>';
        }
        ?>
    </tbody>
</table>

          <!-- make an order  -->
          <div class="modal fade" id="Order" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">fair achat</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="order.php" class="needs-validation" novalidate>
                <!-- <div class="mb-3">
                <label for="commandname" class="form-label">nom:</label>
                <input type="text" class="form-control" id="commandname" name="commandname" placeholder="Enter order name" required>
               </div> -->
                    <div class="mb-3">
                        <label for="supplier" class="form-label">Sélectionnez un fournisseur:</label>
                        <select class="form-select" name="supplier" id="supplier" required>
                            <?php foreach ($suppliers as $Supp): ?>
                                <option value="<?php echo $Supp['ID_SUPPLIER']; ?>"><?php echo $Supp['SUPPLIER_NAME']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sélectionnez des produits :</label><br>
                        <?php foreach ($products as $prod): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="products[]" value="<?php echo $prod['ID_PRODUCT']; ?>" id="product_<?php echo $prod['ID_PRODUCT']; ?>">
                                <label class="form-check-label" for="product_<?php echo $prod['ID_PRODUCT']; ?>"><?php echo $prod['taille']; ?></label>
                                <input type="number" class="form-control" name="quantity_<?php echo $prod['ID_PRODUCT']; ?>" value="0">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="modal-footer">
                    <button type="submit" class="btn" name="makeorder">Ajouter au panier</i></button>
                    <button type="button" class="btn" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


    
      </section>
      
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
      <script src="jsc.js"></script>
</body>
</html>