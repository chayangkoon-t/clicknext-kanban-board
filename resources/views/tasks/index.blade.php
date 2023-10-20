@extends('layouts.app')

@section('content')
    <div class="relative overflow-hidden">
        <main class="h-100 d-flex flex-column overflow-auto">
            {{-- @dd($users) --}}
            <task-board
            :initial-data="{{ json_encode($tasks) }}"
            :initial-board="{{ json_encode($board) }}"
            :initial-user="{{ json_encode($users) }}">
            </task-board>
        </main>
    </div>
@endsection
