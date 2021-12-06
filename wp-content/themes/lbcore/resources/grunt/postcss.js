var paths = require('../grunt-vars')
var files = {}

files[paths.distCss + paths.pkg.name + '.css'] = paths.buildCss + paths.pkg.name + '.css'

module.exports = {
  options: {
    map: {
      prev: paths.buildCss,
      inline: false,
      annotation: paths.buildCss + 'maps/'
    }
  },
  build: {
    files: files,
    options: {
      processors: [
        require('autoprefixer')(),
        require('postcss-custom-properties')({})
      ]
    }
  },
  dist: {
    files: files,
    options: {
      processors: [
        require('autoprefixer')(),
        require('postcss-custom-properties')({}),
        require('cssnano')({
          reduceTransforms: false,
          zindex: false
        })
      ]
    }
  }
}
