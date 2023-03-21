<template>
    <b-container class="mt-3" style="min-width: 600px">
        <b-row class="mb-3">
            <b-col>
                <center><h2>Insert Task</h2></center>
            </b-col>
        </b-row>
        <b-row class="mb-5">
            <b-col><router-link to="/">Home</router-link></b-col>
            <b-col><router-link to="/search">Search Tasks</router-link></b-col>
            <b-col><router-link to="/insert">Insert Task</router-link></b-col>
        </b-row>
        <b-row class="mb-2">
            <b-col>
                <b-form-input placeholder="Task Title" v-model="title" :state="titleState"></b-form-input>
            </b-col>
        </b-row>
        <b-row class="mb-2">
            <b-col>Due Date: {{  due_date }}<br><b-form-datepicker v-model="due_date"></b-form-datepicker></b-col>
            <b-col>Difficulty: {{ difficulty }}<br><b-form-input type="range" min="1" max="5" v-model="difficulty"></b-form-input></b-col>
        </b-row>
        <b-row class="mb-2">
            <b-col>
                <b-form-textarea placeholder="Task description..." v-model="description" :state="descriptionState"></b-form-textarea>
            </b-col>
        </b-row>
        <b-row>
            <b-col>
                <center><b-button @click="insertTask()">Submit</b-button></center>
            </b-col>
        </b-row>
    </b-container>
</template>

<script>
    export default {
        name: 'task-insert',
        data() {
            return {
                title: "",
                due_date: "",
                difficulty: 1,
                description: "",
                titleState: null,
                descriptionState: null
            }
        },
        methods: {
            insertTask() {
                if (this.validate()) {
                    // Task meets validation, ready to insert
                    const task = {
                        task_title: this.title.trim(),
                        difficulty: this.difficulty,
                        is_completed: false
                    }
                    if (this.due_date) {
                        task.due_date = this.due_date;
                    }
                    if (this.description) {
                        task.description = this.description.trim();
                    }
                    this.$axios
                        .post('https://bxlmly5qs5.execute-api.us-east-2.amazonaws.com/api/tasks', task)
                        .then(() => {
                            // Insert successful, reset form
                            this.clearScreen();
                        })
                        .catch(err => {
                         console.error(err);
                        });
                }
            },
            validate() {
                let isCorrect = true;
                this.titleState = null;
                if (this.title.trim() === "" || this.title.trim().length > 30) {
                    // Title is blank or too long
                    this.titleState = false;
                    isCorrect = false;
                }
                this.descriptionState = null;
                if (this.description.trim().length > 255) {
                    // Description is too long
                    this.descriptionState = false;
                    isCorrect = false;
                }
                return isCorrect;
            },
            clearScreen() {
                // Reset all form fields and states
                this.title = "";
                this.due_date = "";
                this.difficulty = 1;
                this.description = "";
                this.titleState = null;
                this.descriptionState = null;
            }
        }
    }
</script>