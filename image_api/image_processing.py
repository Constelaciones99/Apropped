# image_processing.py
import os
import numpy as np
from PIL import Image
import tensorflow as tf
from sklearn.metrics.pairwise import cosine_similarity
from .resize import resize_image

# Cargar el modelo preentrenado MobileNetV2 para extracción de características
model = tf.keras.applications.MobileNetV2(weights='imagenet', include_top=False, pooling='avg')
image_size = (224, 224)

# Preprocesar la imagen
def preprocess_image(image_path):
    img = Image.open(image_path).convert('RGB').resize(image_size)
    img_array = tf.keras.preprocessing.image.img_to_array(img)
    img_array = tf.keras.applications.mobilenet_v2.preprocess_input(img_array)
    return np.expand_dims(img_array, axis=0)

def extract_features(image_path):
    image = preprocess_image(image_path)
    features = model.predict(image)
    return features[0]

def calculate_similarity(image1_features, image2_features):
    return cosine_similarity([image1_features], [image2_features])[0][0]

# Función que usa la redimension de la imagen y el cálculo de similitud
def find_similar_images(uploaded_image_path, image_dir, top_n=5):
    resized_image = resize_image(uploaded_image_path)  # Redimensionamos la imagen cargada
    # Lógica paralela puede ser agregada aquí si se necesita
    results = []  # Aquí iría la lógica para comparar imágenes en el directorio
    return results[:top_n]
