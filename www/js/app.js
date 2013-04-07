'use strict';

// Declare app level module which depends on filters, and services
angular.module('TodoApp', ['Todo.filters', 'Todo.services', 'Todo.directives']).
  config(['$routeProvider', function($routeProvider) {
    $routeProvider.otherwise({redirectTo: '/'});
  }]);
