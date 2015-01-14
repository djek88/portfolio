<!doctype html>
<html lang="en" ng-app="mainModule">
<head>
	<meta charset="utf-8">
	<title>Admin Page</title>

	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-theme.min.css"/>
	<link rel="stylesheet" href="/admin_include/css/admin.css"><!-- admin.css -->

	<script src="/admin_include/bower_components/angular/angular.js"></script>
	<script src="/admin_include/bower_components/angular-route/angular-route.js"></script>
	<!--<script src="/admin_include/bower_components/angular-resource/angular-resource.js"></script>-->
	<script src="/admin_include/js/angular-file-upload.js"></script>
	<script src="/admin_include/js/app.js"></script>
	<script src="/admin_include/js/controllers.js"></script>
	<script src="/admin_include/js/filters.js"></script>
	<script src="/admin_include/js/services.js"></script>
	<script src="/admin_include/js/directives.js"></script>

	<!--<script type="text/javascript" src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>-->
</head>
<body>
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation" style="border: none;">
		<div class="container">
			<div class="navbar-header">
				<a class="navbar-brand" href="/">Admin Panel</a>
			</div>
			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li><a href="/">Главная</a></li>
					<li><a href="#/addPage">Добавить</a></li>
					<li><a href="#/deletePage">Удалить</a></li>
					<li><a href="#/editPage">Редактировать</a></li>
				</ul>
			</div>
		</div>
	</nav>

	<div ng-view></div>

</body>
</html>