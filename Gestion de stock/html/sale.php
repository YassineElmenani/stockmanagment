<?php 
include_once("connection.php");

function getClient() {
    global $conn; // Use the database connection
    
    // Fetch suppliers from the database
    $Clients = array();
    $query = "SELECT ID_CLIENT , CONCAT( first_name, Last_name ) as fullnameC FROM client"; // Replace with your actual table name
    
    $result = $conn->query($query); // Use the correct mysqli query function
    
    while ($row = $result->fetch_assoc()) { // Use fetch_assoc() for mysqli
        $Clients[] = $row;
    }
    
    return $Clients;
}
$Clients = getClient();


function getDlp() {
    global $conn; // Use the database connection
    
    // Fetch suppliers from the database
    $Levreurs = array();
    $query = "SELECT ID_DELIVERY_PERSON , CONCAT( DELIVERYPERSON_First_Name, ' ',DELIVERYPERSON_Last_Name ) as fullname FROM delivery_person"; // Replace with your actual table name
    
    $result = $conn->query($query); // Use the correct mysqli query function
    
    while ($row = $result->fetch_assoc()) { // Use fetch_assoc() for mysqli
        $Levreurs[] = $row;
    }
    
    return $Levreurs;
}
$Levreurs =getDlp();




function getProds() {
    global $conn; // Use the database connection
    
    // Fetch suppliers from the database
    $prods = array();
    $query = "SELECT ID_PRODUCT, taille  FROM product"; // Replace with your actual table name
    
    $result = $conn->query($query); // Use the correct mysqli query function
    
    while ($row = $result->fetch_assoc()) { // Use fetch_assoc() for mysqli
        $prods[] = $row;
    }
    
    return $prods;
}
$prods = getProds();
function reduceStock($id, $q){
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM product WHERE ID_PRODUCT = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    
    if($product['QUANTITY'] < $q){
        return false;
    }
    
    $newStock = $product['QUANTITY'] - $q;
    $stmt = $conn->prepare("UPDATE product SET QUANTITY = ? WHERE ID_PRODUCT = ?");
    $stmt->bind_param("ii", $newStock, $id);
    $stmt->execute();
    
    return true;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["makesale"])) {
  
    $client = $_POST["Client"];
    $deliveryPerson = $_POST["delevryp"];
    $products = $_POST["prods"];
    $insufficientStock=false;
    $saleEffect = true; // Assume the sale will be successful
    $insertedSaleID = null; // To store the ID of the inserted sale

    // Loop through the selected products
    foreach ($products as $product) {
        $quantity = $_POST["quantitys_" . $product];

        if (!reduceStock($product, $quantity)) {
            $insufficientStock=true;
            $saleEffect = false; // If reducing stock fails for any product, set this to false
        }
    }

    if ($saleEffect) {
        // Insert the sale into the database
        $insertSaleQuery = "INSERT INTO `sale` (ID_CLIENT, ID_DELIVERY_PERSON, SALE_DATE) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($insertSaleQuery);
        $stmt->bind_param("ii",$client, $deliveryPerson);
        if ($stmt->execute()) {
            $insertedSaleID = $stmt->insert_id; // Get the ID of the inserted sale
        } else {
            echo '<div class="alert alert-danger" role="alert">Error occurred while inserting sale into the database.</div>';
        }
        $stmt->close();
    }

    // Insert sale items into the database
    if ($insertedSaleID !== null) {
        foreach ($products as $product) {
            $quantity = $_POST["quantitys_" . $product];
            $insertSaleItemQuery = "INSERT INTO `sale_item` (ID_SALE, ID_PRODUCT, QUANTITY) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insertSaleItemQuery);
            $stmt->bind_param("iii", $insertedSaleID, $product, $quantity);
            $stmt->execute();
            $stmt->close();
        }
    }
}





?>









<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales</title>
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
          <h4>Page de vente</h4>
          </div>
 
           
          <div class="d-flex justify-content-center">
           <div class="p-4">
           <h3 class="mb-0">faire de nouvelles ventes</h5>
           </div>
          </div>




<div class="container p-4" style="background: white;  border-radius: 15px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
<?php
    if(isset($_POST["makesale"])) {
        if($saleEffect && $insertedSaleID !== null) {
            echo '<div class="alert alert-success" role="alert">La vente est réussie et le stock a été mis à jour.</div>';
        } elseif (!$saleEffect && $insufficientStock) {
            echo '<div class="alert alert-danger" role="alert">Échec de la vente en raison dun stock insuffisant pour certains produits.</div>';
        } elseif (!$saleEffect && !$insufficientStock) {
            echo '<div class="alert alert-danger" role="alert">La vente a échoué car aucun produit sélectionné navait suffisamment de stock.</div>';
        }
    }
    ?>

    <form method="post" action="sale.php">
        <div class="mb-3">
            <label for="client" class="form-label">Sélectionnez un Client:</label>
            <select class="form-select" name="Client" id="Client" required>
                <?php foreach ($Clients as $Cl): ?>
                    <option value="<?php echo $Cl['ID_CLIENT']; ?>"><?php echo $Cl['fullnameC']; ?></option>
                <?php endforeach; ?>   
            </select>
        </div>
        <div class="mb-3">
            <label for="delevryp" class="form-label">Sélectionnez un livreur:</label>
            <select class="form-select" name="delevryp" id="delevryp" required>
                <?php foreach ($Levreurs as $Lv): ?>
                    <option value="<?php echo $Lv['ID_DELIVERY_PERSON']; ?>"><?php echo $Lv['fullname']; ?></option>
                <?php endforeach; ?>  
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Sélectionnez des produits :</label>
            <?php foreach ($prods as $prod): ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="prods[]" value="<?php echo $prod['ID_PRODUCT']; ?>" id="product_<?php echo $prod['ID_PRODUCT']; ?>">
                    <label class="form-check-label" for="product_<?php echo $prod['ID_PRODUCT']; ?>"><?php echo $prod['taille']; ?></label>
                    <input type="number" class="form-control" name="quantitys_<?php echo $prod['ID_PRODUCT']; ?>" value="0">
                </div>
            <?php endforeach; ?>
        </div>
        <div class="d-flex justify-content-center">
            <button type="submit" class="btn" name="makesale"><i class="bi bi-check2"></i>Envoyer</button>
        </div>
    </form>
</div>

         
  
 


    
      </section>
      
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
      <script src="jsc.js"></script>
</body>
</html>