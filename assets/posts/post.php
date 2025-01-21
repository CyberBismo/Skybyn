<div class="post" id="post_<?=$post_id?>">
    <div class="post_body">
        <div class="post_header">
            <div class="post_details">
                <div class="post_user">
                    <div class="post_user_image">
                        <img src="<?=$post_user_avatar?>">
                    </div>
                    <div class="post_user_name"><?=$post_user_name?></div>
                </div>
                <div class="post_date"><?=$post_created?></div>
            </div>
            <div class="post_actions">
                <i class="fa-solid fa-ellipsis-vertical"></i>
                <div class="post_action_list" hidden>
                    <!--div class="post_action">
                        <i class="fa-solid fa-share-nodes"></i>
                    </div-->
                    <div class="post_action" onclick="editPost(<?=$post_id?>)">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </div>
                    <div class="post_action" onclick="deletePost(<?=$post_id?>)">
                        <i class="fa-solid fa-trash"></i> Delete
                    </div>
                </div>
            </div>
        </div>
        <div id="post_c_<?=$post_id?>" hidden><?=$post_content?></div>
        <div class="post_content">
            <?=$post_content_res?>
        </div>
        <div class="post_links">
            <?=$post_links?>
        </div>
        <div class="post_comments" onclick="showPost(<?=$post_id?>)">
        <?=$comments?> comments
        </div>
    </div>
</div>