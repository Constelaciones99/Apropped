import os
import numpy as np
from PIL import Image
import tensorflow as tf
import faiss

# Configurar el modelo y tamaño de imagen
model = tf.keras.applications.MobileNetV2(weights='imagenet', include_top=False, pooling='avg')
image_size = (224, 224)

# Cargar y preprocesar imagen
def preprocess_image(image_path):
    img = Image.open(image_path).convert('RGB').resize(image_size)
    img_array = tf.keras.preprocessing.image.img_to_array(img)
    img_array = tf.keras.applications.mobilenet_v2.preprocess_input(img_array)
    return np.expand_dims(img_array, axis=0)

# Extraer features de imagen
def extract_features(image_path):
    image = preprocess_image(image_path)
    features = model.predict(image, verbose=0)
    return features[0]  # Flatten vector

# Precarga los vectores y construye el índice FAISS
def build_faiss_index(image_dir):
    index = faiss.IndexFlatL2(1280)  # MobileNetV2 output = 1280 dims
    filenames = []
    features_list = []

    for filename in os.listdir(image_dir):
        path = os.path.join(image_dir, filename)
        if not os.path.isfile(path): continue
        try:
            features = extract_features(path)
            features_list.append(features.astype('float32'))
            filenames.append(filename)
        except:
            continue

    if not features_list:
        raise Exception("No se pudieron cargar imágenes")

    vectors = np.stack(features_list).astype('float32')
    index.add(vectors)
    return index, filenames, vectors

# Buscar imágenes similares usando el índice
def find_similar_images(uploaded_image_path, index, filenames, vectors, top_n=5):
    uploaded_vector = extract_features(uploaded_image_path).astype('float32').reshape(1, -1)

    if index.ntotal == 0:
        return {
            'best_match': None,
            'others': []
        }

    distances, indices = index.search(uploaded_vector, top_n)

    results = []
    for dist, idx in zip(distances[0], indices[0]):
        if idx >= len(filenames):
            continue
        similarity = 1 / (1 + dist)
        results.append((filenames[idx], float(similarity)))

    if not results:
        return {
            'best_match': None,
            'others': []
        }

    return {
        'best_match': {
            'filename': results[0][0],
            'similarity': round(results[0][1] * 100, 2)
        },
        'others': [
            {'filename': fn, 'similarity': round(score * 100, 2)}
            for fn, score in results[1:]
        ]
    }
