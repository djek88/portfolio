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

.controller('EditPageCtrl', ['$scope', '$http', '$timeout', '$rootScope',
	function($scope, $http, $timeout, $rootScope) {
		//INITIAL DATA SECTION
		$scope.page_data = {};
		$scope.cameAnswer = false;
		$scope.isAllAlbumsShow = true;
		$scope.selectAlbumId = null;
		$scope.isAllPhotosShowInSelectAlbum = true;

		$http.post('/admin/editPage/getAlbum', {
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
			$http.post('/admin/editPage/getPhoto', {
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

		// ALBUM SECTION
		$scope.newNameAlbum = "";
		$scope.newNameAlbumCorrect = true;		
		$scope.message = "";
		$scope.isAlbumDelete = true;

		var albumNotify = function(time) {
			$scope.isAlbumDelete = false;
			$timeout(function() {
				$scope.isAlbumDelete = true;
			}, time);
		}

		$scope.editNameAlbum = function(idAlbum, nameAlbum) {
			$scope.hasActiveRequest = true;
			for (var i = 0; i < $scope.page_data.albums.length; i++) {
				if($scope.page_data.albums[i].id == idAlbum) {
					var oldNameAlbum = $scope.page_data.albums[i].name;
					break;
				}
			};
			$scope.newNameAlbumCorrect = (nameAlbum != oldNameAlbum && /^[a-zA-Z]{1,1}[a-zA-Z1-9]{4,29}$/.test(nameAlbum));

			if($scope.newNameAlbumCorrect) {
				$http.post('/admin/editPage/editAlbum', {
					'id_album'	: idAlbum,
					'name_album': nameAlbum
				}).success(function(data) {
					$scope.hasActiveRequest = false;
					if(data) {
						$rootScope.$broadcast('ns:popover:hide');
						for (var i = 0; i < $scope.page_data.albums.length; i++) {
							if($scope.page_data.albums[i].id == idAlbum) {
								$scope.newNameAlbum = "";
								$scope.page_data.albums[i].name = nameAlbum;

								for (var j = 0; j < $scope.page_data.albums[i].photos.length; j++) {
									$scope.page_data.albums[i].photos[j].name_album = nameAlbum;
								};
								break;
							}
						};
					}
				}).error(function(data) {
					$scope.hasActiveRequest = false;
				});
			} else {
				$scope.hasActiveRequest = false;
			}
		};

		$scope.deleteAlbum = function(id_album) {
			$scope.hasActiveRequest = true;

			$http.post('/admin/editPage/deleteAlbum', {
				'id_album': id_album
			}).success(function(data) {
				$scope.hasActiveRequest = false;
				if(data && data.DeletedAlbum > 0) {
					$scope.message = 'Альбом удален.';					
					for (var i = $scope.page_data.albums.length - 1; i >= 0; i--) {
						if ($scope.page_data.albums[i].id == id_album) {
							$scope.page_data.albums.splice(i, 1);
							$scope.page_data.amount_albums -= 1;
							break;
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

			$http.post('/admin/editPage/getAlbum', {
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
		$scope.newTitlePhoto = "";
		$scope.newDescriptionPhoto = "";
		$scope.isSelectDescription = false;
		$scope.newTitleDescriptionCorrect = true;

		$scope.editPhoto = function(idPhoto, titlePhoto, descriptionPhoto, oldTitlePhoto, oldDescriptionPhoto) {
			$scope.hasActiveRequest = true;

			var titleCorrect = (titlePhoto != oldTitlePhoto && /^([a-zA-Z1-9]{5,30})$/.test(titlePhoto));
			var descriptionCorrect = (descriptionPhoto != oldDescriptionPhoto && /^([a-zA-Z1-9 ]{10,100})$/.test(descriptionPhoto));

			if(!$scope.isSelectDescription && titleCorrect) {
				$scope.newTitleDescriptionCorrect = true;
			} else if($scope.isSelectDescription && titleCorrect && descriptionCorrect) {
				var sendDescPhoto = descriptionPhoto;
				$scope.newTitleDescriptionCorrect = true;
			} else {
				$scope.hasActiveRequest = false;
				$scope.newTitleDescriptionCorrect = false;
				return;
			}

			console.log("message");
			/*$http.post('/admin/editPage/editPhoto', {
				'id_album'			: idAlbum,
				'id_Photo'			: idPhoto,
				'title_photo'		: titlePhoto,
				'description_photo'	: sendDescPhoto
			}).success(function(data) {
				$scope.hasActiveRequest = false;
				console.log(data);

			}).error(function(data) {
				$scope.hasActiveRequest = false;
			});*/
		}

		$scope.delete_photo = function(id_album, id_photo) {
			$scope.hasActiveRequest = true;

			$http.post('/admin/editPage/deletePhoto', {
				'id_photo': id_photo
			}).success(function(data) {
				$scope.hasActiveRequest = false;
				if(data) {
					for (var i = 0; i < $scope.page_data.albums.length; i++) {
						if ($scope.page_data.albums[i].id == id_album) {
							for (var j = $scope.page_data.albums[i].photos.length-1; j >= 0; j--) {
								if($scope.page_data.albums[i].photos[j].id_photo == id_photo) {
									$scope.page_data.albums[i].photos.splice(j, 1);
									$scope.page_data.albums[i].amount_photos -= 1;
									break;
								}
							};
							if($scope.page_data.albums[i].photos.length == 0 &&
									$scope.page_data.albums[i].amount_photos > 0) {
								more_photos(id_album);
							}
							break;
						}
					};
				}
			}).error(function(data) {
				$scope.hasActiveRequest = false;
			});
		}

		var more_photos = function(id_album) {
			for (var i = 0; i < $scope.page_data.albums.length; i++) {
				if($scope.page_data.albums[i].id == id_album) {
					$scope.isAllPhotosShowInSelectAlbum = true;
					getMorePhotos($scope.page_data.albums[i], 3);
					break;
				}
			}				
		}

		window.onscroll = function() {
			var isPageDown = (document.documentElement.scrollHeight - document.documentElement.clientHeight) <= window.pageYOffset;
			if(isPageDown && !$scope.isAllPhotosShowInSelectAlbum && !$scope.hasActiveRequest) {
				more_photos($scope.selectAlbumId);
			}
		}
	}
]);// Реализовать пакетное удаление
