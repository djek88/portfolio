<!DOCTYPE html>
<html lang="en">
	<head>
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <title>Admin page</title>

	    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css"/>
	    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-theme.min.css"/>
	    <link rel="stylesheet" href="/_include/css/custom.css"/>
	    <link rel="stylesheet" href="/_include/css/admin.css"><!-- admin.css -->

	    <script type="text/javascript" src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
	    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
	    @yield('header', '');
	</head>
	<body>
		<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation" style="border: none;">
	 		<div class="container">
	 			<div class="navbar-header">
			      <a class="navbar-brand" href="#">Admin Panel</a>
			    </div>
			    <div class="collapse navbar-collapse">
		 			<ul class="nav navbar-nav">
		 				<li><a href="/">Главная</a></li>
		 				<li><a href="/admin/addPage">Добавить</a></li>
		 				<li><a href="/admin/deletePage">Удалить</a></li>
		 				<li><a href="/admin/editPage">Редактировать</a></li>
		 			</ul>
	 			</div>
	 		</div>
		</nav>
		<div class="wrapper">