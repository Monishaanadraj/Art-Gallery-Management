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
