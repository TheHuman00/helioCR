<?php
  $page_title = 'Ajouter produit';
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('index', false);}
  page_require_level(1);
  $user = current_user();
  $all_categories = find_all('categories');
  $all_products = join_product_table();
  if(!is_null($_POST['productname'])){
    $product_name = $_POST['productname'];
  }
  $database = new MySqli_DB();
?>
<?php
  if(isset($_POST['add_product'])){
    if(empty($errors)){
      $params = [];
      $p_buy   = remove_junk($db->escape($_POST['date-peremp']));
      $p_name  = remove_junk($db->escape($_POST['name']));
      $p_cat  = remove_junk($db->escape($_POST['product-categorie']));
      $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
      $p_utili = remove_junk($db->escape($_POST['utilisation']));
      $p_ci    = remove_junk($db->escape($_POST['contre-indic']));
      $query  = $database->db_prepare("INSERT INTO products (name,quantity,date_peremp,categorie_id,utili,ci) VALUES ( ?, ?, ?, ?, ?, ?)");
      $query->bind_param("ssssss", $p_name, $p_qty, $p_buy, $p_cat, $p_utili, $p_ci);
      if($query->execute()){
        $session->msg('s',"Produit ajouté ");
        redirect('produit', false);
      } else {
        $session->msg('d','Echec de l\'ajout!');
        redirect('produit', false);
      }
 
    } else{
      $session->msg("d", $errors);
      redirect('produit',false);
    }
 
  }
  $exist = false;
  if(!is_null($_POST['productname'])){
    foreach($all_products as $product){
      if($product['name'] == $product_name){
        $exist = true;
        $utilisation = $product['utili'];
        $contreIndic = $product['ci'];
      }
    }
  }

?>
<?php include_once('layouts/header.php'); ?>
  <?php echo display_msg($msg); ?>
  <section class="py-5">
                <div class="container px-2">
                    <div class="bg-light rounded-3 py-5 px-4 px-md-5 mb-5">
                        <div class="text-center mb-5">
                            <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-bag-plus"></i></div>
                            <h1 class="fw-bolder"><?php echo $product_name;?></h1>
                            <p class="lead fw-normal text-muted mb-0">Ajouter un produit : </p>
                        </div>
                        <div class="row gx-5 justify-content-center">
                            <div class="col-lg-8 col-xl-6">
                            <form method="post" action="ajout_produit.php">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" name="name" type="text" placeholder="Nom du produit" value="<?php echo $product_name?>"required/>
                                        <label for="phone">Nom du produit</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input class="form-control" name="date-peremp" type="month" placeholder="Date de péremption" />
                                        <label for="phone">Date de péremption</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input class="form-control" name="product-quantity"  type="number" placeholder="Quantité du produit" required/>
                                        <label for="email">Quantité du produit</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input class="form-control" name="utilisation"  type="text" placeholder="Utilisation" <?php if($exist){echo "value=\"".$utilisation."\"";} ?>/>
                                        <label for="email">Utilisation</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input class="form-control" name="contre-indic"  type="text" placeholder="Contre indication" <?php if($exist){echo "value=\"".$contreIndic."\"";} ?>/>
                                        <label for="email">Contre indication</label>
                                    </div>
                                    <select class="form-select" name="product-categorie" required>
                                        <option selected>Sélectionner où se trouve le produit</option>
                                        <?php  foreach ($all_categories as $cat): ?>
                                          <option value="<?php echo (int)$cat['id'] ?>">
                                            <?php echo $cat['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                       <br>
                                       <br>
                                    <div class="d-grid"><button class="btn btn-primary btn-lg" name="add_product" type="submit">Ajouter le produit</button></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>











<?php include_once('layouts/footer.php'); ?>
