<?php include 'includes/header.php' ?>

<main class="container">
	<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
		Все ок
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
	<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
		А тут не ок
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
	<div class="row mt-5">
		<div class="col">
			<h2 class="text-center">Вход в личный кабинет</h2>
			<p class="text-center">Если вы еще не зарегистрированы, то самое время <a href="register.html">зарегистрироваться</a></p>
		</div>
	</div>
	<div class="row mt-3">
		<div class="col-4 offset-4">
			<form>
				<div class="mb-3">
					<label for="login-input" class="form-label">Логин</label>
					<input type="text" class="form-control is-valid" id="login-input" required>
				</div>
				<div class="mb-3">
					<label for="password-input" class="form-label">Пароль</label>
					<input type="password" class="form-control is-invalid" id="password-input" required>
				</div>
				<button type="submit" class="btn btn-primary">Войти</button>
			</form>
		</div>
	</div>
</main>

<?php include 'includes/footer.php' ?>