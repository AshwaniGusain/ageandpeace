<script>
    Vue.component('snap-edit-inline-popover', {
        template: '<div @mouseover="editable = true" @mouseout="editable = false" class="position-relative">\
                        <div>\
                            <span v-show="!show">\
                                <slot></slot> <i v-show="loading" class="snap-loader-inline"></i><i @click.stop="load" :style="{visibility: (editable && !loading) ? \'visible\' : \'hidden\'}" class="fa fa-edit"></i>\
                            </span>\
                            <div @click.stop v-show="show" class="data-table-edit-inline">\
                                <div v-show="errors" class="alert alert-danger" v-for="(keyErrors, key) in errors">\
                                    <div v-for="(error, k) in keyErrors">{{ error }}</div>\
                                </div>\
                                <div class="clearfix"><div ref="form" class="edit-inline-form"></div></div>\
                                <input type="hidden" name="_method" value="PATCH">\
                                <input type="hidden" name="_token" :value="token">\
                                <div role="toolbar" class="mt-2 pull-right" aria-label="Toolbar">\
                                    <div class="btn-group mr-2" role="group" aria-label="Cancel">\
                                        <button @click.prevent.stop="cancel()" type="button" name="cancel" class="btn btn-sm secondary">Cancel</button>\
                                    </div>\
                                    <div class="btn-group mr-2" role="group" aria-label="Save">\
                                        <button @click.prevent.stop="submit()" type="submit" name="save" class="btn btn-sm btn-primary text-light">Save</button>\
                                    </div>\
                                </div>\
                            </div>\
                        </div>\
                   </div>',

        props: {
            'editUrl': {
                type: String,
                required: true
            },
            'updateUrl': {
                type: String,
                required: true
            },
            'input': {
                type: String,
                required: true
            },
            // 'initValue': {
            //     type: String,
            //    // required: true
            // },
        },

        mounted: function(){
            this.token = $('meta[name="csrf-token"]').attr('content');
            this.parent = this.$parent;
            // this.value = this.initValue;
        },

        data: function(){
            return {
                show: false,
                token: null,
                errors: null,
                value: null,
                editable: false,
                loading: false,
                parent: null
            };
        },

        methods: {
            load: function(e){
                if (this.show) return;
                let self = this;
                let url = this.editUrl;
                let params = {};

                SNAP.event.$emit('datatable-edit-inline-show', this);
                this.loading = true;
                $.get(url, params, function(html){
                    var EmbeddedComponent = Vue.extend({
                        template: '<div>' + html + '</div>'
                    });
                    new EmbeddedComponent().$mount(self.$refs['form']);
                    self.show = true;
                    self.loading = false;
                });
            },

            cancel: function(e) {
                this.show = false;
            },

            submit: function(e) {
                let self = this;
                let url = this.updateUrl;
                let data = $(this.$el).find(':input').serialize();
                let value = $(this.$el).find('[name="' + this.input + '"]').val();

                SNAP.event.$emit('datatable-edit-inline-save', this);

                $.ajax({
                    method: 'post',
                    url: url,
                    dataType: 'json',
                    data: data,
                    success: function(json){
                        self.errors = null;
                        self.show = false;
                        self.value = value;
                        SNAP.event.$emit('datatable-refresh', this);
                        //self.$parent.ref.$emit('refresh');
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        self.errors = jqXHR.responseJSON.errors;
                    }
                });
            }
        }
    });
</script>