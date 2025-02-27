function checkLinkPreviews() {
    console.log('Checking for link previews...');
    const previews = document.querySelectorAll('.post_link_preview[data-url]');
    console.log(`Found ${previews.length} previews to check`);
    
    previews.forEach(preview => {
        const url = preview.dataset.url;
        const previewId = preview.id;
        console.log(`Fetching preview for URL: ${url}, ID: ${previewId}`);
        
        fetch(`../assets/ajax/get_link_preview.php?url=${encodeURIComponent(url)}`)
            .then(response => {
                console.log(`Received response for ${url}:`, response);
                return response.json();
            })
            .then(data => {
                console.log(`Received data for ${url}:`, data);
                if (data.restricted) {
                    console.log(`${url} is restricted, removing preview`);
                    preview.remove();
                    return;
                }
                
                console.log(`Updating preview for ${url}`);
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
            .catch(error => {
                console.error('Error loading preview:', error);
                preview.innerHTML = `
                    <div class="post_link_preview_info">
                        <div class="post_link_preview_title">Error loading preview</div>
                        <div class="post_link_preview_description">${error.message}</div>
                    </div>
                `;
            });
    });
}

console.log('Link preview script loaded');
setInterval(checkLinkPreviews, 2000); 