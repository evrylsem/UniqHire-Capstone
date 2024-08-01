@extends('layout')

@section('page-title', 'Trainings')

@section('page-content')
<div class="trainings-container">
    <div>
        <div class="row mb-3">
            <div class="text-start header-texts back-link-container border-bottom">
                My Trainings.
            </div>
        </div>
        <div class="mb-3">
            <button id="status-all" class="match-info status-btn-default status-btn-active" onclick="statusFilter('status-all')">Show All</button>
            <button id="status-ongoing" class="match-info status-btn-default" onclick="statusFilter('status-ongoing')">Ongoing</button>
            <button id="status-completed" class="match-info status-btn-default" onclick="statusFilter('status-completed')">Completed</button>
        </div>
    </div>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <td class="table-head">Title</td>
                <td class="table-head">Agency</td>
                <td class="table-head">Start</td>
                <td class="table-head">End</td>
                <td class="table-head">Status</td>
                <td class="table-head"></td>
            </tr>
        </thead>
        <tbody class="table-group-divider text-center">
            @foreach ($trainings as $training)
            <tr>
                <td class="text-cap">{{$training->title}}</td>
                <td class="text-cap">{{$training->agency->userInfo->name}}</td>
                <td>{{ \Carbon\Carbon::parse($training->start_date)->format('M d, Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($training->end_date)->format('M d, Y') }}</td>
                <td class="status-cell">
                    <p class="match-info">Completed</p>
                </td>
                <td class="text-cap"><a href="{{ route('programs-show', $training->id) }}">Show Details</a></td>
                <!-- <td></td> -->
            </tr>
            @endforeach

        </tbody>
    </table>
</div>

@endsection

<script>
    function statusFilter(status) {
        document.querySelectorAll('.status-btn-default').forEach(button => {
            button.classList.remove('status-btn-active');
        });
        document.getElementById(status).classList.add('status-btn-active');
    }

    window.onload = () => {
        statusFilter('status-all');
    };
</script>