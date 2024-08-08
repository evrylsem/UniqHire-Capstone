@extends('layout')

@section('page-title', 'Program Details')

@section('page-content')
<div class="mb-5 agency-show-prog">
    <div class="back-btn">
        @if (Route::currentRouteName() == 'training-details')
        <a href="{{ route('pwd-list-program') }}" class="m-1 back-link"><i class='bx bx-left-arrow-alt'></i></a>
        @elseif(Route::currentRouteName() == 'show-details' )
        <a href="{{ route('trainings') }}" class="m-1 back-link"><i class='bx bx-left-arrow-alt'></i></a>
        @endif
    </div>
    <div class="d-flex outer">
        <div class="prog-details">
            <div class="d-flex header">
                <div class="mb-3 titles">
                    <h3 class="text-cap">{{ $program->title }}</h3>
                    <p class="sub-text text-cap">{{ $program->agency->userInfo->name }}</p>
                    <p class="sub-text prog-loc text-cap"><i class='bx bx-map sub-text'></i>{{$program->state . ', ' .(str_contains($program->city, 'City') ? $program->city : $program->city . ' City')}}</p>
                </div>
                <div class="prog-btn">
                    <form id="apply-form-{{ $program->id }}" action="{{ route('pwd-application') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="training_program_id" value="{{ $program->id }}">

                        @php
                        // Determine the application status
                        $applicationStatus = null;
                        foreach ($application as $app) {
                        if ($app->training_program_id == $program->id) {
                        $applicationStatus = $app->application_status;
                        break;
                        }
                        }
                        @endphp

                        @if ($applicationStatus == 'Pending')
                        <button type="submit" class="submit-btn pending border-0" disabled>
                            Pending
                        </button>
                        @elseif($applicationStatus == 'Approved')
                        <button type="submit" class="submit-btn approved border-0" disabled>
                            <i class='bx bx-check'></i>
                        </button>
                        @else
                        <button type="submit" class="submit-btn border-0 
                        @if (!in_array($program->id, $nonConflictingPrograms)) disabled @endif
                        " onclick="confirmApplication(event, 'apply-form-{{ $program->id }}')" @if (!in_array($program->id, $nonConflictingPrograms)) disabled @endif>
                            Apply
                        </button>
                        @if (!in_array($program->id, $nonConflictingPrograms))
                        <div class="text-end text-danger">Conflict to your schedule!</div>
                        @endif
                        @endif
                    </form>
                </div>
            </div>
            <div class="mb-5">
                <div class="col">
                    {{ $program->description }}
                </div>
            </div>
            <ul class="nav nav-underline" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#requirements" role="tab">Requirements</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#competencies" role="tab">Compentencies</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#enrollees" role="tab">Enrollees</a>
                </li>
                @if ($program->crowdfund)
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#sponsors" role="tab">Sponsors</a>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#reviews" role="tab">Reviews</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="requirements" role="tabpanel">
                    <div class="requirements">
                        <div class="d-flex justify-content-start mb-5">
                            <div class="more-info">
                                <h5>Duration</h5>
                                <p>{{ \Carbon\Carbon::parse($program->start)->format('M d, Y') . ' to ' . \Carbon\Carbon::parse($program->end)->format('M d, Y') }}</p>
                            </div>
                            <div class="more-info">
                                <h5>Participants</h5>
                                <p>{{ number_format($program->participants) . ' Persons' }}&nbsp;&nbsp; <span class="sub-text">({{$slots}} slots)</span></p>
                            </div>
                        </div>
                        <!-- AGE -->
                        <div class="d-flex justify-content-start mb-5">
                            <div class="more-info">
                                <h5>Age</h5>
                                <p class="match-info">{{ $program->start_age . ' - ' . $program->end_age . ' Years Old' }}</p>
                            </div>
                            <div class="more-info">
                                <h5>Skills Acquired</h5>
                                <span class="match-info">{{ $program->skill->title }}</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-start more-info">
                            <div class="more-info">
                                <h5>We Accept</h5>
                                <span class="match-info">{{ $program->disability->disability_name }}</span>
                            </div>
                            <div class="more-info">
                                <h5>Education Level</h5>
                                <span class="match-info">{{ $program->education->education_name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="competencies" role="tabpanel">
                    <div>
                        <h5>Competencies</h5>
                        <ul>
                            @forelse ($program->competencies as $competency)
                            <li>{{ $competency->name }}</li>
                            @empty
                            <div>No competencies yet.</div>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="tab-pane enrollees" id="enrollees" role="tabpanel">
                    <table class="table table-striped table-hover">
                        <tbody>
                            @forelse ($enrollees as $enrollee)
                            <tr>
                                <td class="name">
                                    <a href="{{ route('show-profile', $enrollee->application->user->id) }}">
                                        {{ $enrollee->application->user->userInfo->name }}
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">No enrollees yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($program->crowdfund)
                <div class="tab-pane" id="sponsors" role="tabpanel">
                    <div class="crowdfund-progress mb-3">
                        <p class="sub-text">
                            Goal Amount: &nbsp;&nbsp;<span>{{number_format($program->crowdfund->goal, 0, '.', ',') . ' PHP'}}</span>
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
                </div>
                @endif
                <div class="tab-pane" id="reviews" role="tabpanel">
                    <div class="border reviews">
                        <div class="header border-bottom d-flex justify-content-between align-items-center">
                            <h3>Reviews</h3>
                            @if ($isCompletedProgram)
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

    function confirmApplication(event, formId) {
        event.preventDefault();
        Swal.fire({
            title: "Confirmation",
            text: "Do you really want to apply for this training program?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Confirm"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
</script>

@endsection