/**************************************************/
/* Errors                                         */
/**************************************************/
function Errors(){
    this._errors = {};
    this._last = '';
}

Errors.prototype = {

    add: function(val, key){
        if (_.isArray(key)){
            var self = this;
            _.each(key, function(k, v){
                self.add(key, val);
            })
        } else {
            if (!key){
                key = _.size(this._errors);
            }
            this._errors[key] = val;
            this._last = val;
            return this;
        }
    },

    remove: function(key){
        if (this.has(key)){
            delete this._errors[key];   
        }
        return this;
    },

    all: function(){
        return this._errors;
    },

    last: function(){
        return this._last;
    },

    has: function(key){
        if (typeof(this._errors[key]) != 'undefined'){
            return true;
        }
        return false;
    },

    exist: function(){
        return _.size(this._errors) ? true : false;
    },

    clear: function(){
        this._errors = {};
        return this;
    }
}

module.exports = Errors;