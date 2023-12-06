<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>

        <textarea id="text" placeholder="Text" onkeyup="convertEmoji(this.value)"></textarea>

        <script>
        function convertEmoji(string) {
            let text = document.getElementById('text');
            const emojiMap = {
                ':)': '🙂',
                ':D': '😁',
                ':P': '😛',
                ':(': '🙁',
                ';)': '😉',
                ':O': '😮',
                ':*': '😘',
                '<3': '❤️',
                ':/': '😕',
                ':|': '😐',
                ':$': '🤫',
                ':s': '😕',
                ':o)': '👽',
                ':-(': '😞',
                ':-)': '😊',
                ':-D': '😂',
                ':-P': '😜',
                ':-/': '😕',
                ':-|': '😐',
                ';-)': '😉',
                '=)': '😊',
                '=D': '😃',
                '=P': '😛',
                '=\\': '😕',
                ':poop:': '💩',
                ':fire:': '🔥',
                ':rocket:': '🚀',
            };
            text.value = string.replace(/(:\)|:D)/g, (match) => emojiMap[match]);
        }
        </script>

    </body>
</html>