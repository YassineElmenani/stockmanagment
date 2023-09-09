<?php
include_once("connection.php");
$query = "SELECT p.taille, SUM(p.QUANTITY) AS total_quantity
          FROM product p
          GROUP BY p.ID_PRODUCT, p.taille";
$result = $conn->query($query);

$productData = array();
$totalQuantity = 0;

while ($row = $result->fetch_assoc()) {
    $productData[] = $row;
    $totalQuantity += $row['total_quantity'];
}

// Calculate the percentages
$percentages = array();
if ($totalQuantity > 0) {
    foreach ($productData as $product) {
        $percentage = ($product['total_quantity'] / $totalQuantity) * 100;
        $percentages[] = round($percentage, 2); // Round to 2 decimal places
    }
} else {
    // Set all percentages to 0 if there is no total quantity
    foreach ($productData as $product) {
        $percentages[] = 0;
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>in</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/ae360af17e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php 
    include_once("header.php");
 ?>
<section class="w-100 p-5">
    <h2>Bienvenu</h2>
    <p>cette page vous montre les Quantités de produit que nous avons en stock</p>


    <div class="row">
    <?php
    $query = "SELECT taille, QUANTITY
              FROM product";

    $result = mysqli_query($conn, $query);
    while ($row = $result->fetch_assoc()):
        $product_taille = $row['taille'];
        $total_quantity = $row['QUANTITY'];
    ?>
        <div class="col-md-4 mb-4">
            <div class="card text-bg-light">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $product_taille; ?></h5>
                    <p class="card-text">Quantité totale: <?php echo $total_quantity; ?></p>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>







<div id="productStockChart" class=" width: 100%;max-width: 500px;background-color:#f8f9fa;"></div>









<!-- <table class="table">
    <thead>
        <tr>
            <th>Command Name</th>
            <th>Product</th>
            <th>Quantity</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = "SELECT c.COMMAND_NAME, p.taille AS product_taille, SUM(oi.QUANTITY) AS total_quantity
        FROM command c
        JOIN order_item oi ON c.ID_COMMAND = oi.ID_COMMAND
        JOIN product p ON oi.ID_PRODUCT = p.ID_PRODUCT
        WHERE c.STATUS = 'Validated'
        GROUP BY c.ID_COMMAND, p.taille";


        $result = mysqli_query($conn, $query);
        $currentCommand = null; // To keep track of the current command
        while ($row = $result->fetch_assoc()):
            if ($row['COMMAND_NAME'] !== $currentCommand):
                // Display separator for new command
                if ($currentCommand !== null):
        ?>
            <tr>
                <td colspan="3" class="separator"></td>
            </tr>
        <?php
                endif;
                $currentCommand = $row['COMMAND_NAME'];
            endif;
        ?>
            <tr>
                <td><?php echo $row['COMMAND_NAME']; ?></td>
                <td><?php echo $row['product_taille']; ?></td>
                <td><?php echo $row['total_quantity']; ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table> -->





</section>







  
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
      <script src="jsc.js"></script> 
      <script>

const xArray = <?php echo json_encode(array_column($productData, 'taille')); ?>;
   const yArray = <?php echo json_encode($percentages); ?>;

   const layout = {
       title: "Pourcentage de produits en stock",
       paper_bgcolor: '#f8f9fa',  // Set the background color
    //    plot_bgcolor: '#f3f3f3'   // Set the plot area background color
   };

   const data = [{
       labels: xArray,
       values: yArray,
       type: "pie",
       textinfo: "label+percent",
       hoverinfo: "label+percent",
   }];

    Plotly.newPlot("productStockChart", data, layout);
      </script>
</body>
</html>