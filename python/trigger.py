from flask import Flask, request, jsonify

app = Flask(__name__)

@app.route('/trigger', methods=['POST'])
def trigger_script():
    # Extract data from the request
    data = request.json
    message = data.get('message', 'No message received')
    
    # Here you can execute any logic or trigger any script
    print(f"Received message: {message}")
    
    # Respond to the client
    return jsonify({"status": "success", "message": f"Script triggered with message: {message}"}), 200

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
