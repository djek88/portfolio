'use strict';

/* Filters */

angular
	.module('mainModule')
	
	.filter('checkmark', function() {
	  return function(input) {
	    return input ? '\u2713' : '\u2718';
	  };
	});