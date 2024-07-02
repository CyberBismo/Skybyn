setInterval(() => {
    checkPosts();
    cleanPosts();
}, 300000); // Every 5 minutes
removeDuplicateIds();

function checkEnter() {
    let text = document.getElementById('new_post_input');

    text.addEventListener('keydown', function(event) {
        if (event.key === "Enter" && !event.shiftKey) {
            event.preventDefault();
            createPost(); // Call the createPost function directly
        }
    });
}

function createPost() {
    if (isCreatingPost) {
        // If post creation is already in progress, do nothing
        return;
    }

    isCreatingPost = true; // Set the flag to indicate post creation is in progress

    const text = document.getElementById('new_post_input');
    const public = document.getElementById('new_post_public');
    const image = document.getElementById('image_to_share');
    const filesDiv = document.getElementById('new_post_files');

    var formData = new FormData();
    for (var i = 0; i < image.files.length; i++) {
        formData.append('files[]', image.files[i]);
    }

    formData.append('text', text.value);
    formData.append('public', public.value); // Visibility

    $.ajax({
        url: 'assets/post_new.php',
        type: "POST",
        data: formData,
        processData: false,
        contentType: false
    }).done(function(response) {
        if (response == "") {
            newPost();
            checkPosts(); 
            text.value = "";
            image.value = "";
            filesDiv.innerHTML = "";
            isCreatingPost = false;
        }
    });
}

function loadMorePosts() {
    if (loading) {
        return;
    }

    const countPosts = document.querySelectorAll('div.post');
    const offset = countPosts.length;

    loading = true;
    $.ajax({
        url: 'assets/posts_load.php',
        type: 'POST',
        data: {
            offset: offset
        },
        success: function (response) {
            const postsContainer = document.getElementById('posts');
            postsContainer.insertAdjacentHTML('beforeend', response);
            loading = false;
        },
        error: function () {
            loading = false;
        }
    });
}

function checkPosts() {
    const posts = document.querySelectorAll('[id^="post_"]');
    let highestNumber = -Infinity;
    posts.forEach((post) => {
        const numberPart = parseInt(post.id.replace('post_', ''), 10);
        if (numberPart > highestNumber) {
            highestNumber = numberPart;
        }
    });

    $.ajax({
        url: 'assets/posts_check.php',
        type: "POST",
        data: {
            last: highestNumber
        }
    }).done(function (response) {
        if (response != "last") {
            let newPosts = document.createElement('div');
            newPosts.innerHTML = response;
            let postElements = newPosts.querySelectorAll('.post');

            for (let i = 0; i < postElements.length; i++) {
                let postId = postElements[i].id.replace("post_", "");
                if (!insertedPostIds.includes(postId)) {
                    document.getElementById("posts").insertAdjacentElement('afterbegin', postElements[i]);
                    insertedPostIds.push(postId);
                }
            }

            removeDuplicateIds();
        }
    });
}

let initialPosts = document.querySelectorAll('.post');
for (let i = 0; i < initialPosts.length; i++) {
    let postId = initialPosts[i].id.replace("post_", "");
    insertedPostIds.push(postId);
}

function cleanPosts() {
    let posts = document.querySelectorAll('.post');
    let postIds = [];
    for (var i = 0; i < posts.length; i++) {
        let postId = posts[i].id.replace('post_', '');
        postIds.push(postId);
    }

    if (postIds.length > 0) {
        $.ajax({
            url: 'assets/posts_clean.php',
            type: "POST",
            data: {
                ids: postIds
            }
        }).done(function(response) {
            var nonExistingPostIds = response.split(',');
            for (var i = 0; i < nonExistingPostIds.length; i++) {
                var postId = nonExistingPostIds[i];
                var post = document.getElementById('post_' + postId);
                if (post) {
                    post.remove();
                }
            }
        });
    }
}

function removeDuplicateIds() {
    const elements = document.querySelectorAll('*');
    const idMap = new Map();
    elements.forEach(element => {
        const id = element.id;
        if (id) {
            if (idMap.has(id)) {
                element.parentNode.removeChild(element);
            } else {
                idMap.set(id, true);
            }
        }
    });
}

function editPost(x) {
    const post = document.getElementById('post_c_'+ x);
    const new_post_input = document.getElementById('new_post_input');
    new_post_input.value = post.innerHTML;
    new_post_input.focus();
    newPost();
}

function deletePost(x) {
    const post = document.getElementById('post_'+ x);
    $.ajax({
        url: 'assets/functions.php',
        type: "POST",
        data: {
            deletePost : null,
            post_id : x
        }
    }).done(function(response) {
        post.remove();
        checkPosts();
    });
}