<template>
    <div class="container-md">
        <div class="row rows-cols-md-1">
            <!-- No Boards -->
            <div v-if="!boards.length" class="col-12 d-flex flex-column align-items-center justify-content-center py-3">
                <span class="text-muted">No boards yet</span>
                <button class="mt-1 btn btn-link text-warning" @click="showBoardCreate = !showBoardCreate">Add new
                    one</button>
                <AddBoardForm v-if="showBoardCreate" @board-added="handleBoardAdded" @board-canceled="closeAddBoardForm" />
            </div>
            <!-- ./No Boards -->
            <div v-else class="col-12 col-md-12">
                <div class="d-flex flex-column align-items-center justify-content-center pt-3">
                    <span class="text-muted">Create more boards</span>
                    <button class="mt-1 btn btn-link text-warning" @click="showBoardCreate2 = !showBoardCreate2">Add more
                        board</button>
                    <AddBoardForm v-if="showBoardCreate2" @board-added="handleBoardAdded"
                        @board-canceled="closeAddBoardForm2" />
                </div>
            </div>

            <div v-for="board in boards" :key="board.id" class="col-12 col-md-12">
                <div class="mb-3 p-3 h-24 d-flex flex-column bg-white rounded shadow-sm">
                    <div class="d-flex flex-column">
                        <div class="p-2">
                            <div class="d-flex d-flex align-items-start justify-content-between">
                                <div class="d-flex justify-content-start align-items-start">
                                    <template v-if="editBoardId === board.id">
                                        <div class="p-2 flex-1">
                                            <input class="form-control mb-3" type="text" placeholder="Enter a title"
                                                v-model.trim="board.title" :ref="`board${board.id}`" required />
                                            <textarea class="form-control mb-3" rows="2"
                                                placeholder="Add a description (optional)" v-model.trim="board.description"
                                                :ref="`board${board.id}`"></textarea>
                                            <div v-show="errorUpdateMessage">
                                                <span class="text-danger small">{{ errorUpdateMessage }}</span>
                                            </div>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <a :href="'/boards/' + board.id" class="text-decoration-none">
                                            <span class="mb-2 h5 text-dark">{{ board.title }}</span>
                                            <p class="text-muted text-truncate">{{ board.description }}</p>
                                        </a>
                                    </template>
                                </div>
                                <div class="d-flex justify-content-end align-items-start">
                                    <template v-if="editBoardId === board.id">
                                        <i class="fa fa-times px-1" @click.prevent="toggleEdit()"></i>
                                        <i class="fa fa-floppy-o px-1" @click.prevent="handleUpdateBoard(board.id)"></i>
                                    </template>
                                    <template v-else>
                                        <i class="fa fa-pencil-square-o px-1" @click.prevent="toggleEdit(board.id)"></i>
                                    </template>
                                    <template v-if="deleteBoardId === board.id">
                                        <span class="mb-2 h5 text-dark">Are you sure to delete this item</span>
                                        <i class="fa fa-check text-danger px-1"
                                            @click.prevent="handleDeleteBoard(board.id)"></i>
                                        <i class="fa fa-times px-1" @click.prevent="toggleDelete()"></i>
                                    </template>
                                    <template v-else>
                                        <i class="fa fa-trash-o px-1 text-danger"
                                            @click.prevent="toggleDelete(board.id)"></i>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div class="p-2">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex justify-content-start">
                                    <!-- <span v-if="board.board_users.length"></span> -->
                                    <i v-if="board.board_users?.length" class="fa fa-users px-1"></i>
                                    <div class="d-flex justify-content-start">
                                        <div v-for="board_user in board.board_users" :key="board_user.id">
                                            <span v-if="board_user.active" class="text-dark text-underline px-1">
                                                {{ board_user?.user?.name }}
                                            </span>
                                            <span v-else class="text-muted text-underline px-1">
                                                {{ board_user?.user?.name }}
                                            </span>
                                        </div>
                                    </div>
                                    <!-- <i v-if="!addingUser" class="fa fa-user-plus px-1" @click="startAddingUser(board.id)"></i>
                                    <div v-if="addingUser">
                                        <select  v-model="selected" id="userList" :ref="`board_user_${board.id}`">
                                            <option v-for="user in userList" v-bind:value="user.id" :key="user.id">
                                                {{user.name}}
                                            </option>
                                        </select>
                                        <input type="text" v-model="newUserName" :ref="`board_user_${board.id}`"
                                            @keydown.enter="saveUser" placeholder="Enter username"  />
                                        <button @click="saveUser" class="btn btn-primary">Save</button>
                                    </div> -->
                                </div>
                                <div class="d-flex justify-content-end">
                                    <i class="fa fa-user px-1"></i>
                                    <span class="text-primary">{{ board?.user?.name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import AddBoardForm from "./AddBoardForm";

export default {
    props: {
        initialData: Array,
        boardUrl: String,
    },
    components: { AddBoardForm },
    data() {
        return {
            updateBoard: {
                board_id: null,
                title: "",
                description: "",
            },
            boards: [],
            newBoardForUser: 0,
            showBoardCreate: false,
            showBoardCreate2: false,
            showBoardEdit: false,
            editBoardId: null,
            deleteBoardId: null,
            errorUpdateMessage: "",
            addingUser: false,
            newUserName: "",
        };
    },
    mounted() {
        this.boards = this.initialData;
        // this.boards = JSON.parse(JSON.stringify(this.initialData));
    },
    methods: {
        closeAddBoardForm() {
            this.showBoardCreate = false;
        },
        closeAddBoardForm2() {
            this.showBoardCreate2 = false;
        },
        handleBoardAdded(newBoard) {
            this.boards.unshift(newBoard);
            this.closeAddBoardForm();
            this.closeAddBoardForm2();
        },
        toggleEdit(id) {
            if (id) {
                this.editBoardId = id;
                this.$nextTick(() => {
                    if (this.$refs["board" + id]) {
                        this.$refs["board" + id][0].focus();
                    }
                });
            } else {
                id = this.editBoardId;
                this.editBoardId = null;
            }

        },
        handleUpdateBoard(id) {
            if (!this.$refs["board" + id][0].value) {
                this.errorUpdateMessage = "The title field is required";
                return;
            } else {
                this.updateBoard.id = id.parse;
                this.updateBoard.title = this.$refs["board" + id][0].value;
                this.updateBoard.description = this.$refs["board" + id][1].value;
                axios
                    .put("/boards/" + id, this.updateBoard)
                    .then(res => {
                        this.$emit("board-updated", res.data);
                    })
                    .catch(err => {
                        this.handleUpdateErrors(err);
                    });
                this.toggleEdit();
            }
        },
        toggleDelete(id) {
            if (id) {
                this.deleteBoardId = id;
                this.$nextTick(() => {
                    if (this.$refs["board_delete" + id]) {
                        this.$refs["board_delete" + id][0].focus();
                    }
                });
            } else {
                this.deleteBoardId = null;
            }
        },
        handleDeleteBoard(id) {
            if (!id) {
                this.errorDeleteMessage = "The request item is missing";
                return;
            } else {
                axios
                    .delete("/boards/" + id)
                    .then(res => {
                        this.$emit("board-deleted", res.data);
                    })
                    .catch(err => {
                        this.handleDeleteErrors(err);
                    });
                const index = this.boards.findIndex(x => x.id === id);
                this.toggleDelete();
                this.boards.splice(index, 1);
            }
        },
        handleUpdateErrors(err) {
            if (err.response && err.response.status === 422) {
                const errorBag = err.response.data.errors;
                if (errorBag.title) {
                    this.errorUpdateMessage = errorBag.title[0];
                } else if (errorBag.description) {
                    this.errorUpdateMessage = errorBag.description[0];
                } else {
                    this.errorUpdateMessage = err.response.message;
                }
            } else {
                console.log(err.response);
            }
        },
        handleDeleteErrors(err) {
            if (err.response && err.response.status === 422) {
                const errorBag = err.response.data.errors;
                if (errorBag.title) {
                    this.errorDeleteMessage = errorBag.title[0];
                } else if (errorBag.description) {
                    this.errorDeleteMessage = errorBag.description[0];
                } else {
                    this.errorDeleteMessage = err.response.message;
                }
            } else {
                console.log(err.response);
            }
        },
        startAddingUser(id) {
            this.addingUser = true;
            this.$nextTick(() => {
                if (this.$refs["board_user_" + id]) {
                        this.$refs["board_user_" + id][0].focus();
                    }
                console.log(this.$refs["board_user_" + id]);
                // this.$refs.newUserInput.focus();
            });
        },
        saveUser() {
            if (this.newUserName.trim() === "") {
                return;
            }

            this.newUserName = "";
            this.addingUser = false;
        },
    },
};
</script>
