'use strict';

/* App Module */

angular

	.module('mainModule', [
		'ngRoute',
		'angularFileUpload',
		'nsPopover'
	])

	.config(['$routeProvider',
		function($routeProvider) {
			$routeProvider.
			when('/addPage', {
				templateUrl: '/admin_include/partials/add-page.html',
				controller: 'AddPageCtrl'
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