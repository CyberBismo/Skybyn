<!DOCTYPE html>
<html>
<head>
    <title>View Data</title>
</head>
<body>
    <h1>Registered data:</h1>
    <h4>This data was deleted upon read.</h4>
    <pre>
        <?php
        // Read and display the file contents
        $file = fopen('data.txt', 'r');
        echo fread($file, filesize('data.txt'));
        fclose($file);
        unlink('data.txt');
        ?>
    </pre>
</body>
</html>
