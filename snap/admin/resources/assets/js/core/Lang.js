/**************************************************/
/* Lang                                           */
/**************************************************/
function Lang(config){
    this._lang = {};
    this._default_namespace = 'global';
    this.initialize(config);
}

Lang.prototype = {

    initialize: function(config){
        if (config){
            this.createNamespace(this._default_namespace);
            this.set(config, this._default_namespace)
        }
    },

    get: function(key, values, namespace){
        if (!namespace) namespace = this._default_namespace;
        if (namespace && (typeof(this._lang[namespace]) === 'undefined' || typeof(this._lang[namespace][key]) === 'undefined')) {
            return false;
        }

        var str = this._lang[key];
        if (values && $.isPlainObject(values)) {
            for (var n in values){
                str = str.replace('{' + n + '}', values[n]);
            }
        }
        return str;
    },

    set: function(key, val, namespace){
        if (_.isObject(key)){
            var self = this;
            _.each(key, function(k, v){
                var namespace = (!val) ? self._default_namespace : val;
                self._set(k, v, namespace);
            })
        } else {
            if (!namespace) namespace = this._default_namespace;
            this._set(key, val, namespace);
        }
        return this;
    },

    _set: function(key, val, namespace){
        if (namespace){
            this.createNamespace(namespace);
            this._lang[namespace][key] = val;
        } else {
            this._lang[key] = val;
        }
    },

    createNamespace: function(namespace){
        if (!this._lang[namespace]){
            this._lang[namespace] = {};
        }
        return this;
    },

    getNamespace: function(namespace){
        if (!this._lang[namespace]){
            return this._lang[namespace];
        }
        return false;
    }
};

module.exports = Lang;