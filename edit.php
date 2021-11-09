<?php
if (!isset($_GET['id']) && empty($_GET['id'])) {
  header('Location: profile.php');
  die;
}

include 'includes/header.php';

?>
<div class="container mt-4">
  <div class="col">
    <form class="d-flex" action="includes/edit_func.php" method="POST">
      <input type="hidden" name="id" value="<?= $_GET['id'] ?>">
      <input class="form-control me-2" type="text" value="<?= $_GET['link'] ?>" name="link" aria-label="Ссылка">
      <button class="btn btn-success" type="submit">EDIT!</button>
    </form>
  </div>
</div>