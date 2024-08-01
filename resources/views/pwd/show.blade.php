<div class="popup" id="popup">
    <div class="details-container" id="details-container">

        <div class="header border-bottom d-flex align-items-center justify-content-between mb-2">
            <a href="#" id="close-popup"><i class='bx bx-x'></i></a>
            <h4>TRAINING DETAILS</h4>
            <div class="empty-space"></div>
        </div>

        <div class="body">
            <div class="row mb-4">
                <div class="col">
                    <h3 id="title"></h3>
                    <p class="sub-text" id="agency"></p>
                    <p class="sub-text" id="city"><i class='bx bx-map sub-text'></i></p>
                </div>
                <div class="col text-end prog-btn">                    
                    <button type="button" class="submit-btn border-0" id="apply-button" data-user-id="{{ Auth::user()->id }}" data-program-id="">
                        <span id="button-label"></span>
                    </button>

                </div>
            </div>

            <div class="mb-5">
                <p id="desc"></p>
            </div>

            <div class="row more-info">
                <div class="col ">
                    <h5>Start Date</h5>
                    <p id="start"></p>
                </div>
                <div class="col">
                    <h5>End Date</h5>
                    <p id="end">End</p>
                </div>
            </div>

            <div class="row more-info">
                <div class="col">
                    <h5>We Accept</h5>
                    <span class="match-info" id="disability"></span>
                </div>
                <div class="col">
                    <h5>Education Level</h5>
                    <span class="match-info" id="education"></span>
                </div>
            </div>

        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        var closeBtn = document.getElementById('close-popup');
        var container = document.getElementById('popup');
        var applyButton = document.getElementById('apply-button');

        function togglePopup() {
            if (container.style.display === 'none' || container.style.display === '') {
                container.style.display = 'flex';
            } else {
                container.style.display = 'none';
            }
        }

        closeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            container.style.display = 'none';
        });

        function openPopup() {
            container.style.display = 'flex';
        }

        applyButton.addEventListener('click', function(e) {
            e.preventDefault();

            console.log("naka abot sa applybutton");
            var applyButton = this;
            var userId = applyButton.getAttribute('data-user-id');
            var programId = applyButton.getAttribute('data-program-id');
            var buttonLabel = document.getElementById('button-label');

            console.log("after ni sa data-user-id")

            fetch(`/pwd/application`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    user_id: userId,
                    training_program_id: programId,
                    application_status: 'Pending'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Application submitted successfully.');
                    buttonLabel.textContent = 'Pending';
                    applyButton.disabled = true;
                    container.style.display = 'none';
                } else {
                    alert('Failed to submit application.');
                }
            })
            .catch(error => console.error('Error:', error));
        });

    });
</script>