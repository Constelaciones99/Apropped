# test_connection.py
import requests

url = "https://stringify.free.nf/listar-imagenes"

try:
    response = requests.get(url, verify=False, timeout=20)
    print("Status Code:", response.status_code)
    print("Contenido:", response.text)
except Exception as e:
    print("Error:", str(e))