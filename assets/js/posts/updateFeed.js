//setInterval(() => {
//    checkPosts(null);
//    cleanPosts();
//}, 10000); // Every 5 minutes (300000)
//removeDuplicateIds();

function isScrolledToBottom() {
    // Get the current scroll position
    var scrollHeight = document.documentElement.scrollHeight || document.body.scrollHeight;
    var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
    var windowHeight = window.innerHeight;

    // Check if we're at the bottom of the page
    return scrollHeight - scrollTop === windowHeight;
}

window.addEventListener("scroll", function () {
    if (isScrolledToBottom()) {
        loadMorePosts();
    }
});

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
    const new_post = document.getElementById('new_post');
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
        continuePost = false;
        // Make the new post button unclickable
        new_post.disabled = true;

        alert('Posting...');

        if (!new_post.disabled) {
            $.ajax({
            url: './assets/post_new.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                text.value = "";
                image.value = "";
                filesDiv.innerHTML = "";
                newPost();
                post_id = response.post_id;
                loadNewPosts(post_id);
                if (document.getElementById('console')) {
                const console = document.getElementById('console');
                if (document.getElementById('cons_post')) {
                    const cons_post = document.getElementById('cons_post');
                    cons_post.innerHTML = response.message;
                } else {
                    console.innerHTML += '<p id="cons_post">'+response.message+'</p>';
                }
                }
                continuePost = true;
                // Re-enable the new post button
                new_post.disabled = false;
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
                continuePost = true;
                // Re-enable the new post button
                new_post.disabled = false;
            }
            });
        }
    } else {
        text.placeholder = "Please enter a message";
    }
}

function checkPosts(x) {
    const posts = document.querySelectorAll('[id^="post_"]');
    let highestNumber = -Infinity;
    posts.forEach((post) => {
        const numberPart = parseInt(post.id.replace('post_', ''), 10);
        if (numberPart > highestNumber) {
            highestNumber = numberPart;
        }
    });

    if (x) {
        x = x;
    } else {
        x = 0;
    }

    $.ajax({
        url: './assets/posts_check.php',
        type: 'POST',
        data: {
            last: highestNumber,
            post_id: x
        },
        success: function(response) {
            const console = document.getElementById('console');
            const cons_post = document.getElementById('cons_post');
            if (cons_post) {
                cons_post.innerHTML = 'Checked posts...';
            } else {
                console.innerHTML += '<div id="cons_post">Checked posts...</div>';
            }
            if (response.responseCode === 1) {
                loadMorePosts();
            } else {
                const cons_post = document.getElementById('cons_post');
                if (cons_post) {
                    cons_post.innerHTML += ' No new posts';
                } else {
                    console.innerHTML += '<div id="cons_post">Checked posts... No new posts</div>';
                }
            }
        }
    });
}

function loadNewPosts(post_id) {
    $.ajax({
        url: './assets/post_load.php',
        type: 'POST',
        data: {
            post_id: post_id
        },
        success: function (response) {
            const postsContainer = document.getElementById('posts');
            postsContainer.insertAdjacentHTML('afterbegin', response);
        },
        error: function () {
        }
    });
}

function loadMorePosts() {
    const countPosts = document.querySelectorAll('div.post');
    const offset = countPosts.length;
    $.ajax({
        url: './assets/posts_load.php',
        type: 'POST',
        data: {
            offset: offset
        },
        success: function (response) {
            const postsContainer = document.getElementById('posts');
            postsContainer.insertAdjacentHTML('beforeend', response);
        },
        error: function () {
        }
    });
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
            url: './assets/posts_clean.php',
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
    $.ajax({
        url: './assets/functions.php',
        type: "POST",
        data: {
            deletePost : null,
            post_id : x
        }
    }).done(function(response) {
        post.remove();
        checkPosts(x);
    });
}