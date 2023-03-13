<script>
  export default {
    name: 'expand-text',
    props: ['content', 'length'],

    data() {
      return {
        expanded: false,
      }
    },

    computed: {
      displayedText() {
        let length = this.length ? this.length : 150;

        if (this.expanded || this.content.length < length) {
          return this.content
        }

        let truncated = this.content.substring(0, length);
        return truncated + '...';
      }
    },

    methods: {
      toggle() {
        this.expanded = !this.expanded;
      }
    },

    render() {
        return this.$scopedSlots.default({
          expanded: this.expanded,
          toggle: this.toggle,
          text: this.displayedText
        })
    }
  };
</script>
