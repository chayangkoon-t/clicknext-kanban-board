<?php

namespace App\Http\Controllers;

use App\Http\Requests\Boards\StoreRequest;
use App\Repositories\BoardRepository;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    protected $boardRepo;

    public function __construct(
        BoardRepository $boardRepo
    ) {
        $this->boardRepo = $boardRepo;
    }

    public function index(Request $request)
    {
        $boards = $this->boardRepo
            ->owned(false, ['boardUsers', 'boardUsers.user', 'user']);
        $user = auth()->user();

        return view('boards.index', ['boards' => $boards, 'user' => $user]);
    }

    public function create()
    {
        //
    }

    public function store(StoreRequest $request)
    {
        return $this->boardRepo->create($request->all());
    }

    public function show($id)
    {
        $board = $this->boardRepo->findOrFail($id);

        return redirect()->route('tasks.index', $board->id);

    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $this->boardRepo->update($id, $request->all());

        return redirect()->back()->with('success', 'Board updated successfully');
    }

    public function destroy($id)
    {
        $board = $this->boardRepo->findOrFail($id);
        return $board->delete();
    }
}
