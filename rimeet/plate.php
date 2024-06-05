<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Skiltnummer sjekk</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
            }
            h1 {
                margin: 0;
                padding: 1em;
                background-color: #333;
                color: white;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
                padding: 1em;
            }
            form {
                padding: 1em;
                background-color: #f9f9f9;
            }
            label {
                display: block;
                margin-bottom: .5em;
            }
            input {
                box-sizing: border-box;
            }
            input[type="text"] {
                width: 100%;
                padding: .5em;
                margin-bottom: 1em;
            }
            input[type="submit"] {
                padding: .5em;
                background-color: #333;
                color: white;
                border: none;
                cursor: pointer;
            }
            input[type="submit"]:hover {
                background-color: #444;
            }
            #result {
                padding: 1em;
                background-color: #f9f9f9;
                white-space: pre;
            }
        </style>
    </head>

    <body>
        <h1>Skiltnummer sjekk</h1>
        <div class="container">
            <p>Sjekk skiltnummeret til en bil:</p>
            <p>Eksempel: <code>AB12345</code></p>
            <form>
                <label for="nr">Sjekk skiltnummer:</label>
                <input type="text" name="nr" id="nr" placeholder="Skiltnummer">
                <input type="submit" value="Sjekk">
            </form>
            <div id="result"></div>
            <script>
                document.querySelector('form').addEventListener('submit', function(e) {
                    e.preventDefault();
                    var nr = document.querySelector('input[name="nr"]').value;
                    fetch('../assets/plate_check.php?nr='+nr)
                    .then(response => response.json())
                    .then(data => {
                        var result = document.getElementById('result');
                        result.innerHTML = '';
                        if (data.error) {
                            result.innerHTML = data.error;
                        } else {
                            result.innerHTML = data[0]['merke']+'/nModell: '+data[0]['modell']+'/nÅrsmodell: '+data[0]['arsmodell']+'/nFarge: '+data[0]['farge']+'/nUnderstellsnummer (VIN): '+data[0]['understellsnummer'];
                        }
                    });
                });
            </script>
        </div>
    </body>
</html>