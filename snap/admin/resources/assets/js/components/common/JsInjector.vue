<script>
    Vue.component('snap-js-injector', {
        template: '<div></div>',

        props: {
            'files': {
                type: Array,
                default: function(){
                    return [];
                },
                required: false
            }
        },

        created: function(){
            this.inject();
        },

        data: function(){
            return {

            };
        },

        methods: {

            inject: function(){

                var used = this.getLoadedScripts();
                // Loop through the files and if they do not already exist in head, inject them.
                for (var n in this.files){
                    var src = this.files[n];

                    if (used.indexOf(src) === -1){
                        var elem = document.createElement('script');
                        elem.setAttribute('src', src);
                    }
                    document.getElementsByTagName("head")[0].appendChild(elem);
                }
            },

            getLoadedScripts: function() {
                var used = [];
                var tags = document.getElementsByTagName('scripts');
                if (tags.length){
                    for (var i = 0; i < tags.length; i++){
                        var tag = tags[i];
                        if (tag && tag.getAttribute("src")){
                            used.push(tag.getAttribute(src));
                        }
                    }
                }

                return used;
            }


        }

    });
    /*
    * var files = '.$json.';
		var used = [];

		// first get all the current link tags on the page so that we do not append the same file twice
		var tags = document.getElementsByTagName("'.$tag.'");
		if (tags.length){
			for (var i = 0; i < tags.length; i++){
				var tag = tags[i];
				if (tag && '.$used.'){
					used.push(tag.getAttribute("'.$src.'"));
				}
			}
		}

		// loop through the files and if they do not already exist in head, inject them
		for (var n in files){
			if (used.indexOf(files[n]) === -1){
				var elem = document.createElement("'.$tag.'");
				elem.setAttribute("'.$src.'", files[n]);
	';
            foreach ($default as $key => $val) {
                $str .= "\t\t\telem.setAttribute(\"".$key."\", \"".$val."\");\n";
            }

            $used = [$src];
            foreach ($attrs as $key => $val) {
                if (! in_array($key, $used) && ! in_array($key, $default)) {
                    $str .= "\t\t\telem.setAttribute(\"".$key."\", \"".$val."\");\n";
                }
            }
            $str .= '							document.getElementsByTagName("head")[0].appendChild(elem);
			}
		}
		}*/
</script>

