<!-- If compeleted ilisan ang apply to rate -->
<button type="button" class="submit-btn modal-btn border-0" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Add Review</button>
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable ">
        <div class="modal-content">
            <div class="modal-body text-center">
                <form id="rating-form" action="{{ route('rate-program') }}" method="POST">
                    @csrf
                    <input type="hidden" name="program_id" value="{{ $program->id }}">
                    <input type="hidden" name="rating" id="rating-input" value="">
                    <div class="star-rating">
                        <i class='bx bx-star star-light' data-rating="1" id="star-1"></i>
                        <i class='bx bx-star star-light' data-rating="2" id="star-2"></i>
                        <i class='bx bx-star star-light' data-rating="3" id="star-3"></i>
                        <i class='bx bx-star star-light' data-rating="4" id="star-4"></i>
                        <i class='bx bx-star star-light' data-rating="5" id="star-5"></i>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea class="form-control" placeholder="How was your experience?" id="floatingTextarea2" name="content" style="height: 100px"></textarea>
                        <label for="floatingTextarea2">How was your experience?</label>
                        @error('content')
                        <span class="error-msg">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="button" class="deny-btn border-0" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="submit-btn border-0">Submit</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>