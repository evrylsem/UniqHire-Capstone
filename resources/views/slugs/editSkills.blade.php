<div class="mb-3 d-flex justify-content-between exp-header">
    <h4 class="">Skills&nbsp;&nbsp;</h4>
    <div class="d-flex">
        <div>
            <button type="button" id="skill-add-btn" data-bs-toggle="modal" data-bs-target="#skillModal" class="border-0 match-info" style="display: none;"><i class='bx bx-plus'></i></button>
        </div>
        <div>
            <button class="border-0" type="button" id="skill-edit-btn" onclick="toggleEditSkill()"><i class='bx bx-edit-alt sub-text'></i></button>
        </div>
    </div>
</div>
<div>
    <form action="{{route('add-skill')}}" method="POST">
        @csrf
        <div class="modal fade" id="skillModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Skill</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" value="{{ $user->id }}">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="skillSelect" name="skill" aria-label="Floating label select example" required>
                                <option value="" selected disabled>Select a skill</option>
                                @foreach($skills as $skill)
                                <option value="{{ $skill->id }}">{{ $skill->title }}</option>
                                @endforeach
                            </select>
                            <label for="skillSelect">Skill</label>
                            @error('skill')
                            <span class="error-msg">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="deny-btn border-0">Clear</button>
                            <button type="submit" class="border-0 submit-btn">Save Changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <ul class="experiences">
        @forelse($skilluser as $skill)
        <li class="mb-1">
            <div class="d-flex">
                <div class="d-flex">
                    <div class="exp-container">
                        <p class="skill-title">{{ $skill->skill->title }}</p>
                    </div>
                    <form action="{{ route('delete-skill', $skill->id) }}" method="POST" class="d-flex justify-content-end">
                        @csrf
                        @method('DELETE')
                        <button class="border-0 match-info skill-delete-btn delete-btn" style="display: none;"><i class='bx bx-x'></i></button>
                    </form>
                </div>
            </div>
        </li>
        @empty
        <div class="about sub-text">No Skills. Add one.</div>
        @endforelse
    </ul>
</div>

<script>
    function toggleEditSkill() {
        var editBtn = document.getElementById('skill-edit-btn');
        var addBtn = document.getElementById('skill-add-btn');
        var deleteBtns = document.querySelectorAll('.skill-delete-btn');

        if (addBtn.style.display === 'none' || addBtn.style.display === '') {
            addBtn.style.display = 'inline-block';
        } else {
            addBtn.style.display = 'none';
        }

        deleteBtns.forEach(btn => {
            if (btn.style.display === 'none' || btn.style.display === '') {
                btn.style.display = 'inline-block';
            } else {
                btn.style.display = 'none';
            }
        });
    }
</script>