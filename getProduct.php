<?php
require_once('includes/load.php');
if (!empty($_POST['product'])) {
   $query = "SELECT p.id,p.name,p.quantity,p.date_peremp,p.utili,p.ci,c.name AS categorie FROM products p LEFT JOIN categories c ON c.id = p.categorie_id WHERE p.name LIKE '" . $_POST['product'] . "%' ORDER BY p.id ASC";
   $result = $db->query($query);
   if (!empty($result)) {
      echo "<ul id='products'>";
      foreach ($result as $product) {
         echo "<li onClick=\"selectProduct('" . $product['name'] . "')\"><a href=\"#\" class=\"link-secondary\">" . $product['name'] . " - ". $product['categorie'] ."</a></li>";
      }
      echo "</ul>";
   }
}
