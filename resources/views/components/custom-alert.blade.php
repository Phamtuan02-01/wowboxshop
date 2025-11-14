<!-- Load Custom Alert CSS & JS -->
<link href="{{ asset('css/custom-alert.css') }}?v={{ time() }}" rel="stylesheet">
<script src="{{ asset('js/custom-alert.js') }}?v={{ time() }}"></script>

<!-- Custom Alert Overlay -->
<div class="custom-alert-overlay" id="customAlertOverlay">
    <div class="custom-alert-box">
        <div class="custom-alert-icon" id="customAlertIcon">
            <i class="fas fa-check"></i>
        </div>
        <div class="custom-alert-message" id="customAlertMessage"></div>
        <button class="custom-alert-button" onclick="closeCustomAlert()">OK</button>
    </div>
</div>

<!-- Custom Confirm Overlay -->
<div class="custom-alert-overlay" id="customConfirmOverlay">
    <div class="custom-alert-box">
        <div class="custom-alert-icon warning">
            <i class="fas fa-question"></i>
        </div>
        <div class="custom-alert-message" id="customConfirmMessage"></div>
        <div class="custom-confirm-buttons">
            <button class="custom-confirm-no" onclick="closeCustomConfirm(false)">Hủy</button>
            <button class="custom-confirm-yes" onclick="closeCustomConfirm(true)">Đồng ý</button>
        </div>
    </div>
</div>
