function showNotifications() {
    const notifications = document.getElementById('notifications');
    const notiList = document.getElementById('noti-list');
    if (notifications.style.display == "block") {
        notifications.style.display = "none";
    } else {
        notifications.style.display = "block";
        $.ajax({
            url: '../assets/noti/noti_get.php'
        }).done(function(response) {
            notiList.innerHTML = response;
        });
    }
}

function showNoti(x) {
    let notiWin = document.getElementById('notification-window');
    let notWin_avatar = document.getElementById('noti_win_avatar');
    let notWin_user = document.getElementById('noti_win_username');
    let notWin_text = document.getElementById('noti_win_text');
    let notWin_foot_profile = document.getElementById('noti_win_foot_profile');

    notiWin.removeAttribute("hidden");

    $.ajax({
        url: '../assets/noti/noti_window_data.php',
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
            var profileURL = "window.location.href='./profile?u="+noti_profile+"'";
            notWin_foot_profile.setAttribute("onclick",profileURL);
        } else {
            var profileURL = "window.location.href='./profile?u="+data.notiUserUsername+"'";
            notWin_foot_profile.setAttribute("onclick",profileURL);
        }
        notWin_text.innerHTML = data.noti_content;

        $.ajax({
            url: '../assets/noti/noti_status.php',
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
        url: '../assets/noti/noti_status.php',
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
            url: '../assets/noti/noti_delete.php',
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
            url: '../assets/noti/noti_delete.php',
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
    var notiAlert = document.getElementsByClassName('notification_alert');
    $.ajax({
        url: '../assets/noti/noti_check.php'
    }).done(function(response) {
        if (response == "unread") {
            for (i = 0; i < notiAlert.length; i++) {
                notiAlert[i].style.opacity = '1';
            }
        } else {
            for (i = 0; i < notiAlert.length; i++) {
                notiAlert[i].style.opacity = '0';
            }
        }
    });
}
checkNoti();

function expandNoti(x) {
    if (x.style.height === "auto") {
        x.style.height = "40px";
    } else {
        x.style.height = "auto";
    }
}

function markRead(x) {
    let noti = document.getElementById('noti_'+x);
    let mark = noti.querySelectorAll('i');

    for (i = 0; i < mark.length; i++) {
        if (mark[i].classList.contains('fa-envelope')) {
            mark[i].classList.remove('fa-envelope');
            mark[i].classList.add('fa-envelope-open-text');
        }
    }

    $.ajax({
        url: '../assets/noti/noti_read.php',
        type: "POST",
        data: {
            noti : x
        }
    });

    checkNoti();
}