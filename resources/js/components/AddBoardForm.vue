<template>
    <form class="col-12 mb-3 bg-white rounded shadow" @submit.prevent="handleAddNewBoard">
        <div class="p-3 flex-1">
            <input class="form-control mb-3" type="text" placeholder="Enter a title" v-model.trim="newBoard.title" />
            <textarea class="form-control mb-3" rows="2" placeholder="Add a description (optional)"
                v-model.trim="newBoard.description"></textarea>
            <div v-show="errorMessage">
                <span class="text-danger small">
                    {{ errorMessage }}
                </span>
            </div>
        </div>
        <div class="p-3 d-flex justify-content-between align-items-end text-muted bg-light">
            <button @click="handleTaskCanceled" type="button" class="btn btn-link">
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
        userId: Number,
    },
    data() {
        return {
            newBoard: {
                title: "",
                description: "",
            },
            errorMessage: "",
        };
    },
    methods: {
        handleAddNewBoard() {
            console.log(this.newBoard.title);
            if (!this.newBoard.title) {
                this.errorMessage = "The title field is required";
                return;
            }

            axios
                .post("/boards", this.newBoard)
                .then((res) => {
                    this.$emit("board-added", res.data);
                })
                .catch((err) => {
                    this.handleErrors(err);
                });
        },
        handleTaskCanceled() {
            this.$emit("board-canceled");
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
        },
    },
};
</script>
