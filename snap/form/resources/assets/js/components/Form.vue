<script>
Vue.component('snap-form', {

    props: {
    },

    mounted: function() {
        var self = this;

        this.$nextTick(function(){
            var savedTab = SNAP.storage.getLocal(SNAP.ui.getStorageKey('tabs'));
            if (savedTab){
                $('.nav .nav-link[href="' + savedTab + '"]:first').click();
            }
        });

        $('.nav .nav-link', this.$el).on('shown.bs.tab', function(e){
            var activeTab = $(e.target).attr('href');
            SNAP.storage.setLocal(SNAP.ui.getStorageKey('tabs'), activeTab);
        });

        $('#btn-save, #btn-save-exit, #btn-save-create, #btn-save-close').on('click', function(e){
            self.removeCheckSave();
        });

        $('#btn-save-exit').on('click', function(e){
            self.changeRedirect('index');
        });

        $('#btn-save-create').on('click', function(e){
            self.changeRedirect('create');
        });

        $('#btn-untrash').on('click', function(e){
            e.preventDefault();
            var action = $(this).attr('href');
            self.changeAction(action);
            self.submit();
        });

        $('#btn-close').on('click', function(e){
            e.preventDefault();
            SNAP.event.$emit('modal-close');
        });

        $('[data-toggle="tooltip"]').tooltip();

        SNAP.event.$on('snap-template-changed', function(json, comp){
            $('[data-toggle="tooltip"]', comp.$el).tooltip();
        });

        this.initializeCheckSave();

        SNAP.event.$on('removeCheckSave', function(){
            self.removeCheckSave();
        })
    },

    data: function() {
        return {

        }
    },

    methods: {

        changeRedirect: function(to){
            $('#__redirect__').val(to);
        },

        changeAction: function(action){
            $(this.$refs['form']).attr('action', action);
        },

        submit: function(){
            $(this.$refs['form']).submit();
        },

        initializeCheckSave: function(){
            var self = this;
            var inputs = 'select,input,textarea';
            // var inputs = 'select,input,textarea,[contenteditable="true"]';
            // var inputs = 'select,input,textarea,[contenteditable="true"]';
            // $(document).on('focus', inputs, function() {
            //     if (!$(this).data('checksaveOrigValue')) {
            //         if ($(this).is('[contenteditable="true"]')) {
            //             $(this).data('checksaveOrigValue', $(this).html());
            //             console.log($(this).html())
            //         } else {
            //             $(this).data('checksaveOrigValue', $(this).val());
            //         }
            //     }
            // });


            // window.onbeforeunload = function(){
            //     var msg = '';
            //     $(inputs).each(function(i){
            //         // if ($(this).data('checksaveOrigValue') &&
            //         //     (($(this).is('[contenteditable="true"]') && $(this).data('checksaveOrigValue') != $(this).html())
            //         //         || ($(this).not('[contenteditable="true"]') && $(this).data('checksaveOrigValue') != $(this).val()))) {
            //         //     console.log($(this).data('checksaveOrigValue'))
            //         //
            //         //     msg = 'Your unsaved data will be lost.';
            //         // }
            //         if ($(this).data('checksaveOrigValue')){
            //             if ($(this).is('[contenteditable="true"]') && $(this).data('checksaveOrigValue') != $(this).html()) {
            //                 msg = 'Your unsaved data will be lost.';
            //                 console.log($(this).data('checksaveOrigValue') + ':'+$(this).html())
            //             }
            //
            //             if ($(this).not('[contenteditable="true"]') && $(this).data('checksaveOrigValue') != $(this).val()) {
            //                 console.log($(this)[0])
            //                 // console.log($(this).data('checksaveOrigValue') + ':'+$(this).val())
            //                 msg = 'Your unsaved data will be lost.';
            //             }
            //         }
            //     });
            //
            //     if (msg.length){
            //         return msg;
            //     }
            //     //if (needToConfirm) {
            //         // Put your custom message here
            //         //return "Your unsaved data will be lost.";
            //     //}
            // };


            this.$nextTick(function(){

                // We are going to wait 1 second to allow fo AJAX loading
                setTimeout(function(){
                    var $elems = $('input:text, input:checked, textarea, select', self.$el);

                    // get current values
                    $elems.each(function(i){
                        $(this).data('checksaveOrigValue', $(this).val());
                    });

                    window.onbeforeunload = function(e){
                        var msg = '';
                        var changedMsg = 'You are about to lose unsaved data. Do you want to continue?';
                        $elems.each(function(i){
                            //console.log(jQuery(this).attr('name') + " ------ " + escape(jQuery(this).data('checksaveStartValue').toString())  + " ------"  + escape(jQuery(this).val().toString()) )
                            if ($(this).data('checksaveOrigValue') != undefined && $(this).data('checksaveOrigValue').toString() != $(this).val().toString()){
                                msg = changedMsg;
                                return changedMsg;
                            }
                        });

                        if (msg.length){
                            return msg;
                        }
                    }

                }, 1000);
            })
        },

        removeCheckSave: function() {
            window.onbeforeunload = null;
        }
    }
});
</script>