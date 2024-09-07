import requests
import time

url = "https://skybyn.no/assets/ccLog.php"
data = "clean"  # Replace with your actual data

while True:
    response = requests.post(url, data=data)

    if response.status_code == 200:
        print("POST request sent successfully!")
    else:
        print("Failed to send POST request. Status code:", response.status_code)

    time.sleep(60)  # Wait for 60 seconds before sending the next request