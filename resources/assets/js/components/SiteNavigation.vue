<script>
  import accordion from './Accordion.vue';
  import modalTrigger from './ModalTrigger.vue';

  export default {
    components: {accordion, modalTrigger},

    data() {
      return {
        nav: 'closed',
      }
    },

    mounted() {
      this.$refs.activeCategory = null;
    },

    computed: {},

    methods: {
      toggleNav() {
        this.nav = this.nav === 'closed' ? 'open' : 'closed';
      },

      toggleNavCategory($event) {
        this.$refs.activeCategory = $event.target;
        this.$refs.activeCategory.classList.add('submenu-open');
      },
    },
  };
</script>
