// Flash Messages - Toast Notifications
document.addEventListener('DOMContentLoaded', function() {
    const toasts = document.querySelectorAll('.flash-toast');
    
    toasts.forEach(toast => {
        // Auto hide after 5 seconds
        setTimeout(() => {
            closeToast(toast.querySelector('.btn-close'));
        }, 5000);
    });
});

function closeToast(button) {
    const toast = button.closest('.flash-toast');
    if (toast) {
        toast.classList.add('hiding');
        setTimeout(() => {
            toast.remove();
            
            // Remove container if no more toasts
            const container = document.getElementById('toastContainer');
            if (container && container.children.length === 0) {
                container.remove();
            }
        }, 300);
    }
}

// Function to show toast dynamically (for AJAX responses)
function showToast(type, message, title = null) {
    const container = document.getElementById('toastContainer') || createToastContainer();
    
    const iconMap = {
        success: 'fa-check',
        error: 'fa-times',
        warning: 'fa-exclamation',
        info: 'fa-info'
    };
    
    const titleMap = {
        success: 'Thành công!',
        error: 'Lỗi!',
        warning: 'Cảnh báo!',
        info: 'Thông tin!'
    };
    
    const toast = document.createElement('div');
    toast.className = `flash-toast toast-${type}`;
    toast.setAttribute('role', 'alert');
    
    toast.innerHTML = `
        <div class="toast-header">
            <div class="toast-icon">
                <i class="fas ${iconMap[type]}"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title">${title || titleMap[type]}</div>
            </div>
            <button type="button" class="btn-close" onclick="closeToast(this)" aria-label="Close">
                ×
            </button>
        </div>
        <div class="toast-body">
            ${message}
        </div>
        <div class="progress-bar"></div>
    `;
    
    container.appendChild(toast);
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        closeToast(toast.querySelector('.btn-close'));
    }, 5000);
}

function createToastContainer() {
    const container = document.createElement('div');
    container.className = 'toast-container';
    container.id = 'toastContainer';
    document.body.appendChild(container);
    return container;
}

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { showToast, closeToast };
}
