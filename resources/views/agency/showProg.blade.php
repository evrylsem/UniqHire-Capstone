@extends('layout')

@section('page-title', 'Program Details')

@section('page-content')
<div class="mb-5 show-prog-container">
    <!-- @if (Auth::user()->hasRole('Trainer'))
    <a href="{{ route('programs-manage', $program->id) }}" class="m-1 back-link"><i class='bx bx-left-arrow-alt'></i></a>
    @elseif(Auth::user()->hasRole('PWD'))
    <a href="{{ route('trainings') }}" class="m-1 back-link"><i class='bx bx-left-arrow-alt'></i></a>
    @endif -->
    <div>
        @if (Route::currentRouteName() == 'programs-show')
        @if (Auth::user()->hasRole('Trainer'))
        <a href="{{ route('programs-manage') }}" class="m-1 back-link"><i class='bx bx-left-arrow-alt'></i></a>
        @endif

        @if (Auth::user()->hasRole('PWD'))
        <a href="{{ url()->previous() }}" class="m-1 back-link"><i class='bx bx-left-arrow-alt'></i></a>
        @endif
        @endif
    </div>
    <div class="prog-details" @if(Auth::user()->hasRole('PWD')) style="width: 90%;"
        @endif
        >
        @if(Auth::user()->hasRole('Trainer'))
        <!-- <div class="fs-3 text-center">
            Training Program Details
        </div> -->
        @endif

        <div class="prog-texts">
            <!-- <div class="row mb-3"> -->
            <div class="d-flex ">
                <div class="header mb-3">
                    <h3 class="text-cap">{{ $program->title }}</h3>
                    <p class="sub-text text-cap">{{ $program->agency->userInfo->name }}</p>
                    <p class="sub-text prog-loc text-cap"><i class='bx bx-map sub-text'></i>{{(str_contains($program->city, 'City') ? $program->city : $program->city . ' City')}}</p>
                </div>
                @if (Auth::user()->hasRole('Trainer'))
                <div class="prog-btn">
                    <button type="button" class="submit-btn modal-btn border-0" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Enrollee Requests</button>
                    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Enrollee Requests</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="request-grid">
                                        @forelse ($applications as $application)
                                        <div class="request-container">
                                            <a href="{{ route('show-profile', $application->user->id) }}">
                                                <div class="request-owner mb-2">
                                                    <div class="request-pic">

                                                    </div>
                                                    <div class="header">
                                                        <p class="fs-5">{{ $application->user->userInfo->name }}</p>
                                                        <p class="mb-2 location text-cap"><i class='bx bx-map sub-text'></i>{{ $application->user->userInfo->state . ', ' . (str_contains($application->user->userInfo->city, 'City') ? $application->user->userInfo->city : $application->user->userInfo->city. ' City')}}</p>
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
                                            </a>
                                        </div>
                                        @empty
                                        <div>No requests yet.</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="edit-delete">
                        <div class="">
                            <form action="{{ route('programs-edit', $program->id) }}" method="GET">
                                <button class="submit-btn border-0">Edit</button>
                            </form>
                        </div>
                        <div class="">
                            <form action="{{ route('programs-delete', $program->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="deny-btn border-0">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
                @elseif (Auth::user()->hasRole('PWD'))
                <div class="col prog-btn">
                    <!-- <form action="{{ route('pwd-application') }}" method="POST"> -->
                    <button type="button" class="submit-btn border-0" id="apply-button" data-user-id="{{ Auth::user()->id }}" data-program-id="">
                        Apply
                    </button>
                    <!-- </form> -->
                </div>
                @endif
            </div>
            <div>
                <div class="mb-5">
                    <div class="col">
                        {{ $program->description }}
                    </div>
                </div>
                <div class="d-flex body mb-3">
                    <div class="requirements">
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
                                @if ($program->crowdfund)
                                <div class="crowdfund-progress mb-3">
                                    <p class="sub-text">
                                        Goal Amount: &nbsp;&nbsp;<span>{{$program->crowdfund->goal . ' PHP'}}</span>
                                    </p>
                                    <p class="sub-text">
                                        Crowdfunding Progress:
                                    </p>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="{{ $program->crowdfund->progress }}" aria-valuemin="0" aria-valuemax="100">{{ $program->crowdfund->progress }}%</div>
                                    </div>
                                </div>

                                <h5>Sponsors</h5>
                                <span class=""></span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="enrollees">
                        <h5>Enrollees</h5>
                        <ul>
                            <li><a href="">Kler</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border reviews">
                    <div class="header border-bottom d-flex justify-content-between align-items-center">
                        <h3>Reviews</h3>
                        @if (Auth::user()->hasRole('PWD'))
                        @include('slugs.feedback')
                        @endif
                    </div>
                    <div class="outer">
                        <div class="review-grid">
                            @forelse($reviews as $review)
                            <div class="body-review border">
                                <div class="owner border-bottom">
                                    {{$review->pwd->userInfo->name}}
                                </div>
                                <div class="content border-bottom">
                                    <div>
                                        @for ($i = 1; $i <= 5; $i++) @if ($i <=$review->rating)
                                            <i class='bx bxs-star'></i>
                                            @else
                                            <i class='bx bx-star'></i>
                                            @endif
                                            @endfor
                                    </div>
                                    {{$review->content ?? ''}}
                                </div>
                                <div class="time text-end">
                                    {{$review->pwd->created_at->format('d M Y H:i:s')}}
                                </div>
                            </div>
                            @empty
                            <div>No reviews available</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            var rating_data = 0;
            $(document).on('mouseenter', '.star-light', function() {
                var rating = $(this).data('rating');
                reset_background();
                for (var count = 1; count <= rating; count++) {
                    $('#star-' + count).addClass('bxs-star').removeClass('bx-star');
                }
            });

            function reset_background() {
                for (var count = 0; count <= 5; count++) {
                    $('#star-' + count).addClass('bx-star').removeClass('bxs-star');
                }
            }

            function updateStarRating(rating) {
                $('.star-light').each(function() {
                    var starRating = $(this).data('rating');
                    if (starRating <= rating) {
                        $(this).addClass('bxs-star').removeClass('bx-star');
                    } else {
                        $(this).addClass('bx-star').removeClass('bxs-star');
                    }
                });
            }

            $(document).on('mouseleave', '.star-light', function() {
                reset_background();
                if (rating_data > 0) {
                    updateStarRating(rating_data);
                }
            });

            $(document).on('click', '.star-light', function() {
                rating_data = $(this).data('rating');
                $('#rating-input').val(rating_data);
                updateStarRating(rating_data);
            });
        });

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