<script>
    Vue.component('snap-map-list', {

        props: {
            'initItems': {
                type: Object,
                required: true,
                default: {},
            },
            'popUpProperty': {
                type: String,
                required: false,
                default: 'display_name'
            }
        },

        mounted: function(){
            let self = this;
            // console.log($(self.$refs.mapitems).text())
            this.$nextTick(function(){
                // var items = JSON.parse($(self.$refs.mapitems).text());
                self.items = self.initItems;

                if (self.items) {
                    self.initializeItems(self.items);
                }
            });

            $('#limit').on('change', function(e){
                SNAP.event.$emit('submit-form');
            });
        },

        data: function(){
            return {
                items: []
            }
        },

        methods: {
            initializeItems: function(items){
                let map = this.map();
                for(let n in items) {
                    let item = items[n];
                    if (item.lat && item.lng) {
                        // console.log(item.coordinates.coordinates[0], item.coordinates.coordinates[1])
                        let latlng = L.latLng(item.lat, item.lng);
                        // var latlng = L.latLng(item.coordinates.coordinates[0], item.coordinates.coordinates[1]);
                        L.marker(latlng).addTo(map.mapView);
                        item.latlng = latlng;
                        this.items[n] = item;
                    }
                }
            },

            map: function(){
                return this.$refs.map;
            },

            item: function(id) {
                return this.items[id];
            },

            edit: function(id){
                this.panTo(id);
                this.popUp(id);
            },

            panTo: function(id) {
                let item = this.item(id);
                if (item) {
                    let map = this.map();
                    map.mapView.panTo(item.latlng)
                }
            },

            popUp: function(id) {
                let item = this.item(id);
                let content = item.display_name || item.name || item.title;
                if (content) {
                    let map = this.map();

                    let popup = L.popup()
                        .setLatLng(item.latlng)
                        .setContent(content)
                        .openOn(map.mapView);
                }
            }
        }

    });
</script>