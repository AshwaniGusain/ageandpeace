var Ui = Vue.extend({

    props: {
    },

    mounted: function(){
        let self = this;
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
        });

        // Make $refs properties on the Ui object
        for (var n in this.$refs) {
            this[n] = this.$refs[n];    
        }

        this.$nextTick(function(){

        });

        SNAP.event.$on('preview-mode-hidden', function(){
            self.previewMode = false;
        });

        SNAP.event.$on('preview-mode-shown', function(){
            self.previewMode = true;
        });
    },


    data: function(){
        return {
            menu: null,
            previewMode: false
        };
    },

    methods: {
        getStorageKey: function(key){
            return location.pathname + '#' + key;
        },

        toggleMenu: function(){
            if (this.menu) {
                this.hideMenu();
            } else {
                this.showMenu();
            }
        },

        showMenu: function(){
            this.menu = true;
        },

        hideMenu: function(){
            this.menu = false;
        }
    },

    computed: {
        
    }

});

module.exports = Ui;