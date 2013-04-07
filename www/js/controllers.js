'use strict';

/* Controllers */

function TodoCtrl($scope, $http, Task, filterFilter) {
  $scope.tasks = Task.query();
  
  $scope.newTodo = '';
  
  $scope.save = function() {
    $scope.tasks.forEach(function(task) {
      if (typeof(task.task_id) == 'undefined') {
        Task.save(task);
      } else {
        Task.update(task);
      }
    });
  };

  $scope.addTodo = function() {
    $scope.tasks.push({name: $scope.newTodo, completed: 0});
    
    $scope.newTodo = '';
  }

  $scope.$watch('tasks', function () {
    $scope.remainingCount = filterFilter($scope.tasks, {completed: 0}).length;
    $scope.completedCount = $scope.tasks.length - $scope.remainingCount;
    $scope.allChecked = !$scope.remainingCount;
  }, true);
}

