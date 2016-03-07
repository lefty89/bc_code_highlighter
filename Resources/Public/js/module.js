/**
 * Created by Marco_Lewandowsky on 04.08.2015.
 */

/**
 * Angular module for custom flexform
 */
require(['jquery','angular'], function($, angular) {

    angular.module("SelectorModule", [])
        .directive( 'editInPlace', function() {
            return {
                restrict: 'E',
                scope: { value: '=' },
                template: '<span ng-click="edit()" ng-bind="value"></span><input class="form-control" ng-model="value"></input>',
                link: function ( $scope, element, attrs ) {
                    // Let's get a reference to the input element, as we'll want to reference it.
                    var inputElement = angular.element( element.children()[1] );

                    // This directive should have a set class so we can style it.
                    element.addClass( 'edit-in-place' );

                    // Initially, we're not editing.
                    $scope.editing = false;

                    // ng-click handler to activate edit-in-place
                    $scope.edit = function () {
                        $scope.editing = true;

                        // We control display through a class on the directive itself. See the CSS.
                        element.addClass( 'active' );

                        // And we must focus the element.
                        // `angular.element()` provides a chainable array, like jQuery so to access a native DOM function,
                        // we have to reference the first element in the array.
                        inputElement[0].focus();
                    };

                    // When we leave the input, we're done editing.
                    inputElement.prop( 'onblur', function() {
                        $scope.editing = false;
                        element.removeClass( 'active' );
                    });
                }
            };
        })
        .controller("SelectorController", function ($scope) {

            /**
             * the current selected item
             * @type {int}
             */
            $scope.currentItem = 0;

            /**
             * serializes list
             * @type {string}
             */
            $scope.serializedValue = '';

            /**
             * list of all items
             * @type {Array}
             */
            $scope.items = [];

            /**
             * moves an item one position upwards
             * @param {int} index
             */
            $scope.upwards = function(index) {

                if (index > 0) {
                    var tmp = $scope.items[index-1];
                    $scope.items[index-1] = $scope.items[index];
                    $scope.items[index] = tmp;
                }
            };

            /**
             * moves an item one position downwards
             * @param {int} index
             */
            $scope.downwards = function(index) {

                if (index < $scope.items.length-1) {
                    var tmp = $scope.items[index+1];
                    $scope.items[index+1] = $scope.items[index];
                    $scope.items[index] = tmp;
                }
            };

            /**
             * toggle between url and codeblock
             * @param {int} index
             */
            $scope.toggle = function(index) {
                $scope.items[index].external = !$scope.items[index].external;
            };

            /**
             * toggle between url and codeblock
             * @param {int} index
             */
            $scope.show = function(index) {
                $scope.items[index].show = !$scope.items[index].show;
            };

            /**
             * adds an item to the list
             */
            $scope.add = function() {

                // create new item
                var item = {
                    id: 1,
                    name: "test" + (Math.floor(Math.random() * 6) + 1),
                    url: "",
                    code: "",
                    external: false,
                    show: false,
                    collapsed: true,
                    ext: 'html'
                };

                // adds a new item
                $scope.items.push(item);
            };

            /**
             * removes an item from the list
             * @param {int} index
             */
            $scope.remove = function() {
                // removes from list
                $scope.items.splice(index, 1);
            };

            /**
             * @param {string} value
             */
            $scope.initItems = function(value) {
                try {
                    console.log(value);
                    $scope.items = JSON.parse(b64_to_utf8(value));
                } catch (e) {
                    console.log("JSON String not valid");
                }
            };

            /**
             * @param {int} index
             */
            $scope.collapseItem = function(index) {
                $scope.items[index].collapsed = !$scope.items[index].collapsed;
            };

            /**
             * deep watcher for 'items' variable
             */
            $scope.$watch('items', function() {
                $scope.serializedValue =  utf8_to_b64(JSON.stringify($scope.items));
            }, true);

            /**
             * encoding helper
             * @param str
             * @returns {string}
             */
            function utf8_to_b64( str ) {
                return window.btoa(encodeURIComponent( str ));
            }

            /**
             * decoding helper
             * @param str
             * @returns {string}
             */
            function b64_to_utf8( str ) {
                return decodeURIComponent(window.atob( str ));
            }
    });
});
