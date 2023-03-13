/**************************************************/
/* Config                                         */
/**************************************************/
const Util = require('./Util');

function Config(config, HelperUtil){
    this._config = {};
    this.initialize(config);
}

Config.prototype = {

    initialize: function(config){
        if (config){
            this._config = config; // don't need to use set since this is the initialized values
        }
    },

    set: function(key, val, namespace){
        if (_.isObject(key)){
            var self = this;
            _.each(key, function(k, v){
                self.set(key, val, namespace);
            })
        } else {
            if (namepsace){
                this._config[namespace] = { key: val };
            } else {
                this._config[key] = val;    
            }
            
        }
    },

    get: function(key, namespace){
        if (namespace){
            if (Util.hasProp(this._config, namespace) && Util.hasProp(this._config[namespace], key)){
                return this._config[namespace][key];
            }
        } else {
            if (Util.hasProp(this._config, key)){
                return this._config[key];
            }
        }

        return null;
    }
}

module.exports = Config;