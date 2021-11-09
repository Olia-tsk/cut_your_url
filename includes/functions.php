<?php
include 'config.php';

function get_url($page = '')
{
  return HOST . "/$page";
}

function db()
{
  try {
    return new PDO("mysql:host=" . DB_HOST . "; dbname=" . DB_NAME . "; charset=utf8", DB_USER, DB_PASS, [
      PDO::ATTR_EMULATE_PREPARES => false,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
  } catch (PDOException $err) {
    die($err->getMessage());
  }
}

function db_query($sql = '', $exec = false)
{
  if (empty($sql)) return false;

  if ($exec) {
    return db()->exec($sql);
  }

  return db()->query($sql);
}

function get_user_count()
{
  return db_query("SELECT COUNT(id) FROM `users`;")->fetchColumn();
}

function get_links_count()
{
  return db_query("SELECT COUNT(id) FROM `links`;")->fetchColumn();
}

function get_views_sum()
{
  return db_query("SELECT SUM(views) FROM `links`;")->fetchColumn();
}

function get_link_info($url)
{
  if (empty($url)) return [];

  return db_query("SELECT * FROM `links` WHERE `short_link` = '$url';")->fetch();
}

function update_views($url)
{
  if (empty($url)) return false;

  return db_query("UPDATE `links`SET `views` = `views`+1 WHERE `short_link` = '$url';", true);
}

// REGISTRATION

function get_user_info($login)
{
  if (empty($login)) return [];

  return db_query("SELECT * FROM `users` WHERE `login` = '$login';")->fetch();
}

function user_add($login, $pass)
{
  $pass_encript = password_hash($pass, PASSWORD_DEFAULT);

  return db_query("INSERT INTO `users` (`id`, `login`, `pass`) VALUES (NULL, '$login', '$pass_encript');", true);
}

function user_registration($auth_data)
{
  if (empty($auth_data) || !isset($auth_data['login']) || empty($auth_data["login"]) || !isset($auth_data['pass']) || !isset($auth_data['check-pass'])) return false;

  $user = get_user_info($auth_data['login']);

  if (!empty($user)) {
    $_SESSION['error'] = "Sorry, you must be mistaken - user '" . $auth_data['login'] . "' already exists!";
    header('Location: register.php');
    die;
  }

  if ($auth_data['pass'] !== $auth_data['check-pass']) {
    $_SESSION['error'] = "You messed with passwords, check them again. Please.";
    header('Location: register.php');
    die;
  }

  if (user_add($auth_data['login'], $auth_data['pass'])) {
    $_SESSION['success'] = "CONGRATS! You're ours now";
    header('Location: login.php');
    die;
  };

  return true;
}

// AUTHORIZATION

function user_auth($auth_data)
{
  if (empty($auth_data) || !isset($auth_data["login"]) || empty($auth_data['login']) || !isset($auth_data["pass"]) || empty($auth_data['pass'])) {
    $_SESSION["error"] = "Login and/or password can't be empty";
    header('Location: login.php');
    die;
  }

  $user = get_user_info($auth_data['login']);

  if (empty($user)) {
    $_SESSION['error'] = "Login and/or password not correct. Actually user does not exist.";
    header('Location: login.php');
    die;
  }

  if (password_verify($auth_data['pass'], $user['pass'])) {
    $_SESSION['user'] = $user;
    header('Location: profile.php');
    die;
  } else {
    $_SESSION['error'] = "Login and/or password not correct. Actually only password.";
    header('Location: login.php');
    die;
  }
}

// PROFILE DATA

function get_user_links($user_id)
{
  if (empty($user_id)) return [];

  return db_query("SELECT * FROM `links` WHERE `user_id` = $user_id;")->fetchAll();
}

function delete_link($id)
{
  if (empty($id)) return false;

  return db_query("DELETE FROM `links` WHERE `id` = $id;", true);
}

function add_link($user_id, $link)
{
  $short_link = generate_link();

  return db_query("INSERT INTO `links` (`id`, `user_id`, `long_link`, `short_link`, `views`) VALUES (NULL, '$user_id', '$link', '$short_link', '0');", true);
}

function generate_link($length = 3)
{
  $new_link = bin2hex(random_bytes($length));
  $check = db_query("SELECT `short_link` FROM `links` WHERE `short_link` = '$new_link';")->fetch();

  if ($check) {
    $new_link = bin2hex(random_bytes($length));
  }

  return $new_link;
}

function edit_link($id, $link)
{
  if (empty($id) || empty($link)) return false;

  return db_query("UPDATE `links` SET `long_link` = '$link' WHERE `id` = '$id';");
  $_SESSION['success'] = "Link successfully edited";
  // header('Location: profile.php');
  // die;
}
