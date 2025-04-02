var ConfigFile = require('requirejs-config-file').ConfigFile,
    fs = require('fs');

/**
 * Generates a mapping/LUT of aliases from the given requirejs or browserify config
 * @param  {String} configPath
 * @return {Object} A mapping of alias to real filename
 */
module.exports = function(configPath) {
  var content = fs.readFileSync(configPath).toString(),
      isBrowserifyConfig = configPath.indexOf('package.json') !== -1;

  if (! isBrowserifyConfig) {
    return getRequireShims(configPath);
  } else {
    return getBrowserifyShims(configPath);
  }
};

/**
 * Non-destructively mixes one object into another.
 * If o1 already has the property, it's not overwritten
 *
 * @param  {Object} o1 - Base object
 * @param  {Object} o2 - Mixin
 */
function extend(o1, o2) {
  if (! o2) return;

  Object.keys(o2).forEach(function(prop) {
    o1[prop] = o1[prop] || o2[prop];
  });
}

/**
 * @param  {String} configPath
 * @return {Promise} Resolves with an object of all the shims
 */
function getRequireShims(configPath) {
  var config = new ConfigFile(configPath).read(),
      shims = {};

  extend(shims, config.paths);

  Object.keys(config.map).forEach(function(prop) {
    extend(shims, config.map[prop]);
  });

  return shims;
}

/**
 * @param  {String} configPath
 * @return {Object}
 */
function getBrowserifyShims(configPath) {
  var config = getBrowserifyConfig(configPath);
  return config.browser;
}

/**
 * Returns the browserify configuration (usually just package.json)
 * @param  {String} configPath
 * @return {String}
 */
function getBrowserifyConfig(configPath) {
  try {
    return require(configPath);

  } catch(e) {
    try {
      JSON.parse(fs.readFileSync(configPath).toString());

    } catch(err) {
      return {};
    }
  }
}
