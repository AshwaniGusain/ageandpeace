<script>
    Vue.component('snap-coordinates-input', {
        template : '<div class="clearfix"><div class="clearfix"><div style="float: left; margin-right: 7px;"><input type="number" :id="id" :name="latitudeName" v-model="lat" :placeholder="latitudeName" :max="180" :min="-180" step=".0000001" class="form-control" /></div><div style="float: left; margin-right: 5px;"><input type="number" :name="longitudeName" v-model="lng" :placeholder="longitudeName" :max="180" :min="-180" step=".0000001" class="form-control" /></div></div><div class="map" style="margin-top: 5px;"><snap-map :mapid="mapid" :url="mapTilesUrl" :latitude="latitude" :longitude="longitude" ref="map" :width="mapWidth" :height="mapHeight" :zoom="mapZoom" /></div></div>',
        props: {
            'mapid': {
                type: String,
                required: false,
                default: 'mapid'
            },
            'id': {
                type: String,
                default: ''
            },
            'latitude': {
                required: false,
            },
            'longitude': {
                required: false,
            },
            'latitudeName': {
                type: String,
                required: false,
                default: 'latitude'
            },
            'longitudeName': {
                type: String,
                required: false,
                default: 'longitude'
            },
            'mapWidth': {
                required: false,
                default: '100%'
            },
            'mapHeight': {
                required: false,
                default: '200px'
            },
            'mapZoom': {
                type: Number,
                required: false,
                default: 13
            },
            'mapTilesUrl':  {
                type: String,
                required: false,
                default: 'http://{s}.tiles.wmflabs.org/bw-mapnik/{z}/{x}/{y}.png'
            }
        },

        mounted: function(){
            var self = this;

            this.$nextTick(function(){
                self.lat = parseFloat(self.latitude) || null;
                self.lng = parseFloat(self.longitude) || null;
                self.createMarker();

                self.map().mapView.on('click', function (e) {
                    var latlng = self.map().mapView.mouseEventToLatLng(e.originalEvent);
                    self.lat = latlng.lat.toFixed(5);
                    self.lng = latlng.lng.toFixed(5);
                });
            });


        },

        data: function(){
            return {
                lat: null,
                lng: null,
                marker: null
            }
        },

        watch: {
            'lat': function(){
                this.createMarker();
            },
            'lng': function(){
                this.createMarker();
            }
        },

        methods: {

            latInput: function(){
                return this.$refs.latitude;
            },

            lngInput: function(){
                return this.$refs.longitude;
            },

            map: function(){
                return this.$refs.map;
            },

            createMarker: function(){
                var map = this.map();
                map.lat = this.lat;
                map.lng = this.lng;

                // if (!map.mapView) {
                //     map.createMap();
                // }

                // if (map.mapView) {
                // map.remove();
                // map.createMap();
                // mapView.eachLayer(function (layer) {
                //     map.mapView.removeLayer(layer);
                // });
                if (this.marker) {
                    map.mapView.removeLayer(this.marker);
                }

                if (this.lat && this.lng) {
                    var latlng = L.latLng(this.lat, this.lng);
                    this.marker = L.marker(latlng).addTo(map.mapView);
                    map.mapView.panTo(latlng)
                }
                // }

            },

            getOptions: function(){
                return JSON.parse($(this.$el).next().text());
            }
        }

    });
</script>