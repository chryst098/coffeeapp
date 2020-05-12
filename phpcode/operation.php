<?php
$host = 'coffeeapp.mysql.database.azure.com';
$username = 'coffeeapp@coffeeapp';
$password = 'Deepak@123';
$db_name = 'coffee';

//Establishes the connection
$conn = mysqli_init();
mysqli_real_connect($conn, $host, $username, $password, $db_name, 3306);
if (mysqli_connect_errno($conn)) {
die('Failed to connect to MySQL: '.mysqli_connect_error());
}

if($_REQUEST['action'] == 'insert') {
    //Create an Insert prepared statement and run it
    $product_name = 'BrandNewProduct';
    $product_color = 'Blue';
    $product_price = 15.5;
    $result = mysqli_query($conn, "INSERT INTO Products (ProductName, Color, Price) VALUES ('$product_name', '$product_color', '$product_price')");
    print_r($result);
}

if($_REQUEST['action'] == 'read') {
    $result = mysqli_query($conn, "SELECT * FROM Products");
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
          echo json_encode($row);
        }
      } else {
        echo "0 results";
      }
}

if($_REQUEST['action'] == 'chart') {
  $data['visitor'] = [['Country', 'KG']];
  $data['geo'] = [['Country', 'coffee', 'tea', 'mate', 'cocoa' , 'allsources']];
  $resultchart = mysqli_query($conn, "SELECT distinct(`country`), `kg_val` FROM `geo` ORDER BY country asc");
  $resultgeo = mysqli_query($conn, "SELECT distinct(country), `coffee`, `tea`, `mate`, `cocoa` FROM visitors ORDER BY `country` asc");
  while($row = mysqli_fetch_array($resultchart)){
    array_push($data['visitor'], [$row['country'],$row['kg_val']]);
  }
  while($row = mysqli_fetch_array($resultgeo)) {
    array_push($data['geo'], [
      $row['country'],
      $row['coffee'],
      $row['tea'],
      $row['mate'],
      $row['cocoa'],
      ($row['coffee'] + $row['tea'] + $row['mate'] + $row['cocoa']),
    ]);
  }
  
  echo json_encode($data); 
}

if($_REQUEST['action'] == 'update') {
    // Update   
    $product_name = 'BrandNewProduct';
    $new_product_price = 15;
    $result = mysqli_query($conn, "UPDATE Products SET Price = '$new_product_price' WHERE ProductName = '$product_name'");
    print_r($result);
    echo $result;
}

if($_REQUEST['action'] == 'delete') {
    //Run the Delete statement
    $product_name = 'BrandNewProduct';
    $result = mysqli_query($conn, "DELETE FROM Products WHERE ProductName = '$product_name'");
    print_r($result);
    echo $result;
}
// Close the connection
mysqli_close($conn);
?>