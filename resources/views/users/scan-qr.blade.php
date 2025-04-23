<x-app-layout>
    @if(session('access_allowed'))
        <div class="fixed-top w-100">
            <div class="alert alert-success alert-dismissible fade show mx-auto mt-3" style="max-width: 800px;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle fa-2x me-3 text-success"></i>
                    <div>
                        <h5 class="mb-1">Доступ разрешен!</h5>
                        <p class="mb-0">
                            {{ session('access_allowed') }}
                            @if(session('visit_time'))
                                <br><small class="text-muted">Время: {{ session('visit_time') }}</small>
                            @endif
                        </p>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="fixed-top w-100">
            <div class="alert alert-danger alert-dismissible fade show mx-auto mt-3" style="max-width: 800px;">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div class="fixed-top w-100">
            <div class="alert alert-warning alert-dismissible fade show mx-auto mt-3" style="max-width: 800px;">
                <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">



                    <h2 class="text-2xl font-semibold mb-4">Сканирование QR-кода</h2>

                    <div class="mb-6">
                        <label class="block text-gray-700 mb-2">Или введите UUID вручную:</label>
                        <form method="POST" action="{{ route('scan.qr.process') }}">
                            @csrf
                            <div class="flex">
                                <input type="text" name="uuid" class="form-input rounded-l-md w-full"
                                       placeholder="Введите UUID пользователя" required>
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-r-md">
                                    Поиск
                                </button>
                            </div>
                        </form>
                    </div>

                    <div id="scanner-container" class="text-center">
                        <video id="qr-video" width="300" height="300" class="mx-auto border"></video>
                        <div id="qr-result" class="mt-4 hidden">
                            <p class="text-green-500">QR-код распознан!</p>
                            <form id="qr-form" method="POST" action="{{ route('scan.qr.process') }}">
                                @csrf
                                <input type="hidden" name="uuid" id="scanned-uuid">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const video = document.getElementById('qr-video');
                const qrResult = document.getElementById('qr-result');
                const scannedUuid = document.getElementById('scanned-uuid');
                const qrForm = document.getElementById('qr-form');

                navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
                    .then(function(stream) {
                        video.srcObject = stream;
                        video.play();
                        requestAnimationFrame(tick);
                    })
                    .catch(function(err) {
                        console.error("Ошибка доступа к камере:", err);
                    });

                function tick() {
                    if (video.readyState === video.HAVE_ENOUGH_DATA) {
                        const canvas = document.createElement('canvas');
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                        const code = jsQR(imageData.data, imageData.width, imageData.height);

                        if (code) {
                            qrResult.classList.remove('hidden');
                            scannedUuid.value = code.data;
                            qrForm.submit();
                        }
                    }
                    requestAnimationFrame(tick);
                }
            });
        </script>
    @endpush
</x-app-layout>
