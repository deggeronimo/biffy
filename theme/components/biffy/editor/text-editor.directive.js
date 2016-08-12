'use strict';

angular.module('biffy.editor')
    .directive('textEditor', function () {
        return {
            restrict: 'E',
            templateUrl: 'components/biffy/editor/text-editor.html',
            scope: {
                ngModel: '='
            },
            controller: function ($scope, RestangularAppService) {
                $scope.fileDrop = function (file, insertAction) {
                    if (file.name) {
                        var formData = new FormData();
                        formData.append('file', file);
                        formData.append('name', file.name);
                        RestangularAppService.all('file').withHttpConfig({transformRequest: angular.identity}).customPOST(formData, 'upload', undefined, {'Content-Type': undefined}).then(function (data) {
                            insertAction('insertHTML', '<img src="' + data.location + '">');
                        });
                    }
                    return true;
                };
            }
        };
    });