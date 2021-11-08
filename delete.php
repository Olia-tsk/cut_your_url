<?php
if (!isset($_GET['id']) && empty($_GET['id'])) {
  header('Location: profile.php');
  die;
}

include 'includes/functions.php';

delete_link($_GET['id']);

$_SESSION['success'] = "Link was perish to dust...";
header('Location: profile.php');
die;
