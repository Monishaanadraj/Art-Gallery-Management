### shims

> Get a lookup table of aliases from your requirejs or browserify config

`npm install shims`

### Usage

```js
var shims = require('shims');

var requireShims = shims('path/to/my/requirejs/config.js');
var browserifyShims = shims('path/to/my/package.json');
```

### Example

RequireJS Config:

```js
require.config({
  paths: {
    some: "some/v1.0"
  },
  map: {
    '*': {
      foo: 'foo1.2'
    },
    'some/oldmodule': {
      foo: 'foo1.0'
    }
  },
  shim: {
    'jquery.colorize': {
      deps: ['jquery'],
      exports: 'jQuery.fn.colorize'
    },
    'jquery.scroll': {
      deps: ['jquery'],
      exports: 'jQuery.fn.scroll'
    },
    'backbone.layoutmanager': {
      deps: ['backbone'],
      exports: 'Backbone.LayoutManager'
    }
  }
});

```

`shims` returns:

```js
{
  some: "some/v1.0",
  foo: 'foo1.2'
}
```

**Note:** `shims` will honor aliases in a first-come first serve fashion. Since '*' defined `foo`,
that's the alias that will be used.

* The primary use case for this module isn't to retain the per-module aliases,
just to get the general aliases


Browserify Example: Taken from `browserify-shim`

```js
{
  "main": "./js/entry.js",
  "browser": {
    "jquery": "./js/vendor/jquery.js",
    "underscore": "./js/vendor/underscore.js",
    "backbone": "./js/vendor/backbone.js"
  },
  "browserify-shim": {
    "jquery": "$",
    "three": "global:THREE"
  },
  "browserify": {
    "transform": [ "browserify-shim" ]
  },
  "dependencies": {
    "browserify-shim": "~3.2.0"
  }
}

```

`shims` returns:

```js
{
  "jquery": "./js/vendor/jquery.js",
  "underscore": "./js/vendor/underscore.js",
  "backbone": "./js/vendor/backbone.js"
}
```
