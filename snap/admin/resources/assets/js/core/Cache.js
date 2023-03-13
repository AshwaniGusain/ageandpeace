/**************************************************/
/* Cache                                          */
/**************************************************/
var Cache = function() {
    this._cache = {};
}

Cache.prototype = {

    add : function(key, val, max){
        if (!val) val = key;
        if (!max || val.length < max){
            this._cache[key] = val;
        }
    },

    remove : function(key){
        if (key != null) {
            delete this._cache[key];
        } else {
            this._cache = {};
        }
    },

    has : function(key){
        return this.get(key) ? true : false;
    },

    get : function(key){
        return this._cache[key];
    },

    isCached : function(key){
        return (this._cache[key] != null)
    },

    size : function(){
        var size = 0;
        var self = this;
        _.each(this._cache, function(i, n){
            if (self._cache[n].length) size += self._cache[n].length;
        });
        return size;
    }
}

module.exports = Cache;