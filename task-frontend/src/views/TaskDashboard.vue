<template>
    <b-container class="mt-3" style="min-width: 600px">
        <b-row class="mb-3">
            <b-col><center><h2>Today's Tasks</h2></center></b-col>
        </b-row>
        <b-row class="mb-5">
            <b-col><router-link to="/">Home</router-link></b-col>
            <b-col><router-link to="/search">Search Tasks</router-link></b-col>
            <b-col><router-link to="/insert">Insert Task</router-link></b-col>
        </b-row>
        <b-row v-for="(task, index,) in tasks">
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
                ]
            }
        },
        mounted() {
            this.getData();
        },
        methods: {
            async getData() {
                this.$axios
                    .get('https://bxlmly5qs5.execute-api.us-east-2.amazonaws.com/api/tasks')
                    .then(response => {
                        let allTasks = response.data;
                        allTasks = allTasks.filter(task => !task.is_completed);
                        allTasks.sort((a,b) => {
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
                        if (allTasks.length <= 5) {
                            this.tasks = allTasks;
                        }
                        else {
                            this.tasks = allTasks.slice(0,5);
                        }
                    })
                    .catch(err => {
                        console.error(err);
                    });
            },
            checked(index) {
                let task = this.tasks[index];
                if (!task.is_completed) {
                    task.is_completed = true;
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