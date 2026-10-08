@extends('layouts.app')

@section('title', 'Register Student — Teacher Dashboard')

@section('content')
<div style="max-width: 640px; margin: 0 auto;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 700;">Register Student</h1>
            <p style="color: var(--color-muted);">Add a new BSIT 3rd Year student to the official records.</p>
        </div>
        <a href="{{ route('teacher.students.search') }}" style="padding: 0.55rem 1.25rem; background: #F1F5F9; color: #475569; border: 1px solid #CBD5E1; border-radius: 50px; font-weight: 600; text-decoration: none; font-size: 0.875rem;">
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
            <div style="margin-bottom: 1.5rem; padding: 1.25rem; border: 1px dashed var(--color-border); border-radius: var(--radius-md); background: #FAFBFD;">
                <label style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.5rem;">Student Photo (Optional)</label>
                
                <input type="file" name="photo" accept="image/*" id="photo-file" style="width: 100%; margin-bottom: 0.75rem; font-size: 0.8125rem;">
                <input type="hidden" name="photo_webcam" id="photo-webcam-data">

                <!-- Image Live Preview Box -->
                <div id="photo-preview-wrap" style="display: none; margin-bottom: 0.75rem; text-align: center; background: #FFFFFF; padding: 0.875rem; border-radius: var(--radius-md); border: 1px solid var(--color-border);">
                    <img id="photo-preview-img" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid var(--color-brand); display: inline-block;">
                    <div id="photo-preview-label" style="font-size: 0.75rem; color: var(--color-brand); font-weight: 700; margin-top: 0.35rem;">Photo Preview Ready</div>
                    <button type="button" id="btn-re-adjust-photo" style="margin-top: 0.5rem; padding: 0.35rem 0.85rem; background: #F1F5F9; color: #8C0D47; border: 1px solid #CBD5E1; border-radius: 50px; font-size: 0.75rem; font-weight: 700; cursor: pointer; display: none;">
                        Adjust / Crop Photo
                    </button>
                </div>

                <!-- Webcam Capture Add-on Toggle -->
                <div style="border-top: 1px solid var(--color-border); padding-top: 0.75rem;">
                    <button type="button" id="btn-toggle-cam" style="padding: 0.45rem 1rem; background: #F1F5F9; color: #475569; border: 1px solid #CBD5E1; border-radius: 50px; font-size: 0.8125rem; font-weight: 600; cursor: pointer; white-space: nowrap;">
                        Use Camera Photo Capture
                    </button>

                    <div id="cam-container" style="display: none; margin-top: 0.75rem; text-align: center;">
                        <!-- Camera Viewport with Dotted Circle Guide -->
                        <div style="position: relative; width: 100%; height: 380px; border-radius: var(--radius-md); overflow: hidden; background: #000; box-shadow: var(--shadow-sm);">
                            <video id="webcam-video" width="100%" height="380" autoplay playsinline style="width: 100%; height: 380px; object-fit: cover;"></video>
                            <canvas id="webcam-canvas" style="display: none;"></canvas>

                            <!-- Dotted Circle Guide Overlay -->
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 270px; height: 270px; border: 3px dashed rgba(255, 255, 255, 0.95); border-radius: 50%; pointer-events: none; box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.4);">
                                <div style="position: absolute; bottom: -32px; width: 100%; text-align: center; color: #FFFFFF; font-size: 0.8125rem; font-weight: 700; text-shadow: 0 1px 3px rgba(0,0,0,0.9);">
                                    Align Face Inside Circle
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 0.75rem; display: flex; gap: 0.5rem; justify-content: center; align-items: center;">
                            <button type="button" id="btn-snap-cam" style="padding: 0.5rem 1.25rem; background: #10B981; color: #FFF; border: none; border-radius: 50px; font-weight: 700; font-size: 0.875rem; cursor: pointer; white-space: nowrap;">
                                Snap Photo
                            </button>
                            <span id="cam-status" style="font-size: 0.75rem; color: #10B981; font-weight: 700; transition: opacity 0.5s ease;"></span>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" style="width: 100%; padding: 0.85rem; background: var(--color-brand); color: #FFF; border: none; border-radius: 50px; font-weight: 700; font-size: 1rem; cursor: pointer; white-space: nowrap;">
                Save Student Registration
            </button>
        </form>
    </div>
</div>

<!-- Uploaded Image Crop & Adjustment Modal -->
<div id="crop-modal" class="modal-overlay" style="display: none;">
    <div class="modal-card" style="max-width: 440px; text-align: center; background: #FFFFFF; border-radius: 24px; padding: 2rem; box-shadow: var(--shadow-lg);">
        <h3 style="margin-bottom: 0.25rem; color: var(--color-dark);">Adjust & Crop Photo</h3>
        <p style="color: var(--color-muted); font-size: 0.8125rem; margin-bottom: 1.25rem;">Position and rotate your photo inside the circular frame.</p>

        <!-- Crop Viewport Canvas Area -->
        <div style="position: relative; width: 280px; height: 280px; margin: 0 auto 1.25rem; border-radius: 50%; overflow: hidden; border: 3.5px dashed var(--color-brand); background: #1E293B; box-shadow: 0 4px 15px rgba(0,0,0,0.15);">
            <canvas id="crop-canvas" width="280" height="280" style="width: 280px; height: 280px; display: block;"></canvas>
        </div>

        <!-- Controls: Zoom & Rotate -->
        <div style="background: #F8FAFC; padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--color-border); margin-bottom: 1.25rem;">
            <!-- Zoom Slider -->
            <div style="margin-bottom: 0.85rem;">
                <label style="font-size: 0.75rem; font-weight: 700; color: var(--color-dark); display: block; margin-bottom: 0.35rem;">Zoom Scale</label>
                <input type="range" id="crop-zoom" min="0.5" max="3.0" step="0.05" value="1.0" style="width: 85%;">
            </div>

            <!-- Action Buttons: Rotate & Flip -->
            <div style="display: flex; gap: 0.5rem; justify-content: center; flex-wrap: wrap;">
                <button type="button" id="btn-rotate-left" style="padding: 0.4rem 0.85rem; background: #FFFFFF; color: #475569; border: 1px solid #CBD5E1; border-radius: 50px; font-size: 0.75rem; font-weight: 600; cursor: pointer;">
                    Rotate Left 90°
                </button>
                <button type="button" id="btn-rotate-right" style="padding: 0.4rem 0.85rem; background: #FFFFFF; color: #475569; border: 1px solid #CBD5E1; border-radius: 50px; font-size: 0.75rem; font-weight: 600; cursor: pointer;">
                    Rotate Right 90°
                </button>
                <button type="button" id="btn-flip-h" style="padding: 0.4rem 0.85rem; background: #FFFFFF; color: #475569; border: 1px solid #CBD5E1; border-radius: 50px; font-size: 0.75rem; font-weight: 600; cursor: pointer;">
                    Flip Horizontal
                </button>
            </div>
        </div>

        <div style="display: flex; gap: 0.75rem; justify-content: center;">
            <button type="button" id="btn-close-crop" style="padding: 0.65rem 1.25rem; background: #FEF2F2; color: #EF4444; border: 1px solid #FCA5A5; border-radius: 50px; font-weight: 700; font-size: 0.875rem; cursor: pointer; white-space: nowrap;">
                Cancel
            </button>
            <button type="button" id="btn-apply-crop" class="btn-primary" style="background: var(--color-brand); color: #FFF; padding: 0.65rem 1.25rem; border-radius: 50px; font-weight: 700; font-size: 0.875rem; border: none; cursor: pointer;">
                Apply & Use Photo
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const photoFileInput = document.getElementById('photo-file');
    const photoPreviewWrap = document.getElementById('photo-preview-wrap');
    const photoPreviewImg = document.getElementById('photo-preview-img');
    const photoPreviewLabel = document.getElementById('photo-preview-label');
    const btnReAdjustPhoto = document.getElementById('btn-re-adjust-photo');

    const btnToggleCam = document.getElementById('btn-toggle-cam');
    const camContainer = document.getElementById('cam-container');
    const video = document.getElementById('webcam-video');
    const canvas = document.getElementById('webcam-canvas');
    const btnSnap = document.getElementById('btn-snap-cam');
    const camDataInput = document.getElementById('photo-webcam-data');
    const camStatus = document.getElementById('cam-status');

    // Crop Modal Elements
    const cropModal = document.getElementById('crop-modal');
    const cropCanvas = document.getElementById('crop-canvas');
    const cropZoom = document.getElementById('crop-zoom');
    const btnRotateLeft = document.getElementById('btn-rotate-left');
    const btnRotateRight = document.getElementById('btn-rotate-right');
    const btnFlipH = document.getElementById('btn-flip-h');
    const btnCloseCrop = document.getElementById('btn-close-crop');
    const btnApplyCrop = document.getElementById('btn-apply-crop');

    let stream = null;
    let statusTimer = null;
    let loadedImageObj = null;

    let zoomVal = 1.0;
    let rotateVal = 0;
    let flipHVal = 1;

    function renderCropCanvas() {
        if (!loadedImageObj) return;
        const ctx = cropCanvas.getContext('2d');
        const size = 280;
        cropCanvas.width = size;
        cropCanvas.height = size;

        ctx.clearRect(0, 0, size, size);
        ctx.save();
        ctx.translate(size / 2, size / 2);
        ctx.rotate((rotateVal * Math.PI) / 180);
        ctx.scale(zoomVal * flipHVal, zoomVal);

        const aspect = loadedImageObj.width / loadedImageObj.height;
        let drawW, drawH;
        if (aspect > 1) {
            drawH = size;
            drawW = size * aspect;
        } else {
            drawW = size;
            drawH = size / aspect;
        }
        ctx.drawImage(loadedImageObj, -drawW / 2, -drawH / 2, drawW, drawH);
        ctx.restore();
    }

    if (photoFileInput) {
        photoFileInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (!file) return;

            if (camDataInput.value !== '') {
                const confirmReplace = confirm("You have already captured a camera photo. Do you want to remove it and use this uploaded file instead?");
                if (!confirmReplace) {
                    photoFileInput.value = '';
                    return;
                }
                camDataInput.value = '';
                camStatus.innerText = '';
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }
                camContainer.style.display = 'none';
            }

            const reader = new FileReader();
            reader.onload = (event) => {
                const img = new Image();
                img.onload = () => {
                    loadedImageObj = img;
                    zoomVal = 1.0;
                    rotateVal = 0;
                    flipHVal = 1;
                    cropZoom.value = 1.0;
                    renderCropCanvas();
                    cropModal.style.display = 'flex';
                };
                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
        });
    }

    if (cropZoom) {
        cropZoom.addEventListener('input', (e) => {
            zoomVal = parseFloat(e.target.value);
            renderCropCanvas();
        });
    }

    if (btnRotateLeft) {
        btnRotateLeft.addEventListener('click', () => {
            rotateVal = (rotateVal - 90) % 360;
            renderCropCanvas();
        });
    }

    if (btnRotateRight) {
        btnRotateRight.addEventListener('click', () => {
            rotateVal = (rotateVal + 90) % 360;
            renderCropCanvas();
        });
    }

    if (btnFlipH) {
        btnFlipH.addEventListener('click', () => {
            flipHVal = flipHVal * -1;
            renderCropCanvas();
        });
    }

    if (btnCloseCrop) {
        btnCloseCrop.addEventListener('click', () => {
            cropModal.style.display = 'none';
            if (!photoPreviewImg.src || photoPreviewWrap.style.display === 'none') {
                photoFileInput.value = '';
            }
        });
    }

    if (btnApplyCrop) {
        btnApplyCrop.addEventListener('click', () => {
            renderCropCanvas();
            const croppedDataUrl = cropCanvas.toDataURL('image/png');
            photoPreviewImg.src = croppedDataUrl;
            photoPreviewWrap.style.display = 'block';
            photoPreviewLabel.innerText = 'Uploaded Photo Adjusted & Crop Ready!';
            btnReAdjustPhoto.style.display = 'inline-block';
            camDataInput.value = croppedDataUrl;
            cropModal.style.display = 'none';
        });
    }

    if (btnReAdjustPhoto) {
        btnReAdjustPhoto.addEventListener('click', () => {
            if (loadedImageObj) {
                renderCropCanvas();
                cropModal.style.display = 'flex';
            }
        });
    }

    // Camera Toggle Handler
    if (btnToggleCam) {
        btnToggleCam.addEventListener('click', async () => {
            if (camContainer.style.display === 'none') {
                if (photoFileInput.files && photoFileInput.files.length > 0) {
                    const confirmSwitch = confirm("You have already selected an uploaded photo file. Do you want to remove it and switch to camera photo capture instead?");
                    if (!confirmSwitch) {
                        return;
                    }
                    photoFileInput.value = '';
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

    // Camera Snap Photo Handler with Auto-Dismiss Status Text
    if (btnSnap) {
        btnSnap.addEventListener('click', () => {
            if (photoFileInput.files && photoFileInput.files.length > 0) {
                const confirmSwitch = confirm("You have already selected an uploaded photo file. Do you want to remove it and use this camera photo instead?");
                if (!confirmSwitch) {
                    return;
                }
                photoFileInput.value = '';
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
            btnReAdjustPhoto.style.display = 'none';

            // Auto-dismiss status text after 2.5s so retaking photo is clear
            camStatus.style.opacity = '1';
            camStatus.innerText = 'Photo captured!';
            if (statusTimer) clearTimeout(statusTimer);
            statusTimer = setTimeout(() => {
                camStatus.style.opacity = '0';
                setTimeout(() => {
                    camStatus.innerText = '';
                    camStatus.style.opacity = '1';
                }, 400);
            }, 2500);
        });
    }
</script>
@endsection
