<template>
    <b-container class="mt-3" style="min-width: 600px">
        <b-row class="mb-3">
            <b-col><center><h2>Top 5 Tasks</h2></center></b-col>
        </b-row>
        <b-row class="mb-5">
            <b-col><router-link to="/">Home</router-link></b-col>
            <b-col><router-link to="/search">Search Tasks</router-link></b-col>
            <b-col><router-link to="/insert">Insert Task</router-link></b-col>
        </b-row>
        <b-row v-for="(task, index,) in tasks" :key="`task-${task.id}`">
            <b-card v-if="!task.is_completed" class="mb-3">
                <b-row>
                    <b-col cols="8">
                        <h5>{{ task.task_title }}</h5>
                    </b-col>
                    <b-col cols="4">
                        <b-form-checkbox @input="checked(index)"><span style="color:transparent">X</span>Completed!</b-form-checkbox>
                    </b-col>
                </b-row>
                <b-row>
                    <b-col v-if="task.due_date">
                        <p><i>Due: {{ task.due_date }}</i></p>
                    </b-col>
                    <b-col>
                        <p><i>Difficulty: {{ task.difficulty }}</i></p>
                    </b-col>
                </b-row>
                <b-row v-if="task.description">
                    <b-col>
                        <p>{{  task.description }}</p>
                    </b-col>
                </b-row>
            </b-card>
        </b-row>
    </b-container>
</template>

<script>
    export default {
        name: 'task-dashboard',
        data() {
            return {
                tasks: [
                ],
                allTasks: [
                ]
            }
        },
        mounted() {
            this.getData();
        },
        methods: {
            async getData() {
                // Load up the tasks
                this.$axios
                    .get('https://bxlmly5qs5.execute-api.us-east-2.amazonaws.com/api/tasks')
                    .then(response => {
                        this.allTasks = response.data;

                        // Only get tasks that are not completed
                        this.allTasks = this.allTasks.filter(task => !task.is_completed);

                        // Sort with closer due dates having higher priority
                        this.allTasks.sort((a,b) => {
                            if (a.due_date && b.due_date) {
                                if (a.due_date < b.due_date) {
                                    return -1;
                                }
                                else if (a.due_date > b.due_date) {
                                    return 1;
                                }
                                else {
                                    return 0;
                                }
                            }
                            else if (a.due_date) {
                                return -1;
                            }
                            else {
                                return 1;
                            }
                        });

                        // User can only see top five tasks at a time
                        if (this.allTasks.length <= 5) {
                            this.tasks = this.allTasks;
                        }
                        else {
                            this.tasks = this.allTasks.slice(0,5);
                        }
                    })
                    .catch(err => {
                        console.error(err);
                    });
            },
            checked(index) {
                // User completed task at index
                let task = this.tasks[index];
                if (!task.is_completed) {
                    // Update view to display top five tasks
                    task.is_completed = true;
                    this.allTasks = this.allTasks.filter(task => !task.is_completed);
                    if (this.allTasks.length <= 5) {
                            this.tasks = this.allTasks;
                    }
                    else {
                        this.tasks = this.allTasks.slice(0,5);
                    }

                    // Prepare task for update
                    let data = {
                        task_title: task.task_title,
                        difficulty: task.difficulty,
                        is_completed: true
                    }
                    if (task.due_date) {
                        data.due_date = task.due_date;
                    }
                    if (task.description) {
                        data.description = task.description;
                    }

                    // Update task to be completed
                    this.$axios
                    .put(`https://bxlmly5qs5.execute-api.us-east-2.amazonaws.com/api/tasks/${task.id}`, data)
                    .catch(err => {
                        console.error(err);
                    });

                }
            }
        }
    }
</script>