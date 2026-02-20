{{-- Photo Timeline Component --}}
@php
    $allPhotos = $order->details->flatMap(function($detail) {
        return collect($detail->photos ?? [])->map(function($photo) use ($detail) {
            return [
                'path' => $photo,
                'date' => $detail->created_at,
                'status' => $detail->translated_status,
                'description' => $detail->progress_description,
            ];
        });
    })->filter()->values();
@endphp

@if($allPhotos->isNotEmpty())
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold text-primary">
            <i class="bi bi-images me-2"></i>Timeline Foto Progress
        </h6>
        <span class="badge bg-primary">{{ $allPhotos->count() }} foto</span>
    </div>
    <div class="card-body">
        {{-- Timeline View --}}
        <div class="photo-timeline">
            @foreach($allPhotos as $index => $photo)
                <div class="timeline-item mb-4">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="timeline-marker {{ $index == 0 ? 'bg-success' : 'bg-primary' }}">
                                {{ $index + 1 }}
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <span class="badge bg-info">{{ $photo['status'] }}</span>
                                    <small class="text-muted ms-2">
                                        {{ $photo['date']->format('d M Y, H:i') }}
                                    </small>
                                </div>
                                @if($index == 0 && $allPhotos->count() > 1)
                                    <span class="badge bg-success">Terbaru</span>
                                @endif
                            </div>
                            @if($photo['description'])
                                <p class="small text-muted mb-2">{{ Str::limit($photo['description'], 100) }}</p>
                            @endif
                            <a href="{{ asset('storage/' . $photo['path']) }}" target="_blank" class="d-block">
                                <img src="{{ asset('storage/' . $photo['path']) }}" 
                                     alt="Progress {{ $index + 1 }}" 
                                     class="img-thumbnail timeline-photo"
                                     loading="lazy">
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Before/After Comparison Slider (if more than 1 photo) --}}
        @if($allPhotos->count() >= 2)
        <hr>
        <h6 class="fw-bold mb-3"><i class="bi bi-arrow-left-right me-2"></i>Perbandingan Awal vs Terbaru</h6>
        <div class="comparison-container position-relative" style="max-width: 600px; margin: 0 auto;">
            <div class="comparison-slider" id="comparisonSlider">
                <div class="comparison-image-wrapper">
                    {{-- Before Image (First/Oldest) --}}
                    <img src="{{ asset('storage/' . $allPhotos->last()['path']) }}" 
                         alt="Sebelum" 
                         class="comparison-image comparison-before">
                    
                    {{-- After Image (Latest) --}}
                    <div class="comparison-after" id="comparisonAfter">
                        <img src="{{ asset('storage/' . $allPhotos->first()['path']) }}" 
                             alt="Sesudah" 
                             class="comparison-image">
                    </div>
                    
                    {{-- Slider Handle --}}
                    <div class="comparison-handle" id="comparisonHandle">
                        <div class="handle-line"></div>
                        <div class="handle-circle">
                            <i class="bi bi-arrows-expand"></i>
                        </div>
                        <div class="handle-line"></div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between mt-2">
                <span class="badge bg-secondary">Awal</span>
                <span class="badge bg-success">Terbaru</span>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.photo-timeline .timeline-item {
    position: relative;
}
.photo-timeline .timeline-marker {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 0.8rem;
}
.photo-timeline .timeline-photo {
    max-height: 200px;
    width: auto;
    object-fit: cover;
    border-radius: 8px;
    transition: transform 0.2s;
}
.photo-timeline .timeline-photo:hover {
    transform: scale(1.02);
}

/* Comparison Slider Styles */
.comparison-container {
    overflow: hidden;
    border-radius: 8px;
}
.comparison-image-wrapper {
    position: relative;
    width: 100%;
    aspect-ratio: 4/3;
}
.comparison-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.comparison-after {
    position: absolute;
    top: 0;
    left: 0;
    width: 50%;
    height: 100%;
    overflow: hidden;
}
.comparison-after img {
    width: 200%;
    max-width: none;
}
.comparison-handle {
    position: absolute;
    top: 0;
    left: 50%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    cursor: ew-resize;
    transform: translateX(-50%);
}
.comparison-handle .handle-line {
    flex: 1;
    width: 3px;
    background: white;
    box-shadow: 0 0 5px rgba(0,0,0,0.5);
}
.comparison-handle .handle-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.3);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const slider = document.getElementById('comparisonSlider');
    const afterDiv = document.getElementById('comparisonAfter');
    const handle = document.getElementById('comparisonHandle');
    
    if (!slider || !afterDiv || !handle) return;
    
    let isDragging = false;
    
    function updateSliderPosition(e) {
        if (!isDragging) return;
        
        const rect = slider.getBoundingClientRect();
        let x = (e.clientX || e.touches[0].clientX) - rect.left;
        x = Math.max(0, Math.min(x, rect.width));
        
        const percent = (x / rect.width) * 100;
        afterDiv.style.width = percent + '%';
        handle.style.left = percent + '%';
    }
    
    handle.addEventListener('mousedown', () => isDragging = true);
    handle.addEventListener('touchstart', () => isDragging = true);
    document.addEventListener('mouseup', () => isDragging = false);
    document.addEventListener('touchend', () => isDragging = false);
    document.addEventListener('mousemove', updateSliderPosition);
    document.addEventListener('touchmove', updateSliderPosition);
});
</script>
@else
<div class="alert alert-info">
    <i class="bi bi-info-circle me-2"></i>Belum ada foto progress yang diunggah untuk pesanan ini.
</div>
@endif
