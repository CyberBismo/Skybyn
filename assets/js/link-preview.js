function checkLinkPreviews() {
    const previews = document.querySelectorAll('.post_link_preview[data-url]');
    
    previews.forEach(preview => {
        const url = preview.dataset.url;
        const previewId = preview.id;
        
        fetch(`../assets/ajax/get_link_preview.php?url=${encodeURIComponent(url)}`)
            .then(response => response.json())
            .then(data => {
                if (data.restricted) {
                    preview.remove();
                    return;
                }
                
                // Update preview content
                preview.innerHTML = `
                    ${data.featured ? `
                        <div class="post_link_preview_image">
                            <img src="${data.featured}" alt="Preview">
                        </div>
                    ` : ''}
                    <div class="post_link_preview_info">
                        <div class="post_link_preview_icon">
                            <img src="${data.favicon}" alt="Site Icon">
                        </div>
                        <div class="post_link_preview_title">${data.title}</div>
                        <div class="post_link_preview_description">${data.description}</div>
                    </div>
                `;
            })
            .catch(error => console.error('Error loading preview:', error));
    });
}

// Check for new previews periodically
setInterval(checkLinkPreviews, 2000); 