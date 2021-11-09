<?php
include 'functions.php';

if (isset($_POST['link']) && !empty($_POST['link']) && isset($_POST['user_id']) && !empty($_POST['user_id'])) {
  if (add_link($_POST['user_id'], $_POST['link'])) {
    $_SESSION['success'] = "Your link successfully added!";
  } else {
    $_SESSION['error'] = "Smth went wrong..";
  }
}

header('Location: /profile.php');
die;
