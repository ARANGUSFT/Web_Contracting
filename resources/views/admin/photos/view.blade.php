@extends('admin.layouts.superadmin')

@section('title', 'Project Photos - ' . ($tipo === 'job_request' ? 'Job Request' : 'Emergency') . ' #' . $id)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

  {{-- Encabezado --}}
  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
    <div>
      <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
        <span class="capitalize">{{ $tipo === 'job_request' ? 'Job Request' : 'Emergency' }}</span> #{{ $id }} Photos
      </h1>
      <p class="text-sm text-gray-500 mt-1">
        {{ $fotos->count() }} {{ Str::plural('photo', $fotos->count()) }} available
      </p>
    </div>

    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
      <a href="{{ route('superadmin.photos.projects') }}"
         class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Projects
      </a>

      {{-- Botón: generar/reusar link público --}}
      <form method="POST" action="{{ route('superadmin.photos.share') }}" class="inline-flex">
        @csrf
        <input type="hidden" name="tipo" value="{{ $tipo }}">
        <input type="hidden" name="id" value="{{ $id }}">
        <button type="submit"
                class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
          <svg class="-ml-1 mr-2 h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 10l4.553-4.553a1.5 1.5 0 10-2.121-2.121L12.88 6.877M9 14l-4.553 4.553a1.5 1.5 0 102.121 2.121L11.12 17.123M7 12h10"/>
          </svg>
          Share public link
        </button>
      </form>
      
      {{-- Botón: Descargar todas las fotos --}}
      <button type="button" id="downloadAllBtn"
              class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
              {{ $fotos->isEmpty() ? 'disabled' : '' }}>
        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
        Download All
      </button>
    </div>
  </div>

  {{-- Mensaje flash y/o URL pública para copiar --}}
  @if(session('status'))
    <div class="mb-4 p-4 rounded-md bg-green-50 border border-green-200">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm font-medium text-green-800">{{ session('status') }}</p>
        </div>
      </div>
    </div>
  @endif

  @php $publicUrl = session('share_url') ?? ($shareUrl ?? null); @endphp
  @if($publicUrl)
    <div class="mb-6 p-4 rounded-md border border-blue-200 bg-blue-50">
      <div class="flex flex-col sm:flex-row sm:items-center gap-3">
        <div class="flex-1">
          <p class="text-sm font-medium text-blue-800 mb-1">Public Share URL</p>
          <div class="flex items-center gap-2">
            <input id="shareUrl"
                   class="flex-1 bg-white rounded px-3 py-2 border border-blue-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                   readonly
                   value="{{ $publicUrl }}">
            <button type="button"
                    onclick="copyToClipboard('shareUrl')"
                    class="px-3 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition-colors duration-200 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
              Copy
            </button>
          </div>
        </div>
        <a href="{{ $publicUrl }}" target="_blank"
           class="inline-flex items-center px-3 py-2 rounded-md border border-blue-300 text-blue-700 bg-white hover:bg-blue-50 transition-colors duration-200 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
          <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
          </svg>
          Open
        </a>
      </div>
    </div>
  @endif

  {{-- Información Detallada del Proyecto - ACTUALIZADA CON CAMPOS REALES --}}
  <div class="mb-8 bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
      <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
        @if($tipo === 'job_request')
          <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
          </svg>
        @else
          <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
          </svg>
        @endif
        Project Details
      </h2>
    </div>
    
    <div class="p-6">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Información Principal --}}
        <div class="space-y-6">
          {{-- Información Básica --}}
          <div>
            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Basic Information</h3>
            <div class="space-y-3">
              @if(isset($projectInfo->company_name) && $projectInfo->company_name)
              <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-600">Company:</span>
                <span class="text-sm text-gray-900">{{ $projectInfo->company_name }}</span>
              </div>
              @endif

              @if(isset($projectInfo->job_number_name) && $projectInfo->job_number_name)
              <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-600">Job Number:</span>
                <span class="text-sm text-gray-900">{{ $projectInfo->job_number_name }}</span>
              </div>
              @endif

              @if(isset($projectInfo->type_of_supplement) && $projectInfo->type_of_supplement)
              <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-600">Type:</span>
                <span class="text-sm text-gray-900">{{ $projectInfo->type_of_supplement }}</span>
              </div>
              @endif

              @if(isset($projectInfo->status) && $projectInfo->status)
              <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-600">Status:</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                  {{ $projectInfo->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                  {{ $projectInfo->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                  {{ $projectInfo->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                  {{ $projectInfo->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                  {{ ucfirst($projectInfo->status) }}
                </span>
              </div>
              @endif
            </div>
          </div>

          {{-- Información del Cliente/Contacto --}}
          <div>
            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">
              {{ $tipo === 'job_request' ? 'Customer Information' : 'Contact Information' }}
            </h3>
            <div class="space-y-3">
              {{-- Para JobRequest: Información del cliente --}}
              @if($tipo === 'job_request' && ((isset($projectInfo->customer_first_name) && $projectInfo->customer_first_name) || (isset($projectInfo->customer_last_name) && $projectInfo->customer_last_name)))
              <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-600">Customer Name:</span>
                <span class="text-sm text-gray-900">
                  {{ ($projectInfo->customer_first_name ?? '') . ' ' . ($projectInfo->customer_last_name ?? '') }}
                </span>
              </div>
              @endif

              {{-- Para JobRequest: Teléfono del cliente --}}
              @if($tipo === 'job_request' && isset($projectInfo->customer_phone) && $projectInfo->customer_phone)
              <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-600">Customer Phone:</span>
                <a href="tel:{{ $projectInfo->customer_phone }}" class="text-sm text-blue-600 hover:text-blue-800">
                  {{ $projectInfo->customer_phone }}
                </a>
              </div>
              @endif

              {{-- Para Emergencies: Email de contacto --}}
              @if($tipo === 'emergency' && isset($projectInfo->company_contact_email) && $projectInfo->company_contact_email)
              <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-600">Contact Email:</span>
                <a href="mailto:{{ $projectInfo->company_contact_email }}" class="text-sm text-blue-600 hover:text-blue-800">
                  {{ $projectInfo->company_contact_email }}
                </a>
              </div>
              @endif

              {{-- Para JobRequest: Representante de la compañía --}}
              @if($tipo === 'job_request' && isset($projectInfo->company_rep) && $projectInfo->company_rep)
              <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-600">Company Rep:</span>
                <span class="text-sm text-gray-900">{{ $projectInfo->company_rep }}</span>
              </div>
              @endif

              {{-- Para JobRequest: Teléfono del representante --}}
              @if($tipo === 'job_request' && isset($projectInfo->company_rep_phone) && $projectInfo->company_rep_phone)
              <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-600">Rep Phone:</span>
                <a href="tel:{{ $projectInfo->company_rep_phone }}" class="text-sm text-blue-600 hover:text-blue-800">
                  {{ $projectInfo->company_rep_phone }}
                </a>
              </div>
              @endif

              {{-- Para JobRequest: Email del representante --}}
              @if($tipo === 'job_request' && isset($projectInfo->company_rep_email) && $projectInfo->company_rep_email)
              <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-600">Rep Email:</span>
                <a href="mailto:{{ $projectInfo->company_rep_email }}" class="text-sm text-blue-600 hover:text-blue-800">
                  {{ $projectInfo->company_rep_email }}
                </a>
              </div>
              @endif
            </div>
          </div>
        </div>

        {{-- Información Secundaria --}}
        <div class="space-y-6">
          {{-- Ubicación --}}
          <div>
            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Location</h3>
            <div class="space-y-3">
              {{-- Dirección para JobRequest --}}
              @if($tipo === 'job_request' && isset($projectInfo->job_address_street) && $projectInfo->job_address_street)
              <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-600">Address:</span>
                <span class="text-sm text-gray-900 text-right">
                  {{ $projectInfo->job_address_street }}
                  @if(isset($projectInfo->job_address_street_line2) && $projectInfo->job_address_street_line2)
                  <br>{{ $projectInfo->job_address_street_line2 }}
                  @endif
                </span>
              </div>
              @endif

              {{-- Dirección para Emergencies --}}
              @if($tipo === 'emergency' && isset($projectInfo->job_address) && $projectInfo->job_address)
              <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-600">Address:</span>
                <span class="text-sm text-gray-900 text-right">
                  {{ $projectInfo->job_address }}
                  @if(isset($projectInfo->job_address_line2) && $projectInfo->job_address_line2)
                  <br>{{ $projectInfo->job_address_line2 }}
                  @endif
                </span>
              </div>
              @endif

              {{-- Ciudad --}}
              @if(isset($projectInfo->job_address_city) && $projectInfo->job_address_city)
              <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-600">City:</span>
                <span class="text-sm text-gray-900">{{ $projectInfo->job_address_city }}</span>
              </div>
              @elseif(isset($projectInfo->job_city) && $projectInfo->job_city)
              <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-600">City:</span>
                <span class="text-sm text-gray-900">{{ $projectInfo->job_city }}</span>
              </div>
              @endif

              {{-- Estado --}}
              @if(isset($projectInfo->job_address_state) && $projectInfo->job_address_state)
              <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-600">State:</span>
                <span class="text-sm text-gray-900">{{ $projectInfo->job_address_state }}</span>
              </div>
              @elseif(isset($projectInfo->job_state) && $projectInfo->job_state)
              <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-600">State:</span>
                <span class="text-sm text-gray-900">{{ $projectInfo->job_state }}</span>
              </div>
              @endif

              {{-- ZIP Code --}}
              @if(isset($projectInfo->job_address_zip) && $projectInfo->job_address_zip)
              <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-600">ZIP Code:</span>
                <span class="text-sm text-gray-900">{{ $projectInfo->job_address_zip }}</span>
              </div>
              @elseif(isset($projectInfo->job_zip) && $projectInfo->job_zip)
              <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-600">ZIP Code:</span>
                <span class="text-sm text-gray-900">{{ $projectInfo->job_zip }}</span>
              </div>
              @endif
            </div>
          </div>

          {{-- Fechas --}}
          <div>
            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Dates</h3>
            <div class="space-y-3">
              @if(isset($projectInfo->install_date_requested) && $projectInfo->install_date_requested)
              <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-600">Requested Date:</span>
                <span class="text-sm text-gray-900">
                  {{ \Carbon\Carbon::parse($projectInfo->install_date_requested)->format('M d, Y') }}
                </span>
              </div>
              @endif

              @if(isset($projectInfo->delivery_date) && $projectInfo->delivery_date)
              <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-600">Delivery Date:</span>
                <span class="text-sm text-gray-900">
                  {{ \Carbon\Carbon::parse($projectInfo->delivery_date)->format('M d, Y') }}
                </span>
              </div>
              @endif

              @if(isset($projectInfo->date_submitted) && $projectInfo->date_submitted)
              <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-600">Submitted:</span>
                <span class="text-sm text-gray-900">
                  {{ \Carbon\Carbon::parse($projectInfo->date_submitted)->format('M d, Y') }}
                </span>
              </div>
              @endif

              @if(isset($projectInfo->created_at) && $projectInfo->created_at)
              <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-600">Created:</span>
                <span class="text-sm text-gray-900">
                  {{ \Carbon\Carbon::parse($projectInfo->created_at)->format('M d, Y - H:i') }}
                </span>
              </div>
              @endif
            </div>
          </div>
        </div>
      </div>

      {{-- Información Adicional - ACTUALIZADA --}}
      @if(($tipo === 'job_request' && (
          (isset($projectInfo->special_instructions) && $projectInfo->special_instructions) ||
          (isset($projectInfo->material_verification) && $projectInfo->material_verification) ||
          (isset($projectInfo->material_roof_loaded) && $projectInfo->material_roof_loaded) ||
          (isset($projectInfo->starter_bundles_ordered) && $projectInfo->starter_bundles_ordered) ||
          (isset($projectInfo->hip_and_ridge_ordered) && $projectInfo->hip_and_ridge_ordered) ||
          (isset($projectInfo->field_shingle_bundles_ordered) && $projectInfo->field_shingle_bundles_ordered)
        )) || 
        ($tipo === 'emergency' && (
          (isset($projectInfo->terms_conditions) && $projectInfo->terms_conditions) ||
          (isset($projectInfo->requirements) && $projectInfo->requirements)
        )))
      <div class="mt-8 pt-6 border-t border-gray-200">
        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-4">Additional Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          {{-- Para JobRequest --}}
          @if($tipo === 'job_request')
            @if(isset($projectInfo->special_instructions) && $projectInfo->special_instructions)
            <div>
              <p class="text-xs text-gray-500 mb-2">Special Instructions</p>
              <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3">{{ $projectInfo->special_instructions }}</p>
            </div>
            @endif

            @if(isset($projectInfo->material_verification) && $projectInfo->material_verification)
            <div>
              <p class="text-xs text-gray-500 mb-2">Material Verification</p>
              <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3">{{ $projectInfo->material_verification }}</p>
            </div>
            @endif

            @if(isset($projectInfo->material_roof_loaded) && $projectInfo->material_roof_loaded)
            <div>
              <p class="text-xs text-gray-500 mb-2">Material Roof Loaded</p>
              <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3">{{ $projectInfo->material_roof_loaded ? 'Yes' : 'No' }}</p>
            </div>
            @endif

            @if(isset($projectInfo->starter_bundles_ordered) && $projectInfo->starter_bundles_ordered)
            <div>
              <p class="text-xs text-gray-500 mb-2">Starter Bundles</p>
              <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3">{{ $projectInfo->starter_bundles_ordered }}</p>
            </div>
            @endif

            @if(isset($projectInfo->hip_and_ridge_ordered) && $projectInfo->hip_and_ridge_ordered)
            <div>
              <p class="text-xs text-gray-500 mb-2">Hip & Ridge</p>
              <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3">{{ $projectInfo->hip_and_ridge_ordered }}</p>
            </div>
            @endif

            @if(isset($projectInfo->field_shingle_bundles_ordered) && $projectInfo->field_shingle_bundles_ordered)
            <div>
              <p class="text-xs text-gray-500 mb-2">Field Shingle Bundles</p>
              <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3">{{ $projectInfo->field_shingle_bundles_ordered }}</p>
            </div>
            @endif
          @endif

          {{-- Para Emergencies --}}
          @if($tipo === 'emergency')
            @if(isset($projectInfo->terms_conditions) && $projectInfo->terms_conditions)
            <div>
              <p class="text-xs text-gray-500 mb-2">Terms & Conditions</p>
              <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3">{{ $projectInfo->terms_conditions }}</p>
            </div>
            @endif

            @if(isset($projectInfo->requirements) && $projectInfo->requirements)
            <div>
              <p class="text-xs text-gray-500 mb-2">Requirements</p>
              <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3">{{ $projectInfo->requirements }}</p>
            </div>
            @endif
          @endif
        </div>
      </div>
      @endif
    </div>
  </div>

  {{-- Galería --}}
  @if($fotos->isEmpty())
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
      <div class="px-6 py-16 text-center">
        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <h3 class="mt-2 text-lg font-medium text-gray-900">No photos available</h3>
        <p class="mt-1 text-sm text-gray-500">This project doesn't have any uploaded photos yet.</p>
      </div>
    </div>
  @else
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
      {{-- Header de la galería --}}
      <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
          <div class="flex items-center">
            <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="text-sm font-medium text-gray-700">Photo Gallery</span>
          </div>
          
          <div class="flex items-center gap-3">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
              {{ $fotos->count() }} {{ Str::plural('item', $fotos->count()) }}
            </span>
            
            {{-- Filtros de vista --}}
            <div class="flex items-center border border-gray-300 rounded-md overflow-hidden">
              <button type="button" id="gridViewBtn" class="p-2 bg-white hover:bg-gray-50 border-r border-gray-300">
                <svg class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
              </button>
              <button type="button" id="listViewBtn" class="p-2 bg-gray-100 hover:bg-gray-200">
                <svg class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>

      {{-- Vista de cuadrícula (por defecto) --}}
      <div id="gridView" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 p-6">
        @foreach($fotos as $foto)
          <div class="group relative rounded-lg overflow-hidden border border-gray-200 hover:border-blue-300 transition-all duration-200 bg-white shadow-sm hover:shadow-md">
            <div class="aspect-w-4 aspect-h-3 bg-gray-100 overflow-hidden">
              <img src="{{ asset('storage/' . $foto->url) }}"
                   alt="Project photo {{ $loop->iteration }}"
                   class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105 cursor-pointer"
                   onclick="openLightbox('{{ asset('storage/' . $foto->url) }}', {{ $loop->index }})"
                   loading="lazy">
            </div>
            <div class="p-3">
              <div class="flex justify-between items-center">
                <div class="text-xs text-gray-500">
                  <span class="font-medium text-gray-700">Photo #{{ $loop->iteration }}</span><br>
                  <span>{{ $foto->created_at->format('M d, Y - H:i') }}</span>
                </div>
                <div class="flex items-center gap-1">
                  <button type="button" 
                          onclick="openLightbox('{{ asset('storage/' . $foto->url) }}', {{ $loop->index }})"
                          class="inline-flex items-center justify-center p-1.5 rounded-full text-gray-400 hover:text-blue-600 hover:bg-blue-100 transition-colors duration-200"
                          title="View full size">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m0 0l3-3m-3 3L7 13"/>
                    </svg>
                  </button>
                  <a href="{{ asset('storage/' . $foto->url) }}"
                     download="{{ $tipo }}-{{ $id }}-photo-{{ $loop->iteration }}.jpg"
                     class="inline-flex items-center justify-center p-1.5 rounded-full text-gray-400 hover:text-green-600 hover:bg-green-100 transition-colors duration-200"
                     title="Download photo">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                  </a>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      {{-- Vista de lista (oculta por defecto) --}}
      <div id="listView" class="hidden p-6 space-y-4">
        @foreach($fotos as $foto)
          <div class="flex items-center gap-4 p-4 border border-gray-200 rounded-lg hover:border-blue-300 transition-colors duration-200 bg-white">
            <div class="flex-shrink-0 w-24 h-24 bg-gray-100 rounded-md overflow-hidden">
              <img src="{{ asset('storage/' . $foto->url) }}"
                   alt="Project photo {{ $loop->iteration }}"
                   class="w-full h-full object-cover cursor-pointer"
                   onclick="openLightbox('{{ asset('storage/' . $foto->url) }}', {{ $loop->index }})">
            </div>
            <div class="flex-1 min-w-0">
              <h4 class="text-sm font-medium text-gray-900 truncate">Photo #{{ $loop->iteration }}</h4>
              <p class="text-sm text-gray-500 mt-1">{{ $foto->created_at->format('M d, Y - H:i') }}</p>
            </div>
            <div class="flex items-center gap-2">
              <button type="button" 
                      onclick="openLightbox('{{ asset('storage/' . $foto->url) }}', {{ $loop->index }})"
                      class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                View
              </button>
              <a href="{{ asset('storage/' . $foto->url) }}"
                 download="{{ $tipo }}-{{ $id }}-photo-{{ $loop->iteration }}.jpg"
                 class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200">
                Download
              </a>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  @endif
</div>

{{-- Lightbox Modal Compacto --}}
<div id="lightboxModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        {{-- Fondo oscuro --}}
        <div class="fixed inset-0 bg-black bg-opacity-70 transition-opacity" onclick="closeLightbox()"></div>
        
        {{-- Contenedor principal más compacto --}}
        <div class="relative bg-transparent rounded-lg max-w-2xl w-full mx-auto">
            {{-- Botón cerrar --}}
            <button onclick="closeLightbox()" 
                    class="absolute -top-10 right-0 text-white hover:text-gray-300 transition-colors duration-200 focus:outline-none z-10">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            
            {{-- Controles de navegación --}}
            <button onclick="navigateLightbox(-1)" 
                    class="absolute left-2 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 transition-colors duration-200 focus:outline-none z-10 bg-black bg-opacity-50 rounded-full p-2">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            
            <button onclick="navigateLightbox(1)" 
                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 transition-colors duration-200 focus:outline-none z-10 bg-black bg-opacity-50 rounded-full p-2">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            
            {{-- Imagen --}}
            <div class="bg-white rounded-lg shadow-xl overflow-hidden">
                <img id="lightboxImage" 
                     class="w-full max-h-[70vh] object-contain" 
                     src="" 
                     alt="Enlarged view">
                
                {{-- Contador --}}
                <div class="absolute bottom-3 left-1/2 transform -translate-x-1/2 bg-black bg-opacity-70 text-white px-3 py-1 rounded-full text-sm">
                    <span id="lightboxCounter"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Variables globales para el lightbox
let currentPhotoIndex = 0;
const photos = @json($fotos->map(function($foto) { return asset('storage/' . $foto->url); }));

// Funciones de utilidad
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    element.select();
    element.setSelectionRange(0, 99999);
    document.execCommand('copy');
    
    // Mostrar feedback visual
    const originalText = element.nextElementSibling.textContent;
    element.nextElementSibling.textContent = 'Copied!';
    setTimeout(() => {
        element.nextElementSibling.textContent = originalText;
    }, 2000);
}

// Funciones del lightbox
function openLightbox(imageUrl, index = 0) {
    currentPhotoIndex = index;
    const lightboxImage = document.getElementById('lightboxImage');
    const lightboxModal = document.getElementById('lightboxModal');
    
    // Preload image for smooth transition
    lightboxImage.src = imageUrl;
    lightboxModal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    updateLightboxCounter();
}

function closeLightbox() {
    document.getElementById('lightboxModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function navigateLightbox(direction) {
    currentPhotoIndex += direction;
    
    // Circular navigation
    if (currentPhotoIndex < 0) {
        currentPhotoIndex = photos.length - 1;
    } else if (currentPhotoIndex >= photos.length) {
        currentPhotoIndex = 0;
    }
    
    document.getElementById('lightboxImage').src = photos[currentPhotoIndex];
    updateLightboxCounter();
}

function updateLightboxCounter() {
    document.getElementById('lightboxCounter').textContent = 
        `${currentPhotoIndex + 1} / ${photos.length}`;
}

// Funciones de vista (grid/list)
function switchView(viewType) {
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    const gridViewBtn = document.getElementById('gridViewBtn');
    const listViewBtn = document.getElementById('listViewBtn');
    
    if (viewType === 'grid') {
        gridView.classList.remove('hidden');
        listView.classList.add('hidden');
        gridViewBtn.classList.remove('bg-gray-100', 'hover:bg-gray-200');
        gridViewBtn.classList.add('bg-white', 'hover:bg-gray-50');
        listViewBtn.classList.remove('bg-white', 'hover:bg-gray-50');
        listViewBtn.classList.add('bg-gray-100', 'hover:bg-gray-200');
    } else {
        gridView.classList.add('hidden');
        listView.classList.remove('hidden');
        listViewBtn.classList.remove('bg-gray-100', 'hover:bg-gray-200');
        listViewBtn.classList.add('bg-white', 'hover:bg-gray-50');
        gridViewBtn.classList.remove('bg-white', 'hover:bg-gray-50');
        gridViewBtn.classList.add('bg-gray-100', 'hover:bg-gray-200');
    }
}

// Descargar todas las fotos
function downloadAllPhotos() {
    photos.forEach((photoUrl, index) => {
        const link = document.createElement('a');
        link.href = photoUrl;
        link.download = `{{ $tipo }}-{{ $id }}-photo-${index + 1}.jpg`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
}

// Event listeners cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Navegación del lightbox con teclado
    document.addEventListener('keydown', function(event) {
        if (document.getElementById('lightboxModal').classList.contains('hidden')) return;
        
        switch(event.key) {
            case 'Escape':
                closeLightbox();
                break;
            case 'ArrowLeft':
                navigateLightbox(-1);
                break;
            case 'ArrowRight':
                navigateLightbox(1);
                break;
        }
    });
    
    // Botones de vista
    document.getElementById('gridViewBtn').addEventListener('click', () => switchView('grid'));
    document.getElementById('listViewBtn').addEventListener('click', () => switchView('list'));
    
    // Botón de descargar todas
    document.getElementById('downloadAllBtn').addEventListener('click', downloadAllPhotos);
    
    // Cerrar modal al hacer clic fuera de la imagen
    document.getElementById('lightboxModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeLightbox();
        }
    });
});
</script>

<style>
.aspect-w-4 {
    position: relative;
    width: 100%;
    padding-bottom: 75%; /* 4:3 Aspect Ratio */
}
.aspect-w-4 > * {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

/* Transiciones suaves */
.transition-all {
    transition: all 0.3s ease;
}

/* Mejoras de accesibilidad */
@media (prefers-reduced-motion: reduce) {
    .transition-all,
    .transition-colors,
    .transition-transform,
    .transition-opacity {
        transition: none;
    }
}

/* Focus styles para mejor accesibilidad */
button:focus,
a:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

/* Animación suave para el modal */
#lightboxModal {
    animation: fadeIn 0.2s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>
@endsection