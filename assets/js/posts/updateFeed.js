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
    const edit_post = document.getElementById('edit_post').value;
    const create_post_edit = document.getElementById('create_post_edit');
    const submitButton = document.getElementById('create_post_btn'); // Add your submit button ID

    // Prevent multiple submissions
    if (submitButton.disabled) return;
    submitButton.disabled = true;

    const formData = new FormData();
    formData.append('text', text.value);
    for (let i = 0; i < image.files.length; i++) {
        formData.append('image[]', image.files[i]);
    }

    if (text.value.length > 0) {
        if (edit_post !== "") {
            create_post_edit.removeAttribute("hidden");
            var post = document.getElementById('post_c_' + edit_post);
            newPost();
            $.ajax({
                url: '../assets/posts/post_update.php',
                type: 'POST',
                data: { id: edit_post, text: text },
                success: function (response) {
                    const data = { type: 'post_edit', id: edit_post.value };
                    post.innerHTML = text.value;
                    text.value = "";
                    image.value = "";
                    filesDiv.innerHTML = "";
                    ws.send(JSON.stringify(data));
                },
                error: function () {
                },
                complete: function () {
                    submitButton.disabled = false; // Re-enable submit button
                }
            });
        } else {
            if (!create_post_edit.hasAttribute("hidden")) {
                create_post_edit.setAttribute("hidden","");
            }
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
                    submitButton.disabled = false; // Re-enable submit button
                }
            });
        }
    } else {
        text.placeholder = "Please enter a message";
        submitButton.disabled = false; // Re-enable if input is empty
    }
}

function editPost(x) {
    const new_post_input = document.getElementById('new_post_input');
    const edit_post = document.getElementById('edit_post');
    $.ajax({
        url: '../assets/posts/post_edit.php',
        type: "POST",
        data: {
            id : x
        }
    }).done(function(response) {
        if (response.status === "success") {
            edit_post.value = x;
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