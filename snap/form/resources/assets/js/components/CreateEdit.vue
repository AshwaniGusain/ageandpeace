<script>
    Vue.component('snap-create-edit', {

        // template : '<div style="display: inline-block;"><a :href="editUrl" @click.prevent="showModal(editUrl)" class="btn btn-primary edit-inline-button">Edit</a> <a :href="createUrl" @click.prevent="showModal(createUrl)" class="btn btn-primary add-inline-button">Create</a></div>',
        template : '<div class="row"><div class="col-md-9"><slot></slot></div><div class="col-md-3"><div class="btn-group mt-1"><a :href="editUrl" @click.prevent="showModal(editUrl, false)" class="btn btn-secondary btn-sm edit-inline-button border" :class="{ disabled: editStatus }"><i class="fa fa-pencil"></i></a> <a :href="createUrl" @click.prevent="showModal(createUrl, true)" class="btn btn-secondary btn-sm add-inline-button border"><i class="fa fa-plus"></i></a></div></div></div>',
        //template : '<div><slot></slot></div>',
        props: {
            'activeUrl': {
                type: String,
                required: false,
                default: null
            },

            'moduleUrl': {
                type: String,
                required: true
            },

            'targetSelector': {
                type: String,
                required: false
            },

            'editIdSelector': {
                type: String,
                required: false,
                default: '#id'
            },

            'urlParams': {
                type: String
            }
        },

        mounted: function(){
            let self = this;
            this.$nextTick(function(){
                self.targetElem = self.getTargetElem();

                self.updateEditUrl();

                $(self.targetElem).on('change', function(e){
                    self.updateEditUrl();
                    self.editStatus = self.checkIsEditDisable();
                });

                self.editStatus = self.checkIsEditDisable();
            });

            SNAP.event.$on('snap-modal-saved', function(e){
                self.updateTargetValue();
            });

            SNAP.event.$on('snap-modal-after-hide', function(e){
                self.active = false;
            });

        },

        data: function(){
            return {
                createUrl: this.moduleUrl + '/create_inline',
                editUrl: '',
                active: false,
                editStatus: false
            };
        },

        methods: {
            getTargetElem() {

                if (this.targetSelector) {
                    return $(this.$el).closest('form').find(this.targetSelector);
                }

                return $(this.$el).closest('.row').find('.col-md-9 select');
            },

            updateEditUrl: function(){
                let val = this.targetElem.val();

                if (val && val.length) {
                    if (typeof(val) !== 'string') {
                        val = val[0];
                    }
                    this.editUrl = this.moduleUrl + '/' + val + '/edit_inline';
                } else {
                    //alert('Select an item to edit.');
                }

                return this;
            },

            checkIsEditDisable: function(){
                let val = this.targetElem.val();
                return (val == null || val === '' || val.length !== 1) ? true : false;
            },

            updateTargetValue: function(){

                if ( ! this.active) return;

                let input = this.targetElem.attr('name');

                if (input) {
                    let self = this;
                    let iframe = $('#snap-modal').find('iframe');
                    let val = iframe[0].contentWindow.$(this.editIdSelector).val();
                    let url = this.activeUrl + '/input/' + input;
                    if (val) {
                        url += '/' + val;
                    }
                    $.get(url, {value: $(this.targetElem).val()}, function(html){
                        if (html){

                            // Only get the select element to replace otherwise we mess with Vue too much
                            let $html = $(document.createDocumentFragment());
                            let $fragment = $(html);
                            $html.append($fragment);

                            let MyComponent = Vue.extend({
                                template: $html.find('snap-create-edit').html()
                            });
                            let component = new MyComponent().$mount();

                            //let targetElem = self.getTargetElem();
                            self.targetElem.parent().html(component.$el);

                            //https://stackoverflow.com/questions/50241423/how-to-update-a-slot-in-vue-js
                            //targetElem.closest('.form-input-col').html(component.$el);
                            // self.$slots.default = component._vnode;
                            // console.log(self.$slots.default);
                            //self.$forceUpdate()

                        }
                    });
                }
            },

            showModal: function(url, create){
                if (!url) {
                    // TODO...put in js language file
                    alert('Select an item to edit first.');
                } else {
                    let val = this.targetElem.val();
                    if (!create && typeof(val) !== 'string' && val.length > 1) {
                        // TODO...put in js language file
                        alert('You must have a single item to edit.');
                    } else {
                        if (this.urlParams) {
                            url += '?' + this.urlParams;
                        }

                        SNAP.ui.modal.load(url).show();
                        this.active = true;
                    }
                }
            }
        }

    });
</script>