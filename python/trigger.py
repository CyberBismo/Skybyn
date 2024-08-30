from flask import Flask, request

app = Flask(__name__)

@app.route('/trigger', methods=['POST'])
def trigger_script():
    # Your script logic here
    return "Script triggered", 200

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
