<?php require("html_imports.php"); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Register</title>

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
          <div style="padding:10  10">
            <select type="text" class="form-control"  name = 'period' >
              <option>Period 1</option>
              <option>Period 2</option>
              <option>Period 3</option>
              <option>Period 4</option>
              <option>Period 5</option>
            </select>
          </div>

        <button class="btn btn-lg btn-primary btn-block" type="submit"> register </button>
      </form>
    </div> <!-- /container -->
</body>
</html>