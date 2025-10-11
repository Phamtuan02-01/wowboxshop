<!-- Toast Container - Góc dưới phải -->
<div class="toast-container" id="toastContainer">
    @if(session('success'))
        <div class="flash-toast toast-success" role="alert">
            <div class="toast-header">
                <div class="toast-icon">
                    <i class="fas fa-check"></i>
                </div>
                <div class="toast-content">
                    <div class="toast-title">Thành công!</div>
                </div>
                <button type="button" class="btn-close" onclick="closeToast(this)" aria-label="Close">
                    ×
                </button>
            </div>
            <div class="toast-body">
                {{ session('success') }}
            </div>
            <div class="progress-bar"></div>
        </div>
    @endif

    @if(session('error'))
        <div class="flash-toast toast-error" role="alert">
            <div class="toast-header">
                <div class="toast-icon">
                    <i class="fas fa-times"></i>
                </div>
                <div class="toast-content">
                    <div class="toast-title">Lỗi!</div>
                </div>
                <button type="button" class="btn-close" onclick="closeToast(this)" aria-label="Close">
                    ×
                </button>
            </div>
            <div class="toast-body">
                {{ session('error') }}
            </div>
            <div class="progress-bar"></div>
        </div>
    @endif

    @if(session('warning'))
        <div class="flash-toast toast-warning" role="alert">
            <div class="toast-header">
                <div class="toast-icon">
                    <i class="fas fa-exclamation"></i>
                </div>
                <div class="toast-content">
                    <div class="toast-title">Cảnh báo!</div>
                </div>
                <button type="button" class="btn-close" onclick="closeToast(this)" aria-label="Close">
                    ×
                </button>
            </div>
            <div class="toast-body">
                {{ session('warning') }}
            </div>
            <div class="progress-bar"></div>
        </div>
    @endif

    @if(session('info'))
        <div class="flash-toast toast-info" role="alert">
            <div class="toast-header">
                <div class="toast-icon">
                    <i class="fas fa-info"></i>
                </div>
                <div class="toast-content">
                    <div class="toast-title">Thông tin!</div>
                </div>
                <button type="button" class="btn-close" onclick="closeToast(this)" aria-label="Close">
                    ×
                </button>
            </div>
            <div class="toast-body">
                {{ session('info') }}
            </div>
            <div class="progress-bar"></div>
        </div>
    @endif
</div>