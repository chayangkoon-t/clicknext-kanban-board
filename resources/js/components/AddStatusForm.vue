<template>
    <form class="mb-3 bg-white rounded shadow" @submit.prevent="handleStatusAdded">
        <div class="p-3 flex-1">
            <input class="form-control mb-3" type="text" placeholder="Enter a title" v-model.trim="newStatus.title" />
            <div v-show="errorMessage">
                <span class="text-danger small">
                    {{ errorMessage }}
                </span>
            </div>
        </div>
        <div class="p-3 d-flex justify-content-between align-items-end text-muted bg-light">
            <button @click="$emit('status-canceled')" type="reset" class="btn btn-link">
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
        statuses: Array,
    },
    data() {
        return {
            newStatus: {
                title: "",
                board_id: null,
                order: null,
            },
            errorMessage: ""
        };
    },
    mounted() {
        this.newStatus.board_id = this.boardId;
        this.statuses = this.statuses;
    },
    methods: {
        handleStatusAdded() {
            if (!this.newStatus.title) {
                this.errorMessage = "The title field is required";
                return;
            }
            this.newStatus.board_id = this.boardId;
            const statusIndex = this.statuses.length;
            this.newStatus.order = statusIndex + 1;

            axios
                .post("/statuses", this.newStatus)
                .then(res => {
                    this.$emit("status-added", res.data);
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
