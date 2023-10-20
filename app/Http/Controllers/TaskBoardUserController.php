<?php

namespace App\Http\Controllers;

use App\Notifications\TaskAssigned;
use App\Repositories\BoardUserRepository;
use App\Repositories\TaskBoardUserRepository;
use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class TaskBoardUserController extends Controller
{
    protected $taskBoardUserRepo;
    protected $boardUserRepo;
    protected $taskRepo;
    protected $userRepo;

    public function __construct(
        TaskBoardUserRepository $taskBoardUserRepo,
        BoardUserRepository $boardUserRepo,
        TaskRepository $taskRepo,
        UserRepository $userRepo,
    ) {
        $this->taskBoardUserRepo = $taskBoardUserRepo;
        $this->boardUserRepo = $boardUserRepo;
        $this->taskRepo = $taskRepo;
        $this->userRepo = $userRepo;
    }

    public function store(Request $request)
    {
        $task_board_user = $this->taskBoardUserRepo->create($request->all());

        $board_user = $this->boardUserRepo->findOrFail($request->board_user_id);
        $task = $this->taskRepo->findOrFail($request->task_id);

        $board_name = $board_user->board->title;
        $task_name = $task->title;
        $user = $this->userRepo->findOrFail($board_user->user_id);
        $user->notify(new TaskAssigned($task_name, $board_name));

        return $task_board_user;
    }

    public function markAsRead(){
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back();
    }

    public function update(Request $request, int $id)
    {
        $board_user_task = $this->taskBoardUserRepo->update($id, $request->all());

        return redirect()->route('tasks.index', $request->board_id);
    }
}
