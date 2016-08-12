'use strict';

angular.module('biffyApp')
    .directive('workorderNoteList', function () {
        return {
            restrict: 'E',
            replace: true,
            templateUrl: 'src/pos/workorder-note-list/workorder-note-list.directive.html',
            scope: {
                notes: '='
            },
            controller: function ($scope) {
                for (var i = 0; i < $scope.notes.length; i++) {
                    var diag = $scope.notes[i].diag;
                    if (diag !== null) {
                        diag = JSON.parse(diag);
                        $scope.notes[i].diag = diag;
                    }
                }

                $scope.filterNoteDateTime = function(datetime)
                {
                    var currentTime = new Date();
                    currentTime.setHours(0, 0, 0, 0);

                    var noteTime = new Date(datetime);
                    noteTime.setHours(0, 0, 0, 0);

                    var returnTime = new Date(datetime);

                    if (currentTime <= noteTime && currentTime >= noteTime)
                    {
                        return 'Today at ' + returnTime.toLocaleTimeString();//returnTime.getHours() + ':' + returnTime.getMinutes() + ':' + returnTime.getSeconds();
                    }
                    else
                    {
                        return returnTime.toLocaleDateString() + ' ' + returnTime.toLocaleTimeString();
                    }
                };
            }
        };
    });