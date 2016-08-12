'use strict';

angular.module('biffyApp').controller(
    'WebsiteFiltersEditController',
    function($q, $scope, $stateParams, $state, RestangularAppService, NotifierService)
    {
        $scope.filterGroupList = RestangularAppService.all('websitefiltergroups').getList().$object;

        $scope.id = $stateParams.id || null;
        $scope.data = {};
        $scope.mode = 'Loading';

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
            if($scope.isAdd())
            {
                $scope.nameAttributes = [];

                $scope.mode = 'Add';
            }
            else
            {
                $scope.createKeyNames();

                $scope.nameAttributes = RestangularAppService.one('languagekeys', $scope.nameLanguageKey).all('strings').getList().$object;

                RestangularAppService.one('websitefilters', $scope.id).get().then(
                    function(data)
                    {
                        $scope.mode = 'Edit';
                        $scope.data = data;
                    },
                    function(data)
                    {
                        NotifierService.error('Invalid reference, cannot load data ' + JSON.stringify(data.data.messages));
                    }
                );
            }
        };

        $scope.store = function()
        {
            RestangularAppService.all('websitefilters').post($scope.data).then(
                function()
                {
                    $scope.id = data.id;

                    $scope.createKeyNames();

                    $scope.saveLanguageKeySet($scope.languageKeySet, 'website.filters');
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

                    $scope.saveLanguageKeySet($scope.languageKeySet, 'website.filters');
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
                    $state.transitionTo('website.filters');
                },
                function(data)
                {
                    NotifierService.error('Could not delete ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.cancel = function()
        {
            $state.transitionTo('website.filters');
        };

        $scope.languageList = RestangularAppService.all('languages').getList().$object;

        $scope.createKeyNames = function()
        {
            var languageKeyBase = 'website_filter_' + $scope.id + '_';

            $scope.nameLanguageKey = languageKeyBase + 'name';

            $scope.languageKeySet = [
                { key: $scope.nameLanguageKey, values: $scope.nameAttributes }
            ];
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

        $scope.init();
    }
);