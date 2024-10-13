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
    $.ajax({
        url: 'assets/comment_delete.php',
        type: "POST",
        data: {
            comment_id : x
        }
    }).done(function(response) {
        const res = JSON.parse(response);
        const postId = res.post_id;
        comment.remove();
        const data = {
            type: 'delete_comment',
            cid: x,
            pid: postId
        };
        ws.send(JSON.stringify(data)); // Send the new post ID to the server
    });
}