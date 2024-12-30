document.addEventListener('DOMContentLoaded', function() {
    // Remplacer les images manquantes par des placeholders
    document.querySelectorAll('img[src*="products/"]').forEach(function(img) {
        img.onerror = function() {
            const productName = img.alt;
            const width = img.getAttribute('width') || 500;
            const height = img.getAttribute('height') || 500;
            img.src = `https://via.placeholder.com/${width}x${height}.jpg/CCCCCC/FFFFFF?text=${encodeURIComponent(productName)}`;
        };
    });
});
