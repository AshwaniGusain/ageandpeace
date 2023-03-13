/**************************************************/
/* Paths                                          */
/**************************************************/
const Util = require('./Util');

function Paths(paths){
    this._paths = {};
    this.initialize(paths);
}

Paths.prototype = {

    initialize: function(paths){
        if (paths){
            this._paths = paths; // don't need to use set since this is the initialized values
        }
    },

    set: function(key, val){
        this._paths[key] = val;
        return this;
    },

    get: function(key, path){
        if (Util.hasProp(this._paths, key)){
            path = (path) ? this._paths[key] + path : this._paths[key];
        }
        return path;
    }
}

module.exports = Paths;