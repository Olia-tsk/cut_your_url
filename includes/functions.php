<?php
include 'includes/config.php';

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
}
