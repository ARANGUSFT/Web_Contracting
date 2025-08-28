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
      width: 8px;
    }
    
    ::-webkit-scrollbar-track {
      background: #f1f1f1;
    }
    
    ::-webkit-scrollbar-thumb {
      background: #888;
      border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
      background: #555;
    }
  </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-50 min-h-screen text-gray-800">

  <!-- Header -->
  <header class="bg-white shadow-sm sticky top-0 z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 py-4">
        <div class="flex items-center gap-2">
          <i class="fas fa-camera text-blue-600 text-2xl"></i>
          <h1 class="text-2xl md:text-3xl font-bold text-blue-700">
            {{ $title ?? 'Public Photo Gallery' }}
          </h1>
        </div>

        <!-- URL sharing -->
        <div class="flex flex-col sm:flex-row items-stretch gap-2 w-full md:w-auto">
          <div class="relative flex-1">
            <input id="publicLink"
                   class="w-full bg-white rounded-lg border border-gray-300 pl-10 pr-3 py-2 text-sm"
                   readonly
                   value="{{ request()->fullUrl() }}">
            <i class="fas fa-link absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
          </div>
          <div class="flex gap-2">
            <button type="button"
                    class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm hover:bg-blue-700 transition-colors flex items-center gap-2"
                    onclick="copyLink()">
              <i class="fas fa-copy"></i> Copy
            </button>
            <button type="button"
                    class="px-4 py-2 rounded-lg bg-green-600 text-white text-sm hover:bg-green-700 transition-colors flex items-center gap-2 share-btn"
                    data-url="{{ request()->fullUrl() }}"
                    data-title="{{ $title ?? 'Public Photo Gallery' }}">
              <i class="fas fa-share-alt"></i> Share
            </button>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
 

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
      <div class="bg-white rounded-xl shadow-sm p-10 text-center max-w-2xl mx-auto">
        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        <h2 class="text-xl font-medium text-gray-700">No photos available</h2>
        <p class="mt-2 text-gray-500">This public gallery doesn't have any images yet.</p>
      </div>
    @else
      <!-- Image counter visible -->
      <div class="mb-4 bg-blue-50 border border-blue-200 rounded-lg p-3">
        <p class="text-blue-800 font-medium text-center">
          Total images: <span class="text-blue-600 font-bold">{{ count($items) }}</span>
        </p>
      </div>
      
      <div class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
        @foreach ($items as $i => $u)
          @php
            $isAbs = preg_match('#^https?://#i', $u);
            $src   = $isAbs ? $u : asset('storage/'.$u);
          @endphp
          <div class="gallery-item bg-white rounded-xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md">
            <div class="relative overflow-hidden">
              <img src="{{ $src }}"
                   alt="Photo {{ $i + 1 }}"
                   loading="lazy"
                   class="gallery-image w-full h-48 object-cover cursor-pointer"
                   data-index="{{ $i }}"
                   onclick="openModal(this)">
              
              <!-- Overlay with buttons -->
              <div class="absolute inset-0 bg-black bg-opacity-0 transition-all duration-300 flex items-center justify-center gap-2 opacity-0 hover:bg-opacity-40 hover:opacity-100">
                <button class="bg-white p-2 rounded-full shadow-md hover:bg-blue-50" onclick="openModal(this.parentElement.parentElement.querySelector('img'))">
                  <i class="fas fa-expand text-gray-700"></i>
                </button>
                <button class="bg-white p-2 rounded-full shadow-md hover:bg-blue-50" onclick="downloadImage('{{ $src }}', 'photo-{{ $i+1 }}')">
                  <i class="fas fa-download text-gray-700"></i>
                </button>
              </div>
            </div>
            
            <!-- Image information section (only with name) -->
            <div class="p-3">
              <p class="text-sm font-medium text-gray-700 truncate">Photo {{ $i + 1 }}</p>
              <!-- The line showing the date has been removed -->
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </main>

  <!-- Modal for enlarged image -->
  <div id="imageModal" class="modal fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50 opacity-0 pointer-events-none transition-opacity duration-300">
    <div class="relative max-w-4xl w-full mx-4">
      <!-- Close button -->
      <button class="absolute -top-12 right-0 text-white text-2xl z-10" onclick="closeModal()">
        <i class="fas fa-times-circle"></i>
      </button>
      
      <!-- Image container -->
      <div class="bg-black rounded-lg overflow-hidden">
        <img id="modalImage" src="" alt="Enlarged view" class="w-full max-h-[80vh] object-contain">
      </div>
      
      <!-- Navigation controls -->
      <button class="absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-12 text-white text-2xl bg-black bg-opacity-50 rounded-full p-2 hover:bg-opacity-75" onclick="navigateModal(-1)">
        <i class="fas fa-chevron-left"></i>
      </button>
      <button class="absolute right-0 top-1/2 transform -translate-y-1/2 translate-x-12 text-white text-2xl bg-black bg-opacity-50 rounded-full p-2 hover:bg-opacity-75" onclick="navigateModal(1)">
        <i class="fas fa-chevron-right"></i>
      </button>
      
      <!-- Image information -->
      <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4 text-white">
        <p id="modalCaption" class="font-medium"></p>
      </div>
    </div>
  </div>

  <!-- Share Modal -->
  <div id="shareModal" class="modal fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 opacity-0 pointer-events-none transition-opacity duration-300">
    <div class="bg-white rounded-xl shadow-lg p-6 max-w-md w-full mx-4">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Share Gallery</h3>
        <button class="text-gray-400 hover:text-gray-600" onclick="closeShareModal()">
          <i class="fas fa-times"></i>
        </button>
      </div>
      
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Share this link</label>
        <div class="flex">
          <input id="shareLink" type="text" readonly class="flex-1 bg-gray-100 rounded-l-lg border border-r-0 border-gray-300 px-3 py-2 text-sm">
          <button onclick="copyShareLink()" class="bg-blue-600 text-white px-4 py-2 rounded-r-lg text-sm hover:bg-blue-700 transition-colors">Copy</button>
        </div>
      </div>
      
      <div class="border-t border-gray-200 pt-4">
        <p class="text-sm font-medium text-gray-700 mb-2">Share on social media</p>
        <div class="flex justify-center gap-4">
          <a id="facebookShare" class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition-colors">
            <i class="fab fa-facebook-f"></i>
          </a>
          <a id="twitterShare" class="w-10 h-10 rounded-full bg-blue-400 text-white flex items-center justify-center hover:bg-blue-500 transition-colors">
            <i class="fab fa-twitter"></i>
          </a>
          <a id="linkedinShare" class="w-10 h-10 rounded-full bg-blue-700 text-white flex items-center justify-center hover:bg-blue-800 transition-colors">
            <i class="fab fa-linkedin-in"></i>
          </a>
          <a id="whatsappShare" class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center hover:bg-green-600 transition-colors">
            <i class="fab fa-whatsapp"></i>
          </a>
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
        // Temporarily change the button text
        const copyButton = document.querySelector('button[onclick="copyLink()"]');
        const originalHtml = copyButton.innerHTML;
        copyButton.innerHTML = '<i class="fas fa-check"></i> Copied!';
        
        setTimeout(() => {
          copyButton.innerHTML = originalHtml;
        }, 2000);
      });
    }
    
    // Function to open the image modal
    function openModal(element) {
      const index = parseInt(element.getAttribute('data-index'));
      currentModalIndex = index;
      
      const modal = document.getElementById('imageModal');
      const modalImage = document.getElementById('modalImage');
      const modalCaption = document.getElementById('modalCaption');
      
      modalImage.src = galleryImages[index];
      modalCaption.textContent = `Photo ${index + 1} of ${galleryImages.length}`;
      
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
      const modalCaption = document.getElementById('modalCaption');
      
      modalImage.src = galleryImages[currentModalIndex];
      modalCaption.textContent = `Photo ${currentModalIndex + 1} of ${galleryImages.length}`;
    }
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
      if (event.key === 'Escape') {
        closeModal();
        closeShareModal();
      }
    });
    
    // Download image
    function downloadImage(url, filename) {
      fetch(url)
        .then(response => response.blob())
        .then(blob => {
          const blobUrl = window.URL.createObjectURL(blob);
          const a = document.createElement('a');
          a.style.display = 'none';
          a.href = blobUrl;
          a.download = filename;
          document.body.appendChild(a);
          a.click();
          window.URL.revokeObjectURL(blobUrl);
        })
        .catch(() => alert('Error downloading image'));
    }
    
    // Share modal
    document.querySelectorAll('.share-btn').forEach(button => {
      button.addEventListener('click', function() {
        const url = this.getAttribute('data-url');
        const title = encodeURIComponent(this.getAttribute('data-title'));
        
        document.getElementById('shareLink').value = url;
        
        // Set up social media sharing links
        document.getElementById('facebookShare').href = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
        document.getElementById('twitterShare').href = `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${title}`;
        document.getElementById('linkedinShare').href = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`;
        document.getElementById('whatsappShare').href = `https://wa.me/?text=${title} ${encodeURIComponent(url)}`;
        
        const modal = document.getElementById('shareModal');
        modal.classList.remove('opacity-0', 'pointer-events-none');
        modal.classList.add('opacity-100');
      });
    });
    
    function closeShareModal() {
      const modal = document.getElementById('shareModal');
      modal.classList.remove('opacity-100');
      modal.classList.add('opacity-0', 'pointer-events-none');
    }
    
    function copyShareLink() {
      const shareInput = document.getElementById('shareLink');
      navigator.clipboard.writeText(shareInput.value);
      
      // You can add a notification here that the link was copied
      alert('Link copied to clipboard!');
    }
  </script>
</body>
</html>