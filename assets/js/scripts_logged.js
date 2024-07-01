// Show search form
function showSearch() {
    const mobileSearch = document.getElementById('mobile-search');
    const search = document.getElementById('searchInput');
    const searchRes = document.getElementById('search_result');
    const usermenu = document.getElementById('usermenu');

    // Function to hide the search form
    function hideSearchForm() {
        mobileSearch.style.transform = "translateY(-155px)";
        searchRes.style.display = "none";
    }

    // Function to show the search form
    function showSearchForm() {
        mobileSearch.style.transform = "translateY(0px)";
        search.focus();
    }

    // Listen for the window resize event (keyboard hide)
    window.addEventListener('resize', function() {
        if (window.innerHeight === window.screen.height) {
            // Keyboard is hidden
            hideSearchForm();
        }
    });

    // Listen for the search input blur event (if user taps outside)
    search.addEventListener('blur', function() {
        setTimeout(function() {
            if (!search.matches(':focus')) {
                // Keyboard is hidden
                hideSearchForm();
            }
        }, 300); // You may need to adjust the delay based on your specific needs
    });

    // Toggle the search form
    if (mobileSearch.style.transform == "translateY(0px)") {
        hideSearchForm();
    } else {
        showSearchForm();
    }
}

// Start searching while typing
function startSearch(x) {
    const searchResult = document.getElementById('search_result');
    const searchRes = document.getElementById('search_res');
    const searchResUsers = document.getElementById('search_res_users');
    const searchRUsers = document.getElementById('search_r_users');
    const searchResGroups = document.getElementById('search_res_groups');
    const searchRGroups = document.getElementById('search_r_groups');
    const searchResPages = document.getElementById('search_res_pages');
    const searchRPages = document.getElementById('search_r_pages');

    if (x.value.length >= 4) {
        searchResult.style.display = "block";

        // Check if the input starts with "/user"
        if (x.value.startsWith("@user ")) {
            $.ajax({
                url: 'assets/search_users.php',
                type: "POST",
                data: {
                    text: x.value
                }
            }).done(function(response) {
                // Handle the response for user search
                if (response != "") {
                    searchResUsers.removeAttribute("hidden");
                    searchRUsers.innerHTML = response;
                } else {
                    searchResUsers.setAttribute("hidden", "");
                    searchRUsers.innerHTML = "";
                }
            });
        } else
        if (x.value.startsWith("/page ")) {
            $.ajax({
                url: 'assets/search_pages.php',
                type: "POST",
                data: {
                    text: x.value
                }
            }).done(function(response) {
                // Handle the response for page search
                if (response != "") {
                    searchResPages.removeAttribute("hidden");
                    searchRPages.innerHTML = response;
                } else {
                    searchResPages.setAttribute("hidden", "");
                    searchRPages.innerHTML = "";
                }
            });
        } else
        if (x.value.startsWith("/group ")) {
            $.ajax({
                url: 'assets/search_groups.php',
                type: "POST",
                data: {
                    text: x.value
                }
            }).done(function(response) {
                // Handle the response for page search
                if (response != "") {
                    searchResPages.removeAttribute("hidden");
                    searchRPages.innerHTML = response;
                } else {
                    searchResPages.setAttribute("hidden", "");
                    searchRPages.innerHTML = "";
                }
            });
        } else {
            $.ajax({
                url: 'assets/search.php',
                type: "POST",
                data: {
                    text: x.value
                }
            }).done(function(response) {
                // Handle the response for page search
                if (response != "") {
                    searchResult.removeAttribute("hidden");
                    searchRes.innerHTML = response;
                } else {
                    searchResult.setAttribute("hidden", "");
                    searchRes.innerHTML = "";
                }
            });
        }

        // Add more conditions here if needed for other types of searches

    } else {
        searchResult.style.display = "none";
    }
}


function updateFileNameLabel() {
    const fileInput = document.getElementById('image_to_share');
    const fileNameLabel = document.getElementById('image_to_share_text');
    const filesDiv = document.getElementById('new_post_files');

    filesDiv.innerHTML = ''; // Clear the previous content

    if (fileInput.files.length === 0) {
        fileNameLabel.textContent = 'No image selected';
    } else if (fileInput.files.length === 1) {
        const file = fileInput.files[0];
        if (isFileSupported(file)) {
            fileNameLabel.textContent = file.name;
            displayImageAsThumbnail(file, filesDiv);
        } else {
            fileNameLabel.textContent = 'Invalid file type';
        }
    } else {
        // Check the file types of all selected files
        let allFilesSupported = true;
        for (let i = 0; i < fileInput.files.length; i++) {
            const file = fileInput.files[i];
            if (!isFileSupported(file)) {
                allFilesSupported = false;
                break;
            }
            displayImageAsThumbnail(file, filesDiv);
        }

        fileNameLabel.textContent = allFilesSupported ? fileInput.files.length + ' files selected' : 'Invalid file type';
    }
}
function isFileSupported(file) {
    const allowedExtensions = ['.jpg', '.jpeg', '.gif', '.png'];
    const fileExtension = file.name.split('.').pop().toLowerCase();
    return allowedExtensions.includes('.' + fileExtension);
}
function displayImageAsThumbnail(file, filesDiv) {
    const reader = new FileReader();
    reader.onload = function (event) {
        const img = document.createElement('img');
        img.src = event.target.result;
        filesDiv.appendChild(img);
    };
    reader.readAsDataURL(file);
}
let isCreatingPost = false;
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
function checkEnter() {
    let text = document.getElementById('new_post_input');

    text.addEventListener('keydown', function(event) {
        if (event.key === "Enter" && !event.shiftKey) {
            event.preventDefault();
            createPost(); // Call the createPost function directly
        }
    });
}

function convertEmoji(string) {
    let text = document.getElementById('new_post_input');
    const emojiMap = {
        ':)': '🙂',
        ':D': '😁',
        ':P': '😛',
        ':(': '🙁',
        ';)': '😉',
        ':O': '😮',
        ':*': '😘',
        '<3': '❤️',
        ':/': '😕',
        ':|': '😐',
        ':$': '🤫',
        ':s': '😕',
        ':o)': '👽',
        ':-(': '😞',
        ':-)': '😊',
        ':-D': '😂',
        ':-P': '😜',
        ':-/': '😕',
        ':-|': '😐',
        ';-)': '😉',
        '=)': '😊',
        '=D': '😃',
        '=P': '😛',
        '=\\': '😕',
        ':poop:': '💩',
        ':fire:': '🔥',
        ':rocket:': '🚀',
    };
    text.value = string.replace(/(:\)|:D)/g, (match) => emojiMap[match]);
}
function adjustTextareaHeight() {
    const newPost = document.getElementById("new_post");
    const textarea = document.getElementById("new_post_input");0 
    textarea.rows = textarea.value.split("\n").length;
    newPost.style.height = 40 + textarea.clientHeight + "px";
}

function showNotifications(event) {
    const notifications = document.getElementById('notifications');
    const notiList = document.getElementById('noti-list');
    $.ajax({
        url: 'assets/noti_get.php'
    }).done(function(response) {
        notiList.innerHTML = response;
        notifications.style.display = "block";
    });
}
function hideMenus(event) {
    const usermenu = document.getElementById('usermenu');
    const notification = document.getElementById('notification');
    const notifications = document.getElementById('notifications');
    if (notifications.style.display == "block") {
        if (!notifications.contains(event.target) && !notification.contains(event.target)) {
            notifications.style.display = "none";
        }
    }
    if (usermenu.style.display == "block") {
        if (!usermenu.contains(event.target)) {
            usermenu.style.display = "none";
        }
    }
}

function showNoti(x) {
    let notiWin = document.getElementById('notification-window');
    let notWin_avatar = document.getElementById('noti_win_avatar');
    let notWin_user = document.getElementById('noti_win_username');
    let notWin_text = document.getElementById('noti_win_text');
    let notWin_foot = document.getElementById('noti_win_foot');
    let notWin_foot_profile = document.getElementById('noti_win_foot_profile');

    $.ajax({
        url: 'assets/noti_window_data.php',
        type: "POST",
        data: {
            noti : x
        }
    }).done(function(response) {
        data = response;
        noti_from = data.noti_from;
        noti_date = data.noti_date;
        noti_profile = data.noti_profile;
        noti_post = data.noti_post;
        noti_type = data.noti_type;
        
        if (noti_from !== null) {
            notWin_avatar.src.value = data.notiUserAvatar;
            notWin_user.innerHTML = data.notiUserUsername;
        } else {
            notWin_user.innerHTML = "Skybyn";
        }
        if (noti_profile !== null) {
            notWin_foot.removeAttribute("hidden");
            var profileURL = "window.location.href='./profile="+noti_profile+"'";
            notWin_foot_profile.setAttribute("onclick",profileURL);
        } else {
            notWin_foot.setAttribute("hidden","");
        }
        notWin_text.innerHTML = data.noti_content;

        notiWin.removeAttribute("hidden");

        $.ajax({
            url: 'assets/noti_status.php',
            type: "POST",
            data: {
                noti : x
            }
        }).done(function(response) {
            const noti_status = document.getElementById('noti_status_'+x);
            if (response === "1") {
                noti_status.innerHTML = '<i class="fa-solid fa-envelope-open-text"></i>';
            }
        });
    });
}
function closeNotiWin() {
    const notiWin = document.getElementById('notification-window');
    if (notiWin.hasAttribute("hidden")) {
        notiWin.removeAttribute("hidden");
    } else {
        notiWin.setAttribute("hidden","");
    }
}
function readNoti() {
    $.ajax({
        url: 'assets/noti_status.php',
        type: "POST",
        data: {
            read: 1
        }
    }).done(function(response) {
        const noti_status = document.getElementsByClassName('noti-status');
        for (let i = 0; i < noti_status.length; i++) {
            noti_status[i].innerHTML = '<i class="fa-solid fa-envelope-open-text"></i>';
        }
    });
}
function delNoti(x) {
    const notiList = document.getElementById('noti-list');
    const noti = document.getElementsByClassName('noti');
    if (x === "all") {
        $.ajax({
            url: 'assets/noti_delete.php',
            type: "POST",
            data: {
                noti: 'all'
            }
        }).done(function(response) {
            for (let i = 0; i < noti.length; i++) {
                noti[i].remove();
            }
            notiList.innerHTML = '<center><br>No new notifications<br><br></center>';
        });
    } else {
        $.ajax({
            url: 'assets/noti_delete.php',
            type: "POST",
            data: {
                noti: x
            }
        }).done(function(response) {
            document.getElementById('noti_'+x).remove();
            if (noti.length == 0) {
                notiList.innerHTML = '<center><br>No new notifications<br><br></center>';
            }
        });
    }
    checkNoti();
}
function checkNoti() {
    var notiAlert = document.getElementById('noti_alert');
    $.ajax({
        url: 'assets/noti_check.php'
    }).done(function(response) {
        if (response == "unread") {
            notiAlert.style.opacity = '1';
        } else {
            notiAlert.style.opacity = '0';
        }
    });
}


function expandNoti(x) {
    if (x.style.height === "auto") {
        x.style.height = "40px";
    } else {
        x.style.height = "auto";
    }
}
function markRead(x) {
    $.ajax({
        url: 'assets/noti_read.php',
        type: "POST",
        data: {
            noti : x
        }
    }).done(function(response) {
        
    });
}

function showImage(x) {
    const image_viewer = document.getElementById('image_viewer');
    const image_post = document.getElementById('image_post');
    const image_frame = document.getElementById('image_frame');
    const image_slider = document.getElementById('image_slider');

    image_slider.style.display = "flex";

    if (image_viewer.style.display === "flex") {
        image_viewer.style.display = "none";
    } else {
        $.ajax({
            url: 'assets/post_full.php',
            type: "POST",
            data: {
                post : x
            }
        }).done(function(postData) {
            image_post.innerHTML = postData;
            image_viewer.style.display = "flex";
        });

        $.ajax({
            url: 'assets/post_images.php',
            type: "POST",
            data: {
                post : x
            }
        }).done(function(images) {
            let sliderHTML = "";
            images.forEach((image, index) => {
                const isActive = index === 0 ? "active" : ""; // Set first image as active by default
                sliderHTML += `<img src="${image.file_url}" class="${isActive} image_slider_item" onclick="changeImage(${index},${x})">`;
            });

            image_frame.innerHTML = `<img src="${images[0].file_url}" id="mainImage">`;
            image_slider.innerHTML = sliderHTML;
        });
    }
}
function toggleImageSlider() {
    const image_slider = document.getElementById('image_slider');
    if (image_slider.style.display == "none") {
        image_slider.style.display = "flex";
    } else {
        image_slider.style.display = "none";
    }
}

function changeImage(index,x) {
    $.ajax({
        url: 'assets/post_images.php',
        type: "POST",
        data: {
            post : x
        }
    }).done(function(response) {
        const mainImage = document.getElementById('mainImage');
        mainImage.src = response[index].file_url;

        const image_slider = document.getElementById("image_slider");
        const images = image_slider.getElementsByClassName("image_slider_item");
        for (let i = 0; i < images.length; i++) {
            images[i].classList.remove("active");
        }
        images[index].classList.add("active");
    });
}

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

let loading = false;
const limit = 3;

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

// Attach the scroll event listener to load more posts when scrolled to the bottom
window.addEventListener('scroll', function () {
    const windowHeight = window.innerHeight;
    const documentHeight = document.documentElement.scrollHeight;
    const scrollPosition = window.scrollY;

    if (documentHeight - (scrollPosition + windowHeight) < 200) {
        loadMorePosts();
    }
});

function hitEnter(input,x) {
    const button = document.getElementById('login');

    function handleKeyPress(event) {
        if (event.keyCode === 13) {
            sendComment(x);
        }
    }

    input.addEventListener('keydown', handleKeyPress, { once: true });
}
function sendComment(x) {
    const input = document.getElementById('pc_'+x);

    if (input.value.length > 0) {
        $.ajax({
            url: 'assets/comment_new.php',
            type: "POST",
            data: {
                post_id : x,
                comment : input.value
            }
        }).done(function(response) {
            input.value = "";
            checkComments(x);
        });
    }
}
function checkComments(x) {
    const comments = document.getElementById('post_comments_'+x);
    const comment = comments.firstElementChild;
    $.ajax({
        url: 'assets/comments_check.php',
        type: "POST",
        data: {
            post : x
        }
    }).done(function(response) {
        if (response != "") {
            comments.insertAdjacentHTML('afterbegin', response);
            removeDuplicateIds();
        }
    });
}
function cleanComments() {
    let comments = document.querySelectorAll('.post_comment');
    let commentIds = [];
    for (var i = 0; i < comments.length; i++) {
        let commentId = comments[i].id.replace('comment_', '');
        commentIds.push(commentId);
    }

    if (commentIds.length > 0) {
        $.ajax({
            url: 'assets/comments_clean.php',
            type: "POST",
            data: {
                ids: commentIds
            }
        }).done(function(response) {
            var nonExistingCommentIds = response.split(',');
            for (var i = 0; i < nonExistingCommentIds.length; i++) {
                var commentId = nonExistingCommentIds[i];
                var comment = document.getElementById('comment_' + commentId);
                if (comment) {
                    comment.remove();
                }
            }
        });
    }
}
function delComment(x) {
    const comment = document.getElementById('comment_'+x);
    $.ajax({
        url: 'assets/comment_delete.php',
        type: "POST",
        data: {
            comment_id : x
        }
    }).done(function(response) {
        comment.remove();
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
function showPostActions(x) {
    const actionList = document.getElementById("pal_"+x);
    
    if (actionList.hidden == true) {
        actionList.hidden = false;
    } else {
        actionList.hidden = true;
    }
}
let insertedPostIds = [];

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
setInterval(() => {
    checkPosts();
    cleanPosts();
}, 300000); // Every 5 minutes
removeDuplicateIds();



function checkRegistrationDuration(registrationTimestamp, unlockDuration) {
    const currentTimestamp = Math.floor(Date.now() / 1000);
    const duration = currentTimestamp - registrationTimestamp;

    if (duration >= unlockDuration) {
        return true;
    } else {
        return false;
    }
}

function genRef() {
    const code = document.getElementById('frc');
    $.ajax({
        url: 'assets/generate_ref_code.php',
        type: "POST"
    }).done(function(response) {
        if (response != null) {
            code.innerHTML = response;
        }
    });
}
function checkRef() {
    let ref = document.getElementById('frc');
    $.ajax({
        url: 'assets/check_refer_code.php',
        type: "POST",
        data: {
            code: ref
        }
    }).done(function(response) {
        if (response == "expired") {
            code.innerHTML = "GENERATE CODE";
        }
    });
}
setInterval(() => {
    checkRef();
}, 300000);
checkRef();

function expandFR() {
    const fr = document.getElementById('fr');
    if (fr.style.height == "auto") {
        fr.style.height = "40px";
    } else {
        fr.style.height = "auto";
    }
}

function friExpand() {
    const fri = document.getElementById('fri');
    const frit = document.getElementById('frit');
    if (frit.style.height == "auto") {
        fri.innerHTML = "+";
        frit.style.height = "0px";
    } else {
        fri.innerHTML = "-";
        frit.style.height = "auto";
    }
}