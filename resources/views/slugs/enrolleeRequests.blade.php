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
                    @forelse ($requests as $request)
                    <input type="hidden" name="program" value="{{ $program->id }}">
                    <div class="request-container">
                        <a href="{{ route('show-profile', $request->user->id) }}">
                            <div class="request-owner mb-2">
                                <div class="request-pic">

                                </div>
                                <div class="owner-name">
                                    <p class="fs-5">{{ $request->user->userInfo->name }}</p>
                                    <p class="mb-2 location text-cap"><i class='bx bx-map sub-text'></i>{{ $request->user->userInfo->state . ', ' . (str_contains($request->user->userInfo->city, 'City') ? $request->user->userInfo->city : $request->user->userInfo->city. ' City')}}</p>
                                    <span class="match-info">{{ $request->user->userInfo->disability->disability_name }}</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="text-end btn-container">
                                    <form action="{{ route('agency-accept') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="pwd_id" value="{{ $request->user->id }}">
                                        <input type="hidden" name="program_id" value="{{ $program->id }}">
                                        <input type="hidden" name="training_application_id" value="{{ $request->id }}">
                                        <button type="submit" class="submit-btn border-0">Accept</button>
                                    </form>
                                    <button type="button" class="deny-btn border-0">Deny</button>
                                </div>
                                >
                            </div>
                        </a>
                    </div>
                    @empty
                    <div class="text-center">No requests yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>