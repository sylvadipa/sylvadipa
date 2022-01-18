<?php
//Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


//On vérifie que la méthode utilisée est correcte
if($_SERVER['REQUEST_METHOD']=='GET'){
    //On inclut les fichiers de configuration et d'accès aux données
    include_once '../config/Database.php';
    include_once '../Models/Produits.php';
//on instancie la base de données

$database = new Database();
$db = $database->getConnection();

//on instacie les produits

$produit = new Produits($db);

//on recupère les données
$stmt = $produit->lire();

//on vérfie si on a au moins un produit
if($stmt->rowCount() > 0){

    //on initialise le tableau associatiatif
    $tableauProduits = [];
    $tableauProduits['produits'] =[];

    //on parcours les produits
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $prod = [
            "id"=>$id,
            "nom"=>$nom,
            "description"=>$description,
            "prix"=>$prix,
            "categories_id" =>$categorie_id,
            "categories"=>$categories_nom
        ];

        $tableauProduits['produits'][]= $prod;

    }

    http_response_code(200);
    echo json_encode($tableauProduits);
}

}

else{

    //On gère l'erreur
    http_response_code(405);
    echo json_encode(["Message"=> "La méthode n'est pas correcte"]);
} 
