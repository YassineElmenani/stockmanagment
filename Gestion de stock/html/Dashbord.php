<?php 
session_start();
include_once("connection.php");
// Query to get the number of clients
$clientsQuery = "SELECT COUNT(*) AS count FROM client";
$clientsResult = $conn->query($clientsQuery);
$clientsCount = ($clientsResult->num_rows > 0) ? $clientsResult->fetch_assoc()['count'] : 0;

// Query to get the number of delivery persons
$deliveryPersonsQuery = "SELECT COUNT(*) AS count FROM delivery_person";
$deliveryPersonsResult = $conn->query($deliveryPersonsQuery);
$deliveryPersonsCount = ($deliveryPersonsResult->num_rows > 0) ? $deliveryPersonsResult->fetch_assoc()['count'] : 0;

// Query to get the number of suppliers
$suppliersQuery = "SELECT COUNT(*) AS count FROM supplier";
$suppliersResult = $conn->query($suppliersQuery);
$suppliersCount = ($suppliersResult->num_rows > 0) ? $suppliersResult->fetch_assoc()['count'] : 0;

$SQuery = "SELECT COUNT(*) AS count FROM sale";
$SResult = $conn->query($SQuery);
$SCount = ($SResult->num_rows > 0) ? $SResult->fetch_assoc()['count'] : 0;

$CQuery = "SELECT COUNT(*) AS count FROM COMMAND where  STATUS = 'Validated'";
$CResult = $conn->query($CQuery);
$CCount = ($CResult->num_rows > 0) ? $CResult->fetch_assoc()['count'] : 0;

$PQuery = "SELECT COUNT(*) AS count FROM product";
$PResult = $conn->query($PQuery);
$PCount = ($PResult->num_rows > 0) ? $PResult->fetch_assoc()['count'] : 0;


// Check if the user is logged in
if (!isset( $_SESSION['firstname'] )&&!isset($_SESSION['email'])&&!isset($_SESSION['lastname'])){
    header('Location: login.php');
    exit();
}


$query = "SELECT p.taille, COUNT(si.ID_PRODUCT) AS product_count
          FROM product p
          LEFT JOIN sale_item si ON p.ID_PRODUCT = si.ID_PRODUCT
          GROUP BY p.ID_PRODUCT, p.taille";
$result = $conn->query($query);

$productData = array();
$totalSales = 0;

while ($row = $result->fetch_assoc()) {
    $productData[] = $row;
    $totalSales += $row['product_count'];
}

// Calculate the percentages
$percentages = array();
foreach ($productData as $product) {
    $percentage = ($product['product_count'] / $totalSales) * 100;
    $percentages[] = round($percentage, 2); // Round to 2 decimal places
}
?>







<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/ae360af17e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- ======== Main wrapper for dashboard =========== -->
    

<!-- ========= Main navbar section of dashboard ======= -->

<!-- <nav class="navbar navbar-expand px-3 border-bottom">
  <button class="btn" onclick="toggleSidebar()" data-bs-target="#sidebar" data-bs-toggle="collapse">
    <span class="navbar-toggler-icon"></span>
  </button>
  
</nav> -->

<nav class="navbar navbar-expand-lg sticky-top top-0">
  <div class="container-fluid">
    
     <button class="btn" onclick="toggleSidebar()" data-bs-target="#sidebar" data-bs-toggle="collapse">
    <span class="navbar-toggler-icon"></span>
     </button>
  <div class="sidebar-logo">
    <a href="Dashbord.php">STE TAMANAR-GAZ</a>
  </div>
    <div class="search-center">
      <form class="d-flex">
        <div class="input-group">
          <input type="search" class="form-control" placeholder="Search" aria-label="Search..." aria-describedby="button-addon2">
          <button class="btn btn-outline-secondary" type="button" id="button-addon2"><i class="bi bi-search"></i></button>
        </div>
      </form>
    </div>
    <div class="dropdown-right">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-fill"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <?php if (isset( $_SESSION['firstname'] )&&isset($_SESSION['lastname'])) : ?>
                    <li>
                        <div class="d-flex align-items-center p-2">
                            <!-- You can add the user's profile image here -->
                            <img src="https://cdn1.vectorstock.com/i/1000x1000/23/70/man-avatar-icon-flat-vector-19152370.jpg" alt="User Image" class="rounded-circle me-2" width="40">
                            <div>
                                <strong><?php echo  $_SESSION['firstname'] ; ?><?php echo '&nbsp';?><?php echo $_SESSION['lastname'];?></strong>
                                <br>
                                <?php echo $_SESSION['email']; ?>
                            </div>
                        </div>
                    </li>
                    
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="logout.php">Log Out</a></li>
                
                <?php endif; ?>
            </ul>
        </li>
    </ul>
</div>

</nav>

<main class="wrapper">
      
        <!-- =========== Sidebar for admin dashboard =========== -->
        
        <aside id="sidebar" class="show">

            <!-- ======== Content For Sidebar ========-->
            <div class="h-100">
                <!-- ======= Navigation links for sidebar ======== -->
                <ul class="sidebar-nav">
                    <li class="sidebar-header" >
                      MENU 
                    </li>
                    <li class="sidebar-item">
                      <a href="#" class="sidebar-link collapsed" data-bs-target="#pages" data-bs-toggle="collapse" aria-expanded="false">
                      achat/vent
                      </a>
                      <ul  id="pages" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                          <a href="order.php" class="sidebar-link">Achats</a>
                        </li>
                        <li class="sidebar-item">
                          <a href="sale.php" class="sidebar-link">Vents</a>
                        </li>
                      </ul>
                    </li>
                    <li class="sidebar-item">
                    <a href="in.php"  class="sidebar-link collapsed"> 
                      Stock
                    </a>
                    </li>
                    <li class="sidebar-header" >
                      maintenance 
                    </li>
                    <li>
                    <a href="client.php" class="sidebar-link collapsed" >
                      Clients
                    </a>
                    </li>
                    <li>
                    <a href="supplier.php" class="sidebar-link collapsed">
                     Fournisseurs
                    </a>
                    </li>
                    <li>
                      <a href="DELIVERYPERSON.php" class="sidebar-link collapsed" >
                      Livreurs
                      </a>
                    </li>
                    <li>
                      <a href="product.php" class="sidebar-link collapsed" >
                    Produits
                      </a>
                    </li>
                  
                </ul>
            </div>
        </aside>
        <section class="w-100 p-5">
        <h4 class=" mt-4 mb-4">Bienvenu, <strong> <?php echo  $_SESSION['firstname']  ; ?><?php echo '&nbsp';?><?php echo $_SESSION['lastname'];?></strong>!</h4>
        <p class="date  mt-4 mb-4"><?php echo date('F j, Y'); ?></p>
<div class="row">
    <div class="col-md-4">
        <div class="card text-bg-light custom-card">
            <div class="card-body">
                <h5 class="card-title">Nombre de clients</h5>
                <p class="card-text"><?php echo $clientsCount; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-light custom-card">
            <div class="card-body">
                <h5 class="card-title">Nombre de livreurs</h5>
                <p class="card-text"><?php echo $deliveryPersonsCount; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-light custom-card">
            <div class="card-body">
                <h5 class="card-title">Nombre de fournisseurs</h5>
                <p class="card-text"><?php echo $suppliersCount; ?></p>
            </div>
        </div>
    </div>  
</div>
<div class="row p-3">
    <div class="col-md-4">
        <div class="card text-bg-light custom-card">
            <div class="card-body">
                <h5 class="card-title">Nombre de Ventes</h5>
                <p class="card-text"><?php echo $SCount; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-light custom-card">
            <div class="card-body">
                <h5 class="card-title">Nombre d'achats</h5>
                <p class="card-text"><?php echo $CCount; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-light custom-card">
            <div class="card-body">
                <h5 class="card-title">Nombre de produits</h5>
                <p class="card-text"><?php echo $PCount; ?></p>
            </div>
        </div>
    </div>
</div>

<div class="sales-boxes p-5">
    <div class="recent-sales box">
        <div class=" title d-flex justify-content-center">Ventes récentes</div>
        <div class="sales-details">
            <ul class="details">
                <li class="topic">Date</li>
                <?php
                // Retrieve latest sales data with concatenated full name and delivery person
                $query = "SELECT s.*, CONCAT(c.first_name, ' ', c.Last_name) AS fullname, CONCAT(dp.DELIVERYPERSON_First_Name, ' ', dp.DELIVERYPERSON_Last_Name) AS delivery_person FROM sale s
                          JOIN client c ON s.ID_CLIENT = c.ID_CLIENT
                          JOIN delivery_person dp ON s.ID_DELIVERY_PERSON = dp.ID_DELIVERY_PERSON
                          ORDER BY s.SALE_DATE DESC
                          LIMIT 5"; // Fetch the latest 5 sales
                $result = mysqli_query($conn, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<li><h5>' . $row['SALE_DATE'] . '</h5></li>';
                }
                ?>
            </ul>
            <ul class="details">
                <li class="topic">Client</li>
                <?php
                mysqli_data_seek($result, 0); // Reset result pointer

                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<li><h5>' . $row['fullname'] . '</h5></li>';
                }
                ?>
            </ul>
            <ul class="details">
                <li class="topic">Personne de livraison</li>
                <?php
                mysqli_data_seek($result, 0); // Reset result pointer

                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<li><h5>' . $row['delivery_person'] . '</h5></li>';
                }
                ?>
            </ul>
        </div>
    </div>
    <div id="productSalesChart" class="chart-container"></div>

</div>






        
         
        </section>
      </main>
        <!-- ========= Main section of dashboard ======= -->
    <!-- <?php
      // include_once('footer.php');
     ?> -->
    
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
   <script sre="jsc.js"></script>
   <script>
   const xArray = <?php echo json_encode(array_column($productData, 'taille')); ?>;
    const yArray = <?php echo json_encode($percentages); ?>;

    const layout = {
    title: "Pourcentage de produits vendus",
    paper_bgcolor: '#f8f9fa',  // Set the background color
    plot_bgcolor: '#f3f3f3'   // Set the plot area background color
};

    const data = [{
        labels: xArray,
        values: yArray,
        type: "pie",
        textinfo: "label+percent",
        hoverinfo: "label+percent",
    }];

    Plotly.newPlot("productSalesChart", data, layout);
   </script>

</body>

</html>