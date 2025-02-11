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
    const submitButton = document.getElementById('create_post_btn');

    // Prevent multiple submissions
    if (submitButton.disabled) return;
    submitButton.disabled = true;

    const formData = new FormData();
    formData.append('text', text.value);
    for (let i = 0; i < image.files.length; i++) {
        formData.append('image[]', image.files[i]);
    }

    if (text.value.length > 0) {
        if (submitButton.classList.length > 3 && /^\d+$/.test(submitButton.classList[3])) {
            var post_id = submitButton.classList[3];
            var post = document.getElementById('post_c_'+post_id);
            newPost();
            $.ajax({
                url: '../assets/posts/post_update.php',
                type: 'POST',
                data: {
                    id: post_id,
                    text: text.value
                },
                success: function (response) {
                    console.log(response);
                    const data = {
                        type: 'post_edit',
                        id: post_id
                    };
                    post.innerHTML = text.value;
                    text.value = "";
                    image.value = "";
                    filesDiv.innerHTML = "";
                    ws.send(JSON.stringify(data));
                },
                error: function (response) {
                    console.log(response);
                },
                complete: function () {
                    submitButton.disabled = false;
                }
            });
        } else {
            newPost();
            $.ajax({
                url: '../assets/posts/post_new.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    text.value = "";
                    image.value = "";
                    filesDiv.innerHTML = "";
                    post_id = response.post_id;
                    const data = { type: 'new_post', id: post_id };
                    ws.send(JSON.stringify(data));
                },
                error: function (response) {
                    if (document.getElementById('console')) {
                        const console = document.getElementById('console');
                        if (document.getElementById('cons_post')) {
                            document.getElementById('cons_post').innerHTML = response.message;
                        } else {
                            console.innerHTML += '<p id="cons_post">' + response.message + '</p>';
                        }
                    }
                },
                complete: function () {
                    submitButton.disabled = false;
                }
            });
        }
    } else {
        text.placeholder = "Please enter a message";
        submitButton.disabled = false;
    }
}

function editPost(x) {
    const new_post_input = document.getElementById('new_post_input');
    var edit_post = document.getElementById('create_post_btn');
    $.ajax({
        url: '../assets/posts/post_edit.php',
        type: "POST",
        data: {
            id : x
        }
    }).done(function(response) {
        if (response.status === "success") {
            edit_post.classList.add(x);
            new_post_input.value = response.content;
            new_post_input.focus();
            newPost(x);
        }
    });
}

function deletePost(x) {
    const post = document.getElementById('post_'+ x);
    post.remove();
    $.ajax({
        url: '../assets/functions.php',
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

function loadMorePosts() {
    const lastPost = document.getElementById('last_post');
    if (lastPost) {
        const lastPostId = lastPost.dataset.id;
        $.ajax({
            url: '../assets/posts/posts_load.php',
            type: 'POST',
            data: {
                lastPostId: lastPostId
            },
            success: function (response) {
                const posts = document.getElementById('posts');
                posts.insertAdjacentHTML('beforeend', response);
            }
        });
    }
}