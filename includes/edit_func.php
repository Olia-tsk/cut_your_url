<?php
include 'functions.php';

edit_link($_POST['id'], $_POST['link']);

$_SESSION['success'] = "Link successfully edited";
header('Location: /profile.php');
die;
