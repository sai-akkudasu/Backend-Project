import requests
url = "http://localhost/projects/crud_opertions/get_user.php"
params = {"id": 1}  

try:
    response = requests.get(url, params)
    if response.status_code == 200:
        user_data = response.json()
        print("User Data:", user_data)
    else:
        print(f"Failed to retrieve data. Status code: {response.status_code}")
        print("Error:", response.json().get('error', 'Unknown error'))

except requests.exceptions.RequestException as e:
    print("An error occurred:", e)