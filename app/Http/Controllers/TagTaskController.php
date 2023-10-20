<?php

namespace App\Http\Controllers;

use App\Repositories\TagRepository;
use App\Repositories\TagTaskRepository;
use Illuminate\Http\Request;

class TagTaskController extends Controller
{
    protected $tagRepo;
    protected $tagTaskRepo;

    public function __construct(
        TagRepository $tagRepo,
        TagTaskRepository $tagTaskRepo
    ) {
        $this->tagRepo = $tagRepo;
        $this->tagTaskRepo = $tagTaskRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tag_data = [
            'title' => $request->title,
            'board_id' => $request->board_id,
        ];
        $tag = $this->tagRepo->all(false, ['title' => $request->title], [])->first();

        if (empty($tag)) {
            $tag = $this->tagRepo->create($tag_data);
        }
        
        $tag_task_data = [
            'tag_id' => $tag->id,
            'task_id' => $request->task_id
        ];

        $tag_task = $this->tagTaskRepo->create($tag_task_data);

        return $tag_task;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->tagTaskRepo->update($id, $request->all());

        return redirect()->back()->with('success', 'TagTask updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tagTask = $this->tagTaskRepo->findOrFail($id);
        return $tagTask->delete();
    }
}
