from flask import Flask, request

app = Flask(__name__)

@app.route('/', methods=['POST'])
def log_message():
    data = request.json
    message = data.get('message')
    if message:
        with open('visitor_log.txt', 'a') as f:
            f.write(f'{message}\n')
        return 'Message logged!', 200
    return 'No message received!', 400

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
