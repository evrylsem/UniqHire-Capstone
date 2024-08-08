@extends('layout')

@section('page-title', 'Training Programs')
@section('page-content')
<div class="d-flex justify-content-center agency-prog-container">
    <div class="mt-2 prog-grid">
        <div class="add-prog-card d-flex justify-content-center align-items-center ">
            <a href="{{ route('programs-add') }}" class="">+</a>
        </div>
        @foreach ($programs as $program)
        <div class="prog-card">
            <a href="{{ route('programs-show', $program->id) }}" class="prog-texts">
                <h3 class="text-cap">{{ $program->title }}</h3>
                <p class="sub-text prog-loc text-cap">
                    <i class='bx bx-map sub-text prog-loc'></i>{{$program->state . ', ' .(str_contains($program->city, 'City') ? $program->city : $program->city . ' City')}}
                </p>
                <div class="prog-desc-container">
                    <p class="prog-desc mt-3">
                        {{ $program->description }}
                    </p>
                </div>
                @if ($program->crowdfund)
                <div class="crowdfund-progress mb-3">

                    <p class="sub-text">
                        Goal Amount: &nbsp;&nbsp;<span>{{number_format($program->crowdfund->goal, 0, '.', ',') . ' PHP'}}</span>
                    </p>
                    <p class="sub-text">
                        Crowdfunding Progress:
                    </p>
                    <div class="progress" role="progressbar" aria-label="Animated striped example" aria-valuenow="{{ $program->crowdfund->progress }}" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" @if ($program->crowdfund->progress > 0)
                            style="width: {{$program->crowdfund->progress}}%;"
                            else
                            style="width: 10%;"
                            @endif >{{ $program->crowdfund->progress }}%</div>
                    </div>
                </div>
                @endif
                <div class="d-flex prog-details">
                    <p class="sub-text">
                        <i class='bx bx-group sub-text'></i> {{ number_format($program->slots) }} Slots
                    </p>
                    <span class="sub-text period">•</span>
                    <p class="sub-text"><i class='bx bx-calendar sub-text'></i> {{ $program->remainingDays }} days to go</p>
                </div>
            </a>
            <!-- <div class="d-flex justify-content-center prog-btn">
                <form action="{{ route('programs-delete', $program->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="deny-btn border-0">Delete</button>
                </form>
                <form action="{{ route('programs-edit', $program->id) }}" method="GET">
                    <button class="submit-btn border-0">Edit</button>
                </form>
            </div> -->
        </div>
        @endforeach
    </div>
</div>


@endsection