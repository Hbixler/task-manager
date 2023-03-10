<template>
    <b-container class="mt-3">
        <b-row class="mb-5">
            <b-col>
                <center><h2>Search Tasks</h2></center>
            </b-col>
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
            <b-card v-if="!task.is_completed" class="mb-5">
                <b-row>
                    <b-col>
                        <h5>{{ task.task_title }}</h5>
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
        name: 'task-search',
        data() {
            return {
                titleQuery: "",
                tasks: []
            }
        },
        mounted() {
            // Load tasks here
        },
        methods: {
            searchTasks() {
                // CALL BACKEND TO SEARCH TASKS HERE
                if (this.titleQuery.trim() === "") {
                    // GET ALL TASKS
                    const data = {
                        task_title: this.titleQuery
                    }
                    this.$axios
                    .get('https://bxlmly5qs5.execute-api.us-east-2.amazonaws.com/api/tasks')
                    .then(response => {
                        this.tasks = response.data.results;
                    })
                    .catch(err => {
                        console.error(err);
                    });
                }
                else {
                    // SEARCH TASKS
                    const data = {
                        task_title: this.titleQuery
                    };
                    this.$axios
                    .post('https://bxlmly5qs5.execute-api.us-east-2.amazonaws.com/api/tasks/search', data)
                    .then(response => {
                        console.log(response.data.results);
                        // this.tasks = response.data.results;
                    })
                    .catch(err => {
                        console.error(err);
                    });
                }
            }
        }
    }
</script>