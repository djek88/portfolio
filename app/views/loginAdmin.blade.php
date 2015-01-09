<!DOCTYPE html>
<html lang="en">
	<head>
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <title>Login</title>

	    <!-- Bootstrap -->
	    <link href="/_include/css/bootstrap3.min.css" rel="stylesheet">

	    <link href="/_include/css/login.css" rel="stylesheet">
	</head>

	<body>
 	 	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	 		<div class="container">
	 			<div class="navbar-header">
			      <a class="navbar-brand" href="#">Admin Panel</a>
			    </div>
			    <div class="collapse navbar-collapse">
		 			<ul class="nav navbar-nav">
		 				<li><a href="http://portfolio.ru/">Главная</a></li>
		 				<li><a href="#">В группу</a></li>
		 			</ul>
	 			</div>
	 		</div>
		</nav>

		<div class="container">
			<form class="form-signin" role="form" action="{{ URL::route('loginPageAdmin') }}" method="post">
				<h2 class="form-signin-heading">Please sign in</h2>
				<input name="login" type="login" class="form-control" placeholder="User" required autofocus>
				<input name="password" type="password" class="form-control" placeholder="Password" required>
				<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
			</form>
		</div>
	</body>
</html>