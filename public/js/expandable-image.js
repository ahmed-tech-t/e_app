document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing expandable images...');
    
    // Initialize expandable images
    function initExpandableImages() {
        const expandableImages = document.querySelectorAll('.expandable-image');
        console.log('Found expandable images:', expandableImages.length);
        
        expandableImages.forEach(img => {
            console.log('Adding click listener to:', img.id);
            img.addEventListener('click', function() {
                console.log('Image clicked:', this.id);
                expandImage(this);
            });
        });
    }
    
    // Initialize on page load
    initExpandableImages();
    
    // Initialize after Livewire updates
    if (typeof Livewire !== 'undefined') {
        Livewire.hook('message.processed', () => {
            initExpandableImages();
        });
    }
    
    function expandImage(imgElement) {
        console.log('expandImage function called with:', imgElement);
        
        // Generate unique ID for this image
        const imageId = imgElement.id;
        const uniqueId = imageId.replace('expandable-image-', '');
        console.log('Unique ID:', uniqueId);
        
        // Remove any existing expanded images
        const existingExpanded = document.getElementById('expanded-image-' + uniqueId);
        if (existingExpanded) {
            existingExpanded.remove();
        }
        
        // Clone the image for expanded view
        const expandedImage = imgElement.cloneNode(true);
        expandedImage.id = 'expanded-image-' + uniqueId;
        expandedImage.className = 'w-full max-w-2xl aspect-[3/1.5] object-cover shadow-2xl z-50 fixed inset-0 m-auto transform transition-all duration-300 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 cursor-pointer';
        expandedImage.style.aspectRatio = '3 / 1.5';
        expandedImage.style.objectFit = 'cover';
        expandedImage.style.width = '100%';
        expandedImage.style.maxWidth = '42rem';
        console.log('Created expanded image:', expandedImage);

        // Add backdrop overlay
        let backdrop = document.getElementById('expanded-image-backdrop');
        if (!backdrop) {
            backdrop = document.createElement('div');
            backdrop.id = 'expanded-image-backdrop';
            backdrop.className = 'fixed inset-0 bg-black/60 z-40 transition-opacity duration-300';
            backdrop.addEventListener('click', () => shrinkImage(expandedImage));
            document.body.appendChild(backdrop);
        }
        
        // Add click listener to expanded image
        expandedImage.addEventListener('click', function(e) {
            console.log('Expanded image clicked');
            e.stopPropagation();
            shrinkImage(expandedImage);
        });
        
        // Add click listener to document to close when clicking outside
        function handleOutsideClick(event) {
            console.log('Outside click detected');
            if (!expandedImage.contains(event.target)) {
                shrinkImage(expandedImage);
                document.removeEventListener('click', handleOutsideClick);
            }
        }
        
        // Add the outside click listener with a small delay to avoid immediate trigger
        setTimeout(() => {
            document.addEventListener('click', handleOutsideClick);
        }, 100);
        
        // Add the expanded image to the page
        console.log('Adding expanded image to body');
        document.body.appendChild(expandedImage);
        document.body.style.overflow = 'hidden';
        console.log('Expanded image should now be visible');
    }
    
    function shrinkImage(expandedImage) {
        expandedImage.style.opacity = '0';
        expandedImage.style.transform = 'scale(0.9)';
        expandedImage.style.transition = 'all 0.3s ease-in-out';

        const backdrop = document.getElementById('expanded-image-backdrop');
        if (backdrop) {
            backdrop.style.opacity = '0';
            backdrop.style.transition = 'opacity 0.3s ease-in-out';
        }
        
        setTimeout(() => {
            expandedImage.remove();
            if (backdrop) {
                backdrop.remove();
            }
            document.body.style.overflow = 'auto';
        }, 300);
    }
});