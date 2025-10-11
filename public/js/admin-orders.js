/**
 * Admin Order Management JavaScript
 */

$(document).ready(function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Auto-submit filter form on select change
    $('.auto-filter').on('change', function() {
        $('#filterForm').submit();
    });

    // Clear all filters
    $('#clearFilters').on('click', function(e) {
        e.preventDefault();
        $('#filterForm')[0].reset();
        $('#filterForm').submit();
    });

    // Export orders
    $('#exportOrders').on('click', function() {
        var form = $('#filterForm');
        var params = form.serialize();
        window.location.href = "{{ route('admin.orders.export') }}?" + params;
    });

    // Bulk actions
    $('#selectAll').on('change', function() {
        $('.order-checkbox').prop('checked', this.checked);
        updateBulkActions();
    });

    $('.order-checkbox').on('change', function() {
        updateBulkActions();
        
        // Update "select all" checkbox
        var totalCheckboxes = $('.order-checkbox').length;
        var checkedCheckboxes = $('.order-checkbox:checked').length;
        
        if (checkedCheckboxes === 0) {
            $('#selectAll').prop('indeterminate', false).prop('checked', false);
        } else if (checkedCheckboxes === totalCheckboxes) {
            $('#selectAll').prop('indeterminate', false).prop('checked', true);
        } else {
            $('#selectAll').prop('indeterminate', true);
        }
    });

    function updateBulkActions() {
        var checkedCount = $('.order-checkbox:checked').length;
        var bulkActions = $('#bulkActions');
        
        if (checkedCount > 0) {
            bulkActions.removeClass('d-none');
            $('#selectedCount').text(checkedCount);
        } else {
            bulkActions.addClass('d-none');
        }
    }

    // Bulk status update
    $('#bulkStatusUpdate').on('click', function() {
        var selectedOrders = [];
        $('.order-checkbox:checked').each(function() {
            selectedOrders.push($(this).val());
        });

        if (selectedOrders.length === 0) {
            alert('Vui lòng chọn ít nhất một đơn hàng');
            return;
        }

        $('#bulkStatusModal').modal('show');
        $('#selectedOrdersInput').val(selectedOrders.join(','));
    });

    // Handle bulk status form submission
    $('#bulkStatusForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        var submitBtn = $(this).find('button[type="submit"]');
        
        submitBtn.addClass('loading').prop('disabled', true);
        
        $.ajax({
            url: "{{ route('admin.orders.bulk-status') }}",
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                location.reload();
            },
            error: function(xhr) {
                alert('Có lỗi xảy ra: ' + xhr.responseText);
                submitBtn.removeClass('loading').prop('disabled', false);
            }
        });
    });

    // Quick status update from dropdown
    $('.quick-status-update').on('click', function(e) {
        e.preventDefault();
        
        var orderId = $(this).data('order-id');
        var newStatus = $(this).data('status');
        var orderRow = $(this).closest('tr');
        
        if (confirm('Bạn có chắc chắn muốn cập nhật trạng thái đơn hàng này?')) {
            updateOrderStatus(orderId, newStatus, orderRow);
        }
    });

    function updateOrderStatus(orderId, newStatus, orderRow) {
        $.ajax({
            url: `/admin/orders/${orderId}/status`,
            method: 'PATCH',
            data: {
                trang_thai: newStatus,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                orderRow.addClass('loading');
            },
            success: function(response) {
                // Update status badge
                var statusCell = orderRow.find('.order-status');
                var badgeClass = 'badge status-' + newStatus.toLowerCase().replace(/\s+/g, '-');
                statusCell.html(`<span class="badge ${badgeClass}">${newStatus}</span>`);
                
                // Show success message
                showAlert('success', 'Cập nhật trạng thái thành công!');
                
                orderRow.removeClass('loading');
            },
            error: function(xhr) {
                showAlert('danger', 'Có lỗi xảy ra: ' + xhr.responseText);
                orderRow.removeClass('loading');
            }
        });
    }

    // Search functionality
    var searchTimeout;
    $('#searchInput').on('input', function() {
        clearTimeout(searchTimeout);
        var query = $(this).val();
        
        searchTimeout = setTimeout(function() {
            if (query.length >= 2 || query.length === 0) {
                searchOrders(query);
            }
        }, 300);
    });

    function searchOrders(query) {
        var currentUrl = new URL(window.location);
        
        if (query) {
            currentUrl.searchParams.set('search', query);
        } else {
            currentUrl.searchParams.delete('search');
        }
        
        window.location.href = currentUrl.toString();
    }

    // Print functionality
    window.printOrder = function() {
        window.print();
    };

    // Date range picker (if you want to add this feature)
    if (typeof daterangepicker !== 'undefined') {
        $('#dateRange').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
                format: 'DD/MM/YYYY'
            }
        });

        $('#dateRange').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            $('#filterForm').submit();
        });

        $('#dateRange').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            $('#filterForm').submit();
        });
    }

    // Real-time updates (if you implement WebSocket/Server-Sent Events)
    function initializeRealTimeUpdates() {
        // This is where you would initialize WebSocket connection
        // for real-time order status updates
        
        /*
        const eventSource = new EventSource('/admin/orders/stream');
        
        eventSource.onmessage = function(event) {
            const data = JSON.parse(event.data);
            
            if (data.type === 'order_status_updated') {
                updateOrderRow(data.orderId, data.newStatus);
            }
        };
        
        eventSource.onerror = function(event) {
            console.error('EventSource failed:', event);
        };
        */
    }

    // Utility functions
    function showAlert(type, message) {
        var alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        $('.content-header').after(alertHtml);
        
        // Auto-hide after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 5000);
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(amount);
    }

    function getStatusBadgeClass(status) {
        const statusMap = {
            'Chờ xử lý': 'status-cho-xu-ly',
            'Đang xử lý': 'status-dang-xu-ly',
            'Đang giao': 'status-dang-giao',
            'Đã giao': 'status-da-giao',
            'Đã hủy': 'status-da-huy'
        };
        
        return statusMap[status] || 'bg-secondary';
    }

    // Initialize any additional features
    initializeRealTimeUpdates();
});

// Global functions for inline event handlers
window.updateOrderStatus = function(status) {
    $('#newStatus').val(status);
    $('#updateStatusModal').modal('show');
};

window.cancelOrder = function() {
    $('#cancelOrderModal').modal('show');
};

window.viewOrderDetails = function(orderId) {
    window.location.href = `/admin/orders/${orderId}`;
};

// Export functionality
window.exportToCSV = function() {
    var form = document.getElementById('filterForm');
    var formData = new FormData(form);
    formData.append('format', 'csv');
    
    var params = new URLSearchParams(formData).toString();
    window.location.href = '/admin/orders/export?' + params;
};

window.exportToExcel = function() {
    var form = document.getElementById('filterForm');
    var formData = new FormData(form);
    formData.append('format', 'excel');
    
    var params = new URLSearchParams(formData).toString();
    window.location.href = '/admin/orders/export?' + params;
};