<script>
require('chart.js');

Vue.component('snap-chart', {
    template: '<canvas id="myChart" :width="width" :height="height"></canvas>',

    props: {
        'type': {
            type: String,
            required: false,
            default: 'line'
        },
        'width': {
            type: Number,
            required: false,
            default: 400
        },
        'height': {
            type: Number,
            required: false,
            default: 300
        },
        'dataset': {
            type: Object,
            required: false
        },
        'labels': {
            type: Array,
            required: false
        },
    },

    mounted: function(){
        var ctx = document.getElementById("myChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: this.type,
            data: {
                labels: this.labels,
                //labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
                datasets: [this.dataset,
                    // backgroundColor: [
                    //     'rgba(255, 99, 132, 0.2)',
                    //     'rgba(54, 162, 235, 0.2)',
                    //     'rgba(255, 206, 86, 0.2)',
                    //     'rgba(75, 192, 192, 0.2)',
                    //     'rgba(153, 102, 255, 0.2)',
                    //     'rgba(255, 159, 64, 0.2)'
                    // ],
                    // borderColor: [
                    //     'rgba(255,99,132,1)',
                    //     'rgba(54, 162, 235, 1)',
                    //     'rgba(255, 206, 86, 1)',
                    //     'rgba(75, 192, 192, 1)',
                    //     'rgba(153, 102, 255, 1)',
                    //     'rgba(255, 159, 64, 1)'
                    // ],

                ]

            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        });
    },

    data: function(){
        return {

        };
    },

    methods: {


    }

});
</script>