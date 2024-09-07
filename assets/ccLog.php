<?php if (isset($_GET['x'])) {?>
<script>
    function readClientInfoLog() {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'cleanClientLog.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                console.log(response.message);
                setTimeout(() => {
                    readClientInfoLog();
                }, 10000);
            }
        };
        xhr.send();
    }
    readClientInfoLog();
</script>
<?php }?>