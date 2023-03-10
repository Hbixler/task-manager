<template>
    <b-container class="mt-3">
        <b-row class="mb-5">
            <b-col><center><h2>Today's Tasks</h2></center></b-col>
        </b-row>
        <b-row v-for="(task, index,) in tasks">
            <b-card v-if="!task.is_completed" class="mb-3">
                <b-row>
                    <b-col cols="8">
                        <h5>{{ task.task_title }}</h5>
                    </b-col>
                    <b-col cols="4">
                        <b-form-checkbox @input="checked(index)"></b-form-checkbox>
                        Completed!
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
                        console.log(response);
                        let allTasks = response.data;
                        if (allTasks.length > 5) {
                            this.tasks = allTasks;
                        }
                        else {
                            this.tasks = allTasks.slice(0,4);
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
                    console.log(task);
                    this.$axios
                    .put(`https://bxlmly5qs5.execute-api.us-east-2.amazonaws.com/api/tasks/${task.id}`, task)
                    .then(() => {
                        console.log('updated');
                    })
                    .catch(err => {
                        console.error(err);
                    });

                }
            }
        }
    }
</script>