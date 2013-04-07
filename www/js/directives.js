'use strict';

/* Directives */

angular.module('Todo.directives', []).
  directive('appVersion', ['version', function(version) {
    return function(scope, elm, attrs) {
      elm.text(version);
    };
  }]).directive('myDirective', function() {
    return {
        require: 'ngModel',
        link: function(scope, element, attrs, modelCtrl) {
            if(!modelCtrl) return;                   
            
            modelCtrl.$formatters.push(function(modelValue){
              return modelValue == 1 ? true : false;
            });
            
            modelCtrl.$parsers.push(function(modelValue){
              return modelValue == true ? 1 : 0;
            });
        }
    }
});
