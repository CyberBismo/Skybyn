function isScrolledToBottom() {
    // Get the current scroll position
    var scrollHeight = document.documentElement.scrollHeight || document.body.scrollHeight;
    var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
    var windowHeight = window.innerHeight;

    // Check if we're at the bottom of the page
    return scrollHeight - scrollTop === windowHeight;
}

// Attach the scroll event listener to load more posts when scrolled to the bottom
window.addEventListener('scroll', function () {
    const windowHeight = window.innerHeight;
    const documentHeight = document.documentElement.scrollHeight;
    const scrollPosition = window.scrollY;

    if (documentHeight - (scrollPosition + windowHeight) < 200) {
        loadMorePosts();
    }
});

function createPost() {
    const text = document.getElementById('new_post_input');
    const image = document.getElementById('image_to_share');
    const filesDiv = document.getElementById('new_post_files');
    
    const formData = new FormData();
    formData.append('text', text.value);
    for (let i = 0; i < image.files.length; i++) {
        formData.append('image[]', image.files[i]);
    }

    let continuePost = false;
    
    if (text.value.length > 0) {
        continuePost = true;
    }

    if (continuePost) {
        text.value = "";
        image.value = "";
        filesDiv.innerHTML = "";
        newPost();
        continuePost = false;
        $.ajax({
            url: './assets/post_new.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                post_id = response.post_id;
                const data = {
                    type: 'new_post',
                    id: post_id
                };
                ws.send(JSON.stringify(data)); // Send the new post ID to the server
                continuePost = true;
            },
            error: function (response) {
                if (document.getElementById('console')) {
                    const console = document.getElementById('console');
                    if (document.getElementById('cons_post')) {
                        const cons_post = document.getElementById('cons_post');
                        cons_post.innerHTML = response.message;
                    } else {
                        console.innerHTML += '<p id="cons_post">'+response.message+'</p>';
                    }
                }
            }
        });
    } else {
        text.placeholder = "Please enter a message";
    }
}

function editPost(x) {
    const post = document.getElementById('post_c_'+ x);
    const new_post_input = document.getElementById('new_post_input');
    $.ajax({
        url: './assets/getPost.php',
        type: "POST",
        data: {
            id : x
        }
    }).done(function(response) {
        if (response.status === "success") {
            new_post_input.value = response.content;
            new_post_input.focus();
            newPost();
        }
    });
}

function deletePost(x) {
    const post = document.getElementById('post_'+ x);
    post.remove();
    $.ajax({
        url: './assets/functions.php',
        type: "POST",
        data: {
            deletePost : null,
            post_id : x
        }
    }).done(function() {
        post_id = x;
        const data = {
            type: 'delete_post',
            id: post_id
        };
        ws.send(JSON.stringify(data)); // Send the new post ID to the server
    });
}