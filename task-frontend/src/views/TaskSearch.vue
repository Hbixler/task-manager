<template>
    <b-container class="mt-3" style="min-width: 600px">
        <b-row class="mb-3">
            <b-col>
                <center><h2>Search Tasks</h2></center>
            </b-col>
        </b-row>
        <b-row class="mb-5">
            <b-col><router-link to="/">Home</router-link></b-col>
            <b-col><router-link to="/search">Search Tasks</router-link></b-col>
            <b-col><router-link to="/insert">Insert Task</router-link></b-col>
        </b-row>
        <b-row class="mb-5">
            <b-col cols="11">
                <b-form-input placeholder="Search Task Title" v-model="titleQuery"></b-form-input>
            </b-col>
            <b-col cols="1">
                <b-button @click="searchTasks()">Search</b-button>
            </b-col>
        </b-row>
        <b-row v-for="(task, index,) in tasks">
            <b-card class="mb-3">
                <b-row>
                    <b-col cols="8">
                        <h5>{{ task.task_title }}</h5>
                    </b-col>
                    <b-col cols="4">
                        <b-button variant="link" @click="deleteTask(task)">
                            <b-icon icon="trash-fill"></b-icon>
                        </b-button>
                    </b-col>
                </b-row>
                <b-row>
                    <b-col>
                        <p><i>Due: {{ task.due_date ? task.due_date : "N/A" }}</i></p>
                    </b-col>
                    <b-col>
                        <p><i>Difficulty: {{ task.difficulty }}</i></p>
                    </b-col>
                    <b-col>
                        <p><i>Completed:</i> {{ task.is_completed ? "Yes!" : "No" }}</p>
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
        name: 'task-search',
        data() {
            return {
                titleQuery: "",
                tasks: []
            }
        },
        methods: {
            searchTasks() {
                if (this.titleQuery.trim() === "") {
                    // No search query entered, get all tasks
                    this.$axios
                    .get('https://bxlmly5qs5.execute-api.us-east-2.amazonaws.com/api/tasks')
                    .then(response => {
                        this.tasks = response.data;
                    })
                    .catch(err => {
                        console.error(err);
                    });
                }
                else {
                    // Search for tasks that match title query
                    const data = {
                        task_title: this.titleQuery
                    };
                    this.$axios
                    .post('https://bxlmly5qs5.execute-api.us-east-2.amazonaws.com/api/tasks/search', data)
                    .then(response => {
                        this.tasks = response.data;
                    })
                    .catch(err => {
                        console.error(err);
                    });
                }
            },
            deleteTask(task) {
                // Delete task
                this.$axios
                .delete(`https://bxlmly5qs5.execute-api.us-east-2.amazonaws.com/api/tasks/${task.id}`)
                .then(() => {
                    // Task should disappear from screen
                    this.tasks = this.tasks.filter(taskItem => taskItem.id !== task.id);
                })
                .catch(err => {
                    console.error(err);
                });
            }
        }
    }
</script>