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
        expandedImage.className = 'max-w-full max-h-full shadow-2xl z-50 fixed inset-0 m-auto p-4 transform transition-all duration-500 rounded-xl';
        expandedImage.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
        console.log('Created expanded image:', expandedImage);
        
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
        expandedImage.style.transform = 'scale(0.8)';
        expandedImage.style.transition = 'all 0.5s ease-in-out';
        
        setTimeout(() => {
            expandedImage.remove();
            document.body.style.overflow = 'auto';
        }, 500);
    }
});