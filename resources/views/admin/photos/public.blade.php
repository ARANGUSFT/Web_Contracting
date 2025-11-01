<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>{{ $title ?? 'Public Photo Gallery' }}</title>
  
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    .gallery-image {
      transition: all 0.3s ease;
    }
    
    .gallery-item:hover .gallery-image {
      transform: scale(1.05);
    }
    
    .modal {
      transition: opacity 0.3s ease;
    }
    
    /* Scrollbar customization */
    ::-webkit-scrollbar {
      width: 6px;
    }
    
    ::-webkit-scrollbar-track {
      background: #f1f1f1;
    }
    
    ::-webkit-scrollbar-thumb {
      background: #cbd5e0;
      border-radius: 3px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
      background: #a0aec0;
    }

    /* Smooth animations */
    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.95); }
      to { opacity: 1; transform: scale(1); }
    }

    .modal-content {
      animation: fadeIn 0.2s ease-out;
    }
  </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-50 min-h-screen text-gray-800">

  <!-- Header Compacto -->
  <header class="bg-white shadow-sm sticky top-0 z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 py-3">
        <div class="flex items-center gap-2">
          <i class="fas fa-camera text-blue-600 text-xl"></i>
          <h1 class="text-xl font-bold text-blue-700 truncate">
            {{ $title ?? 'Public Photo Gallery' }}
          </h1>
          <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full">
            {{ count($items ?? []) }} photos
          </span>
        </div>

        <!-- URL sharing compacto -->
        <div class="flex items-center gap-2">
          <div class="relative flex-1 min-w-0 max-w-xs">
            <input id="publicLink"
                   class="w-full bg-gray-50 rounded-lg border border-gray-300 pl-3 pr-20 py-1.5 text-xs truncate"
                   readonly
                   value="{{ request()->fullUrl() }}">
          </div>
          <div class="flex gap-1">
            <button type="button"
                    class="px-3 py-1.5 rounded-lg bg-blue-600 text-white text-xs hover:bg-blue-700 transition-colors flex items-center gap-1"
                    onclick="copyLink()">
              <i class="fas fa-copy text-xs"></i> Copy
            </button>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-6">
    @php
      // Normalize the list of photos that come as $photos or $fotos,
      // as string, array ['url'=>...], or object with ->url
      $raw = $photos ?? $fotos ?? [];
      $items = [];
      foreach ($raw as $it) {
        if (is_string($it)) {
          $items[] = $it;
        } elseif (is_array($it) && isset($it['url'])) {
          $items[] = $it['url'];
        } elseif (is_object($it) && isset($it->url)) {
          $items[] = $it->url;
        }
      }
    @endphp

    <!-- Gallery Grid -->
    @if (empty($items))
      <div class="bg-white rounded-xl shadow-sm p-8 text-center max-w-md mx-auto">
        <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        <h2 class="text-lg font-medium text-gray-700">No photos available</h2>
        <p class="mt-1 text-sm text-gray-500">This gallery doesn't have any images yet.</p>
      </div>
    @else
      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
        @foreach ($items as $i => $u)
          @php
            $isAbs = preg_match('#^https?://#i', $u);
            $src   = $isAbs ? $u : asset('storage/'.$u);
          @endphp
          <div class="gallery-item bg-white rounded-lg shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md border border-gray-100">
            <div class="relative overflow-hidden aspect-square">
              <img src="{{ $src }}"
                   alt="Photo {{ $i + 1 }}"
                   loading="lazy"
                   class="gallery-image w-full h-full object-cover cursor-pointer"
                   data-index="{{ $i }}"
                   onclick="openModal(this)">
              
              <!-- Overlay con botones más pequeños -->
              <div class="absolute inset-0 bg-black bg-opacity-0 transition-all duration-300 flex items-center justify-center gap-1 opacity-0 hover:bg-opacity-30 hover:opacity-100">
                <button class="bg-white p-1.5 rounded-full shadow-sm hover:bg-blue-50 transition-colors" 
                        onclick="openModal(this.parentElement.parentElement.querySelector('img')); event.stopPropagation();"
                        title="View full size">
                  <i class="fas fa-expand text-xs text-gray-600"></i>
                </button>
                <button class="bg-white p-1.5 rounded-full shadow-sm hover:bg-green-50 transition-colors" 
                        onclick="downloadImage('{{ $src }}', 'photo-{{ $i+1 }}'); event.stopPropagation();"
                        title="Download photo">
                  <i class="fas fa-download text-xs text-gray-600"></i>
                </button>
              </div>
            </div>
            
            <!-- Información mínima -->
            <div class="p-2">
              <p class="text-xs font-medium text-gray-600 text-center">#{{ $i + 1 }}</p>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </main>

  <!-- Modal Compacto para imagen ampliada -->
  <div id="imageModal" class="modal fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50 opacity-0 pointer-events-none transition-opacity duration-300 p-3">
    <div class="relative max-w-2xl w-full modal-content">
      <!-- Botón cerrar compacto -->
      <button class="absolute -top-8 right-0 text-white hover:text-gray-300 transition-colors z-10" 
              onclick="closeModal()"
              title="Close (ESC)">
        <i class="fas fa-times text-lg"></i>
      </button>
      
      <!-- Contenedor de imagen -->
      <div class="bg-black rounded-lg overflow-hidden">
        <img id="modalImage" src="" alt="Enlarged view" class="w-full max-h-[65vh] object-contain">
      </div>
      
      <!-- Controles de navegación compactos -->
      <button class="absolute left-2 top-1/2 transform -translate-y-1/2 text-white bg-black bg-opacity-50 rounded-full p-2 hover:bg-opacity-70 transition-colors z-10"
              onclick="navigateModal(-1)"
              title="Previous (←)">
        <i class="fas fa-chevron-left text-sm"></i>
      </button>
      <button class="absolute right-2 top-1/2 transform -translate-y-1/2 text-white bg-black bg-opacity-50 rounded-full p-2 hover:bg-opacity-70 transition-colors z-10"
              onclick="navigateModal(1)"
              title="Next (→)">
        <i class="fas fa-chevron-right text-sm"></i>
      </button>
      
      <!-- Información e controles inferiores -->
      <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between">
        <!-- Contador -->
        <div class="bg-black bg-opacity-70 text-white px-3 py-1 rounded-full text-sm">
          <span id="modalCounter">{{ count($items ?? []) ? '1/' . count($items) : '0/0' }}</span>
        </div>
        
        <!-- Botones de acción -->
        <div class="flex gap-1">
          <button class="bg-black bg-opacity-70 text-white p-2 rounded-full hover:bg-opacity-90 transition-colors"
                  onclick="downloadCurrentImage()"
                  title="Download current photo">
            <i class="fas fa-download text-xs"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Image data for the modal
    const galleryImages = [
      @foreach($items as $u)
        @php
          $isAbs = preg_match('#^https?://#i', $u);
          $src   = $isAbs ? $u : asset('storage/'.$u);
        @endphp
        '{{ $src }}',
      @endforeach
    ];
    
    let currentModalIndex = 0;
    
    // Function to copy the link
    function copyLink() {
      const linkInput = document.getElementById('publicLink');
      navigator.clipboard.writeText(linkInput.value).then(() => {
        // Feedback visual temporal
        const copyButton = document.querySelector('button[onclick="copyLink()"]');
        const originalHtml = copyButton.innerHTML;
        copyButton.innerHTML = '<i class="fas fa-check"></i> Copied!';
        copyButton.classList.remove('bg-blue-600');
        copyButton.classList.add('bg-green-600');
        
        setTimeout(() => {
          copyButton.innerHTML = originalHtml;
          copyButton.classList.remove('bg-green-600');
          copyButton.classList.add('bg-blue-600');
        }, 1500);
      });
    }
    
    // Function to open the image modal
    function openModal(element) {
      const index = parseInt(element.getAttribute('data-index'));
      currentModalIndex = index;
      
      const modal = document.getElementById('imageModal');
      const modalImage = document.getElementById('modalImage');
      const modalCounter = document.getElementById('modalCounter');
      
      modalImage.src = galleryImages[index];
      modalCounter.textContent = `${index + 1}/${galleryImages.length}`;
      
      modal.classList.remove('opacity-0', 'pointer-events-none');
      modal.classList.add('opacity-100');
      
      // Prevent body scroll when modal is open
      document.body.style.overflow = 'hidden';
    }
    
    // Function to close the modal
    function closeModal() {
      const modal = document.getElementById('imageModal');
      modal.classList.remove('opacity-100');
      modal.classList.add('opacity-0', 'pointer-events-none');
      
      // Restore body scroll
      document.body.style.overflow = 'auto';
    }
    
    // Navigation between images in the modal
    function navigateModal(direction) {
      currentModalIndex += direction;
      
      // Circular navigation
      if (currentModalIndex >= galleryImages.length) {
        currentModalIndex = 0;
      } else if (currentModalIndex < 0) {
        currentModalIndex = galleryImages.length - 1;
      }
      
      const modalImage = document.getElementById('modalImage');
      const modalCounter = document.getElementById('modalCounter');
      
      modalImage.src = galleryImages[currentModalIndex];
      modalCounter.textContent = `${currentModalIndex + 1}/${galleryImages.length}`;
    }
    
    // Download current image in modal
    function downloadCurrentImage() {
      const currentImage = galleryImages[currentModalIndex];
      const filename = `photo-${currentModalIndex + 1}.jpg`;
      downloadImage(currentImage, filename);
    }
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
      const modal = document.getElementById('imageModal');
      if (!modal.classList.contains('opacity-0')) {
        switch(event.key) {
          case 'Escape':
            closeModal();
            break;
          case 'ArrowLeft':
            navigateModal(-1);
            break;
          case 'ArrowRight':
            navigateModal(1);
            break;
        }
      }
    });
    
    // Close modal when clicking on background
    document.getElementById('imageModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeModal();
      }
    });
    
    // Download image function
    function downloadImage(url, filename) {
      // Crear un enlace temporal para descargar
      const a = document.createElement('a');
      a.href = url;
      a.download = filename;
      a.style.display = 'none';
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
    }
    
    // Swipe support for mobile
    let touchStartX = 0;
    let touchEndX = 0;
    
    document.addEventListener('touchstart', e => {
      touchStartX = e.changedTouches[0].screenX;
    });
    
    document.addEventListener('touchend', e => {
      touchEndX = e.changedTouches[0].screenX;
      handleSwipe();
    });
    
    function handleSwipe() {
      const modal = document.getElementById('imageModal');
      if (!modal.classList.contains('opacity-0')) {
        if (touchEndX < touchStartX - 50) {
          navigateModal(1); // Swipe left - next
        }
        if (touchEndX > touchStartX + 50) {
          navigateModal(-1); // Swipe right - previous
        }
      }
    }
  </script>
</body>
</html>