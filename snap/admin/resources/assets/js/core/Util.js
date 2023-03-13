/**************************************************/
/* UTIL FUNCTIONS                                 */
/**************************************************/
var Util = {};

Util.hasProp = function(obj, prop){
    return typeof(obj[prop]) != 'undefined';
}

Util.createObjectFromString = function(key, val, obj, delimiter){
    if (!delimiter) delimiter = '.';
    if (!obj) obj = window;
    var keyPath = key.split(delimiter);
    lastKeyIndex = keyPath.length-1;
    for (var i = 0; i < lastKeyIndex; ++ i) {
        key = keyPath[i];
        if (!(key in obj)){
            obj[key] = {};
        }
        obj = obj[key]; 
    }
    obj[keyPath[lastKeyIndex]] = val;
    return obj[keyPath[lastKeyIndex]];
}

Util.getQueryStringParamenter = function(name, url) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)");
    if (!url) {
        url = location.search;
    }
    results = regex.exec(url);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

Util.isTrue = function(val){
    val = new String(val).toLowerCase();
    if (val == "1" || val == "true" || val == "yes" || val == "y") {
        return true;
    } else {
        return false;
    }
}

module.exports = Util;