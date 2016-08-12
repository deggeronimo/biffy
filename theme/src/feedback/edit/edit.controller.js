'use strict';

/* jshint newcap: false */

angular.module('biffyApp')
    .controller('FeedbackEditController',
    function($rootScope, $state, $scope, $q, $location, $filter, ngTableParams, RestangularAppService, NotifierService, $interval, $modal)
    {
        $scope.adminUsers = RestangularAppService.all('users?admin=1').getList().$object;

        $scope.feedbackStatuses = [
            '', 'Reported', 'Customer Contacted', 'Awaiting Response', 'Resolved', 'Response Needed', 'Store Contacted', 'Other'
        ];

        $scope.agreeRatings = [
            '', 'Strongly Disagree', 'Disagree', 'Neutral', 'Agree', 'Strongly Agree'
        ];

        $scope.recommendRatings = [
            '', 'Very Unlikely', 'Unlikely', 'Neutral', 'Likely', 'Very Likely'
        ];

        $scope.satisfactionRatings = [
            '', 'Very Dissatisfied', 'Dissatisfied', 'Neutral', 'Satisfied', 'Very Satisfied'
        ];

        $scope.wellRatings = [
            '', 'Very Poorly', 'Poorly', 'Neutral', 'Well', 'Very Well'
        ];

        $scope.feedbackStatusRange = function()
        {
            var result = [];
            for (var i = 1; i < $scope.feedbackStatuses.length; i++)
            {
                result.push($scope.feedbackStatuses[i]);
            }
            return result;
        };

        $scope.feedbackId = $state.params.feedbackId;

        $scope.loadFeedback = function()
        {
            RestangularAppService.one('feedback', $scope.feedbackId).get().then(
                function(data)
                {
                    $scope.feedback = data;

                    $scope.currentCustomer = $scope.feedback.customer;

                    $scope.assignedToUserId = $scope.feedback.assigned_to.id;
                    $scope.selectedFeedbackStatusId = parseInt($scope.feedback.feedback_status_id);

                    $scope.setCurrentFeedback();
                },
                function()
                {

                }
            );
        };

        $scope.findById = function(arr, id)
        {
            for (var i=0; i<arr.length; i++)
            {
                if (arr[i].id == id)
                {
                    return arr[i];
                }
            }

            return null;
        };

        $scope.assignedToUserId = 0;
        $scope.$watch(
            'assignedToUserId',
            function()
            {
                if ($scope.feedback != null && $scope.feedback.assigned_to.id != $scope.assignedToUserId)
                {
                    $scope.feedback.assigned_to = $scope.findById($scope.adminUsers, $scope.assignedToUserId);
                    $scope.feedback.assigned_to_user_id = $scope.selectedFeedbackStatusId;

                    RestangularAppService.one('feedback', $scope.feedback.id).put({'assigned_to_user_id' : $scope.assignedToUserId});
                }
            }
        );

        $scope.selectedFeedbackStatusId = 0;
        $scope.$watch(
            'selectedFeedbackStatusId',
            function()
            {
                if ($scope.feedback != null && $scope.selectedFeedbackStatusId != 0)
                {
                    $scope.feedback.feedback_status_id = $scope.selectedFeedbackStatusId;

                    RestangularAppService.one('feedback', $scope.feedback.id).put({'feedback_status_id' : $scope.selectedFeedbackStatusId});
                }
            }
        );

        $scope.reloadCustomer = function()
        {
            $scope.currentCustomer = RestangularAppService.one('customers', $scope.currentCustomer.id).get().$object;
        };

        $scope.loadFeedback();

        $scope.currentItem = null;
        $scope.currentItemType = 'none';

        $scope.setCurrentCall = function(id)
        {
            $scope.currentItem = $scope.findById($scope.feedback.feedback_call_logs, id);
            $scope.currentItemType = 'call';
        };

        $scope.setCurrentDocument = function(id)
        {
            $scope.currentItem = $scope.findById($scope.feedback.feedback_docs, id);
            $scope.currentItemType = 'document';
        };

        $scope.setCurrentFeedback = function()
        {
            $scope.currentItem = $scope.feedback;
            $scope.currentItemType = 'feedback';
        };

        $scope.setCurrentNote = function(id)
        {
            $scope.currentItem = $scope.findById($scope.feedback.feedback_notes, id);
            $scope.currentItemType = 'note';
        };

        $scope.addNewDocument = function()
        {
            var modalInstance = $modal.open(
                {
                    templateUrl : 'src/modals/feedback.new.document.modal.html',
                    controller : 'FeedbackNewDocumentModalController',
                    size : 'lg',
                    resolve : {
                        feedbackId : function()
                        {
                            return $scope.feedback.id;
                        }
                    }
                }
            );

            modalInstance.result.then(
                function(data)
                {
                    $scope.loadFeedback();
                },
                function()
                {
                }
            );
        };

        $scope.addExistingDocument = function()
        {
            var modalInstance = $modal.open(
                {
                    templateUrl : 'src/modals/feedback.existing.document.modal.html',
                    controller : 'FeedbackExistingDocumentModalController',
                    size : 'lg',
                    resolve : {
                        feedbackId : function()
                        {
                            return $scope.feedback.id;
                        }
                    }
                }
            );

            modalInstance.result.then(
                function(data)
                {
                    $scope.loadFeedback();
                },
                function()
                {
                }
            );
        };

        $scope.addNewNote = function()
        {
            var modalInstance = $modal.open(
                {
                    templateUrl : 'src/modals/feedback.notes.modal.html',
                    controller : 'FeedbackNoteModalController',
                    size : 'lg',
                    resolve : {
                        feedbackId : function()
                        {
                            return $scope.feedback.id;
                        }
                    }
                }
            );

            modalInstance.result.then(
                function(data)
                {
                    $scope.loadFeedback();
                },
                function()
                {
                }
            );
        };

        $scope.addNewCall = function()
        {
            var modalInstance = $modal.open(
                {
                    templateUrl : 'src/modals/feedback.calllog.modal.html',
                    controller : 'FeedbackCallLogModalController',
                    size : 'lg',
                    resolve : {
                        feedbackId : function()
                        {
                            return $scope.feedback.id;
                        }
                    }
                }
            );

            modalInstance.result.then(
                function(data)
                {
                    $scope.loadFeedback();
                },
                function()
                {
                }
            );
        };

        $scope.openEditCustomerModal = function(size)
        {
            var modalInstance = $modal.open(
                {
                    templateUrl : 'src/modals/editcustomer.modal.html',
                    controller : 'EditCustomerModalController',
                    size : size,
                    resolve : {
                        customerId : function()
                        {
                            return $scope.feedback.customer_id;
                        }
                    }
                }
            );

            modalInstance.result.then(
                function(data)
                {
                    $scope.currentCustomer = data;
                },
                function()
                {
                }
            );
        };

        $scope.editSale = function()
        {
            $state.transitionTo(
                'pos.checkout',
                {
                    saleId : $scope.feedback.sale_id
                }
            );
        }
    }
);
