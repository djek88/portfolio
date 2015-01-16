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
				$scope.cameAnswer = true;
			});

			$scope.$watch("all_albums", function(newValue, oldValue) {
				if (typeof newValue === "object") {
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
						$scope.nameAlbum = "";
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

				// filters
			uploader.filters.push({
				name: 'imageFilter',
				fn: function(item /*{File|FileLikeObject}*/ , options) {
					var type = '|' + item.type.slice(item.type.lastIndexOf('/') + 1) + '|';
					/*Check the type file and file size*/
					return ('|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1 && item.size/1024/1024 < 10);
				}
			});

				// callbacks
			uploader.onWhenAddingFileFailed = function(item /*{File|FileLikeObject}*/ , filter, options) {
				console.info('onWhenAddingFileFailed', item, filter, options);
			};
			uploader.onAfterAddingFile = function(fileItem) {
				fileItem.formData = [{title: '', description: '', album: ''}];
				fileItem.isValidFields = true;

				// Override "upload" method
				var upload = fileItem.upload;
				fileItem.upload = function() {
					fileItem.isValidFields = (/^([a-zA-Z1-9]{5,30})$/.test(fileItem.formData[0].title) &&
											  /^([a-zA-Z1-9 ]{10,100})$/.test(fileItem.formData[0].description) &&
											  fileItem.formData[0].album != "");
					if(fileItem.isValidFields) {
						upload.call(fileItem);
					} else {
						upload.call(fileItem);
						fileItem.cancel();
					}
				}

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
				console.info('onCompleteItem', response, status, headers);
			};
			uploader.onCompleteAll = function() {
				console.info('onCompleteAll');
			};
			console.info('uploader', uploader);
		}
	])

	.controller('DeletePageCtrl', ['$scope', '$http', '$timeout',
		function($scope, $http, $timeout) {
			//INITIAL DATA SECTION
			$scope.cameAnswer = false;

			$http.get('/admin/deletePage').success(function(data) {
				$scope.all_albums = data.all_albums;
				$scope.albums_with_photos = data.albums_with_photos;
				$scope.all_photos = data.all_photos;
				$scope.cameAnswer = true;
			});

			$scope.$watch("all_albums", function(newValue, oldValue) {
				if (typeof newValue === "object") {
					$scope.isExistAlbum = (newValue.length != 0);
				}
			});

			$scope.$watch("all_photos", function(newValue, oldValue) {
				if (typeof newValue === "object") {
					$scope.isExistPhotos = (newValue.length != 0);
				}
			});
			//ALBUM SECTION
			$scope.message = "";
			$scope.isAlbumDelete = true;

			var albumNotify = function(time) {
				$scope.isAlbumDelete = false;
				$timeout(function() {
					$scope.isAlbumDelete = true;
				}, time);
			}

			$scope.deleteAlbum = function(id_album) {
				$http.post('/admin/deletePage', {
					'id_album': id_album
				}).success(function(data) {
					if(data.isDeleteAlbum) {
						$scope.message = 'Альбом удален.';
						$scope.all_albums = data.all_albums;
						$scope.albums_with_photos = data.albums_with_photos;

						if(data.deletedPhotos) {
							$scope.message += ' Фотографий удалено: '+data.deletedPhotos;
						}
						albumNotify(4000);
					}
				});
			}

			// PHOTO SECTION

			// Реализовать вывод не всех фотографий и подгрузку их
			// Реализовать пакетное удаление
			// улучшить интерфейс
			// Разместить заглавный текст по середине
		}
	])

	.controller('EditPageCtrl', ['$scope',
		function($scope) {
			$scope.edit = "editpage";
		}
	]);