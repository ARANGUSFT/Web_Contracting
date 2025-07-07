@extends('admin.layouts.superadmin')

@section('title', 'Edit Insurance')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-file-contract text-primary me-2"></i>Edit Insurance Policy
        </h1>
        <a href="{{ route('superadmin.subcontractors.insurances.index') }}" class="btn btn-light">
            <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>

    <div class="card border-0 shadow-lg">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0">
                <i class="fas fa-pencil-alt me-2"></i>Edit Insurance Details
            </h5>
        </div>
        <div class="card-body p-5">
            <form method="POST" action="{{ route('superadmin.subcontractors.insurances.update', [$sub->id, $ins->id]) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="expires_at" class="form-label fw-bold text-gray-700">Expiration Date <span class="text-danger">*</span></label>
                        <input type="date" name="expires_at" id="expires_at" class="form-control border-2 border-gray-300 rounded-3" 
                               value="{{ old('expires_at', $ins->expires_at) }}" required>
                    </div>
                </div>

                @if(is_array($ins->file) && count($ins->file))
                    <div class="row mb-4">
                        <div class="col-12">
                            <label class="form-label fw-bold text-gray-700">Current Files</label>
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    @foreach($ins->file as $index => $f)
                                        @if(isset($f['path'], $f['original_name']))
                                            <div class="d-flex align-items-center justify-content-between mb-3 p-3 bg-light rounded-3">
                                                <div>
                                                    <a href="{{ Storage::url($f['path']) }}" target="_blank" class="text-decoration-none">
                                                        <i class="fas fa-file-pdf text-danger me-2"></i>
                                                        <span class="text-primary">{{ $f['original_name'] }}</span>
                                                    </a>
                                                </div>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="delete_files[]" 
                                                           value="{{ $f['path'] }}" id="delete_{{ $index }}" style="width: 2.5em; height: 1.25em;">
                                                    <label class="form-check-label small text-danger ms-2" for="delete_{{ $index }}">
                                                        Delete
                                                    </label>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row mb-4">
                    <div class="col-12">
                        <label for="file_upload" class="form-label fw-bold text-gray-700">Add More Files <span class="text-muted">(optional)</span></label>
                        <div class="card border-2 border-dashed border-gray-300 rounded-3">
                            <div class="card-body text-center">
                                <i class="fas fa-cloud-upload-alt fa-3x text-gray-400 mb-3"></i>
                                <h5 class="mb-2">Drag & drop files here or click to browse</h5>
                                <input type="file" name="file[]" id="file_upload" class="form-control d-none" multiple accept=".pdf,.jpg,.png">
                                <button type="button" class="btn btn-outline-primary mt-2" onclick="document.getElementById('file_upload').click()">
                                    <i class="fas fa-folder-open me-2"></i>Select Files
                                </button>
                                <p class="small text-muted mt-2 mb-0">Maximum file size: 5MB each. Supported formats: PDF, JPG, PNG</p>
                            </div>
                        </div>
                        <div id="file-list" class="mt-2"></div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <label for="notes" class="form-label fw-bold text-gray-700">Notes</label>
                        <textarea name="notes" id="notes" class="form-control border-2 border-gray-300 rounded-3" 
                                  rows="4" placeholder="Add any additional notes here...">{{ old('notes', $ins->notes) }}</textarea>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-12 text-end">
                        <button type="reset" class="btn btn-light me-3">
                            <i class="fas fa-undo me-1"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-1"></i> Update Insurance
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
