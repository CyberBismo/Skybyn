<?php if (isset($_GET['x'])) {?>
<script>
    function tickingClock() {
        var currenttime = new Date().toLocaleTimeString();
        document.getElementById('time').innerHTML = `<p>${currenttime}</p>`;
        setTimeout(() => {
            tickingClock();
        }, 1000);
    }
    function readClientInfoLog() {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'cleanClientLog.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                const status = document.getElementById('status');
                const time = new Date().toLocaleTimeString();
                status.innerHTML = `<p>${time}: ${response.message}</p>`;
                setTimeout(() => {
                    readClientInfoLog();
                }, 10000);
            }
        };
        xhr.send();
    }
    tickingClock();
    readClientInfoLog();
</script>
<div id="time"></div>
<div id="status"></div>
<?php }?>