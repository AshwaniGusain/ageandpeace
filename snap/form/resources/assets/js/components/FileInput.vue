<script>
    Vue.component('snap-file-input', {
        template: 	'<div>\
					<label class="custom-file">\
						<input ref="fileinput" type="file" class="custom-file-input" :multiple="true ? multiple : null" :name="inputName" :id="id" @change="onFileChange">\
						<label class="custom-file-label" :for="id">Choose file</label>\
					</label>\
					<div v-if="files.length && preview">\
						<div class="row">\
							<div v-for="(file, index) in files" class="mr-3 ml-3 mb-3">\
								<div v-if="file.isImage">\
									<a :href="previewUrlLink(file)" target="_blank"><img :src="file.preview" v-bind:style="{ maxWidth: previewMaxWidth }" style="display: block;"></a>\
								</div>\
								<div class="mt-1">\
                                    <button @click="removeFile(index)" type="button" class="btn btn-sm btn-danger" aria-label="Remove"><i class="fa fa-trash"></i></button>\
                                    <small>{{ file.name }} ({{ file.friendlySize }})</small>\
                                </div>\
                                <input :name="inputName" type="hidden" :value="file.id">\
                            </div>\
						</div>\
					</div>\
					<div v-else>\
					    <input :name="inputName" type="hidden">\
					 </div>\
                     <input type="hidden" :name="name + \'_options\'" :value="optionsJSON">\
                </div>',
        props: {
            'name': {
                type: String,
                required: true,
                default: ''
            },
            'id': {
                type: String,
                required: false
            },
            'type': {
                type: String,
                required: false
            },
            'previewMaxWidth': {
                required: false,
                default: '200px'
            },
            'multiple': {
                type: Boolean,
                required: false,
                default: false
            },
            'options': {
                type: Object,
                required: false
            },
            'value': {
                //type: Array,
                required: false
            },
            'preview': {
                type: Boolean,
                required: false,
                default: true
            }
        },

        mounted: function(){
            if (this.value) {
                // var files = [];
                // for (var n in this.value) {
                //     var file = this.value[n];
                //     files[n] = new File([""], file.name, {type: file.type, lastModified: file.last_modified});
                // }

                this.files = this.createFiles(this.value);
            }

            this.optionsJSON = (typeof(this.options) != 'string') ? JSON.stringify(this.options) : this.options;

            //this.optionsJSON = this.options;
            // console.log(this.$get('previewMaxWidth'))
        },

        data: function(){
            return {
                files: [],
                optionsJSON: {}
            };
        },

        computed: {
            'inputName' : function(){
                if (this.multiple) {
                    return this.name + '[]';
                }

                return this.name;
            },

            'optionsValue' : function(){
                if (this.options) {
                    return this.name + '[]';
                }

                return this.name;
            }
        },

        methods: {

            onFileChange: function(e) {
                e.preventDefault();
                var files = e.target.files || e.dataTransfer.files;
                if ( ! files.length) {
                    return;
                }

                this.files = this.createFiles(files);
            },

            createFiles: function(files){
                var newFiles = [];
                for (var n in files){
                    var file = files[n];
                    if (this.isImage(file)){
                        this.createImage(n, file);
                        file.isImage = true;
                    } else {
                        file.isImage = false;
                        file.preview = file.name;
                    }

                    file.friendlySize = this.humanFileSize(file.size);
                    newFiles[n] = file;
                }

                return newFiles;
            },

            createImage: function(n, file) {
                if ( ! file.preview) {
                    var reader = new FileReader();
                    var self = this;

                    reader.onload = function(e) {
                        var file = self.files[n];
                        file.preview = e.target.result;
                        self.files.splice(n, 1, file);
                    };

                    reader.readAsDataURL(file);
                }
            },

            removeFile: function (index) {
                this.files.splice(index, 1);

                $(this.$refs['fileinput']).val('');
                // $fileInput.replaceWith($fileInput.val('').clone(true));
            },

            removeFiles: function () {
                this.files = [];

                $(this.$refs['fileinput']).val('');
                // $fileInput.replaceWith($fileInput.val('').clone(true));
            },

            isImage: function(file){
                return (file.type == 'image/png' || file.type == 'image/jpeg' || file.type == 'image/gif');
            },

            humanFileSize: function(bytes, si) {
                var thresh = si ? 1000 : 1024;
                if(Math.abs(bytes) < thresh) {
                    return bytes + ' B';
                }
                var units = si
                    ? ['kB','MB','GB','TB','PB','EB','ZB','YB']
                    : ['KiB','MiB','GiB','TiB','PiB','EiB','ZiB','YiB'];
                var u = -1;
                do {
                    bytes /= thresh;
                    ++u;
                } while(Math.abs(bytes) >= thresh && u < units.length - 1);

                return bytes.toFixed(1)+' '+units[u];
            },

            previewUrlLink: function(file) {
                if (file.preview_url) {
                    return file.preview_url;
                } else {
                    return file.preview;
                }
            }
        }
    });
</script>