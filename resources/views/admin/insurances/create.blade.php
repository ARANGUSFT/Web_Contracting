@extends('admin.layouts.superadmin')

@section('title', "Add Insurance for {$sub->name}")

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="h3 mb-1 text-gray-800">
                <i class="fas fa-file-medical text-primary me-2"></i>Add New Insurance Policy
            </h1>
            <p class="mb-0 text-muted">For subcontractor: <span class="fw-bold">{{ $sub->name }} {{ $sub->last_name }}</span></p>
        </div>
        <a href="{{ route('superadmin.subcontractors.insurances.index') }}" class="btn btn-light">
            <i class="fas fa-times me-1"></i> Cancel
        </a>
    </div>

    <div class="card border-0 shadow-lg">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0">
                <i class="fas fa-info-circle me-2"></i>Insurance Details
            </h5>
        </div>
        <div class="card-body p-5">
            <form method="POST" action="{{ route('superadmin.subcontractors.insurances.store', $sub->id) }}" enctype="multipart/form-data">
                @csrf

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="expires_at" class="form-label fw-bold text-gray-700">
                            Expiration Date <span class="text-danger">*</span>
                        </label>
                        <input type="date" name="expires_at" id="expires_at" 
                               class="form-control border-2 border-gray-300 rounded-3 @error('expires_at') is-invalid @enderror" 
                               required>
                        @error('expires_at')
                            <div class="invalid-feedback d-block mt-1">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <label class="form-label fw-bold text-gray-700">
                            Insurance Documents <span class="text-danger">*</span>
                        </label>
                        <div class="card border-2 @error('file') border-danger @else border-dashed border-gray-300 @enderror rounded-3">
                            <div class="card-body text-center py-4">
                                <i class="fas fa-cloud-upload-alt fa-3x text-gray-400 mb-3"></i>
                                <h5 class="mb-2">Drag & drop files here or click to browse</h5>
                                <input type="file" name="file[]" id="file_upload" 
                                       class="form-control d-none @error('file') is-invalid @enderror" 
                                       multiple required>
                                <button type="button" class="btn btn-outline-primary mt-2" onclick="document.getElementById('file_upload').click()">
                                    <i class="fas fa-folder-open me-2"></i>Select Files
                                </button>
                                <p class="small text-muted mt-2 mb-0">
                                    Maximum file size: 5MB each. Supported formats: PDF, JPG, PNG
                                </p>
                            </div>
                        </div>
                        <div id="file-list" class="mt-2"></div>
                        @error('file')
                            <div class="invalid-feedback d-block mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                            </div>
                        @enderror
                        @error('file.*')
                            <div class="invalid-feedback d-block mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <label for="notes" class="form-label fw-bold text-gray-700">Additional Notes</label>
                        <textarea name="notes" id="notes" class="form-control border-2 border-gray-300 rounded-3" 
                                  rows="4" placeholder="Enter any additional notes about this insurance policy..."></textarea>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-12 text-end">
                        <button type="reset" class="btn btn-light me-3">
                            <i class="fas fa-undo me-1"></i> Reset Form
                        </button>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-1"></i> Save Insurance
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    // Display selected file names
    document.getElementById('file_upload').addEventListener('change', function(e) {
        const fileList = document.getElementById('file-list');
        fileList.innerHTML = '';
        
        if (this.files.length > 0) {
            const list = document.createElement('ul');
            list.className = 'list-group';
            
            for (let i = 0; i < this.files.length; i++) {
                const item = document.createElement('li');
                item.className = 'list-group-item d-flex justify-content-between align-items-center';
                
                const fileName = document.createElement('span');
                fileName.textContent = this.files[i].name;
                
                const fileSize = document.createElement('span');
                fileSize.className = 'badge bg-secondary rounded-pill';
                fileSize.textContent = formatFileSize(this.files[i].size);
                
                item.appendChild(fileName);
                item.appendChild(fileSize);
                list.appendChild(item);
            }
            
            fileList.appendChild(list);
        }
    });
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
</script>

@endsection