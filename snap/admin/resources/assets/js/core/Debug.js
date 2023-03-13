/**************************************************/
/* Debug                                          */
/**************************************************/
function Debug(level, clear, expand){
    this._level = 0;
    this._levels = { 'NONE': 0, 'ERROR': 1, 'WARNING': 2, 'DEBUG': 3, 'INFO': 4, 'ALL': 5 }
    this._expand = expand;
    this._output = [];
    this.setLevel(level);
    if (clear){
        console.clear();
    }
}

Debug.prototype = {

    log: function(msg, level, expand, delimiter){
        if(!window.console){ 
            window.console = {};
            window.console.log = function(){};
        }
        this.add(msg, level);

        // set whether you want the console log objects to be expanded
        if (!expand) expand = this._expand;

        // set the delimiter between objects
        if (!delimiter) delimiter = ' : ';

        // show expanded object
        if (expand){
            console.dir(this._output);

        // show string output
        } else {
            var output = this._output.join(delimiter);
            if (output.length){
                console.log(output);    
            }
        }
        this.clear();
    },

    add: function(msg, level){

        // set to debug level if no level is provided
        if (!level) level = 3;

        // only add those to the output if the debug level is high enough
        if (msg && this.getLevel() > 0 && level <= this.getLevel()){
            this._output.push(msg); 
        }
    },

    clear: function(){
        this._output = [];
    },

    setLevel: function(level){
        if (typeof level ==  'string'){
            level = this._levels[level.upperCase()];
        } else if (level === false){
            level = 0;
        }
        this._level = parseInt(level);
    },

    getLevel: function(level){
        return this._level;
    },

    // shortcuts
    error: function(msg){
        this.log(msg, 1);
    },

    warn: function(msg){
        this.log(msg, 2);
    },

    info: function(msg){
        this.log(msg, 4);
    }
}

module.exports = Debug;