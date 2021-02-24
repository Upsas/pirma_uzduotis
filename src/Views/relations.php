<?php

include "../../vendor/autoload.php";

use App\Controllers\RelationsController;

$relationsController = new RelationsController();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visma | Praktika</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
</head>
<body>
<?php include './Layouts/nav.php' ?>
<h1 class="font-weight-bold text-center mt-3">Relations</h1>
<table class="text-center mx-auto table table-hover col-6">
<caption>List of Selected patterns</caption>
  <thead>
  <tr class="text-center">
      <th scope="col">WordId</th>
      <th scope="col">Word</th>
      <th scope="col">PatternId</th>
      <th scope="col">Pattern</th>
    </tr>
    </thead>
    <?php
    if (isset($_POST['searchButton']) && !empty($relationsController->searchRelation($_POST['searchValue']))) :
        $values = $relationsController->searchRelation($_POST['searchValue']);
    ?>
    <?php
    else :
$values = $relationsController->getPaginationData($_GET['page'] ?? null) ;
    ?>
    <?php
    endif
     ?>

    <?php foreach ($values as $data) : ?>
<tbody>
    <tr>
      <th scope="row"><?=$data->word_id?></th>
      <td ><?=$relationsController->getWordsById($data->word_id)[0]->word?></td>
      <td ><?=$data->pattern_id?></td>
      <td ><?=$relationsController->getPatternsById(intval($data->pattern_id))[0]->pattern?></td>
      </tr>
    <?php endforeach?>

</form>
</table>
<div class="text-center">
<?php
foreach ($relationsController->numberOfPages($_GET['page'] ?? null) as $page) : ?>
<a class=" btn btn-primary btn-small" href="relations.php?page=<?=$page?>"><?=$page?></a>
<?php endforeach?>
</div>
</body>
</html>