// Redirect to post view
function showPost(x) {
    window.location.href = "../post/"+x;
}

// Show search form
function showSearch() {
    const mobileSearch = document.getElementById('mobile-search');
    const search = document.getElementById('searchInput');
    const searchRes = document.getElementById('search_result');
    const usermenu = document.getElementById('usermenu');
    usermenu.style.transform = 'translateX(100%)';

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
    const friend_list = document.getElementById('friend_list');
    const searchResult = document.getElementById('search_result');
    const searchResUsers = document.getElementById('search_res_users');
    const searchRUsers = document.getElementById('search_r_users');
    const searchResGroups = document.getElementById('search_res_groups');
    const searchRGroups = document.getElementById('search_r_groups');
    const searchResPages = document.getElementById('search_res_pages');
    const searchRPages = document.getElementById('search_r_pages');
    const searchResPosts = document.getElementById('search_res_posts');
    const searchRPosts = document.getElementById('search_r_posts');

    if (x.value.length >= 4) {
        searchResult.removeAttribute("hidden");
        $.ajax({
            url: '../assets/search.php',
            type: "POST",
            data: {
                text: x.value
            }
        }).done(function(result) {
            const res = JSON.parse(result);
            if (res != "") {
                if (res.user) {
                    searchResUsers.removeAttribute("hidden");
                    res.user.forEach(user => {
                        // Check if the user already exists in the search results
                        if (!document.querySelector(`.search_res_user[data-username="${user.username}"]`)) {
                            const userDiv = document.createElement('div');
                            userDiv.classList.add('search_res_user');
                            userDiv.setAttribute('data-username', user.username);
                            userDiv.onclick = () => window.location.href = `../profile?user=${user.username}`;

                            const avatarDiv = document.createElement('div');
                            avatarDiv.classList.add('search_res_user_avatar');

                            const avatarImg = document.createElement('img');
                            avatarImg.src = user.avatar;

                            avatarDiv.appendChild(avatarImg);
                            userDiv.appendChild(avatarDiv);
                            userDiv.appendChild(document.createTextNode(user.username));

                            searchRUsers.appendChild(userDiv);
                        }
                    });
                } else
                if (res.groups) {
                    searchResGroups.removeAttribute("hidden");
                    res.groups.forEach(group => {
                        const groupDiv = document.createElement('div');
                        groupDiv.classList.add('search_res_group');
                        groupDiv.onclick = () => window.location.href = `./group?g=${group.id}`;

                        const avatarDiv = document.createElement('div');
                        avatarDiv.classList.add('search_res_group_avatar');

                        const avatarImg = document.createElement('img');
                        avatarImg.src = group.icon;

                        avatarDiv.appendChild(avatarImg);
                        groupDiv.appendChild(avatarDiv);
                        groupDiv.appendChild(document.createTextNode(group.name));

                        searchRGroups.appendChild(groupDiv);
                    });
                } else
                if (res.pages) {
                    searchResPages.removeAttribute("hidden");
                    res.pages.forEach(page => {
                        const pageDiv = document.createElement('div');
                        pageDiv.classList.add('search_res_page');
                        pageDiv.onclick = () => window.location.href = `./page?p=${page.id}`;

                        const avatarDiv = document.createElement('div');
                        avatarDiv.classList.add('search_res_page_avatar');

                        const avatarImg = document.createElement('img');
                        avatarImg.src = page.icon;

                        avatarDiv.appendChild(avatarImg);
                        pageDiv.appendChild(avatarDiv);
                        pageDiv.appendChild(document.createTextNode(page.name));

                        searchRPages.appendChild(pageDiv);
                    });
                } else {
                    searchResUsers.setAttribute("hidden", "");
                    searchResGroups.setAttribute("hidden", "");
                    searchResPages.setAttribute("hidden", "");
                    searchResPosts.setAttribute("hidden", "");
                }
            } else {
                searchResult.innerHTML += "No results found";
            }
        });
    } else {
        searchResult.setAttribute("hidden", "");
    }
}

function feedbackInfo() {
    alert("This is a BETA feature.\n\nPlease report any bugs or issues you find.\nYour ID and current timestamp will be stored with what you submit.\n\nThank you for your help and feedback!");
}
document.querySelectorAll('#unsolved').forEach(item => {
    item.addEventListener('mouseover', event => {
        item.classList.remove('fa-regular', 'fa-circle');
        item.classList.add('fa-solid', 'fa-circle-check');
    });
    item.addEventListener('mouseout', event => {
        item.classList.remove('fa-solid', 'fa-circle-check');
        item.classList.add('fa-regular', 'fa-circle');
    });
});
function sendFeedback() {
    const feedback = document.getElementById('beta-feedback-text').value;
    const currentPage = window.location.href;
    if (feedback.length > 0) {
        $.ajax({
            url: '../assets/feedback.php',
            type: 'POST',
            data: {
                feedback: feedback,
                page: currentPage
            },
            success: function(response) {
                document.getElementById('beta-feedback-text').value = "";
                alert("Feedback sent!");
            }
        });
    }
}
function deleteFeedback(x) {
    $.ajax({
        url: '../assets/feedback.php',
        type: 'POST',
        data: {
            delete: x
        },
        success: function(response) {
            document.getElementById('feedback-'+x).remove();
        }
    });
}
function solveFeedback(x) {
    $.ajax({
        url: '../assets/feedback.php',
        type: 'POST',
        data: {
            solve: x
        },
        success: function() {
            window.location.reload();
        }
    });
}

function updateFileNameLabel() {
    const fileInput = document.getElementById('image_to_share');
    const fileNameLabel = document.getElementById('image_to_share_text');
    const filesDiv = document.getElementById('new_post_files');

    filesDiv.innerHTML = ''; // Clear the previous content

    if (fileInput.files.length === 0) {
        fileNameLabel.textContent = '0';
    } else if (fileInput.files.length === 1) {
        const file = fileInput.files[0];
        if (isFileSupported(file)) {
            fileNameLabel.textContent = fileInput.files.length;
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

        fileNameLabel.textContent = allFilesSupported ? fileInput.files.length : 'Invalid file type';
    }

    if (fileNameLabel.textContent === 'Invalid file type') {
        setTimeout(() => {
            fileNameLabel.textContent = '';
        }, 3000);
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
    const textarea = document.getElementById("new_post_input");
    textarea.rows = textarea.value.split("\n").length;
    newPost.style.height = newPost.innerHeight + textarea.clientHeight + "px";
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
            url: '../assets/posts/post_full.php',
            type: "POST",
            data: {
                post : x
            }
        }).done(function(postData) {
            image_post.innerHTML = postData;
            image_viewer.style.display = "flex";
        });

        $.ajax({
            url: '../assets/posts/post_images.php',
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
        url: '../assets/posts/post_images.php',
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

function hitEnter(input,x) {
    const button = document.getElementById('login');

    function handleKeyPress(event) {
        if (event.keyCode === 13) {
            sendComment(x);
        }
    }

    input.addEventListener('keydown', handleKeyPress, { once: true });
}

function showPostActions(x) {
    const actionList = document.getElementById("pal_"+x);
    
    if (actionList) {
        actionList.hidden = !actionList.hidden;
    }
}

let insertedPostIds = [];

function checkRegistrationDuration(registrationTimestamp, unlockDuration) {
    const currentTimestamp = Math.floor(Date.now() / 1000);
    const duration = currentTimestamp - registrationTimestamp;

    if (duration >= unlockDuration) {
        return true;
    } else {
        return false;
    }
}

function ctc() {
    let code = document.getElementById('frc');
    code = code.innerHTML.replace(/\s+/g, '');
    navigator.clipboard.writeText(code).then(() => {
        skybynAlert("ok","Referral code copied. Send it to a friend for use during sign up.");
    }).catch(err => {
        skybynAlert("err","Failed to copy.");
    });
}

function genRef() {
    const code = document.getElementById('frc');
    if (isNaN(code.innerHTML)) {
        $.ajax({
            url: '../assets/generate_ref_code.php',
            type: "POST"
        }).done(function(response) {
            if (response != null) {
                code.innerHTML = response;
            }
        });
    }
}

function expandFR() {
    const fr = document.getElementById('fr');
    if (fr.style.height == "auto") {
        fr.style.height = "35px";
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

function friendAction(friend, action) {
    const actions = document.getElementById('friend_actions');
    const buttons = {
        send: `
            <button class="blue" onclick="friendAction('${friend}', 'send')">
                <i class="fa-solid fa-user-plus"></i> <span>Send friend request</span>
            </button>`,
        cancel: `
            <button onclick="friendAction('${friend}', 'cancel')">
                <i class="fa-solid fa-user-xmark"></i> <span>Cancel friend request</span>
            </button>`,
        unfriend: `
            <button onclick="friendAction('${friend}', 'unfriend')">
                <i class="fa-solid fa-user-minus"></i> <span>Unfriend</span>
            </button>`,
        block: `
            <button class="red" onclick="friendAction('${friend}', 'block')">
                <i class="fa-solid fa-user-slash"></i> <span>Block</span>
            </button>`,
        unblock: `
            <button class="red" onclick="friendAction('${friend}', 'unblock')">
                <i class="fa-solid fa-user-slash"></i> <span>Unblock</span>
            </button>`,
        report: `
            <button class="orange" onclick="friendAction('${friend}', 'report')">
                <i class="fa-solid fa-triangle-exclamation"></i> <span>Report</span>
            </button>`
    };

    const templates = {
        "Friend request sent.": buttons.cancel + buttons.block + buttons.report,
        "Friend request accepted.": buttons.unfriend + buttons.block + buttons.report,
        "Friend request ignored.": buttons.send + buttons.block + buttons.report,
        "Friend request canceled.": buttons.send + buttons.block + buttons.report,
        "Unfriended.": buttons.send + buttons.block + buttons.report,
        "Blocked.": buttons.unblock + buttons.report,
        "Unblocked.": buttons.send + buttons.block + buttons.report
    };
    if (action == "check") {
        $.ajax({
            url: '../assets/friendship.php',
            type: 'POST',
            data: { friend, action }
        }).done(function(response) {
            
        });
    } else {
        $.ajax({
            url: '../assets/friendship.php',
            type: 'POST',
            data: { friend, action }
        }).done(function(response) {
            if (templates[response]) {
                actions.innerHTML = templates[response];
                ws.send(JSON.stringify({
                    type: 'notify',
                    to: friend
                }));
            } else {
                alert(`Error: ${response}`);
            }
        });
    }
}

// Function to extract metadata from a given URL
function extractMetadata(url) {
    // Create an iframe to load the URL content
    const iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    document.body.appendChild(iframe);
    
    iframe.onload = () => {
        // Wait for the content to load
        const doc = iframe.contentDocument || iframe.contentWindow.document;
        
        // Extract metadata
        const metadata = {
            title: doc.querySelector('title') ? doc.querySelector('title').innerText : '',
            description: doc.querySelector('meta[name="description"]') ? doc.querySelector('meta[name="description"]').getAttribute('content') : '',
            images: []
        };
        
        // Extract all images from meta tags and img elements
        const metaImages = doc.querySelectorAll('meta[property="og:image"], meta[name="twitter:image"], meta[itemprop="image"]');
        metaImages.forEach(meta => {
            metadata.images.push(meta.getAttribute('content'));
        });
        
        const imgElements = doc.querySelectorAll('img');
        imgElements.forEach(img => {
            metadata.images.push(img.getAttribute('src'));
        });
        
        // Log the metadata
        console.log(metadata);
        
        // Clean up
        document.body.removeChild(iframe);
    };
    
    // Set the iframe source to the given URL
    iframe.src = url;
}

async function fetchPreviews(urls,id) {
    const response = await fetch(`fetch_url_preview.php?urls=${encodeURIComponent(urls.join(','))}`);
    const metadataList = await response.json();

    const previewsContainer = document.getElementById('previews_'+id);
    previewsContainer.innerHTML = '';

    metadataList.forEach(metadata => {
        const previewContainer = document.createElement('div');
        previewContainer.classList.add('preview-container');
        
        const previewHeader = document.createElement('div');
        previewHeader.classList.add('preview-header');
        
        const logo = document.createElement('img');
        logo.src = metadata.logo;
        logo.alt = 'Logo';
        logo.classList.add('logo');
        
        const titleDescription = document.createElement('div');
        titleDescription.classList.add('title-description');
        
        const title = document.createElement('div');
        title.textContent = metadata.title;
        title.classList.add('title');
        
        const description = document.createElement('div');
        description.textContent = metadata.description;
        description.classList.add('description');
        
        titleDescription.appendChild(title);
        titleDescription.appendChild(description);
        
        previewHeader.appendChild(logo);
        previewHeader.appendChild(titleDescription);
        
        const featuredImage = document.createElement('img');
        featuredImage.src = metadata.featured_image;
        featuredImage.alt = 'Featured Image';
        featuredImage.classList.add('featured-image');
        
        previewContainer.appendChild(previewHeader);
        if (metadata.featured_image) {
            previewContainer.appendChild(featuredImage);
        }
        
        previewsContainer.appendChild(previewContainer);
    });
}

setTimeout(() => {
    const messageBoxes = document.getElementsByClassName('message-box');
    if (messageBoxes.length > 0) {
        for (let i = 0; i < messageBoxes.length; i++) {
            if (messageBoxes[i].classList.contains('open')) {
                messageBoxes[i].classList.remove('open');
                messageBoxes[i].classList.add('maximized');
            }
            const messageBox = messageBoxes[i].getElementsByClassName('message-body')[0];
            messageBox.scrollTop = messageBox.scrollHeight;
        }
    }
}, 1000);