@extends('layout')

@section('page-title', 'Program Details')

@section('page-content')
<div class="row vh-100 mb-5 prog-details-container">
    <div class="col-2">
        <a href="{{ route('programs-manage') }}" class="m-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="100" height="40" viewBox="0 0 24 24" style="fill: rgba(4, 176, 0, 1);">
                <path d="M21 11H6.414l5.293-5.293-1.414-1.414L2.586 12l7.707 7.707 1.414-1.414L6.414 13H21z"></path>
            </svg>
        </a>
    </div>
    <div class="col prog-details">
        <div class="fs-3 border-bottom text-center mb-3">
            Training Program Details
        </div>
        <div class="prog-texts">
            <div class="row mb-3">
                <div class="col">
                    <h3>{{ $program->title }}</h3>
                    <p class="sub-text">by {{ $program->agency->userInfo->name }}</p>
                    <p class="sub-text prog-loc"><i class='bx bx-map sub-text'></i>{{ $program->city }}</p>
                </div>
                <div class="col text-end prog-btn">
                    <form action="{{ route('programs-edit', $program->id) }}" method="GET">
                        <button class="submit-btn border-0">Edit</button>
                    </form>
                    <form action="{{ route('programs-delete', $program->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="deny-btn border-0">Delete</button>
                    </form>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col">
                    {{ $program->description }}
                </div>
            </div>
            <div class="row more-info">
                <div class="col">
                    <h5>Start Date</h5>
                    <p>{{ $program->start_date }}</p>
                </div>
                <div class="col">
                    <h5>End Date</h5>
                    <p>{{ $program->start_date }}</p>
                </div>
            </div>
            <div class="row more-info">
                <div class="col">
                    <h5>We Accept</h5>
                    <span class="requirement">{{ $program->disability->disability_name }}</span>
                </div>
                <div class="col">
                    <h5>Education Level</h5>
                    <span class="requirement">{{ $program->education->education_name }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <!-- <div class="crowdfund-progress mb-3">
                        @if ($program->crowdfund)
                        
                        <p class="sub-text">
                            Crowdfunding Progress: {{ $program->crowdfund->progress }}%
                        </p>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="{{ $program->crowdfund->progress }}" aria-valuemin="0" aria-valuemax="100">{{ $program->crowdfund->progress }}%</div>
                        </div>
                        
                        @endif
                    </div> -->
                    <h5>Sponsors</h5>
                    <span class=""></span>
                </div>
            </div>
        </div>

    </div>
    <div class="col enrollee-requests">
        <div class="fs-3 text-center border-bottom">
            Enrollee Requests
        </div>
    </div>
    <div class="col-2"></div>
</div>


<!-- <div class="mt-3 vh-100">
    <div class="row">
        <div class="col-2 empty-space"></div>
        <div class="col job-details">
            <div class="row">
                <div class="col default-text header-texts border-bottom">
                    <a href="{{ route('programs-manage') }}" class="m-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="100" height="40" viewBox="0 0 24 24" style="fill: rgba(4, 176, 0, 1);">
                            <path d="M21 11H6.414l5.293-5.293-1.414-1.414L2.586 12l7.707 7.707 1.414-1.414L6.414 13H21z"></path>
                        </svg>
                    </a>
                    JOB DETAILS
                </div>
            </div>

            <div class="row ">
                <div class="col mt-2">
                    <h2>{{ $program->title }}</h2>
                    <p class="sub-text prog-loc">
                        <i class='bx bx-map sub-text'></i>{{ $program->city }}
                    </p>
                </div>
                <div class="col">
                    <div class="row d-flex justify-content-end mt-3 prog-btn">
                        <form class="d-flex justify-content-end" action="{{ route('programs-edit', $program->id) }}" method="GET">
                            <button class="edit-btn btn-default">Edit</button>
                        </form>
                        <form class="d-flex justify-content-end" action="{{ route('programs-delete', $program->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn">Delete</button>
                        </form>
                    </div>
                </div>
                <p class="prog-desc mt-3">
                    {{ $program->description }}
                </p>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <p><strong>Company</strong></p>
                    <p>Inclusive Vulcanizing</p>
                    <p class="mt-3"><strong>Shift Schedule</strong></p>
                    <p>24 hours/day</p>
                </div>
                <div class="col">
                    <p><strong>Salary</strong></p>
                    <p>10k/Month</p>
                </div>
            </div>
            <div class="row mt-3 border-top">
                <div class="col">
                    <p class="bold-texts">We Accept</p>
                    <span class="btn-default btn-outlinee d-inline-flex p-2 prog-btnn">Leg Amputee</span>
                </div>
                <div class="col">
                    <p class="bold-texts">Education Level</p>
                    <span class="btn-default btn-outlinee d-inline-flex p-2 prog-btnn">Highschool Level</span>
                </div>
                <div class="col">
                    <p class="bold-texts">Certifications</p>
                    <span class="btn-default btn-outlinee d-inline-flex p-2 prog-btnn">ICT Training</sp>
                </div>
            </div>
        </div>

        <div class="col border-start">
            <div class="row border-bottom">
                <p class="text-center default-text header-texts">APPLICANTS</p>
            </div>
            <div class="row card card-default">
                <div class="col container">
                    <div class="row">
                        <div class="col">
                            <p>Claire Anon</p>
                            <p class="sub-text prog-loc">
                                <i class='bx bx-map sub-text'></i>{{ $program->city }} City
                            </p>
                            <p>Leg Amputee</p>
                        </div>
                        <div class="col">
                            <div class="row d-flex justify-content-center mt-3 prog-btn">
                                <form class="d-flex justify-content-end" action="">
                                    <button class="edit-btn btn-default">Accept</button>
                                </form>
                                <form class="d-flex justify-content-end" action="">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-btn">Decline</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-2 empty-space"></div>
    </div>
</div> -->

@endsection