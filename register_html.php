<?php require("html_imports.php"); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Login</title>

	<!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">


</head>
<body>
	<div class="container">
      <form class="form-signin" action="register.php" method="POST">
        <h2 class="form-signin-heading">Please register</h2>
        <label for="inputUsername" class="sr-only">username</label>
        <input type="text" id="inputUsername" class="form-control" placeholder="username" name = 'username' required autofocus>
        <label for="inputPassword" class="sr-only">password</label>
        <input type="password" id="inputPassword" class="form-control" placeholder="password" name = 'password' required>
       <button class="btn btn-lg btn-primary btn-block" type="submit"> register </button>
      </form>

    </div> <!-- /container -->

</body>
</html>