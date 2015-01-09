'use strict';

/* Controllers */

angular
  .module('mainModule')

  .controller('AddPageCtrl', ['$scope', '$http', '$timeout',
    function($scope, $http, $timeout) {
      //INITIAL DATA SECTION
      $scope.cameAnswer = false;
      $http.get('/admin/addPage').success(function (data) {
        $scope.all_albums = data;
      });

      $scope.$watch("all_albums", function(newValue, oldValue) {
        if(typeof newValue === "object") {
          $scope.cameAnswer = true;
          $scope.isExistAlbum = (newValue.length != 0);
        }
      });
     
      //ALBUM SECTION
      $scope.nameAlbum = "";
      $scope.correctNameAlbum = true;
      $scope.message = "";

      var albumNotify = function (time) {
        $scope.correctNameAlbum = false;
        $timeout(function() {
          $scope.correctNameAlbum = true;
        }, time);
      }
      
      $scope.submitFormAlbum = function() {
        $scope.correctNameAlbum = $scope.albumForm.formInputName.$valid;
        if($scope.correctNameAlbum) {
          $http.post('/admin/addPage', {'name_album' : $scope.nameAlbum}).success(function(data) {
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

  }])

  .controller('DeletePageCtrl', ['$scope',
    function($scope) {
      $scope.delete = "deletepage";
  }])

  .controller('EditPageCtrl', ['$scope',
    function($scope) {
      $scope.edit = "editpage";
  }]);