@extends('layout')
@section('page-title', 'Notifications')
@section('page-content')
<div class="container">
    <h1>Notifications</h1>
    <ul class="list-group">
        @forelse ($notifications as $notification)
        <li class="list-group-item">
            {{ $notification->data['message'] }}
        </li>
        @empty
        <li class="list-group-item">No new notifications</li>
        @endforelse
    </ul>
</div>
@endsection