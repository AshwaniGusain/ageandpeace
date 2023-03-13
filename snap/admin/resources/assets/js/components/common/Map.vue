<script>
    /*define([
'jquery',
'Vue',
'https://unpkg.com/leaflet@1.0.2/dist/leaflet.js',
'cssplugin!https://unpkg.com/leaflet@1.0.2/dist/leaflet.css'
], function($, Vue){*/
    Vue.component('snap-map', {
        template: '<div :id="mapid" :style="styles"></div>',
        props: {
            'mapid': {
                type: String,
                required: false,
                default: 'mapid'
            },
            'latitude': {
                type: Number,
                default: 45.5122,
                required: false
            },
            'longitude': {
                type: Number,
                default: -122.6587,
                required: false
            },
            'width': {
                required: false,
                default: '100%'
            },
            'height': {
                required: false,
                default: '200px'
            },
            'zoom': {
                type: Number,
                required: false,
                default: 13
            },
            'url':  {
                type: String,
                required: false,
                //default: 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'
                default: 'http://{s}.tiles.wmflabs.org/bw-mapnik/{z}/{x}/{y}.png',
            }
        },

        mapView: null,

        mounted: function(){
            this.lat = this.latitude;
            this.lng = this.longitude;

            this.createMap();
        },

        data: function(){
            return {
                // url: 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                lat: null,
                lng: null,
                // zoom: 13,
                styles: {},
                options: {}
            };
        },

        methods: {

            createMap: function(opts){
                if (opts) this.options = opts;

                var self = this;
                this.styles = { width: this.width, height: this.height};

                this.$nextTick(function(){
                    // if (self.latitude && self.longitude) {
                    self.mapView = L.map(self.mapid).setView([self.latitude, self.longitude], self.zoom);
                    L.tileLayer(self.url, self.options).addTo(self.mapView);
                    // }

                    // L.marker([self.latitude, self.longitude]).addTo(this.mapView);
                    // L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpandmbXliNDBjZWd2M2x6bDk3c2ZtOTkifQ._QA7i5Mpkd_m30IGElHziw', {
                    // 	maxZoom: 18,
                    // 	attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
                    // 		'<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                    // 		'Imagery © <a href="http://mapbox.com">Mapbox</a>',
                    // 	id: 'mapbox.streets'
                    // }).addTo(map);
                    // L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpandmbXliNDBjZWd2M2x6bDk3c2ZtOTkifQ._QA7i5Mpkd_m30IGElHziw', {
                    //     attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
                    //     maxZoom: 18,
                    //     id: 'mapbox.mapbox-streets-v6',
                    //     accessToken: 'your.mapbox.public.access.token'
                    // }).addTo(map);
                    //mapbox.mapbox-streets-v7
                })
            }
        }


    });
</script>