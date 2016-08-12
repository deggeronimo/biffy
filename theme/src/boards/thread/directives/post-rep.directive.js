'use strict';

angular.module('biffyApp')
    .directive('postRep', function () {
        return {
            restrict: 'E',
            templateUrl: 'src/boards/thread/directives/post-rep.html',
            scope: {
                post: '='
            },
            controller: function ($scope, RestangularAppService, UserService) {
                $scope.isUpvote = false;
                $scope.isDownvote = false;
                $scope.canVote = false;
                $scope.currentUserId = 0;
                $scope.currentUserId = UserService.getUser().id;

                $scope.evaluate = function () {
                    if ($scope.post.rep_votes[0]) {
                        if ($scope.post.rep_votes[0].rating === 1) {
                            $scope.isUpvote = true;
                        } else {
                            $scope.isDownvote = true;
                        }
                    }

                    $scope.canVote = !($scope.isUpvote || $scope.isDownvote);
                    if ($scope.post.user_id === $scope.currentUserId) {
                        $scope.canVote = false;
                    }
                };

                $scope.evaluate();

                $scope.votePost = function (rating) {
                    RestangularAppService.all('boards').customPOST({post_id: $scope.post.id, rating: rating}, 'vote').then(function (data) {
                        $scope.post = data;
                        $scope.evaluate();
                    });
                };

                $scope.upvotePost = function () {
                    $scope.votePost(1);
                };

                $scope.downvotePost = function () {
                    $scope.votePost(-1);
                };
            }
        }
    });