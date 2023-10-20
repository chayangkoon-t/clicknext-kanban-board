<template>
    <form class="mb-3 bg-white rounded shadow" @submit.prevent="handleAddNewTask">
        <div class="p-3 flex-1">
            <input class="form-control mb-3" type="text" placeholder="Enter a title" v-model.trim="newTask.title" />
            <textarea class="form-control mb-3" rows="2" placeholder="Add a description (optional)"
                v-model.trim="newTask.description"></textarea>
            <div v-show="errorMessage">
                <span class="text-danger small">
                    {{ errorMessage }}
                </span>
            </div>
        </div>
        <div class="p-3 d-flex justify-content-between align-items-end text-muted bg-light">
            <button @click="$emit('task-canceled')" type="reset" class="btn btn-link">
                Cancel
            </button>
            <button type="submit" class="btn btn-warning">
                Add
            </button>
        </div>
    </form>
</template>

<script>
export default {
    props: {
        statusId: Number,
        boardId: Number,
    },
    data() {
        return {
            newTask: {
                title: "",
                description: "",
                status_id: null,
                board_id: null,
            },
            errorMessage: ""
        };
    },
    mounted() {
        this.newTask.status_id = this.statusId;
        this.newTask.board_id = this.boardId;
    },
    methods: {
        handleAddNewTask() {
            if (!this.newTask.title) {
                this.errorMessage = "The title field is required";
                return;
            }

            axios
                .post("/tasks", this.newTask)
                .then(res => {
                    this.$emit("task-added", res.data);
                })
                .catch(err => {
                    this.handleErrors(err);
                });
        },
        handleErrors(err) {
            if (err.response && err.response.status === 422) {
                const errorBag = err.response.data.errors;
                if (errorBag.title) {
                    this.errorMessage = errorBag.title[0];
                } else if (errorBag.description) {
                    this.errorMessage = errorBag.description[0];
                } else {
                    this.errorMessage = err.response.message;
                }
            } else {
                console.log(err.response);
            }
        }
    }
};
</script>
