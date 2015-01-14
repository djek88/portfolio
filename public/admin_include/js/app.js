'use strict';

/* App Module */

angular

	.module('mainModule', [
		'ngRoute',
		'angularFileUpload'
	])

	.config(['$routeProvider',
		function($routeProvider) {
			$routeProvider.
			when('/addPage', {
				templateUrl: '/admin_include/partials/add-page.html',
				controller: 'AddPageCtrl'
			}).
			when('/deletePage', {
				templateUrl: '/admin_include/partials/delete-page.html',
				controller: 'DeletePageCtrl'
			}).
			when('/editPage', {
				templateUrl: '/admin_include/partials/edit-page.html',
				controller: 'EditPageCtrl'
			}).
			otherwise({
				redirectTo: '/addPage'
			});
		}
	]);