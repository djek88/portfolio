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
		$scope.page_data = {};
		$scope.cameAnswer = false;
		$scope.isAllAlbumsShow = true;
		$scope.selectAlbumId = null;
		$scope.isAllPhotosShowInSelectAlbum = true;

		$http.post('/admin/deletePage/getAlbum', {
			'offset_album' : 0,
			'amount_album': 4,
			'amount_photo_in_album' : 0
		}).success(function(data) {
			$scope.cameAnswer = true;
			$scope.page_data = data;
		});

		var check_isExistAlbums_isAllAlbumsShow = function(page_data) {
			$scope.isExistAlbums = (page_data.albums.length != 0);
			$scope.isAllAlbumsShow = (page_data.amount_albums - page_data.albums.length == 0);
		}

		var check_isAllPhotosShowInSelectAlbum = function(album) {
			$scope.isAllPhotosShowInSelectAlbum = (album.amount_photos - album.photos.length == 0);
		}

		var getMorePhotos = function(album, amount_photos) {
			$scope.hasActiveRequest = true;
			$http.post('/admin/deletePage/getPhoto', {
				'id_album' 		: album.id,
				'offset' 		: album.photos.length,
				'amount_photo' 	: amount_photos
			}).success(function(data) {
				$scope.hasActiveRequest = false;
				album.photos = album.photos.concat(data);
				check_isAllPhotosShowInSelectAlbum(album);
			}).error(function(data) {
				$scope.hasActiveRequest = false;
			});
		}

		$scope.$watch("selectAlbumId", function(newValue, oldValue) {
			console.log("Зашло в наблюдатель selectAlbumId");
			console.log(oldValue);
			console.log(newValue);
			if($scope.selectAlbumId != null) {
				for (var i = 0; i < $scope.page_data.albums.length; i++) {
					if($scope.page_data.albums[i].id == $scope.selectAlbumId) {
						if ($scope.page_data.albums[i].amount_photos > 0 && $scope.page_data.albums[i].photos.length == 0) {
							$scope.isAllPhotosShowInSelectAlbum = true;
							getMorePhotos($scope.page_data.albums[i], 6);
						}
						check_isAllPhotosShowInSelectAlbum($scope.page_data.albums[i]);
						break;							
					}
				}
			}
		});

		$scope.$watch("page_data", function(newValue, oldValue) {
			console.log("Зашло в наблюдатель page_data!");
			console.log(oldValue);
			console.log(newValue);
			if ($scope.cameAnswer) {
				check_isExistAlbums_isAllAlbumsShow(newValue);
			}
		}, true);

		$scope.filteringAlbums = function(album) {
			return album.id === $scope.selectAlbumId;
		}

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
			$scope.hasActiveRequest = true;

			$http.post('/admin/deletePage/deleteAlbum', {
				'id_album': id_album
			}).success(function(data) {
				$scope.hasActiveRequest = false;
				if(data) {
					$scope.message = 'Альбом удален.';
					$scope.page_data.amount_albums -= 1;
					// splice с foreachом работает не корректно нужно использовать
					// for в обратном порядке 
					for (var i = $scope.page_data.albums.length - 1; i >= 0; i--) {
						if ($scope.page_data.albums[i].id_albom == id_album) {
							$scope.page_data.albums.splice(i, 1);							
						}
					}
					if(data.deletedPhotos) {
						$scope.message += ' Фотографий удалено: '+data.deletedPhotos;
					}					
					albumNotify(4000);				
				}
			}).error(function(data) {
				$scope.hasActiveRequest = false;
				$scope.message = 'Альбом не удален! Ошибка на сервере.';
				albumNotify(4000);
			});
		}

		$scope.more_albums = function() {
			$scope.isAllAlbumsShow = true;

			$http.post('/admin/deletePage/getAlbum', {
				'offset_album' : $scope.page_data.albums.length,
				'amount_album': 4,
				'amount_photo_in_album' : 0
			}).success(function(data) {
				if(!data.albums.length) {
					check_isExistAlbums_isAllAlbumsShow($scope.page_data);
				} else {
					$scope.page_data.amount_albums = data.amount_albums;
					$scope.page_data.albums = $scope.page_data.albums.concat(data.albums);
				}
			}).error(function(data) {
				check_isExistAlbums_isAllAlbumsShow($scope.page_data);
			});
		}

		// PHOTO SECTION

		/*$scope.getArrayAllPhotos = function () {
			var allPhotos = [];
			angular.forEach($scope.page_data.albums, function(album, key){
				angular.forEach(album.photos, function(photo, key){
					allPhotos.push(photo);//// переделать используя конкатинация массивов
				});
			});
			return allPhotos;
		}*/

		$scope.more_photos = function(id_album) {
			for (var i = 0; i < $scope.page_data.albums.length; i++) {
				if($scope.page_data.albums[i].id == id_album) {
					$scope.isAllPhotosShowInSelectAlbum = true;
					getMorePhotos($scope.page_data.albums[i], 6);
					break;
				}
			}				
		}
	}
	])// Реализовать пакетное удаление

.controller('EditPageCtrl', ['$scope',
	function($scope) {
		$scope.edit = "editpage";
	}
	]);