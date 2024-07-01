<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Delete</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            body {
                margin: 0 auto;
                margin-top: 50px;
                font-family: Arial, sans-serif;
            }

            .container {
                width: 70%;
                margin: 0 auto;
            }

            h1 {
                text-align: center;
            }

            .btn {
                display: block;
                width: 100%;
                padding: 10px;
                margin: 10px 0;
                border: none;
                border-radius: 5px;
                background-color: #007bff;
                color: #fff;
                cursor: pointer;
            }

            .btn-danger {
                background-color: #dc3545;
            }

            .btn-primary {
                background-color: #007bff;
            }

            .btn-block {
                display: block;
                width: 100%;
                padding: 10px;
                margin: 10px 0;
                border: none;
                background-color: #007bff;
                color: #fff;
                cursor: pointer;
            }
        </style>
    </head>
    <body>

        <div class="container">
            <h1>Are you sure you want to delete this?</h1>
            <button type="submit" name="delete" class="btn btn-danger btn-block">Delete</button>
            <button type="submit" name="cancel" class="btn btn-primary btn-block">Cancel</button>
        </div>

    </body>
</html>