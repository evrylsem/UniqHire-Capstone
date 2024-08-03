@extends('layout')

@section('page-title', 'Program Details')

@section('page-content')
<div class="mb-5 show-prog-container">
    <div>
        @if (Route::currentRouteName() == 'training-details')
        <a href="{{ route('pwd-list-program') }}" class="m-1 back-link"><i class='bx bx-left-arrow-alt'></i></a>
        @elseif(Route::currentRouteName() == 'show-details' )
        <a href="{{ route('trainings') }}" class="m-1 back-link"><i class='bx bx-left-arrow-alt'></i></a>
        @endif
    </div>
    <div class="prog-details">
        <div class="prog-texts">
            <!-- <div class="row mb-3"> -->
            <div class="d-flex ">
                <div class="header mb-3">
                    <h3 class="text-cap">{{ $program->title }}</h3>
                    <p class="sub-text text-cap">{{ $program->agency->userInfo->name }}</p>
                    <p class="sub-text prog-loc text-cap"><i class='bx bx-map sub-text'></i>{{(str_contains($program->city, 'City') ? $program->city : $program->city . ' City')}}</p>
                </div>
                <div class="col prog-btn">
                    <form action="{{ route('pwd-application') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="training_program_id" value="{{ $program->id }}">
                        @if ($application && $application->application_status == 'Pending')
                        <button type="submit" class="submit-btn border-0" disabled>
                            Pending
                        </button>
                        @elseif($application && $application->application_status == 'Approved')
                        <button type="submit" class="submit-btn border-0" disabled>
                            Approved
                        </button>
                        @else
                        <button type="submit" class="submit-btn border-0">
                            Apply
                        </button>
                        @endif
                    </form>
                </div>
            </div>
            <div>
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
                        <a class="nav-link" data-bs-toggle="tab" href="" role="tab">Compentencies</a>
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
                                    <h5>Start Date</h5>
                                    <p>{{ \Carbon\Carbon::parse($program->start)->format('M d, Y') }}</p>
                                </div>
                                <div class="more-info">
                                    <h5>End Date</h5>
                                    <p>{{ \Carbon\Carbon::parse($program->end)->format('M d, Y') }}</p>
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

                    <div class="tab-pane enrollees" id="enrollees" role="tabpanel">
                        <ul>
                            <li><a href="">Kler</a></li>
                        </ul>
                    </div>
                    @if ($program->crowdfund)
                    <div class="tab-pane" id="sponsors" role="tabpanel">
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
                    </div>
                    @endif
                    <div class="tab-pane" id="reviews" role="tabpanel">
                        <div class="border reviews">
                            <div class="header border-bottom d-flex justify-content-between align-items-center">
                                <h3>Reviews</h3>
                                @include('slugs.feedback')
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

        

    </script>

    @endsection