var shims = require('../'),
    assert = require('assert');

var requireShims = shims(__dirname + '/config.js');
var bShims = shims(__dirname + '/package.json');

assert(equal(requireShims, {
  some: "some/v1.0",
  foo: 'foo1.2'
}));

assert(equal(bShims, {
  "jquery":     "./js/vendor/jquery.js",
  "underscore": "./js/vendor/underscore.js",
  "backbone":   "./js/vendor/backbone.js"
}));

/**
 * Whether or not the two objects are loosely equal
 *
 * Assumes flat objects since that's what the shim objects will be
 *
 * @param  {Object} obj1
 * @param  {Object} obj2
 * @return {Boolean}
 */
function equal(obj1, obj2) {
  var obj1keys = Object.keys(obj1);
  var obj2keys = Object.keys(obj2);

  if (obj1keys.length !== obj2keys.length) return false;

  var failure = false;

  obj1keys.forEach(function(prop) {
    if (failure) return;
    if (obj1[prop] !== obj2[prop]) failure = true;
  });

  return !failure;
}
