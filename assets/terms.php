<?php include "functions.php";

$getTerms = mysqli_query($conn, "SELECT * FROM `terms`");
while($term = mysqli_fetch_assoc($getTerms)) {
    $term = $term['term'];
    
    echo '<li>'.$term.'</li>';
}

?>