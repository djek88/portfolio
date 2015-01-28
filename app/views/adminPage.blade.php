<!doctype html>
<html lang="en" ng-app="mainModule">
<head>
	<meta charset="utf-8">
	<title>Admin Page</title>

	<link rel="stylesheet" href="/admin_include/bower_components/bootstrap/dist/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="/admin_include/css/nsPopover.css">
	<link rel="stylesheet" href="/admin_include/css/admin.css"><!-- admin.css -->

	<!-- <script src="/admin_include/bower_components/jquery/jquery.min.js"></script> -->

	<script src="/admin_include/bower_components/angular/angular.js"></script>
	<script src="/admin_include/bower_components/angular-route/angular-route.js"></script>
	<script src="/admin_include/js/angular-file-upload.js"></script>
	<script src="/admin_include/js/nsPopover.js"></script>
	<script src="/admin_include/js/app.js"></script>
	<script src="/admin_include/js/controllers.js"></script>
	<script src="/admin_include/js/filters.js"></script>
	<script src="/admin_include/js/services.js"></script>
	<script src="/admin_include/js/directives.js"></script>
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
					<li><a href="#/editPage">Редактировать</a></li>
				</ul>
			</div>
		</div>
	</nav>

	<div ng-view></div>

</body>
</html>