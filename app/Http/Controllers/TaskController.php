<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tasks\StoreRequest;
use App\Http\Requests\Tasks\SyncRequest;
use App\Repositories\BoardRepository;
use App\Repositories\BoardUserRepository;
use App\Repositories\StatusRepository;
use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $boardRepo;
    protected $statusRepo;
    protected $taskRepo;
    protected $boardUserRepo;
    protected $userRepo;

    public function __construct(
        BoardRepository $boardRepo,
        StatusRepository $statusRepo,
        TaskRepository $taskRepo,
        BoardUserRepository $boardUserRepo,
        UserRepository $userRepo,
    ) {
        $this->boardRepo = $boardRepo;
        $this->statusRepo = $statusRepo;
        $this->taskRepo = $taskRepo;
        $this->boardUserRepo = $boardUserRepo;
        $this->userRepo = $userRepo;
    }

    public function index(Request $request)
    {
        $board_id = key($request->all());
        $board = $this->boardRepo
            ->owned(false, ['statuses', 'boardUsers', 'boardUsers.user', 'user'])
            ->find($board_id);

        $tasks = $board->statuses()
            ->with(['tasks', 'tasks.taskBoardUsers', 'tasks.taskBoardUsers.boardUser.user', 'tasks.tagTasks', 'tasks.tagTasks.tag'])
            ->get();

        $users = $this->userRepo->exclude();

        return view('tasks.index', ['board' => $board, 'tasks' => $tasks, 'users' => $users]);
    }

    public function create()
    {
        //
    }

    public function store(StoreRequest $request)
    {
        $data = $request->all();
        $board = $this->statusRepo->findOrFail($data['status_id'])->get()->first();
        $board_id = $board->id;

        $data['board_id'] = $board_id;

        return $this->taskRepo->create($data);
    }

    public function sync(SyncRequest $request)
    {
        foreach ($request->columns as $status) {
            foreach ($status['tasks'] as $i => $task) {
                $order = $i + 1;
                if ($task['status_id'] !== $status['id'] || $task['order'] !== $order) {
                    $task_model = $this->taskRepo->find($task['id']);
                    $task_model->update(['status_id' => $status['id'], 'order' => $order]);
                }
            }
        }

        return $task_model->get();
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $task = $this->taskRepo->findOrFail($id, ['taskBoardUsers', 'taskBoardUsers.boardUsers.user']);

        return view('tasks.edit', ['task' => $task]);
    }

    public function updatetask(Request $request, $id)
    {
        $this->taskRepo->update($id, $request->all());

        return redirect()->back()->with('success', 'Task updated successfully');
    }

    public function update(Request $request, $id)
    {
        $this->taskRepo->update($id, $request->all());

        return redirect()->back()->with('success', 'Task updated successfully');
    }

    public function destroy($id)
    {
        $task = $this->taskRepo->findOrFail($id);
        return $task->delete();
    }
}
