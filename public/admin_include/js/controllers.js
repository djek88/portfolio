'use strict';

/* Controllers */

angular

	.module('mainModule')

	.controller('AddPageCtrl', ['$scope', '$http', '$timeout', 'FileUploader',
		function($scope, $http, $timeout, FileUploader) {
			//INITIAL DATA SECTION
			$scope.cameAnswer = false;

			$http.get('/admin/addPage').success(function(data) {
				$scope.all_albums = data;
			});

			$scope.$watch("all_albums", function(newValue, oldValue) {
				if (typeof newValue === "object") {
					$scope.cameAnswer = true;
					$scope.isExistAlbum = (newValue.length != 0);
				}
			});

			//ALBUM SECTION
			$scope.nameAlbum = "";
			$scope.correctNameAlbum = true;
			$scope.message = "";

			var albumNotify = function(time) {
				$scope.correctNameAlbum = false;
				$timeout(function() {
					$scope.correctNameAlbum = true;
				}, time);
			}

			$scope.submitFormAlbum = function() {
				$scope.correctNameAlbum = $scope.albumForm.formInputName.$valid;
				if ($scope.correctNameAlbum) {
					$http.post('/admin/addPage', {
						'name_album': $scope.nameAlbum
					}).success(function(data) {
						$scope.message = data.message;
						$scope.all_albums = data.all_albums;
						albumNotify(4000);
					});
				} else {
					$scope.message = "Имя альбома не корректное! Кириллица, от 5 символов...";
					albumNotify(4000);
				}
			};

			//PHOTO SECTION
			var uploader = $scope.uploader = new FileUploader({
				url: '/admin/addPage'
			});

			// FILTERS
			uploader.filters.push({
				name: 'imageFilter',
				fn: function(item /*{File|FileLikeObject}*/ , options) {
					var type = '|' + item.type.slice(item.type.lastIndexOf('/') + 1) + '|';
					return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
				}
			});

			// CALLBACKS
			uploader.onWhenAddingFileFailed = function(item /*{File|FileLikeObject}*/ , filter, options) {
				console.info('onWhenAddingFileFailed', item, filter, options);
			};
			uploader.onAfterAddingFile = function(fileItem) {
				fileItem.formData = [{title: '', description: '', album: ''}];
				console.info('onAfterAddingFile', fileItem);
			};
			uploader.onAfterAddingAll = function(addedFileItems) {
				console.info('onAfterAddingAll', addedFileItems);
			};
			uploader.onBeforeUploadItem = function(item) {
				console.info('onBeforeUploadItem', item);
			};
			uploader.onProgressItem = function(fileItem, progress) {
				console.info('onProgressItem', fileItem, progress);
			};
			uploader.onProgressAll = function(progress) {
				console.info('onProgressAll', progress);
			};
			uploader.onSuccessItem = function(fileItem, response, status, headers) {
				console.info('onSuccessItem', fileItem, response, status, headers);
			};
			uploader.onErrorItem = function(fileItem, response, status, headers) {
				console.info('onErrorItem', fileItem, response, status, headers);
			};
			uploader.onCancelItem = function(fileItem, response, status, headers) {
				console.info('onCancelItem', fileItem, response, status, headers);
			};
			uploader.onCompleteItem = function(fileItem, response, status, headers) {
				console.info('onCompleteItem', fileItem, response, status, headers);
			};
			uploader.onCompleteAll = function() {
				console.info('onCompleteAll');
			};

			console.info('uploader', uploader);
		}
	])

	.controller('DeletePageCtrl', ['$scope',
		function($scope) {
			$scope.delete = "deletepage";
		}
	])

	.controller('EditPageCtrl', ['$scope',
		function($scope) {
			$scope.edit = "editpage";
		}
	]);