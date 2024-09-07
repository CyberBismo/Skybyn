<?php if (isset($_GET['x'])) {?>
<script>
    function cleanClientLog() {
        const password = prompt('Enter password to clean client log:');
        if (password == null) {
            return;
        }
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'cleanClientLog.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
            setTimeout(() => {
                readClientInfoLog();
            }, 10000);
            }
        };
        xhr.send();
    }
    cleanClientLog();
</script>
<?php }?>