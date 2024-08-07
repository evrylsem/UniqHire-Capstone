
<div>
    <button type="button" id="skill-add-btn" data-bs-toggle="modal" data-bs-target="#profileModal" class="border-0" style="background-color: transparent;"><i class='bx bx-plus' style="transform: scale(2);"></i></button>
</div>

<form action="{{route('add-pic')}}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="modal fade" id="profileModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Skill</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>                    
                <div class="modal-body"> 
                    <input type="file" name="profilePic" id="">                             
                    <div class="modal-footer">
                        <button type="reset" class="deny-btn border-0">Clear</button>
                        <button type="submit" class="border-0 submit-btn">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>