<div class="details-container" id="details-container">
    <div class="header border-bottom d-flex align-items-center justify-content-between mb-2">
        <a href="#" id="close-popup"><i class='bx bx-x'></i></a>
        <h4>TRAINING DETAILS</h4>
        <div class="empty-space"></div>
    </div>
    <div class="body">
        <div class="row mb-4">
            <div class="col">
                <h3>Title</h3>
                <p class="sub-text">by name</p>
                <p class="sub-text"><i class='bx bx-map sub-text'></i> Coty</p>
            </div>
            <div class="col text-end prog-btn">
                <form action="" method="POST">
                    <button class="submit-btn border-0">Apply</button>
                </form>
            </div>
        </div>

        <div class="mb-5">
            <p>Description</p>
        </div>
        <div class="row more-info">
            <div class="col">
                <h5>We Accept</h5>
                <span class="requirement">Disability</span>
            </div>
            <div class="col">
                <h5>Education Level</h5>
                <span class="requirement">Education</span>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var closeBtn = document.getElementById('close-popup');
        var container = document.getElementById('details-container');

        function togglePopup() {
            if (container.style.display === 'none' || container.style.display === '') {
                container.style.display = 'block';
            } else {
                container.style.display = 'none';
            }
        }

        closeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            container.style.display = 'none';
        });

        // Example function to open the popup (you should call this function where needed)
        function openPopup() {
            container.style.display = 'block';
        }
    });
</script>