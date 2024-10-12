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
            cid = response.id;
            pid = response.post_id;
            const data = {
                type: 'new_comment',
                cid: cid,
                pid: pid
            };
            ws.send(JSON.stringify(data)); // Send the new post ID to the server
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