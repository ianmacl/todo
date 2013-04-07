'use strict';

/* Services */


// Demonstrate how to register services
// In this case it is a simple value service.
angular.module('Todo.services', ['ngResource']).
  factory('Task', function($resource){
    return $resource(
      'api/tasks/:taskId',
      {taskId:'@task_id'},
      {
        'get':    {method:'GET'},
        'save':   {method:'POST'},
        'query':  {method:'GET', isArray:true},
        'remove': {method:'DELETE'},
        'delete': {method:'DELETE'},
        'update': {method:'PUT'}
      }        
    );
  });
