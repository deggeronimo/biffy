'use strict';

angular.module('biffyApp').controller(
    'DeviceTypesRepairsEditController',
    function($rootScope, $scope, $stateParams, $state, RestangularAppService, NotifierService)
    {
        $scope.languageList = RestangularAppService.all('languages').getList().$object;

        $scope.id = $stateParams.deviceRepairTypeId;
        $scope.data = { };
        $scope.mode = 'Loading';

        $scope.createKeyNames = function()
        {
            var languageKeyBase = 'device_repair_type_' + $scope.id + '_';

            $scope.estimatedCostLanguageKey = languageKeyBase + 'estimated_cost';
            $scope.nameLanguageKey = languageKeyBase + 'name';
            $scope.metaDescriptionLanguageKey = languageKeyBase + 'meta_description';
            $scope.metaKeywordsLanguageKey = languageKeyBase +  'meta_keywords';
            $scope.webDescriptionLanguageKey = languageKeyBase + 'web_description';

            $scope.languageKeySet = [
                { key: $scope.estimatedCostLanguageKey, values: $scope.estimatedCostAttributes },
                { key: $scope.nameLanguageKey, values: $scope.nameAttributes },
                { key: $scope.metaDescriptionLanguageKey, values: $scope.metaDescriptionAttributes },
                { key: $scope.metaKeywordsLanguageKey, values: $scope.metaKeywordsAttributes },
                { key: $scope.webDescriptionLanguageKey, values: $scope.webDescriptionAttributes }
            ];
        };

        $scope.isAdd = function()
        {
            return $scope.id === 'new';
        };

        $scope.isEdit = function()
        {
            return $scope.mode === 'Edit';
        };

        $scope.init = function()
        {
            if ($scope.isAdd())
            {
                $scope.estimatedCostAttributes = [];
                $scope.nameAttributes = [];
                $scope.metaDescriptionAttributes = [];
                $scope.metaKeywordsAttributes = [];
                $scope.webDescriptionAttributes = [];
            }
            else
            {
                RestangularAppService.one('devicerepairtypes', $scope.id).get().then(
                    function(data)
                    {
                        $scope.data = data;

                        $scope.mode = 'Edit';
                    },
                    function()
                    {

                    }
                );

                $scope.createKeyNames();

                $scope.estimatedCostAttributes = RestangularAppService.one('languagekeys', $scope.estimatedCostLanguageKey).all('strings').getList().$object;
                $scope.nameAttributes = RestangularAppService.one('languagekeys', $scope.nameLanguageKey).all('strings').getList().$object;
                $scope.metaDescriptionAttributes = RestangularAppService.one('languagekeys', $scope.metaDescriptionLanguageKey).all('strings').getList().$object;
                $scope.metaKeywordsAttributes = RestangularAppService.one('languagekeys', $scope.metaKeywordsLanguageKey).all('strings').getList().$object;
                $scope.webDescriptionAttributes = RestangularAppService.one('languagekeys', $scope.webDescriptionLanguageKey).all('strings').getList().$object;
            }
        };

        $scope.init();

        $scope.store = function()
        {
            RestangularAppService.all('devicerepairtypes').post($scope.data).then(
                function(data)
                {
                    $scope.id = data.id;

                    $scope.createKeyNames();

                    $scope.saveLanguageKeySet($scope.languageKeySet, 'devicetypes.repairs');
                },
                function(data)
                {
                    NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.update = function()
        {
            $scope.data.put().then(
                function()
                {
                    $scope.createKeyNames();

                    $scope.saveLanguageKeySet($scope.languageKeySet, 'devicetypes.repairs');
                },
                function(data)
                {
                    NotifierService.error('Could not edit ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.destroy = function()
        {
            $scope.data.remove().then(
                function()
                {
                    $state.transitionTo('devicetypes.repairs');
                },
                function(data)
                {
                    NotifierService.error('Could not delete ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.cancel = function()
        {
            $state.transitionTo('devicetypes.repairs');
        };

        $scope.saveLanguageKeySet = function(languageKeySet, transition)
        {
            var finished = 0;

            languageKeySet.forEach(
                function(element, index, array)
                {
                    RestangularAppService.one('languagekeys', element.key).all('strings').getList().then(
                        function(data)
                        {
                            for (var i=0; i<$scope.languageList.length; i++)
                            {
                                if (element.values[i])
                                {
                                    element.values[i].id = data[i].id;
                                }
                                else
                                {
                                    element.values[i] = { id: data[i].id }
                                }
                            }

                            RestangularAppService.one('languagekeys', element.key).one('strings', $scope.id).customPUT({strings:element.values}).then(
                                function()
                                {
                                    finished ++;

                                    if (finished == languageKeySet.length)
                                    {
                                        $state.transitionTo(transition);
                                    }
                                },
                                function()
                                {
                                    NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));
                                }
                            );
                        },
                        function()
                        {
                            NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));
                        }
                    );
                }
            );
        };
    }
);
