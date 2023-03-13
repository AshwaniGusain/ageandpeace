<template>
    <div>
        <section class="task-progress py-8">
            <div class="container">
                <div class="row">
                    <div class="col-12 mb-2">
                        <div class="d-flex justify-content-between">
                            <h2 class="task-progress-header">Your Progress</h2>
                            <div class="task-progress-amount" v-if="totalProgress">{{totalProgress}} Total Complete</div>
                        </div>
                    </div>

                    <div class="col-12 mb-7">
                        <progress-bar :bars="progress"></progress-bar>
                    </div>

                    <slot name="progress-icons" :progress="progress"></slot>
                </div>
            </div>
        </section>

        <section class="tasks-preview pb-8">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h3 class="task-progress-header">Your Upcoming Tasks</h3>
                        <p>Set due dates on your tasks to see what's upcoming.</p>
                    </div>
                </div>
            </div>

            <task-list :initial-tasks="tasks" @task-completed="updateProgress">
                <div class="text-center text-md-left" slot="after-tasks">
                    <a href="/dashboard/upcoming-tasks" class="btn btn-primary mb-2" style="width: 200px">All Upcoming Tasks</a>
                    <a href="/dashboard/all-tasks" class="btn btn-outline-primary mb-2" style="width: 200px">All Tasks</a>
                </div>
            </task-list>
        </section>
    </div>
</template>

<script>
  import progressBar from './ProgressBar.vue';
  import taskList from './TaskList.vue';

  export default {
    components: {progressBar, taskList},

    props: ['initialProgress', 'tasks'],

    computed: {
      totalProgress() {
        let sum = 0;

        for (let category in this.progress) {
          sum += this.progress[category].progress;
        }

        return Math.round(sum * 100/6) + '%';
      }
    },

    data() {
      return {
        progress: this.initialProgress
      }
    },

    methods: {
      updateProgress({id, completed, category}) {
        let cat = this.progress[category];
        cat.completedTasks += parseInt(completed);
        cat.progress = Math.round((cat.completedTasks / cat.totalTasks) * 100) / 100;
      }
    }
  };
</script>

