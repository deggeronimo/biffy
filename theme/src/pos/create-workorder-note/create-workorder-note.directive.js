'use strict';

angular.module('biffyApp')
    .directive('createWorkorderNote', function () {
        return {
            restrict: 'E',
            replace: true,
            scope: {
                statuses: '=',
                user: '=',
                note: '=',
                createFn: '&',
                deviceTypeId: '=',
                workorder: '='
            },
            templateUrl: 'src/pos/create-workorder-note/create-workorder-note.directive.html',
            controller: function ($scope, RestangularAppService, $modal) {
                $scope.selectedStatus = _.findWhere($scope.statuses, {id: $scope.workorder.workorder_status_id});

                var parseNextUpdateTime = function () {
                    var nextTime = $scope.selectedStatus.next_time;
                    var time;

                    if (nextTime === null) {
                        time = moment($scope.workorder.next_update, 'YYYY-MM-DD HH:mm:ss');
                    } else if (nextTime.indexOf('|') >= 0) {
                        var pieces = nextTime.split('|');
                        time = moment().add(pieces[0], pieces[1]);
                    } else {
                        switch (nextTime) {
                            case 'queue':
                                break;
                            case 'po':
                                break;
                            case 'order':
                                break;
                            default:
                                break;
                        }

                        time = moment(); // todo move this to default case and implement other cases
                    }

                    return time.unix() * 1000;
                };

                $scope.clearWorkOrderNote = function () {
                    $scope.note = {
                        'public' : 0,
                        'work_order_id' : $scope.workorder.id,
                        'workorder_status_id' : $scope.workorder.workorder_status_id,
                        'diag': null,
                        'next_update_time' : parseNextUpdateTime() // todo set to appropriate time in the future
                    };
                };
                $scope.clearWorkOrderNote();

                $scope.createNote = function()
                {
                    $scope.note.next_update_time /= 1000;
                    $scope.createFn();
                    $scope.clearWorkOrderNote();
                };

                $scope.setNextUpdate = function (newDate) {
                    $scope.note.next_update_time = moment(newDate).unix() * 1000;
                };

                $scope.selectStatus = function () {
                    $scope.note.workorder_status_id = $scope.selectedStatus.id;
                    $scope.note.next_update_time = parseNextUpdateTime();
                };

                $scope.checklist = [];
                $scope.addDiag = function () {
                    RestangularAppService.all('devicechecklists').getList({'device_type_id': $scope.deviceTypeId}).then(function (result) {
                        $scope.checklist = result;
                        var modalInstance = $modal.open({
                            templateUrl: 'src/pos/create-workorder-note/add-diag.modal.html',
                            controller: function ($scope, $modalInstance, checklist) {
                                $scope.checklist = checklist;

                                $scope.add = function () {
                                    $modalInstance.close($scope.checklist);
                                };

                                $scope.cancel = function () {
                                    $modalInstance.dismiss('cancel');
                                };
                            },
                            size: 'lg',
                            resolve: {
                                checklist: function () {
                                    return $scope.checklist;
                                }
                            }
                        });

                        modalInstance.result.then(function (diag) {
                            $scope.note.diag = diag.plain();
                        });
                    });
                };
            }
        };
    });