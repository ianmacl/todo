'use strict';

/* Filters */

angular.module('Todo.filters', []).
  filter('interpolate', ['version', function(version) {
    return function(text) {
      return String(text).replace(/\%VERSION\%/mg, version);
    };
  }]).
  filter('intToBool', function() {
    return function(input) {
      return input == 1 ? true : false;
    }
  });
