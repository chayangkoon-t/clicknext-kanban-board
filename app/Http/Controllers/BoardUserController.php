<?php

namespace App\Http\Controllers;

use App\Http\Requests\BoardUsers\StoreRequest;
use App\Repositories\BoardUserRepository;
use Illuminate\Http\Request;

class BoardUserController extends Controller
{
    protected $boardUserRepo;

    public function __construct(
        BoardUserRepository $boardUserRepo
    ) {
        $this->boardUserRepo = $boardUserRepo;
    }

    public function store(StoreRequest $request)
    {
        $board_user = $this->boardUserRepo->create($request->all());

        return json_encode($board_user);
    }

    public function update(Request $request, int $id)
    {

        $data = $request->all();
        $board_user = $this->boardUserRepo->update($id, $data);

        return redirect()->route('tasks.index', $request->board_id);
    }
}
