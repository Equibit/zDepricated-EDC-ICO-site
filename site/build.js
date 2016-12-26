var stealTools = require("steal-tools");

var buildPromise = stealTools.build({
  config: __dirname + "/package.json!npm"
}, {
  bundleAssets: true,
  cleanCSSOptions: {
    "advanced": false
  }
});
