<template>
    <div class="d-flex justify-content-end btn-toolbar align-items-center">
        <span class="small d-none d-md-inline"><strong>Search by</strong></span>
        <div class="btn-group filter-toggle">
            <button class="btn btn-outline-primary" :class="{active: activeFilter === 'category'}"
                    @click.prevent="setTab('category')">Category
            </button>
            <button class="btn btn-outline-primary" :class="{active: activeFilter === 'keyword'}"
                    @click.prevent="setTab('keyword')">Keyword
            </button>
        </div>

        <portal to="search-toolbar">
            <form method="get" ref="search">
                <div class="row justify-content-between form-row">
                    <div class="col-sm form-group" v-if="activeFilter === 'category'">
                        <label for="category">Category</label>
                        <select name="category" id="category" class="custom-select" v-model="selected.category">
                            <option :value="null">Select a Category...</option>
                            <option v-for="(category, i) in categories" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                    </div>
                    <div class="col-sm form-group" v-if="activeFilter === 'category'">
                        <label for="subcategory">Subcategory</label>
                        <select name="subcategory" id="subcategory" class="custom-select" v-model="selected.subcategory" :disabled="selected.category == null">
                            <option :value="null" selected>Select a Subcategory...</option>
                            <option v-for="(subcategory, i) in subcategories" :value="subcategory.id">
                                {{subcategory.name}}
                            </option>
                        </select>
                    </div>
                    <div class="col-sm form-group" v-if="activeFilter === 'category'">
                        <label for="provider">Provider Type</label>
                        <select name="type" id="type" class="custom-select" v-model="selected.provider" :disabled="selected.subcategory == null">
                            <option :value="null">Select a Provider Type...</option>
                            <option v-for="(provider, i) in providerCategories" :value="provider.id">
                                {{provider.name}}
                            </option>
                        </select>
                    </div>

                    <div class="col-sm form-group" v-if="activeFilter !== 'category'">
                        <label for="search">Search</label>
                        <input type="text" name="search" class="form-control" id="search" v-model="search">
                    </div>

                    <div class="form-group zip-field align-self-end">

                            <input type="hidden" name="zip" v-model="zipValue" v-if="zipValue">

                            <button class="btn btn-primary" type="submit" style="width: 100%;" @click="searching=true" v-show="searching == false">
                                Search
                            </button>
                            <div class="loader" style="width: 100%" v-show="searching == true"></div>

                    </div>

                </div>

                <div class="row">
                    <div class="col-12 text-right">
                        <zip-code-form v-model="zipValue" @update-zip="updateZip" :valid="valid"></zip-code-form>
                    </div>
                </div>
            </form>
        </portal>
    </div>

</template>

<script>

  import zipCodeForm from './ZipCodeForm.vue';

  const findIdIndex = (id, arr) => {
    let selectedIndex = -1;

    arr.forEach((x, i) => {
      if (x.id === id) selectedIndex = i;
    });

    return selectedIndex > -1 ? selectedIndex : null;
  };

  export default {
    name: 'provider-filters',

    components: {zipCodeForm},

    props: ['categories', 'zip', 'valid', 'initialFilters', 'activeTab', 'initSearch'],

    data() {
      return {
        selected: {
          category: null,
          subcategory: null,
          provider: null,
        },
        filters: ['category', 'keyword'],
        activeFilter: this.activeTab ? this.activeTab : 'category',
        search: this.initSearch,
        zipValue: this.zip,
        zipCodeMissing: false,
        searching: false
      };
    },

    methods: {
      updateZip() {
        sessionStorage.setItem('provider-search-zipcode', this.zipValue);
        this.$refs.search.submit()
      },

      setTab(tabName) {
        this.activeFilter = tabName;
        if (tabName === 'category') {
          this.s = '';
        } else if (tabName === 'keyword') {
          this.selected.provider = null;
          this.selected.subcategory = null;
          this.selected.category = null;
        }
      }
    },

    computed: {

      subcategories() {
          let selectedCategoryIndex = findIdIndex(this.selected.category, this.categories);
          return selectedCategoryIndex !== null ? this.categories[selectedCategoryIndex].children_categories : [];
      },

      providerCategories() {
        let selectedCategoryIndex = findIdIndex(this.selected.category, this.categories);
        if (selectedCategoryIndex != null) {
            let selectedSubCategoryIndex = findIdIndex(this.selected.subcategory, this.categories[selectedCategoryIndex].children_categories);
            //console.log(this.categories[selectedCategoryIndex].children_categories);
            return (selectedSubCategoryIndex != null) ?
                this.categories[selectedCategoryIndex].children_categories[selectedSubCategoryIndex].provider_types :
                [];
        }
      }
    },

    mounted() {
      let self = this;
      //let initialRender = true;

      //if we don't have a zip code, open the modal
      if (! this.zipValue ) {
        this.zipCodeMissing = true;
      }

      if (this.initialFilters.length) {
          const [categoryFilter, subcategoryFilter, providerFilter] = this.initialFilters;
          this.selected.category = categoryFilter || null;
          this.selected.subcategory = subcategoryFilter || null;
          this.selected.provider = providerFilter || null;
          //initialRender = false;
      }

      //Register watchers here instead of in the watch property on vm so they won't trigger when we're trying to set the values initially
      this.$watch('selected.category', function(val) {
          this.selected.subcategory = null;
      });

      this.$watch('selected.subcategory', function() {
          this.selected.provider = null;
      });

    },
  };
</script>

