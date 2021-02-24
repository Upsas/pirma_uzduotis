<?php

include "../../vendor/autoload.php";

use App\Controllers\PatternController;
use App\Router;

$patternController = new PatternController();
$routers = new Router();
$routers->patternRoutes();
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
<?php include './Layouts/nav.php' ;?>
<h1 class="font-weight-bold text-center m-3">Pattern</h1>

<form method="POST" class="form-inline my-5 justify-content-center">
  
  <div class="form-group mx-sm-3 mb-2">
    <input type="text" autocomplete='off' class="form-control" name="pattern"  placeholder="Add Pattern"
    value="<?=$_POST['oldPattern'] ?? $_POST['pattern'] ?? null ?>"
    >
  </div>
  <?php
  if (isset($_POST['update'])) : ?>
    <input type="hidden" name="oldPattern" value="<?=$_POST['oldPattern']?>">

  <button type="submit" name="edit" class="btn btn-info mb-2">Edit<span class="mx-2 badge badge-light">+</span></button>
  <?php else : ?>
    <button type="submit" name="add" class="btn btn-primary mb-2">Add<span class="mx-2 badge badge-light">+</span></button>
  <?php endif; ?>
</form>
<table class="text-center mx-auto table table-hover col-6">
<caption>List of patterns</caption>
  <thead>
  <tr class="text-center">
      <th scope="col">Id</th>
      <th scope="col">Word</th>
      <th scope="col">Action</th>
    </tr>
    </thead>
    <?php
    if (isset($_POST['searchButton']) && !empty($patternController->searchPattern($_POST['searchValue']))) :
        $values = $patternController->searchPattern($_POST['searchValue']);
    ?>
    <?php
    else :$values = $patternController->getPaginationData($_GET['page'] ?? null) ;
    ?>
    <?php
     endif
     ?>

    <?php foreach ($values as $data) :
        ?>
<tbody>
    <tr>
      <th scope="row"><?=$data->id?></th>
      <td><?=$data->pattern?></td>
      <td >
      <div class="btn-group">
      <form method="POST">
    <input type="hidden" name="id" value="<?=$data->id ?? null?>">
      <button type="submit" name="delete" class="btn btn-danger btn-sm mr-2">Delete</button>
      </form>
      <form method="POST" >
    <input type="hidden" name="oldPattern" value="<?=$data->pattern?>">
      <button type="submit" name="update" class="px-2 btn btn-info btn-sm">Edit</button>
      </form>
      </div>
      </td>
    </tr>
    <?php endforeach?>

</form>
</table>
<div class="text-center">
<?php
foreach ($patternController->numberOfPages($_GET['page'] ?? null) as $page) : ?>
<a class=" btn btn-primary btn-small" href="patterns.php?page=<?=$page?>"><?=$page?></a>
<?php endforeach?>
</div>

</body>
</html>