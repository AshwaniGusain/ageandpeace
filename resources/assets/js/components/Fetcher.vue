<script>
  import axios from 'axios';

  export default {
    name: 'fetcher',

    props: ['url', 'args'],

    data() {
      return {
        fetched: [],
        loading: true,
      }
    },

    created() {
      this.getIt();
    },

    methods: {
      getIt() {
        //if (!this.checkForIt()) {
          let data = {
            params: this.args ? this.args : {},
          };

          axios.get(this.url, data).then(response => {
            this.fetched = response.data;
            this.loading = false;

            //fetched could be array or object
            if (this.fetched.length > 0 || Object.keys(this.fetched).length > 0) {
              sessionStorage.setItem(this.url, JSON.stringify(response.data));
            }
          }).catch(error => {
            console.log(error);
            this.loading = false;
          });
        //}
      },

      checkForIt() {
        if (sessionStorage.getItem(this.url)) {
          this.fetched = JSON.parse(sessionStorage.getItem(this.url));
          this.loading = false;
          return true;
        }

        return false;
      },
    },

    render() {
      return this.$scopedSlots.default({
        fetched: this.fetched,
        loading: this.loading,
      });
    },
  };
</script>
