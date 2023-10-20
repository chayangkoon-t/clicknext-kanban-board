@extends('layouts.app')

@section('content')
    <div class="relative overflow-hidden">
        <main class="h-100 d-flex flex-column overflow-auto">
            {{-- @dd(json_encode($boards), $boards) --}}
            <main-board :initial-data="{{ json_encode($boards) }}" :user-data="{{ json_encode($user) }}" {{-- :board-url="{{ route('boards.show', 1) }}" --}}>
            </main-board>
        </main>
    </div>
@endsection
