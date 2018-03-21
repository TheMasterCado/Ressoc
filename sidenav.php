<?php
//Tous les utilisateurs
$sql = "SELECT prenom, nom, loginID FROM utilisateur ORDER BY prenom ASC;";
$allUsers = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
//Spécialité de l'utilisateur à qui le feed appartient
if($feedDe['fk_specialite'] != NULL) {
  $sql = "SELECT nom FROM specialite WHERE pk_specialite = :specialite;";
  $stmt = $db->prepare($sql);
  $stmt->execute([':specialite' => $feedDe['fk_specialite']]);
  $specialiteUser = $stmt->fetch();
}
 ?>
 <script>
   function refreshSearch() {
     $(".lien-feed-utilisateur").each(function(index) {
       if($(this).text().toLowerCase().indexOf($("#searchBar").val()) >= 0)
          $(this).show();
       else
         $(this).hide();
     });
   }
 </script>
 <div id="sidenav">
   <h6><?= $titre ?><br><?= $titre2 ?></h6>
   <img src="<?= $feedDe['image'] ?>">
   <p class="hide-with-points">Spécialité: <strong><?= (isset($specialiteUser['nom']) ? $specialiteUser['nom'] : "Aucune") ?></strong></p>
   <p>Nombre de sessions: <strong><?= $feedDe['nb_session'] ?></strong></p>
   <div id="sidenav-buttons">
     <?php if($id == $_SESSION['id'] || $id == "ALL") { ?>
       <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#nouvellePublication">Nouvelle publication</button>
     <?php } ?>
     <a class="btn btn-info btn-sm" href="./feed.php">Feed général</a>
     <a class="btn btn-info btn-sm" href="./feed.php?id=<?= $_SESSION['id'] ?>">Mon feed</a>
     <a class="btn btn-info btn-sm" href="./signOut.php">Se déconnecter</a>
   </div>
   <div id="liste-utilisateurs">
     <?php foreach ($allUsers as $pos => $oneUser) { ?>
       <a class="lien-feed-utilisateur hide-with-points" href="./feed.php?id=<?= $oneUser['loginID'] ?>"><?= $oneUser['prenom'] . " " . $oneUser['nom'] ?></a>
     <?php } ?>
   </div>
   <input id="searchBar" type="text" placeholder="Recherche..." onkeyup="refreshSearch()">
   <div id="sidenav-footer">
     <h6>Connecté en tant que<br><?= $currentUser['prenom']." ".$currentUser['nom'] ?></h6>
   </div>
 </div>
