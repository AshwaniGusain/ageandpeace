/**************************************************/
/* Storage                                        */
/**************************************************/
var Storage = {

    setLocal : function(key, val){
        localStorage.setItem(key, JSON.stringify(val)); 
        return this;
    },

    getLocal : function(key){
        var value = localStorage.getItem(key);
        return value && JSON.parse(value);
    },

    removeLocal : function(key){
        localStorage.removeItem(key);
        return this;
    },

    clearLocal : function(){
        localStorage.clear();
    },

    setSession : function(key, val){
        sessionStorage.setItem(key, JSON.stringify(val));
        return this;
    },

    getSession : function(key){
        var value = sessionStorage.getItem(key);
        return value && JSON.parse(value);
    },

    removeSession : function(key){
        sessionStorage.removeItem(key);
        return this;
    },

    clearSession : function(){
        sessionStorage.clear();
    }
}

module.exports = Storage;