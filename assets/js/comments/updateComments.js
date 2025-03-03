function sendComment(x) {
    const input = document.getElementById('pc_'+x);

    if (input.value.length > 0) {
        $.ajax({
            url: 'assets/comments/comment_new.php',
            type: "POST",
            data: {
                post_id : x,
                comment : input.value
            }
        }).done(function(response) {
            const res = JSON.parse(response);
            input.value = "";
            cid = res.comment_id;
            pid = res.post_id;
            const data = {
                type: 'new_comment',
                cid: cid,
                pid: pid
            };
            ws.send(JSON.stringify(data)); // Send the new post ID to the server
        });
    }
}

function delComment(x) {
    const comment = document.getElementById('comment_'+x);
    comment.remove();
    $.ajax({
        url: 'assets/comments/comment_delete.php',
        type: "POST",
        data: {
            comment_id : x
        }
    }).done(function(response) {
        const res = JSON.parse(response);
        const postId = res.post_id;
        const data = {
            type: 'delete_comment',
            cid: x,
            pid: postId
        };
        ws.send(JSON.stringify(data)); // Send the new post ID to the server
    });
}

function expandComments(x) {
    const post = document.getElementById('post_'+x);
    const comments = post.getElementsByClassName('post_comments')[0];
    const expand = document.getElementById('post_comment_expand_'+x);
    if (comments) {
        comments.style.height = "auto";
        comments.style.maxHeight = "500px";
        comments.style.scrollBehavior = "smooth";
        expand.innerHTML = "Show less";
        expand.setAttribute('onclick', `collapseComments(${x})`);
    }
}

function collapseComments(x) {
    const post = document.getElementById('post_'+x);
    const comments = post.getElementsByClassName('post_comments')[0];
    const expand = document.getElementById('post_comment_expand_'+x);
    if (comments) {
        comments.style.height = "auto";
        comments.style.maxHeight = "320px";
        comments.style.scrollBehavior = "smooth";
        expand.innerHTML = "Show more";
        expand.setAttribute('onclick', `expandComments(${x})`);
    }
}