<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Login entregas</title>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        <link rel="stylesheet" href="../css_styles/login_style.css" type="text/css">
	</head>
	<body>
		<div class="login">
			<h1>Login</h1>
            <div>
            <label id="lbl-message">
                <?php
                session_start();

                if (isset($_SESSION['message-login']) && $_SESSION['message-login'])
                {
                    printf('<b>%s</b>', $_SESSION['message-login']);
                    unset($_SESSION['message-login']);
                }
                ?>
            </label>
            </div>
			<form action="autenticar.php" method="post">
				<label for="username">
					<i class="fas fa-user"></i>
				</label>
				<input type="text" name="username" placeholder="Username" id="username" autocomplete="off" required>
				<label for="password">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="password" placeholder="Password" id="password" required>

				<input type="submit" value="Login">
			</form>
		</div>
	</body>
</html>