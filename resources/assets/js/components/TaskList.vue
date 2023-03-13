<template>
    <div>
        <transition name="scale">
            <section class="mb-8" v-if="showProgress && percentComplete">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h5> {{Math.round(percentComplete * 100)}}% Category Complete</h5>

                            <progress-bar :bars="[{progress: percentComplete, slug: tasks[0].categoryClass }]"></progress-bar>
                        </div>
                    </div>
                </div>
            </section>
        </transition>

        <div class="container">
            <div class="row" v-if="tasks.length && showFilters">
                <div class="col d-lg-flex justify-content-between align-items-center mb-4">
                    <div class="d-sm-flex">
                        <div class="form-inline">
                            <div class="form-group mr-3 mb-2 mb-sm-0">
                                <label for="">Display</label>

                                <select name="" id="" class="custom-select form-control ml-sm-3" v-model="filter">
                                    <option selected value="remaining">Remaining Tasks</option>
                                    <option value="completed">Completed Tasks</option>
                                    <option value="all">All Tasks</option>
                                    <optgroup label="Categories" v-if="allowFilterByCategory">
                                        <option value="health-and-wellness">Health and Wellness</option>
                                        <option value="financial-and-insurance">Financial and Insurance</option>
                                        <option value="legal-and-legacy">Legal and Legacy</option>
                                        <option value="home-and-care">Home and Care</option>
                                        <option value="social-and-spiritual">Social and Spiritual</option>
                                        <option value="care-giving-and-tech">Care Giving and Tech</option>
                                    </optgroup>
                                    <optgroup label="Provider Types" v-if="providerTypes">
                                        <option v-for="(type, index) in providerTypes" :value="index">{{type.label}} Tasks</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="d-none d-lg-block">
                        <a :href="`${printUrl}${printUrlParams}`" target="_blank" class="text-inherit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon v-align-middle" viewBox="0 0 23 21">
                                <g fill="#3F474E" fill-rule="evenodd" transform="translate(.053 .703)">
                                    <path d="M16.3345412,0.165234077 L5.65617519,0.165234077 C5.24888142,0.165234077 4.91841327,0.494718549 4.91841327,0.902995994 L4.91841327,3.66394701 L2.70512752,3.66394701 C1.21135637,3.66394701 0,4.87530289 0,6.36907404 L0,13.2059137 C0,14.6996849 1.21135637,15.9110407 2.70512752,15.9110407 L4.67249263,15.9110407 L4.67249263,19.2626931 C4.67249263,19.6709706 5.00296079,20.000455 5.41025455,20.000455 L16.5804618,20.000455 C16.9877556,20.000455 17.3182237,19.6709706 17.3182237,19.2626931 L17.3182237,15.9139918 L19.2855888,15.9139918 C20.77936,15.9139918 21.9907159,14.7026359 21.9907159,13.2088648 L21.9907159,6.36931996 C21.9907159,4.87554881 20.77936,3.66419293 19.2855888,3.66419293 L17.0723031,3.66419293 L17.0723031,0.903241915 C17.0723031,0.49496447 16.7418349,0.165479998 16.3345412,0.165479998 L16.3345412,0.165234077 Z M6.3939371,1.64075791 L15.5967793,1.64075791 L15.5967793,3.66384864 L6.3939371,3.66384864 L6.3939371,1.64075791 Z M6.14801646,18.52862 L6.14801646,12.3997859 L15.8426999,12.3997859 L15.8426999,18.5246853 L6.14801646,18.52862 Z M20.5056011,6.36907404 L20.5056011,13.2059137 C20.5056011,13.5325209 20.3759173,13.8447418 20.1453766,14.0752924 C19.9148358,14.305843 19.6016706,14.4355169 19.2759979,14.4355169 L17.3086328,14.4355169 L17.3086328,12.399958 L18.3730019,12.399958 C18.7802957,12.399958 19.1107639,12.0694899 19.1107639,11.6621961 C19.1107639,11.2539186 18.7802957,10.9244342 18.3730019,10.9244342 L3.6177636,10.9244342 C3.21046984,10.9244342 2.88000169,11.2539186 2.88000169,11.6621961 C2.88000169,12.0694899 3.21046984,12.399958 3.6177636,12.399958 L4.67254182,12.399958 L4.67254182,14.4384188 L2.7051767,14.4384188 C2.02601768,14.4384188 1.47557351,13.8879746 1.47557351,13.2088156 L1.47557351,6.36927077 C1.47557351,5.69011174 2.02601768,5.13966758 2.7051767,5.13966758 L19.2760471,5.13966758 C19.6016952,5.13966758 19.9148752,5.26935137 20.1454258,5.49989213 C20.3759764,5.73043289 20.5056503,6.04359807 20.5056503,6.36927077 L20.5056011,6.36907404 Z"/>
                                    <path d="M15.7640053,7.68206892 L18.4691323,7.68206892 C18.8764261,7.68206892 19.2068942,7.35258445 19.2068942,6.944307 C19.2068942,6.53701324 18.8764261,6.20654508 18.4691323,6.20654508 L15.7640053,6.20654508 C15.3567115,6.20654508 15.0262434,6.53701324 15.0262434,6.944307 C15.0262434,7.35258445 15.3567115,7.68206892 15.7640053,7.68206892 Z"/>
                                </g>
                            </svg>

                            <span class="small"><strong>Print Task List</strong></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row no-gutters">
                <div class="col">
                    <div class="list-group tasks-list mb-6">
                        <transition-group name="task-list" tag="div">
                            <task v-for="task in activeTasks"
                                  @task-completed="complete"
                                  @task-due-date="dueDate"
                                  :category="task.categoryClass"
                                  v-bind="task"
                                  :key="'task-' + task.id">
                                {{task.description}}
                            </task>
                        </transition-group>
                    </div>

                    <div class="px-3 px-md-0">
                        <slot name="after-tasks" :activeTasks="activeTasks.length"></slot>

                        <p v-if="! activeTasks.length">
                            <slot name="empty-list-message" v-if="filter !== 'remaining'">
                                No tasks fit your filtering criteria.
                            </slot>

                            <slot name="finished-list-message" v-else>

                                <div v-if="percentComplete === 100">
                                    Looks like you're all done here. Nice work!
                                </div>

                            </slot>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  import task from './Task.vue';
  import progressBar from './ProgressBar.vue';

  export default {
    name: 'task-list',

    components: {task, progressBar},

    props: ['initialTasks', 'showProgress', 'showFilters', 'providerTypes', 'initialProviderFilter', 'printUrl', 'allowFilterByCategory'],

    data() {
      return {
        filter: 'remaining',
        providerFilter: null,
        tasks: this.initialTasks,
      };
    },

    created() {
        // console.log(this.providerTypes);
      /*if (this.initialProviderFilter && this.providerTypes) {

        // const matchedProvider = this.providerTypes.map(p => p.label).indexOf(this.initialProviderFilter);
        let length = this.providerTypes.length;
        for (let i = 0; i < length; i++) {
          if (this.providerTypes[i].label === this.initialProviderFilter) {
            this.providerFilter = i;
            break;
          }
        }
      }*/
    },

    computed: {
      percentComplete() {
        return this.completedTaskList().length / this.tasks.length;
      },

      activeTasks() {
        this.providerFilter = null;
        let list = [];
        switch (this.filter) {
          case 'all':
            list = this.tasks;
            break;

          case 'remaining':
            list = this.remainingTaskList();
            break;

          case 'completed':
            list = this.completedTaskList();
            break;

          case 'health-and-wellness':
          case 'financial-and-insurance':
          case 'legal-and-legacy':
          case 'home-and-care':
          case 'social-and-spiritual':
          case 'care-giving-and-tech':
             list = this.categoryTaskList();
             break;
          default:
              this.providerFilter = this.filter;
              list = this.providerTypeList();
        }

        return list;
      },

      providerCategories() {
        if (this.providerFilter == null) {
          return;
        }

        return this.providerTypes[this.providerFilter].categories;
      },

      printUrlParams() {
        if (! this.showFilters) {
          return null;
        }

        let prefix = this.printUrl.includes('?') ? '&' : '?';

        switch (this.filter) {
          case 'remaining':
            return `${prefix}completed=0`;
          case 'completed':
            return `${prefix}completed=1`;
          default:
            return '';
        }
      }
    },

    methods: {
      filterByProvider(task) {
        if (this.providerFilter == null) {
          return true;
        }

        let found = false;

        for (let i = 0, length = this.providerCategories.length; i < length; i++) {
          if (this.providerCategories[i] == task.category_id)
            found = true;
        }

        return found
      },

      completedTaskList() {
        return this.tasks.filter(task => {
          return task.completed === 1;
        });
      },

      remainingTaskList() {
        return this.tasks.filter(task => {
          return task.completed === 0;
        });
      },

      categoryTaskList() {
          return this.tasks.filter(task => {
              return task.categoryClass === this.filter;
          });
      },

      providerTypeList() {
          return this.tasks.filter(task => {
              return this.filterByProvider(task);
          });
      },

      findById(id) {
        let found = false;

        for (let i = 0; i < this.tasks.length; i++) {
          if (this.tasks[i].id == id) {
            found = i;
            break;
          }
        }

        return found;
      },

      complete({id, completed}) {
        for (let i = 0; i < this.tasks.length; i++) {
          if (this.tasks[i].id == id) {
            this.tasks[i].completed = parseInt(completed);

            if (completed) {
              this.tasks[i].due_date = null;
            }

            this.$emit('task-completed', {id, completed, category: this.tasks[i].categoryClass});
          }
        }
      },

      dueDate({id, due_date}) {
        let indexFound = this.findById(id);

        if (indexFound !== false) {
          this.tasks[indexFound].due_date = due_date;
        }
        //this.$forceUpdate();
      }
    }
  };
</script>

