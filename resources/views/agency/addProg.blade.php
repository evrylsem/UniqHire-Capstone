@extends('layout')
@section('page-title', 'Add Training Program')
@section('page-content')
<form action="{{ route('programs-add') }}" method="POST" class="container mb-5 add-form">
    @csrf
    <div class="row mt-2 mb-2 border-bottom">
        <div class="text-start header-texts back-link-container">
            <a href="{{ route('programs-manage') }}" class="m-1 back-link"><i class='bx bx-left-arrow-alt'></i></a>
            Add Training Program.
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" name="title" required placeholder="First Name">
                <label for="floatingInput">Title</label>
                @error('title')
                <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" name="city" required placeholder="First Name">
                <label for="floatingInput">City</label>
                @error('city')
                <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-floating mb-3">
                <textarea class="form-control" placeholder="Description" id="floatingTextarea2" name="description" style="height: 200px"></textarea>
                <label for="floatingTextarea2">Description</label>
                @error('description')
                <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="mb-3">
                <label for="">Start Date: </label>
                <input type="date" name="start_date" class="date-input">
            </div>
        </div>
        <div class="col">
            <div class="mb-3">
                <label for="">End Date: </label>
                <input type="date" name="end_date" class="date-input">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-floating mb-3">
                <select class="form-select" id="floatingSelect" name="disability" aria-label="Floating label select example">
                    @foreach ($disabilities as $disability)
                    @if ($disability->disability_name != 'Not Applicable')
                    <option value="{{ $disability->id }}">{{ $disability->disability_name }}</option>
                    @endif
                    @endforeach

                </select>
                <label for="floatingSelect">Disability</label>
            </div>
        </div>
        <div class="col">
            <div class="form-floating mb-3">
                <select class="form-select" id="floatingSelect" name="education" aria-label="Floating label select example">
                    @foreach ($levels as $level)
                    <option value="{{ $level->id }}">{{ $level->education_name }}</option>
                    @endforeach

                </select>
                <label for="floatingSelect">Education Level</label>
            </div>
        </div>
    </div>
    <hr>
    <div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="host-crowdfund" onchange="toggleCrowdfund()">
            <label class="form-check-label" for="flexCheckDefault">
                Host a crowdfunding for this?
            </label>
        </div>
    </div>
    <div class="row" id="crowdfund-section">
        <div class="col">
            <div class="form-floating mb-3">
                <input type="number" class="form-control" id="amount-needed" name="goal" required placeholder="Amount Needed" disabled>
                <label for="floatingInput">Amount Needed</label>
                @error('goal')
                <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-evenly mt-3 prog-btn">
        <button type="reset" class="deny-btn border-0">Clear</button>
        <button type="submit" class="submit-btn border-0">Add</button>
    </div>
</form>


@endsection

<script>
    function toggleCrowdfund() {
        var hostCrowdfund = document.getElementById('host-crowdfund');
        var crowdfundSection = document.getElementById('crowdfund-section');

        if (hostCrowdfund.checked) {
            // crowdfundSection.style.display = 'block';
            document.getElementById('amount-needed').disabled = false;
            document.getElementById('amount-needed').required = true;
        } else {
            // crowdfundSection.style.display = 'none';
            document.getElementById('amount-needed').disabled = true;
            document.getElementById('amount-needed').required = false;
        }
    }
</script>