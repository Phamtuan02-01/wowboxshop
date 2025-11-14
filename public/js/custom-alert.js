// Custom Alert Functions
let confirmCallback = null;

function showCustomAlert(message, type = 'success') {
    const overlay = document.getElementById('customAlertOverlay');
    const icon = document.getElementById('customAlertIcon');
    const messageEl = document.getElementById('customAlertMessage');
    
    if (!overlay || !icon || !messageEl) {
        console.error('Custom alert elements not found');
        return;
    }
    
    // Set icon based on type
    icon.className = 'custom-alert-icon ' + type;
    if (type === 'success') {
        icon.innerHTML = '<i class="fas fa-check"></i>';
    } else if (type === 'error') {
        icon.innerHTML = '<i class="fas fa-times"></i>';
    } else if (type === 'warning') {
        icon.innerHTML = '<i class="fas fa-exclamation"></i>';
    } else if (type === 'info') {
        icon.innerHTML = '<i class="fas fa-info"></i>';
    }
    
    messageEl.textContent = message;
    overlay.classList.add('show');
}

function closeCustomAlert() {
    const overlay = document.getElementById('customAlertOverlay');
    if (overlay) {
        overlay.classList.remove('show');
    }
}

function showCustomConfirm(message, callback) {
    const overlay = document.getElementById('customConfirmOverlay');
    const messageEl = document.getElementById('customConfirmMessage');
    
    if (!overlay || !messageEl) {
        console.error('Custom confirm elements not found');
        return;
    }
    
    messageEl.textContent = message;
    confirmCallback = callback;
    overlay.classList.add('show');
}

function closeCustomConfirm(result) {
    const overlay = document.getElementById('customConfirmOverlay');
    if (overlay) {
        overlay.classList.remove('show');
    }
    
    if (confirmCallback) {
        confirmCallback(result);
        confirmCallback = null;
    }
}

// Close on overlay click
document.addEventListener('DOMContentLoaded', function() {
    const overlays = document.querySelectorAll('.custom-alert-overlay');
    overlays.forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) {
                if (this.id === 'customAlertOverlay') {
                    closeCustomAlert();
                } else if (this.id === 'customConfirmOverlay') {
                    closeCustomConfirm(false);
                }
            }
        });
    });
});
