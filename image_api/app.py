from flask import Flask, request, jsonify
from flask_cors import CORS
import os
from werkzeug.utils import secure_filename
from utils import find_similar_images, build_faiss_index_from_url

app = Flask(__name__)
CORS(app)

UPLOAD_FOLDER = 'image_api/uploads'
os.makedirs(UPLOAD_FOLDER, exist_ok=True)

# URLs remotas
IMAGE_LIST_URL = 'https://stringify.free.nf/listar-imagenes'
IMAGE_BASE_URL = 'https://stringify.free.nf/public/storage/productos/'

# Cargar el índice FAISS desde imágenes remotas
print("🧠 Cargando índice FAISS desde web...")
faiss_index, filenames_list, feature_vectors = build_faiss_index_from_url(IMAGE_LIST_URL, IMAGE_BASE_URL)
print("✅ FAISS cargado con", len(filenames_list), "imágenes")

@app.route('/upload', methods=['POST'])
def upload_image():
    if 'image' not in request.files:
        return jsonify({"error": "No se recibió una imagen"}), 400

    file = request.files['image']
    if file.filename == '':
        return jsonify({"error": "Nombre de archivo vacío"}), 400

    upload_path = os.path.join(UPLOAD_FOLDER, secure_filename(file.filename))
    file.save(upload_path)

    results = find_similar_images(upload_path, faiss_index, filenames_list, feature_vectors)
    return jsonify(results)

if __name__ == '__main__':
    app.run(debug=True)
