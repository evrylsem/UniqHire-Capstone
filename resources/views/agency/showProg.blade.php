@extends('layout')

@section('page-title', 'Program Details')

@section('page-content')
<div class="mb-5 show-prog-container">
    <a href="{{ route('programs-manage', $program->id) }}" class="m-1 back-link"><i class='bx bx-left-arrow-alt'></i></a>
    <div class="prog-details">
        <div class="fs-3 text-center">
            Training Program Details
        </div>
        <div class="prog-texts">
            <div class="row mb-3">
                <div class="col">
                    <h3>{{ $program->title }}</h3>
                    <p class="sub-text">by {{ $program->agency->userInfo->name }}</p>
                    <p class="sub-text prog-loc"><i class='bx bx-map sub-text'></i>{{(str_contains($program->city, 'City') ? $program->city : $program->city . ' City')}}</p>
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
            <div>
                <div class="mb-5">
                    <div class="col">
                        {{ $program->description }}
                    </div>
                </div>
                <div class="row more-info">
                    <div class="col">
                        <h5>Start Date</h5>
                        <p>{{ \Carbon\Carbon::parse($program->start)->format('M d, Y') }}</p>
                    </div>
                    <div class="col">
                        <h5>End Date</h5>
                        <p>{{ \Carbon\Carbon::parse($program->end)->format('M d, Y') }}</p>
                    </div>
                </div>
                <div class="row more-info">
                    <div class="col">
                        <h5>We Accept</h5>
                        <span class="match-info">{{ $program->disability->disability_name }}</span>
                    </div>
                    <div class="col">
                        <h5>Education Level</h5>
                        <span class="match-info">{{ $program->education->education_name }}</span>
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

    </div>
    <div class="enrollee-requests">
        <div class="fs-3 text-center">
            Enrollee Requests
        </div>
        <div class="request-grid">
        @foreach ($applications as $application)
            <a href="">
                <div class="request-container">
                    <div class="request-owner mb-2">
                        <div class="request-pic">

                        </div>
                        <div>
                            <p class="fs-5">{{ $application->user->userInfo->name }}</p>
                            <p class="mb-2 location"><i class='bx bx-map sub-text'></i>{{ $application->user->userInfo->city }}</p>
                            <span class="match-info">{{ $application->user->userInfo->disability->disability_name }}</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="text-end btn-container">
                            <button type="button" class="submit-btn border-0" id="accept-button" data-application-id="{{ $application->training_id }}">Accept</button>
                            <button type="button" class="deny-btn border-0">Deny</button>
                        </div>

                        >
                    </div>
                </div>
            </a>
        @endforeach
        </div>
    </div>
</div>

<script>
    var acceptButton = document.getElementById('accept-button');
    console.log("kaabot ari sa script nga wala pa giclick");
    
    acceptButton.addEventListener('click', function(e) {
            e.preventDefault();
        console.log("kaabot ari sa script");
            var applicationId = acceptButton.getAttribute('data-application-id');

            fetch(`/agency/accept`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    training_application_id: applicationId,
                    completion_status: 'Ongoing'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Accepted successfully.');
                } else {
                    alert('Failed to submit application.');
                }
            })
            .catch(error => console.error('Error:', error));
        });

</script>

@endsection

