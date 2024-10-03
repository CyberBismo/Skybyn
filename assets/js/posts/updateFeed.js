setInterval(() => {
    checkPosts();
}, 300000); // Every 5 minutes
removeDuplicateIds();

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
            }
        });
    } else {
        text.placeholder = "Please enter a message";
    }
}

function checkPosts() {
    const posts = document.querySelectorAll('post_date');
    let last_post_date = 0;
    if (posts.length > 0 && posts[0]) {
        const firstPost = posts[0];
        const postDateStr = firstPost.innerHTML;
        const postDate = new Date(postDateStr);
        if (!isNaN(postDate.getTime())) {
            last_post_date = Math.floor(postDate.getTime() / 1000);
        }
    } else {
        highestNumlast_post_dateber = Math.floor(Date.now() / 1000);
    }

    console.log(postDateStr);
    
    const console = document.getElementById('console');
    if (console) {
        const cons_post = document.getElementById('cons_post');
        if (cons_post) {
            cons_post.innerHTML = 'Checking posts...';
        } else {
            console.innerHTML += '<div id="cons_post">Checking posts...</div>';
        }
    }

    $.ajax({
        url: './assets/posts_load.php',
        type: 'POST',
        data: {
            created: last_post_date
        },
        success: function(response) {
            if (response !== '') {
                if (console) {
                    if (cons_post) {
                        cons_post.innerHTML = 'Adding new posts';
                    } else {
                        console.innerHTML += '<div id="cons_post">Adding new posts</div>';
                    }
                }
                const postsContainer = document.getElementById('posts');
                postsContainer.insertAdjacentHTML('beforeend', response);
            } else {
                if (console) {
                    if (cons_post) {
                        cons_post.innerHTML += ' up to date';
                    } else {
                        console.innerHTML += '<div id="cons_post">Checking posts... up to date</div>';
                    }
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
    });
}