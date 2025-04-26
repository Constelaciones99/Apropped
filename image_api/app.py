#method FAISS
#app.py
from flask import Flask, request, jsonify
from flask_cors import CORS
import os
from werkzeug.utils import secure_filename
from utils import find_similar_images, build_faiss_index

app = Flask(__name__)
CORS(app)

UPLOAD_FOLDER = 'image_api/uploads'
os.makedirs(UPLOAD_FOLDER, exist_ok=True)

# Ruta con imágenes de productos
image_dir = os.path.join('..', 'storage', 'app', 'public', 'productos')

# 🚀 Cargar FAISS index al iniciar
print("🧠 Cargando index FAISS...")
faiss_index, filenames_list, feature_vectors = build_faiss_index(image_dir)
print("✅ FAISS cargado con", len(filenames_list), "imágenes")

@app.route('/upload', methods=['POST'])
def upload_image():
    if 'image' not in request.files:
        return jsonify({"error": "No image file part"}), 400

    file = request.files['image']
    if file.filename == '':
        return jsonify({"error": "No selected file"}), 400

    upload_path = os.path.join(UPLOAD_FOLDER, secure_filename(file.filename))
    file.save(upload_path)

    results = find_similar_images(upload_path, faiss_index, filenames_list, feature_vectors)
    print("Resultado FAISS:", results)
    return jsonify(results)

if __name__ == '__main__':
    app.run(debug=True)
