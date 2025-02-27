<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Test</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="">
        <style>
            ul {
                list-style-type: none;
                padding: 0;
            }
            ul li::before {
                content: '\2713'; /* Unicode for checkmark */
                padding-right: 10px;
                color: green; /* Optional: change color of the checkmark */
            }
        </style>
    </head>
    <body>
        <ul>
            <li>Item 1</li>
            <li>Item 2</li>
            <li>Item 3</li>
        </ul>
    </body>
</html>