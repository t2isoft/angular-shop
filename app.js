
angular.module('DemoApp', ['ngAnimate'])

.service('loadBooks', function($http, randomizeArray) {
    return function() {
        return $http.get('../service/function.php?tag=productList').then(function(response) {
            return randomizeArray(response.data.books);
        });
    };
})

.service('loadCartQuantity', function($http, randomizeArray) {
    return function() {
        return $http.get('../service/function.php?tag=productList').then(function(response) {
            return randomizeArray(response.data.books);
        });
    };
})



// from https://github.com/coolaj86/knuth-shuffle/blob/master/index.js
.service('randomizeArray', function() {

    function randOrd(){
      return (Math.round(Math.random())-0.5);
    }

    return function(array) {
        return array.sort(randOrd);
    };
})

.service('uniqueArray', function() {
    function onlyUnique(value, index, self) {
        return self.indexOf(value) === index;
    }

    return function(array) {
        return array.filter( onlyUnique );
    }
})

.service('Filters', function() {
    var selection = {
        tags: [],
        Levels: []
    };

    function getItemIndex(field, value) {
        // get the checked item position (or -1 if not selected)
        return selection[field].indexOf(value);
    };

    return {
        selection: selection,
        isSelected: function(field, value) {
            return getItemIndex(field, value) > -1;
        },
        toggle: function(field, value) {
            var selectionIndex = getItemIndex(field, value);
            if (selectionIndex > -1) {
                // remove item
                selection[field].splice(selectionIndex, 1);
            } else {
                // add item
                selection[field].push(value);
            }
        }
    }
})




.service('deleteitem', function($http, randomizeArray) {
    return function() {
        return $http.get('../service/function.php?tag=productList').then(function(response) {
            return randomizeArray(response.data.books);
        });
    };
})










.controller('FilterCtrl', function(Filters) {

    this.isSelected = function(field, value) {
        return Filters.isSelected(field, value)
    };

    this.toggle = function(field, value) {
        Filters.toggle(field, value);
    };



})

.filter('filterBooks', function(Filters) {
    return function(array) {

        var results = [];

        var filterTags = Filters.selection.tags.length > 0,
            filterLevels = Filters.selection.Levels.length > 0;

        angular.forEach(array, function(book) {
            var hasTag = false,
                hasLevel = false;

            angular.forEach(book.tags, function(tag) {
                if (Filters.selection.tags.indexOf(tag)>-1) {
                    hasTag = true;
                }
            });

            hasLevel = Filters.selection.Levels.indexOf(book.Level) > -1;

            if ((!filterTags || hasTag) && (!filterLevels || hasLevel)) {
                results.push(book);
            }

        });

        return results;
    };
})

.controller('MainCtrl', function(loadBooks, uniqueArray) {

    function getUniqueTags() {
        var tags = [];
        angular.forEach(self.books, function(book) {
            tags = tags.concat(book.tags);
        });
        return uniqueArray(tags);
    }

    this.name = 'world';

    this.books = [];
    this.Levels = ["3 - 6 ans", "6 - 9 ans", "9 - 12 ans"];

    var self = this;
    loadBooks().then(function(books) {
        self.books = books;
        self.tags = getUniqueTags();
        self.tags.sort();
    });

});

