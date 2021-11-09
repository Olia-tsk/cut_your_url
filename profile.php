<?php include 'includes/header_profile.php';

if (!isset($_SESSION['user']['id'])) header('Location: /');

$error = '';
if (isset($_SESSION["error"]) && !empty($_SESSION["error"])) {
	$error = $_SESSION['error'];
	$_SESSION['error'] = '';
}

$success = '';
if (isset($_SESSION["success"]) && !empty($_SESSION["success"])) {
	$success = $_SESSION['success'];
	$_SESSION['success'] = '';
}

if (isset($_POST["login"]) && !empty($_POST["login"]) && isset($_POST["pass"]) && !empty($_POST["pass"])) {
	user_auth($_POST);
}

$links = get_user_links($_SESSION['user']['id']);

?>

<main class="container">

	<?php if (!empty($success)) { ?>
		<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
			<?= $success; ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php } ?>

	<?php if (!empty($error)) { ?>
		<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
			<?= $error; ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php } ?>

	<div class="row mt-5">
		<?php if (!empty($links)) { ?>
			<table class="table table-striped">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Ссылка</th>
						<th scope="col">Сокращение</th>
						<th scope="col">Переходы</th>
						<th scope="col">Действия</th>
					</tr>
				</thead>
				<tbody>

					<?php foreach ($links as $key => $link) { ?>
						<tr>
							<th scope="row"><?= $key + 1; ?></th>
							<td><a href="<?= $link['long_link'] ?>" target="_blank"><?= $link['long_link'] ?></a></td>
							<td class="short-link"><?= get_url($link['short_link']) ?></td>
							<td><?= $link['views'] ?></td>
							<td>
								<a href="#" class="btn btn-primary btn-sm copy-btn" title="Скопировать в буфер" data-clipboard-text="<?= get_url($link['short_link']) ?>"><i class="bi bi-files"></i></a>
								<a href="<?= get_url('edit.php?id=' . $link['id'] . '&link=' . $link['long_link']) ?>" class="btn btn-warning btn-sm" title="Редактировать"><i class="bi bi-pencil"></i></a>
								<a href="<?= get_url('delete.php?id=' . $link['id']) ?>" class="btn btn-danger btn-sm" title="Удалить"><i class="bi bi-trash"></i></a>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>

		<?php } else { ?>
			<div class="row mt-5">
				<div class="col">
					<h2 class="text-center">You haven't added any links yet.</h2>
					<h2 class="text-center">Let's create one by tapping form below!</h2>
				</div>
			</div>
		<?php } ?>
	</div>
</main>

<div aria-live="polite" aria-atomic="true" class="position-relative">
	<div class="toast-container position-absolute top-0 start-50 translate-middle-x">
		<div class="toast align-items-center text-white bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
			<div class="d-flex">
				<div class="toast-body">
					Ссылка скопирована в буфер
				</div>
				<button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
			</div>
		</div>
	</div>
</div>

<?php include 'includes/footer_profile.php'; ?>