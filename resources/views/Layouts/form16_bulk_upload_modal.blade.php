<!-- Form 16 Bulk Upload Modal -->
<div class="modal fade" id="form16BulkUploadModal" tabindex="-1" role="dialog" aria-labelledby="form16BulkUploadModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="form16BulkUploadModalLabel">
          <i class="fas fa-file-upload"></i> Form 16 Bulk Upload
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form16BulkUploadForm" enctype="multipart/form-data">
          <!-- File Upload Section -->
          <div class="form-group">
            <label for="form16Files" class="form-label"><strong>Upload PDF Files:</strong></label>
            <input type="file" class="form-control-file" id="form16Files" name="form16_files[]" multiple accept=".pdf" required>
            <small class="form-text text-muted">
              <i class="fas fa-info-circle"></i> Only PDF files are allowed. You can select multiple files.
            </small>
          </div>

          <!-- File Preview Area -->
          <div class="form-group">
            <label class="form-label"><strong>Selected Files:</strong></label>
            <div id="filePreviewArea" class="border p-3" style="min-height: 100px; background-color: #f8f9fa;">
              <p class="text-muted text-center">No files selected</p>
            </div>
          </div>


        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="startUploadBtn" onclick="startBulkUpload()">
          <i class="fas fa-upload"></i> Upload
        </button>
      </div>
    </div>
  </div>
</div>

<script>
// File selection handler
document.getElementById('form16Files').addEventListener('change', function(e) {
    const files = e.target.files;
    const previewArea = document.getElementById('filePreviewArea');
    
    if (files.length === 0) {
        previewArea.innerHTML = '<p class="text-muted text-center">No files selected</p>';
        return;
    }
    
    let fileListHtml = '<div class="row">';
    let hasErrors = false;
    
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const fileSize = (file.size / 1024 / 1024).toFixed(2); // Convert to MB
        const filename = file.name;
        const validationResult = validateFilenameClient(filename);
        
        if (!validationResult.valid) {
            hasErrors = true;
        }
        
        const cardClass = validationResult.valid ? 'border-success' : 'border-danger';
        const iconClass = validationResult.valid ? 'text-success' : 'text-danger';
        const statusIcon = validationResult.valid ? 'fas fa-check-circle text-success' : 'fas fa-times-circle text-danger';
        const formTypeLabel = validationResult.valid ? `<small class="badge badge-info">${validationResult.formType}</small>` : '';
        
        fileListHtml += `
            <div class="col-md-6 mb-2">
                <div class="card ${cardClass}">
                    <div class="card-body p-2">
                        <h6 class="card-title mb-1 text-truncate" title="${filename}">
                            <i class="fas fa-file-pdf ${iconClass}"></i> ${filename}
                            <i class="${statusIcon} float-right"></i>
                        </h6>
                        <small class="text-muted">${fileSize} MB</small> ${formTypeLabel}
                        ${!validationResult.valid ? '<br><small class="text-danger">Invalid filename format</small>' : ''}
                    </div>
                </div>
            </div>
        `;
    }
    fileListHtml += '</div>';
    
    if (hasErrors) {
        fileListHtml += '<div class="alert alert-warning mt-2"><i class="fas fa-exclamation-triangle"></i> Some files have invalid filename formats. Please rename them according to the required format.</div>';
    }
    
    previewArea.innerHTML = fileListHtml;
});

// Client-side filename validation - auto-detects form type
function validateFilenameClient(filename) {
    // Remove extension
    const filenameWithoutExt = filename.replace(/\.[^/.]+$/, "").toUpperCase();
    
    // Check for Form B format first (contains PARTB)
    const formBPattern = /^[A-Z0-9]{10}_PARTB_\d{4}-\d{2}$/;
    if (formBPattern.test(filenameWithoutExt)) {
        return {
            valid: true,
            formType: 'Form 16 Part B'
        };
    }
    
    // Check for Form A format (no PARTB)
    const formAPattern = /^[A-Z0-9]{10}_\d{4}-\d{2}$/;
    if (formAPattern.test(filenameWithoutExt)) {
        return {
            valid: true,
            formType: 'Form 16 Part A'
        };
    }
    
    return {
        valid: false,
        formType: null
    };
}

// Function to open the modal
function openForm16BulkModal() {
    $('#form16BulkUploadModal').modal('show');
    // Reset form when modal opens
    document.getElementById('form16BulkUploadForm').reset();
    document.getElementById('filePreviewArea').innerHTML = '<p class="text-muted text-center">No files selected</p>';
    document.getElementById('startUploadBtn').disabled = false;
    document.getElementById('startUploadBtn').innerHTML = '<i class="fas fa-upload"></i> Start Upload';
}

// Function to start bulk upload
function startBulkUpload() {
    const files = document.getElementById('form16Files').files;
    
    if (files.length === 0) {
        toastr.error('Please select at least one PDF file to upload.');
        return;
    }
    
    // Validate all filenames before uploading
    let hasInvalidFiles = false;
    let invalidFiles = [];
    
    for (let i = 0; i < files.length; i++) {
        const filename = files[i].name;
        const validationResult = validateFilenameClient(filename);
        if (!validationResult.valid) {
            hasInvalidFiles = true;
            invalidFiles.push(filename);
        }
    }
    
    if (hasInvalidFiles) {
        toastr.error('Some files have invalid filename formats. Please rename them according to the required format.');
        return;
    }
    
    // Disable upload button and show loading state
    const uploadBtn = document.getElementById('startUploadBtn');
    uploadBtn.disabled = true;
    uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking files...';
    
    // First check if files already exist in database
    checkExistingFiles(files, function(existingFiles) {
        console.log('Callback received existing files:', existingFiles);
        
        if (existingFiles && existingFiles.length > 0) {
            // Files already exist, show error
            console.log('Found existing files, showing error');
            let errorMessage = 'The following files already exist in the database:\n';
            existingFiles.forEach(function(file) {
                errorMessage += '• ' + file + '\n';
            });
            errorMessage += '\nPlease select different files.';
            
            toastr.error(errorMessage);
            
            // Re-enable upload button
            uploadBtn.disabled = false;
            uploadBtn.innerHTML = '<i class="fas fa-upload"></i> Upload';
            return;
        }
        
        // No existing files found, proceed with upload
        uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
        
        // Create FormData
        const formData = new FormData();
        
        for (let i = 0; i < files.length; i++) {
            formData.append('form16_files[]', files[i]);
        }
        
        // Add CSRF token
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        
        // Upload files
    $.ajax({
        url: form16_bulk_upload_url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.status === 'success') {
                let message = `Upload completed: ${response.success_count} successful, ${response.failed_count} failed`;
                
                // Show detailed results if there are failures
                if (response.failed_count > 0 && response.results) {
                    let failedFiles = response.results.filter(result => result.status === 'failed');
                    let failedMessage = 'Failed files:\n';
                    failedFiles.forEach(result => {
                        failedMessage += `• ${result.filename}: ${result.message}\n`;
                    });
                    toastr.warning(message + '\n\n' + failedMessage);
                } else {
                    toastr.success(message);
                }
                
                // Reset the modal completely
                document.getElementById('form16BulkUploadForm').reset();
                document.getElementById('filePreviewArea').innerHTML = '<p class="text-muted text-center">No files selected</p>';
                uploadBtn.disabled = false;
                uploadBtn.innerHTML = '<i class="fas fa-upload"></i> Start Upload';
                
                // Close modal only if all uploads were successful
                if (response.failed_count === 0) {
                    $('#form16BulkUploadModal').modal('hide');
                }
                
                // Refresh the table if any upload was successful
                if (response.success_count > 0) {
                    if (typeof get_ambassadors === 'function') {
                        get_ambassadors();
                    } else {
                        // Reload page if function doesn't exist
                        setTimeout(() => {
                            location.reload();
                        }, 500);
                    }
                }
            } else {
                // Show error message
                toastr.error(response.message || 'Upload failed. Please try again.');
                
                // Re-enable upload button
                uploadBtn.disabled = false;
                uploadBtn.innerHTML = '<i class="fas fa-upload"></i> Start Upload';
            }
        },
        error: function(xhr, status, error) {
            // Show error message
            toastr.error('Upload error occurred. Please try again.');
            
            // Re-enable upload button
            uploadBtn.disabled = false;
            uploadBtn.innerHTML = '<i class="fas fa-upload"></i> Start Upload';
        }
    });
    }); // End of checkExistingFiles callback
}

// Function to check if files already exist in database
function checkExistingFiles(files, callback) {
    // Check if the URL is defined
    if (typeof form16_check_existing_url === 'undefined') {
        console.log('form16_check_existing_url is not defined');
        callback([]);
        return;
    }
    
    console.log('Checking files:', files.length, 'files');
    console.log('Check URL:', form16_check_existing_url);
    
    // Create FormData with filenames to check
    const checkData = new FormData();
    
    for (let i = 0; i < files.length; i++) {
        checkData.append('filenames[]', files[i].name);
        console.log('Adding file to check:', files[i].name);
    }
    
    // Add CSRF token
    checkData.append('_token', $('meta[name="csrf-token"]').attr('content'));
    
    // Make AJAX request to check existing files
    $.ajax({
        url: form16_check_existing_url,
        type: 'POST',
        data: checkData,
        processData: false,
        contentType: false,
        success: function(response) {
            console.log('Check response:', response);
            if (response.status === 'success') {
                console.log('Existing files found:', response.existing_files);
                // Return list of existing files
                callback(response.existing_files || []);
            } else {
                console.log('Check failed, response:', response);
                // If check fails, assume no existing files
                callback([]);
            }
        },
        error: function(xhr, status, error) {
            console.log('AJAX Error checking existing files:');
            console.log('Status:', status);
            console.log('Error:', error);
            console.log('Response:', xhr.responseText);
            
            // Show error to user
            toastr.error('Error checking existing files. Proceeding with upload.');
            
            // If check fails, assume no existing files
            callback([]);
        }
    });
}

// Reset modal when it's closed to prevent old files from showing
$('#form16BulkUploadModal').on('hidden.bs.modal', function () {
    // Reset form completely
    document.getElementById('form16BulkUploadForm').reset();
    document.getElementById('filePreviewArea').innerHTML = '<p class="text-muted text-center">No files selected</p>';
    
    // Reset button state completely
    const uploadBtn = document.getElementById('startUploadBtn');
    uploadBtn.disabled = false;
    uploadBtn.innerHTML = '<i class="fas fa-upload"></i> Start Upload';
    
    // Clear any file input values
    document.getElementById('form16Files').value = '';
});

// Also reset when modal is opened to ensure clean state
$('#form16BulkUploadModal').on('show.bs.modal', function () {
    // Reset form completely
    document.getElementById('form16BulkUploadForm').reset();
    document.getElementById('filePreviewArea').innerHTML = '<p class="text-muted text-center">No files selected</p>';
    
    // Reset button state completely
    const uploadBtn = document.getElementById('startUploadBtn');
    uploadBtn.disabled = false;
    uploadBtn.innerHTML = '<i class="fas fa-upload"></i> Start Upload';
    
    // Clear any file input values
    document.getElementById('form16Files').value = '';
});
</script>