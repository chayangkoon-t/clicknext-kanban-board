<template>
    <div class="d-flex flex-column">
        <div class="p-2 px-4">
            <h3 class="font-weight-medium">
                {{ board?.title }}
            </h3>
            <span class="mb-2 text-muted">
                {{ board?.description }}
            </span>
        </div>
        <div class="p-2 px-4">
            <div class="p-2">
                <div class="d-flex justify-content-between">
                    <div class="d-flex justify-content-start">
                        <i class="fa fa-users px-1"></i>
                        <!-- <span class="text-primary">{{ board?.user?.name }}</span> -->
                        <div class="d-flex justify-content-start">
                            <div v-for="board_user in board.board_users" :key="board_user.id">
                                <span v-if="board_user.active" class="badge bg-primary mx-1">
                                    {{ board_user?.user?.name }}
                                </span>
                                <span v-else class="badge bg-light text-dark mx-1">
                                    {{ board_user?.user?.name }}
                                </span>
                            </div>
                        </div>
                        <template v-if="addUser">
                            <select class="custom-select mx-2" v-model="selected">
                                <option selected>Please select</option>
                                <option v-for="user in users" :key="user.id"
                                    v-bind:value="{ user_id: user.id, board_id: board.id, name: user.name }"
                                    :ref="`user_${user.id}`" @click.prevent="handleAddUser(user.name)">
                                    {{ user.name }}
                                </option>
                            </select>
                            <i class="fa fa-times px-1" @click.prevent="closeAddUserForm()"></i>
                            <i class="fa fa-floppy-o px-1" @click.prevent="handleAddUser()"></i>
                            <div v-show="errorUpdateMessage">
                                <span class="text-danger small">{{ errorUpdateMessage }}</span>
                            </div>
                        </template>
                        <template v-else>
                            <div class="d-flex justify-content-end">
                                <div class="d-flex justify-content-end align-items-start">
                                    <i class="fa fa-user-plus px-1" @click="openAddUserForm(board.id)"></i>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-2">
            <div class="p-2 d-flex overflow-x-auto">
                <!-- Columns (Statuses) -->
                <div v-for="status in statuses" :key="status.slug" class="mx-1 w-100">
                    <div class="rounded shadow-sm overflow-hidden">
                        <div class="p-3 d-flex justify-content-between align-items-center bg-white border-bottom">
                            <template v-if="editStatusId === status.id">
                                <input class="form-control mb-3" type="text" placeholder="Enter a title"
                                    v-model.trim="status.title" :ref="`status_${status.id}`" required />
                            </template>
                            <div class="d-flex justify-content-start">
                                <h4 v-if="editStatusId !== status.id" class="font-weight-medium">
                                    {{ status.title }}
                                </h4>
                                <template v-if="editStatusId === status.id">
                                    <i class="fa fa-times px-1 pb-2" @click.prevent="toggleEditStatus()"></i>
                                    <i class="fa fa-floppy-o px-1 pb-2" @click.prevent="handleEditStatus(status.id)"></i>
                                </template>
                                <template v-if="editStatusId !== status.id">
                                    <i class="fa fa-pencil-square-o px-1 pt-2" @click.prevent="toggleEditStatus(status.id)">
                                    </i>
                                </template>
                            </div>


                            <template v-if="editStatusId !== status.id">
                                <button class="py-1 px-2 btn btn-warning" @click="openAddTaskForm(status.id)">
                                    Add Task
                                </button>
                            </template>
                        </div>
                        <div class="p-2 d-flex flex-column h-100 overflow-x-hidden overflow-y-auto bg-light">
                            <AddTaskForm v-if="newTaskForStatus === status.id" :status-id="status.id" :board-id="board.id"
                                v-on:task-added="handleTaskAdded" v-on:task-canceled="closeAddTaskForm" />
                            <!-- Tasks -->
                            <draggable class="flex-1 overflow-hidden" v-model="status.tasks" v-bind="taskDragOptions"
                                @end="handleTaskMoved">
                                <transition-group
                                    class="flex-1 d-flex flex-column h-100 overflow-x-hidden overflow-y-auto rounded shadow"
                                    tag="div">
                                    <div v-for="task in status.tasks" :key="task.id"
                                        class="mb-3 p-3 h-24 bg-white rounded shadow-sm">
                                        <div class="d-flex justify-content-between ">
                                            <div class="mx-1">
                                                <template v-if="editTaskId === task.id">
                                                    <input class="form-control mb-3" type="text" placeholder="Enter a title"
                                                        v-model.trim="task.title" :ref="`task_${task.id}`" required />
                                                    <textarea class="form-control mb-3" rows="2"
                                                        placeholder="Add a description (optional)"
                                                        v-model.trim="task.description" :ref="`task_${task.id}`"></textarea>
                                                    <div v-show="errorUpdateMessage">
                                                        <span class="text-danger small">{{ errorUpdateMessage }}</span>
                                                    </div>
                                                </template>
                                                <template v-else>
                                                    <span class="mb-2 h5 text-dark">
                                                        {{ task.title }}
                                                    </span>
                                                    <p class="text-secondary text-truncate">
                                                        {{ task.description }}
                                                    </p>
                                                    <span v-for="user in task.task_users" :key="user.id"
                                                        class="mb-2 h5 text-dark">
                                                        {{ user?.name }}
                                                    </span>
                                                </template>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <div class="d-flex justify-content-end align-items-start">
                                                    <template v-if="editTaskId === task.id">
                                                        <i class="fa fa-times px-1" @click.prevent="toggleEdit()"></i>
                                                        <i class="fa fa-floppy-o px-1"
                                                            @click.prevent="handleTaskEdited(task.id)"></i>
                                                    </template>
                                                    <template v-else>
                                                        <i class="fa fa-pencil-square-o px-1"
                                                            @click.prevent="toggleEdit(task.id)">
                                                        </i>
                                                    </template>
                                                    <template v-if="deleteTaskId === task.id">
                                                        <span class="mb-2 h5 text-dark">Are you sure to delete this
                                                            item</span>
                                                        <i class="fa fa-check text-danger px-1"
                                                            @click.prevent="handleDeleteTask(task)"></i>
                                                        <i class="fa fa-times px-1" @click.prevent="toggleDelete()"></i>
                                                    </template>
                                                    <template v-else>
                                                        <i class="fa fa-trash-o px-1 text-danger"
                                                            @click.prevent="toggleDelete(task.id)"></i>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <div class="d-flex justify-content-start flex-wrap">
                                                <div v-for="tag_task in task.tag_tasks" :key="tag_task.id">
                                                    <span class="badge rounded-pill bg-info text-dark">
                                                        {{ tag_task.tag.title }}</span>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                <template v-if="addTag == task.id">
                                                    <form @submit.prevent="handleAddTag" class="my-2">
                                                        <!-- <input type="hidden" name="board_id" value=""> -->
                                                        <input class="form-control mb-3" type="text"
                                                            placeholder="Enter a tag" v-model.trim="newTag.title"
                                                            :ref="`tag_task_${task.id}`" required />
                                                        <button @click="handleTagCancel" type="button" class="btn btn-link">
                                                            Cancel
                                                        </button>
                                                        <button type="submit" class="btn btn-warning">
                                                            Add
                                                        </button>
                                                        <!-- <i class="fa fa-floppy-o px-1"
                                                        @click.prevent="handleAddTag(task.id)"></i> -->
                                                    </form>
                                                </template>
                                                <i v-else class="fa fa-tag px-1 mt-2"
                                                    @click.prevent="toggleAddTag(task.id)">
                                                    <i class="fa fa-plus pr-2 mt-2" style="font-size: 6px !important;"
                                                        @click.prevent="toggleAddTag(task.id)">
                                                    </i>
                                                </i>
                                                <!-- <span class="text-muted mt-2" style="font-size: 14px;">Add tag</span> -->
                                                <!-- </div> -->
                                            </div>
                                            <div class="d-flex justify-content-start flex-wrap">
                                                <div v-for="task_board_user in task.task_board_users"
                                                    :key="task_board_user.id">
                                                    <span class="badge rounded-pill bg-light text-dark">
                                                        {{ task_board_user.board_user.user.name }}
                                                    </span>
                                                </div>
                                                <template v-if="addUserTask == task.id">
                                                    <form @submit.prevent="handleAddUserTask" class="my-2">
                                                        <!-- <input type="hidden" name="board_id" value=""> -->
                                                        <select class="custom-select mb-3 mx-auto" v-model="newUserTask">
                                                            <option selected>Please select</option>
                                                            <option v-for="board_user in board.board_users"
                                                                :key="board_user.id"
                                                                v-bind:value="{ board_user_id: board_user.id, task_id: task.id }"
                                                                :ref="`task_user_${task.id}`">{{ board_user.user.name }}
                                                            </option>
                                                        </select>
                                                        <!-- <input class="form-control mb-3" type="text"
                                                            placeholder="Enter a User" v-model.trim="newUserTask.user_id"
                                                            :ref="`user_task_${task.id}`" required /> -->
                                                        <button @click="handleUserTaskCancel" type="button"
                                                            class="btn btn-link">
                                                            Cancel
                                                        </button>
                                                        <button type="submit" class="btn btn-warning">
                                                            Add
                                                        </button>
                                                    </form>
                                                </template>
                                                <i v-else class="fa fa-user-plus px-1 mt-2"
                                                    @click.prevent="toggleAddUserTask(task.id)">
                                                </i>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ./Tasks -->
                                </transition-group>
                            </draggable>
                            <!-- No Tasks -->
                            <div v-if="!status.tasks.length && newTaskForStatus !== status.id"
                                class="d-flex flex-column align-items-center justify-content-center">
                            <!-- <div v-if="newTaskForStatus !== status.id"
                                class="d-flex flex-column align-items-center justify-content-center"> -->
                                <span class="text-muted">No tasks yet</span>
                                <button class="mt-1 btn btn-link text-warning" @click="openAddTaskForm(status.id)">
                                    Add one
                                </button>
                            </div>
                            <!-- ./No Tasks -->
                        </div>
                    </div>
                </div>
                <!-- ./Columns -->
                <!-- New column -->
                <div class="mx-1 w-10">
                    <AddStatusForm v-if="addStatusBoardId === board.id" :board-id="board.id" :statuses="this.statuses"
                        v-on:status-added="handleStatusAdded" v-on:status-canceled="closeAddStatusForm" />

                    <template v-else>
                    <!-- <template v-if="addStatusBoardId !== board.id"> -->
                        <div class="rounded shadow-sm overflow-hidden">
                            <div class="p-3 d-flex justify-content-between align-items-center bg-white border-bottom">
                                <button class="mt-1 btn btn-link text-warning" @click="openAddStatusForm(board.id)">
                                    <i class="fa fa-plus px-1 text-warning"
                                        @click.prevent="openAddStatusForm(board.id)"></i>
                                    Add column
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
                <!-- /No column -->
            </div>
        </div>
    </div>
</template>

<script>
import draggable from "vuedraggable";
import AddTaskForm from "./AddTaskForm";
import AddStatusForm from './AddStatusForm';
import EditTaskForm from './EditTaskForm';

export default {
    props: {
        initialBoard: Object,
        initialData: Array,
        initialUser: Array,
    },
    components: { draggable, AddTaskForm, EditTaskForm, AddStatusForm },
    data() {
        return {
            statuses: [],
            board: [],
            editStatus: {
                title: "",
            },
            editTask: {
                id: "",
                title: "",
                description: "",
            },
            addStatusBoardId: 0,
            newTaskForStatus: 0,
            editStatusId: 0,
            editTaskId: 0,
            addUserId: 0,
            addUser: false,
            newUserName: "",
            newUser: 0,
            selectedItem: null,
            selected: "",
            showModal: 0,
            modalData: null,
            deleteTaskId: 0,
            errorUpdateMessage: "",
            addTag: 0,
            newTag: {
                title: "",
                board_id: "",
                task_id: "",
            },
            addUserTask: 0,
            newUserTask: {
                user_id: "",
                task_id: "",
            },
        };
    },
    computed: {
        taskDragOptions() {
            return {
                animation: 200,
                group: "task-list",
                dragClass: "status-drag"
            };
        }
    },
    mounted() {
        this.board = JSON.parse(JSON.stringify(this.initialBoard));
        this.statuses = JSON.parse(JSON.stringify(this.initialData));
        this.users = JSON.parse(JSON.stringify(this.initialUser));
    },
    methods: {
        openAddUserForm() {
            this.addUser = true;
        },
        closeAddUserForm() {
            this.addUser = false;
            console.log(this.selected);
            // console.log(this.board.boardUsers, this.initialBoard);
        },
        handleAddUser() {
            let p_name = this.selected.name;
            let p_id = this.selected.user_id;
            let p_obj = {
                user: {
                    name: p_name,
                }
            };
            delete this.selected.name;
            console.log(p_name, p_id, p_obj);
            axios
                .post("/board_users", this.selected)
                .then(res => {
                    console.log(res);
                    this.$emit("board-users-added", res.data);
                })
                .catch(err => {
                    this.handleErrors(err);
                });
            console.log(this.board.board_users);

            // if (p_name)
            // const index = this.board.board_users.filter(x => x.user_id === p_id);
            //     console.log(index);
            this.board.board_users.push(p_obj);

            // this.statuses[statusIndex].tasks.push(newTask);

            this.closeAddUserForm();
        },
        toggleAddTag(taskId) {
            this.addTag = taskId;
        },
        handleAddTag() {
            console.log(this.newTag.title);

            if (!this.newTag.title) {
                this.errorMessage = "The title field is required";
                return;
            }
            this.newTag.board_id = this.board.id;
            this.newTag.task_id = this.addTag;
            console.log(this.newTag);
            axios
                .post("/tag_tasks", this.newTag)
                .then((res) => {
                    this.$emit("tag-added", res.data);
                })
                .catch((err) => {
                    this.handleErrors(err);
                });
            // this.statuses[1].tasks[0].tag_tasks[0].tag.push(this.newTag);
            setTimeout(function () { location.reload(); }, 500);

            this.toggleAddTag(0);
        },
        handleTagCancel() {
            this.toggleAddTag(0);
        },
        toggleAddUserTask(taskId) {
            this.addUserTask = taskId;
        },
        handleAddUserTask() {
            console.log(this.newUserTask);

            if (!this.newUserTask.board_user_id) {
                this.errorMessage = "The user field is required";
                return;
            }
            // this.newUserTask.user_id = this.user_id.id;
            this.newUserTask.task_id = this.addUserTask;
            console.log(this.newUserTask.task_id);
            axios
                .post("/task_board_users", this.newUserTask)
                .then((res) => {
                    this.$emit("user-task-added", res.data);
                })
                .catch((err) => {
                    this.handleErrors(err);
                });

            setTimeout(function () { location.reload(); }, 500);

            this.toggleAddUserTask(0);
        },
        handleUserTaskCancel() {
            this.toggleAddUserTask(0);
        },
        toggleEditStatus(id) {
            if (id) {
                console.log(id)
                this.editStatusId = id;
                this.$nextTick(() => {
                    if (this.$refs["status" + id]) {
                        this.$refs["status" + id][0].focus();
                    }
                });
            } else {
                id = this.editStatusId;
                this.editStatusId = null;
            }

        },
        handleEditStatus(id) {
            if (!this.$refs["status_" + id][0].value) {
                this.errorMessage = "The title field is required";
                return;
            } else {
                this.editStatus.id = id;
                this.editStatus.title = this.$refs["status_" + id][0].value;
                console.log(this.editStatus.title);
                axios
                    .put("/statuses/" + id, this.editStatus)
                    .then(res => {
                        this.$emit("status-edited", res.data);
                    })
                    .catch(err => {
                        this.handleUpdateErrors(err);
                    });
                this.toggleEditStatus();
            }
        },
        openAddTaskForm(statusId) {
            this.newTaskForStatus = statusId;
        },
        closeAddTaskForm() {
            this.newTaskForStatus = 0;
            console.log(this.board.boardUsers, this.initialBoard);
        },
        handleTaskAdded(newTask) {
            const statusIndex = this.statuses.findIndex(
                status => status.id === newTask.status_id
            );

            this.statuses[statusIndex].tasks.push(newTask);

            this.closeAddTaskForm();
        },

        openAddStatusForm(boardId) {
            this.addStatusBoardId = boardId;
        },
        closeAddStatusForm() {
            this.addStatusBoardId = 0;
        },
        handleStatusAdded(newStatus) {
            newStatus.tasks = [];
            this.statuses.push(newStatus);

            this.closeAddStatusForm();
        },

        handleTaskMoved() {
            axios.put("/tasks/sync", { columns: this.statuses }).catch(err => {
                console.log(err.response);
            });
        },
        toggleEdit(id) {
            if (id) {
                console.log(id)
                this.editTaskId = id;
                this.$nextTick(() => {
                    if (this.$refs["task" + id]) {
                        this.$refs["task" + id][0].focus();
                    }
                });
            } else {
                id = this.editTaskId;
                this.editTaskId = null;
            }

        },
        handleTaskEdited(id) {
            if (!this.$refs["task_" + id][0].value) {
                this.errorMessage = "The title field is required";
                return;
            } else {
                this.editTask.id = id;
                this.editTask.title = this.$refs["task_" + id][0].value;
                this.editTask.description = this.$refs["task_" + id][1].value;
                console.log(id, this.editTask);
                axios
                    .put("/tasks/" + id, this.editTask)
                    .then(res => {
                        this.$emit("task-edited", res.data);
                    })
                    .catch(err => {
                        this.handleUpdateErrors(err);
                    });
                this.toggleEdit();
            }
        },
        toggleDelete(id) {
            if (id) {
                console.log(id)
                this.deleteTaskId = id;
                this.$nextTick(() => {
                    if (this.$refs["task_delete" + id]) {
                        this.$refs["task_delete" + id][0].focus();
                    }
                });
            } else {
                this.deleteTaskId = null;
            }
        },
        handleDeleteTask(task) {
            const id = task?.id;
            if (!id) {
                this.errorDeleteMessage = "The request item is missing";
                return;
            } else {
                axios
                    .delete("/tasks/" + id)
                    .then(res => {
                        this.$emit("task-deleted", res.data);
                    })
                    .catch(err => {
                        this.handleDeleteErrors(err);
                    });
                console.log("id: " + id)
                console.log(task)
                console.log(this.statuses)

                const statusIndex = this.statuses.findIndex(
                    status => status.id === task.status_id
                );
                console.log("statusIndex: " + statusIndex)

                // this.statuses[statusIndex].tasks.push(newTask);
                const index = this.statuses[statusIndex].tasks.findIndex(x => x.id === id);
                this.toggleDelete();
                this.statuses[statusIndex].tasks.splice(index, 1);
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
        handleErrors(err) {
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
    }
};
</script>
