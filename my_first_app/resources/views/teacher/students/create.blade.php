@extends('layouts.app')

@section('title', 'Register Student — Teacher Dashboard')

@section('content')
<div style="max-width: 640px; margin: 0 auto;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 700;">Register Student</h1>
            <p style="color: var(--color-muted);">Add a new BSIT 3rd Year student to the official records.</p>
        </div>
        <a href="{{ route('teacher.students.search') }}" style="padding: 0.55rem 1rem; background: #F1F5F9; color: #475569; border: 1px solid #CBD5E1; border-radius: var(--radius-md); font-weight: 600; text-decoration: none; font-size: 0.875rem;">
            &larr; Search Student
        </a>
    </div>

    <!-- Registration Form Card -->
    <div class="card">
        <form action="{{ route('teacher.students.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">First Name *</label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Juan" required style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-md);">
                    @error('first_name') <span style="color: #DC2626; font-size: 0.75rem;">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Last Name *</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Dela Cruz" required style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-md);">
                    @error('last_name') <span style="color: #DC2626; font-size: 0.75rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Section (BSIT 3rd Year Only) *</label>
                <select name="section" required style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-md);">
                    <option value="">Select Section</option>
                    @foreach($sections as $sec)
                        <option value="{{ $sec }}" {{ old('section') == $sec ? 'selected' : '' }}>{{ $sec }}</option>
                    @endforeach
                </select>
                @error('section') <span style="color: #DC2626; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Student Number *</label>
                <input type="text" name="student_number" value="{{ old('student_number', $defaultStudentNumber) }}" placeholder="2024-00452-SR-0" required style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-md); font-family: monospace;">
                <span style="font-size: 0.75rem; color: var(--color-muted);">Required format: <code>2024-00452-SR-0</code></span>
                @error('student_number') <br><span style="color: #DC2626; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <!-- Picture Upload / Camera Capture Add-on -->
            <div style="margin-bottom: 1.5rem; padding: 1rem; border: 1px dashed var(--color-border); border-radius: var(--radius-md); background: #FAFBFD;">
                <label style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.5rem;">Student Photo (Optional)</label>
                
                <input type="file" name="photo" accept="image/*" id="photo-file" style="width: 100%; margin-bottom: 0.75rem; font-size: 0.8125rem;">

                <input type="hidden" name="photo_webcam" id="photo-webcam-data">

                <!-- Image Live Preview Box -->
                <div id="photo-preview-wrap" style="display: none; margin-bottom: 0.75rem; text-align: center; background: #FFFFFF; padding: 0.75rem; border-radius: var(--radius-md); border: 1px solid var(--color-border);">
                    <img id="photo-preview-img" style="width: 90px; height: 90px; border-radius: 50%; object-fit: cover; border: 3px solid var(--color-brand); display: inline-block;">
                    <div id="photo-preview-label" style="font-size: 0.75rem; color: var(--color-brand); font-weight: 700; margin-top: 0.35rem;">Photo Preview Ready</div>
                </div>

                <!-- Webcam Capture Add-on Toggle -->
                <div style="border-top: 1px solid var(--color-border); padding-top: 0.75rem;">
                    <button type="button" id="btn-toggle-cam" style="padding: 0.4rem 0.75rem; background: #F1F5F9; color: #475569; border: 1px solid #CBD5E1; border-radius: var(--radius-sm); font-size: 0.8125rem; font-weight: 600; cursor: pointer; white-space: nowrap;">
                        Use Camera Photo Capture
                    </button>
                    <div id="cam-container" style="display: none; margin-top: 0.75rem; text-align: center;">
                        <video id="webcam-video" width="100%" height="240" autoplay playsinline style="border-radius: var(--radius-md); background: #000; object-fit: cover;"></video>
                        <canvas id="webcam-canvas" style="display: none;"></canvas>
                        <div style="margin-top: 0.5rem; display: flex; gap: 0.5rem; justify-content: center;">
                            <button type="button" id="btn-snap-cam" style="padding: 0.4rem 0.85rem; background: #10B981; color: #FFF; border: none; border-radius: var(--radius-sm); font-weight: 600; font-size: 0.8125rem; cursor: pointer; white-space: nowrap;">
                                Snap Photo
                            </button>
                            <span id="cam-status" style="font-size: 0.75rem; color: #10B981; font-weight: 600; align-self: center;"></span>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" style="width: 100%; padding: 0.85rem; background: var(--color-brand); color: #FFF; border: none; border-radius: var(--radius-md); font-weight: 700; font-size: 1rem; cursor: pointer; white-space: nowrap;">
                Save Student Registration
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const photoFileInput = document.getElementById('photo-file');
    const photoPreviewWrap = document.getElementById('photo-preview-wrap');
    const photoPreviewImg = document.getElementById('photo-preview-img');
    const photoPreviewLabel = document.getElementById('photo-preview-label');

    const btnToggleCam = document.getElementById('btn-toggle-cam');
    const camContainer = document.getElementById('cam-container');
    const video = document.getElementById('webcam-video');
    const canvas = document.getElementById('webcam-canvas');
    const btnSnap = document.getElementById('btn-snap-cam');
    const camDataInput = document.getElementById('photo-webcam-data');
    const camStatus = document.getElementById('cam-status');

    let stream = null;

    // File Input Preview Handler with Confirmation Check
    if (photoFileInput) {
        photoFileInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (!file) return;

            // Prompt if camera photo was already captured
            if (camDataInput.value !== '') {
                const confirmReplace = confirm("You have already captured a camera photo. Do you want to remove it and use this uploaded file instead?");
                if (!confirmReplace) {
                    photoFileInput.value = ''; // Cancel file selection
                    return;
                }
                // Clear camera data
                camDataInput.value = '';
                camStatus.innerText = '';
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }
                camContainer.style.display = 'none';
            }

            const reader = new FileReader();
            reader.onload = (event) => {
                photoPreviewImg.src = event.target.result;
                photoPreviewWrap.style.display = 'block';
                photoPreviewLabel.innerText = 'Uploaded File Selected: ' + file.name;
            };
            reader.readAsDataURL(file);
        });
    }

    // Camera Toggle Handler with Confirmation Check
    if (btnToggleCam) {
        btnToggleCam.addEventListener('click', async () => {
            if (camContainer.style.display === 'none') {
                // Prompt if file was already selected
                if (photoFileInput.files && photoFileInput.files.length > 0) {
                    const confirmSwitch = confirm("You have already selected an uploaded photo file. Do you want to remove it and switch to camera photo capture instead?");
                    if (!confirmSwitch) {
                        return;
                    }
                    photoFileInput.value = ''; // Clear file selection
                }

                try {
                    stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
                    video.srcObject = stream;
                    camContainer.style.display = 'block';
                } catch (err) {
                    alert('Could not access camera. Please choose an image file from your device instead.');
                }
            } else {
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }
                camContainer.style.display = 'none';
            }
        });
    }

    // Camera Snap Photo Handler with Confirmation Check
    if (btnSnap) {
        btnSnap.addEventListener('click', () => {
            // Prompt if file was already selected
            if (photoFileInput.files && photoFileInput.files.length > 0) {
                const confirmSwitch = confirm("You have already selected an uploaded photo file. Do you want to remove it and use this camera photo instead?");
                if (!confirmSwitch) {
                    return;
                }
                photoFileInput.value = ''; // Clear file selection
            }

            canvas.width = video.videoWidth || 400;
            canvas.height = video.videoHeight || 300;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            const dataUrl = canvas.toDataURL('image/png');
            camDataInput.value = dataUrl;
            
            // Show preview
            photoPreviewImg.src = dataUrl;
            photoPreviewWrap.style.display = 'block';
            photoPreviewLabel.innerText = 'Camera Photo Captured!';
            camStatus.innerText = 'Captured!';
        });
    }
</script>
@endsection
