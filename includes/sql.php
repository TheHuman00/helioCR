<?php
  require_once('includes/load.php');

/*--------------------------------------------------------------*/
/* Fonction de recherche de toutes les lignes de table de base de données par nom de table
/*--------------------------------------------------------------*/
function find_all($table) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM ".$db->escape($table));
   }
}

/*--------------------------------------------------------------*/
/* Fonction pour effectuer des requêtes
/*--------------------------------------------------------------*/
function find_by_sql($sql)
{
  global $db;
  $result = $db->query($sql);
  $result_set = $db->while_loop($result);
 return $result_set;
}

/*--------------------------------------------------------------*/
/*  Fonction de recherche des données de la table par identifiant
/*--------------------------------------------------------------*/
function find_by_id($table,$id)
{
  global $db;
  $id = (int)$id;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id='{$db->escape($id)}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}
/*--------------------------------------------------------------*/
/* Fonction pour supprimer les données de la table par identifiant
/*--------------------------------------------------------------*/
function delete_by_id($table,$id)
{
  global $db;
  if(tableExists($table))
   {
    $sql = "DELETE FROM ".$db->escape($table);
    $sql .= " WHERE id=". $db->escape($id);
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
   }
}


function delete_by_title($table,$title)
{
  global $db;
  if(tableExists($table))
   {
    $sql = "DELETE FROM ".$db->escape($table);
    $sql .= " WHERE title='{$title}'";
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
   }
}
function change_hist_statut($event,$user)
{
  global $db;
    $sql = "UPDATE historique SET lu='1' WHERE event ='{$event}' AND user ='{$user}' LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
}
function delete_by_event($table,$title) 
{
  global $db;
  if(tableExists($table))
   {
    $sql = "DELETE FROM ".$db->escape($table);
    $sql .= " WHERE event='{$title}'";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
   }
}
function delete_by_historique($table,$title) 
{
  global $db;
  if(tableExists($table))
   {
    $sql = "DELETE FROM ".$db->escape($table);
    $sql .= " WHERE event='{$title}'";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
   }
}

function historique($event, $user, $dispo){
  global $db;
  $historiques = join_hist_table();
  foreach($historiques as $historique){
    if($historique['user'] == $user){
      if($historique['event'] == $event){
        $sql = "DELETE FROM historique";
        $sql .= " WHERE id=".$historique['id'];
        $sql .= " LIMIT 1";
        $result = $db->query($sql);
      }
    }
  }
    date_default_timezone_set('Europe/Brussels');
    $date2 = date_create();
    $date = date_format($date2, 'Y-m-d H:i:s');
    $sql  = "INSERT INTO historique (";
    $sql .="event,user,dispostatus,date,lu";
    $sql .=") VALUES (";
    $sql .="'{$event}', '{$user}', '{$dispo}', '{$date}', '0')";
    $result = $db->query($sql);

}
/*--------------------------------------------------------------*/
/* Fonction pour Count id par table name
/*--------------------------------------------------------------*/

function count_by_id($table){
  global $db;
  if(tableExists($table))
  {
    $sql    = "SELECT COUNT(id) AS total FROM ".$db->escape($table);
    $result = $db->query($sql);
     return($db->fetch_assoc($result));
  }
}
/*--------------------------------------------------------------*/
/* Déterminer si la table de base de données existe
/*--------------------------------------------------------------*/
function tableExists($table){
  global $db;
  $table_exit = $db->query('SHOW TABLES FROM '.DB_NAME.' LIKE "'.$db->escape($table).'"');
      if($table_exit) {
        if($db->num_rows($table_exit) > 0)
              return true;
         else
              return false;
      }
  }
  
/*--------------------------------------------------------------*/
/* Connectez-vous avec les données fournies dans $_POST,
/* provenant du formulaire de connexion.
/*--------------------------------------------------------------*/
  function authenticate($username='', $password='') {
    global $db;
    $database = new MySqli_DB();
    $username = $db->escape($username);
    $password = $db->escape($password);
    $sql  = $database->db_prepare("SELECT id,username,password,user_level FROM users WHERE username = ? LIMIT 1");
    $sql->bind_param('s', $username);
    $sql->execute();
    $sql_result = $sql->get_result();
    if($db->num_rows($sql_result)){
      $user = $db->fetch_assoc($sql_result);
      if(password_verify($password, $user['password'])){
        return $user['id'];
      }
    }
   return false;
  }
/*--------------------------------------------------------------*/
/* Connectez-vous avec les données fournies dans $ _POST,
/* provenant du formulaire login_v2.php.
/* Si vous avez utilisé cette méthode, supprimez la fonction d'authentification.
/*--------------------------------------------------------------*/
   function authenticate_v2($username='', $password='') {
     global $db;
     $database = new MySqli_DB();
     $username = $db->escape($username);
     $password = $db->escape($password);
     $sql  = $database->db_prepare("SELECT id,username,password,user_level FROM users WHERE username = ? LIMIT 1");
     $sql->bind_param('s', $username);
     $sql_result = $sql->get_result();
     $result = $sql->execute();
     if($db->num_rows($sql_result)){
       $user = $db->fetch_assoc($sql_result);
       if(password_verify($password, $user['password'])){
         return $user;
       }
     }
    return false;
   }


  /*--------------------------------------------------------------*/
  /* Rechercher l'utilisateur de connexion actuel par identifiant de session
  /*--------------------------------------------------------------*/
  function current_user(){
      static $current_user;
      global $db;
      if(!$current_user){
         if(isset($_SESSION['user_id'])):
             $user_id = intval($_SESSION['user_id']);
             $current_user = find_by_id('users',$user_id);
        endif;
      }
    return $current_user;
  }
  /*--------------------------------------------------------------*/
  /* Trouver tous les utilisateurs par
  /* Rejoindre la table des utilisateurs et la table des groupes d'utilisateurs
  /*--------------------------------------------------------------*/
  function find_all_user(){
      global $db;
      $results = array();
      $sql = "SELECT u.id,u.name,u.username,u.user_level,u.status,u.email,u.telephone,u.competence,u.admin,u.permis,u.cle,";
      $sql .="g.group_name ";
      $sql .="FROM users u ";
      $sql .="LEFT JOIN user_groups g ";
      $sql .="ON g.group_level=u.user_level ORDER BY u.name ASC";
      $result = find_by_sql($sql);
      return $result;
  }
  /*--------------------------------------------------------------*/
  /* Fonction pour mettre à jour la dernière connexion d'un utilisateur
  /*--------------------------------------------------------------*/

 function updateLastLogIn($user_id)
	{
		global $db;
    date_default_timezone_set($timeZone);
    $date2 = date_create();
    $date = date_format($date2, 'Y-m-d H:i');
    $sql = "UPDATE users SET email='{$date}' WHERE id ='{$user_id}' LIMIT 1";
    $result = $db->query($sql);
    return ($result && $db->affected_rows() === 1 ? true : false);
	}

  /*--------------------------------------------------------------*/
  /* Trouver tout le nom du groupe
  /*--------------------------------------------------------------*/
  function find_by_groupName($val)
  {
    global $db;
    $sql = "SELECT group_name FROM user_groups WHERE group_name = '{$db->escape($val)}' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }
  /*--------------------------------------------------------------*/
  /* Trouver le niveau du groupe
  /*--------------------------------------------------------------*/
  function find_by_groupLevel($level)
  {
    global $db;
    $sql = "SELECT group_level FROM user_groups WHERE group_level = '{$db->escape($level)}' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }
  /*--------------------------------------------------------------*/
  /* Fonction pour vérifier quel niveau d'utilisateur a accès à la page
  /*--------------------------------------------------------------*/
   function page_require_level($require_level){
     global $session;
     $current_user = current_user();
     $login_level = find_by_groupLevel($current_user['user_level']);
     //if user not login
     if (!$session->isUserLoggedIn(true)):
            $session->msg('d','Connectez vous...');
            redirect('index', false);
      //if Group status Deactive
/*      elseif($login_level['group_status'] === '0'):
           $session->msg('d','Cet utilisateur de niveau a été banni!');
           redirect('index',false);
      //cheackin log in User level and Require level is Less than or equal to */
     elseif($current_user['user_level'] <= (int)$require_level):
              return true;
      else:
            $session->msg("d", "Vous n'êtes pas autorisé à afficher la page.");
            redirect('index', false);
        endif;

     }
   /*--------------------------------------------------------------*/
   /* Fonction de recherche de tous les noms de produits 
   /* JOIN avec catégorie et table de base de données des médias
   /*--------------------------------------------------------------*/
  function join_product_table(){
     global $db;
     $sql  =" SELECT p.id,p.name,p.quantity,p.date_peremp,p.utili,p.ci,c.name";
     $sql  .=" AS categorie";
    $sql  .=" FROM products p";
    $sql  .=" LEFT JOIN categories c ON c.id = p.categorie_id";
    $sql  .=" ORDER BY p.id ASC";
    return find_by_sql($sql);

   }

   function join_product_tablewhere($id){
    global $db;
    $sql  =" SELECT p.id,p.name,p.quantity,p.date_peremp,p.utili,p.ci,c.name";
    $sql  .=" AS categorie";
   $sql  .=" FROM products p";
   $sql  .=" LEFT JOIN categories c ON c.id = p.categorie_id";
   $sql  .=" WHERE p.id = '{$id}' LIMIT 1";
   return find_by_sql($sql);

  }
  function join_dispo_table(){
    global $db;
    $sql  =" SELECT * FROM disponibilite";
   return find_by_sql($sql);

  }

   function join_fiche_table(){
    global $db;
    $sql  =" SELECT p.id,p.nom,p.prenom,p.genre,p.datenaiss,p.langue,p.heurein,p.admission,p.motif,p.date,p.degreurg,p.conscience,p.respiration,p.circulation,p.event,p.symptomes,p.echelledouleur,p.pulse,p.sat,p.tension,p.frequrespi,p.temp,p.glasgowyeux,p.glasgowverbal,p.glasgowmotrice,p.glycemie,p.tetanos,p.pupilles,p.allergie,p.medicament,p.evolution,";
    $sql  .="p.materiel1,p.soignant1,p.materiel2,p.soignant2,p.materiel3,p.soignant3,p.materiel4,p.soignant4,";
    $sql  .="p.formretour,p.zonebonus,p.indiambulance,p.heureapple,p.heurearriver,p.heureout";
    $sql  .=" FROM fichesoins p";
    $sql  .=" ORDER BY p.id ASC";
    return find_by_sql($sql);

  }

  function join_fiche_table2(){
    global $db;
    $sql  =" SELECT * FROM fichesoins p";
    $sql  .=" ORDER BY id ASC";
    return find_by_sql($sql);
  }
  function join_calen_table(){
    global $db;
    $sql  =" SELECT * FROM calendrier";
    $sql  .=" ORDER BY id";
    return find_by_sql($sql);
  }
  function join_hist_table(){
    global $db;
    $sql  =" SELECT * FROM historique";
    $sql  .=" ORDER BY id";
    return find_by_sql($sql);
  }

  function trouver_dispo_table($userr, $eventt){
    global $db;
    $sql = $db->query(" SELECT * FROM disponibilite WHERE user ='{$userr}' AND event ='{$eventt}'");
    if($result = $db->fetch_assoc($sql)){
      return $result;
    }else{
      return null;
    }
  }
  function trouver_event_table($nom){
    global $db;
    $sql = $db->query(" SELECT * FROM calendrier WHERE title='{$nom}'");
    if($result = $db->fetch_assoc($sql)){
      return $result;
    }else{
      return null;
    }
  }

  function exist_user_dispo($userr, $eventt){
    global $db;
    $sql = $db->query(" SELECT * FROM disponibilite WHERE user ='{$userr}' AND event ='{$eventt}'");
    if($result = $db->fetch_assoc($sql)){
      if (is_null($result)){
        return false;
      }else{
        return true;
      }
    }
  }



  // function find_all_fiches_info($p_nom, $p_prenom, $p_event, $p_motif){
  //   global $db;
  //   $sql  = "SELECT p.id,p.nom,p.prenom,p.genre,p.datenaiss,p.langue,p.heurein,p.admission,p.motif,p.date,p.conscience,p.respiration,p.circulation,p.event";
  //   $sql  .=" FROM fichesoins p";
  //   $sql .= " WHERE nom ='{$p_nom}' AND prenom ='{$p_prenom}' AND event ='{$p_event}' AND motif ='{$p_motif}'";
  //   return find_by_sql($sql);
  // }

  function join_user_table(){
    global $db;
    $sql  =" SELECT p.id,p.name,p.username";
   $sql  .=" FROM users p";
   $sql  .=" ORDER BY p.name ASC";
   return find_by_sql($sql);
  }

  function join_user_table2(){
    global $db;
    $sql  =" SELECT *";
   $sql  .=" FROM users p";
   $sql  .=" ORDER BY p.name ASC";
   return find_by_sql($sql);
  }

   function find_product_by_title($product_name){
     global $db;
     $p_name = remove_junk($db->escape($product_name));
     $sql = "SELECT name FROM products WHERE name like '%$p_name%' LIMIT 5";
     $result = find_by_sql($sql);
     return $result;
   }

   function find_product_by_title2($product_name){
    global $db;
    $p_name = remove_junk($db->escape($product_name));
    $sql = "SELECT p.id,p.name,p.quantity,p.date_peremp,p.media_id,p.date FROM products p WHERE name='{$p_name}'";
    $result = $db->query($sql);
    $result2 = $db->fetch_assoc($result);
    return $result2;
  }

  function find_event_by_title($event_name){
    global $db;
    $p_name = remove_junk($db->escape($event_name));
    $sql = "SELECT * FROM calendrier WHERE title='{$p_name}'";
    $result = $db->query($sql);
    $result2 = $db->fetch_assoc($result);
    return $result2;
  }

  function find_disponibilite_by_title($event_name,$user_name){
    global $db;
    $p_event = remove_junk($db->escape($event_name));
    $p_user = remove_junk($db->escape($user_name));
    $sql = "SELECT * FROM disponibilite WHERE user='{$p_user}' AND event='{$p_event}'";
    $result = $db->query($sql);
    $result2 = $db->fetch_assoc($result);
    if (is_null($result2)){
      return false;
    }else{
    return $result2;
    }
  }
  function find_user_by_title($event_name){
    global $db;
    $p_event = remove_junk($db->escape($event_name));
    $sql = "SELECT * FROM users WHERE name='{$p_event}'";
    $result = $db->query($sql);
    $result2 = $db->fetch_assoc($result);
    return $result2;
  }
  function find_all_product_info_by_title($title){
    global $db;
    $sql  = "SELECT * FROM products ";
    $sql .= " WHERE name ='{$title}'";
    $sql .=" LIMIT 1";
    return find_by_sql($sql);
  }


  function update_product_qty($qty,$p_id){
    global $db;
    $qty = (int) $qty;
    $id  = (int)$p_id;
    $sql = "UPDATE products SET quantity=quantity -'{$qty}' WHERE id = '{$id}'";
    $result = $db->query($sql);
    return($db->affected_rows() === 1 ? true : false);

  }


  function update_dispo($p_dispo,$p_id){
    global $db;
    $id  = (int)$p_id;
    $dispo  = $p_dispo;
    $sql = "UPDATE disponibilite SET dispo ='{$p_dispo}' WHERE id = '{$id}'";
    $result = $db->query($sql);
    return($db->affected_rows() === 1 ? true : false);

  }
  function update_dispo_rolecp($p_dispo,$p_id){
    global $db;
    $id  = (int)$p_id;
    $sql = "UPDATE disponibilite SET rolecp ='{$p_dispo}' WHERE id = '{$id}'";
    $result = $db->query($sql);
    return($db->affected_rows() === 1 ? true : false);

  }
  function update_dispo_rolechau($p_dispo,$p_id){
    global $db;
    $id  = (int)$p_id;
    $sql = "UPDATE disponibilite SET rolechau ='{$p_dispo}' WHERE id = '{$id}'";
    $result = $db->query($sql);
    return($db->affected_rows() === 1 ? true : false);

  }

  
 function find_recent_product_added($limit){
   global $db;
   $sql   = " SELECT p.id,p.name,p.media_id,c.name AS categorie,";
   $sql  .= "m.file_name AS image FROM products p";
   $sql  .= " LEFT JOIN categories c ON c.id = p.categorie_id";
   $sql  .= " LEFT JOIN media m ON m.id = p.media_id";
   $sql  .= " ORDER BY p.id DESC LIMIT ".$db->escape((int)$limit);
   return find_by_sql($sql);
 }
