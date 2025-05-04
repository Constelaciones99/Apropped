# utils.py
import os
from json import JSONDecodeError
import requests
import numpy as np
from PIL import Image
import tensorflow as tf
import faiss
from io import BytesIO
import logging
import time
from requests.adapters import HTTPAdapter
from urllib3.util.retry import Retry
import urllib3


urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)
# Configuración de logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# Configuración del modelo
try:
    model = tf.keras.applications.MobileNetV2(
        weights='imagenet', 
        include_top=False, 
        pooling='avg',
        input_shape=(224, 224, 3))
    image_size = (224, 224)
    logger.info("✅ Modelo MobileNetV2 cargado correctamente")
except Exception as e:
    logger.error(f"❌ Error al cargar el modelo: {str(e)}")
    raise

def create_session():
    """Crea una sesión HTTP con reintentos y headers"""
    session = requests.Session()
    retries = Retry(
        total=5,
        backoff_factor=0.3,
        status_forcelist=[500, 502, 503, 504],
        allowed_methods=['GET']
    )
    adapter = HTTPAdapter(max_retries=retries)
    session.mount('https://', adapter)
    
    session.headers.update({
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
        'Accept': 'application/json',
        'Accept-Encoding': 'gzip, deflate, br'
    })
    
    return session

def preprocess_image(image_data):
    """Preprocesa imagen desde bytes o ruta de archivo"""
    try:
        if isinstance(image_data, bytes):
            img = Image.open(BytesIO(image_data)).convert('RGB').resize(image_size)
        else:
            img = Image.open(image_data).convert('RGB').resize(image_size)
            
        img_array = tf.keras.preprocessing.image.img_to_array(img)
        return tf.keras.applications.mobilenet_v2.preprocess_input(img_array)
    except Exception as e:
        logger.error(f"❌ Error en preprocesamiento: {str(e)}")
        raise

def extract_features(image_data):
    """Extrae características de una imagen (bytes o ruta)"""
    try:
        img_array = preprocess_image(image_data)
        return model.predict(np.expand_dims(img_array, axis=0), verbose=0)[0].astype('float32')
    except Exception as e:
        logger.error(f"❌ Error en extracción de features: {str(e)}")
        raise

def build_faiss_index_from_url(list_url, base_url):
    """Construye índice FAISS desde URLs con manejo mejorado de errores"""
    session = create_session()
    
    try:
        logger.info("🔌 Conectando con el servidor...")
        response = session.get(
            list_url,
            verify=False,  # Para desarrollo
            timeout=20
        )
        
        # Debug detallado de la respuesta
        logger.debug(f"Status Code: {response.status_code}")
        logger.debug(f"Contenido respuesta: {response.text[:200]}...")
        
        response.raise_for_status()

        try:
            filenames = response.json()
        except JSONDecodeError as e:
            logger.error("❌ El servidor no devolvió un JSON válido")
            logger.error(f"Error en posición: {e.pos}")
            logger.error(f"Contenido problemático: {response.text[:200]}...")
            raise

        logger.info(f"📦 Recibidas {len(filenames)} referencias de imágenes")
        
    except Exception as e:
        logger.error(f"🔥 Error crítico: {str(e)}")
        raise RuntimeError("Error al comunicarse con el servidor")
    
    # Resto del código sin cambios...
    """Construye índice FAISS desde URLs con manejo mejorado de errores"""
    session = create_session()
    
    try:
        logger.info("🔌 Conectando con el servidor...")
        response = session.get(
            list_url,
            verify=False,
            timeout=20
        )
        
        # --- Nuevo código de debug ---
        logger.debug(f"Status Code: {response.status_code}")
        logger.debug(f"Headers: {response.headers}")
        logger.debug(f"Contenido (primeros 200 caracteres): {response.text[:200]}")
        
        response.raise_for_status()
        
        try:
            filenames = response.json()
        except JSONDecodeError:
            logger.error("⚠️¡El servidor no devolvió JSON válido!")
            logger.error(f"Contenido recibido: {response.text}")
            raise
        
        logger.info(f"📦 Recibidas {len(filenames)} referencias de imágenes")
        
    except Exception as e:
        logger.error(f"🔥 Error crítico: {str(e)}")
        raise RuntimeError("Error al comunicarse con el servidor")

    # Resto del código sin cambios...
    """Construye índice FAISS desde URLs con manejo mejorado de errores"""
    session = create_session()
    
    try:
        logger.info("🔌 Conectando con el servidor...")
        response = session.get(
            list_url,
            verify=False,  # Solo para desarrollo
            timeout=20
        )
        response.raise_for_status()
        filenames = response.json()
        logger.info(f"📦 Recibidas {len(filenames)} referencias de imágenes")
        
    except Exception as e:
        logger.error(f"🔥 Error crítico al conectar con el servidor: {str(e)}")
        logger.error("Verifica:")
        logger.error("1. Que la URL sea accesible desde tu navegador")
        logger.error("2. Que el servidor no esté bloqueando peticiones")
        logger.error("3. Que el firewall permita conexiones salientes")
        raise RuntimeError("Error de conexión con el servidor")

    index = faiss.IndexFlatL2(1280)
    features_list = []
    valid_filenames = []
    
    for idx, filename in enumerate(filenames):
        try:
            image_url = base_url + filename
            logger.debug(f"📡 Procesando ({idx+1}/{len(filenames)}): {filename}")
            
            # Descargar imagen con reintentos
            img_response = session.get(
                image_url,
                verify=False,
                timeout=15
            )
            img_response.raise_for_status()
            
            # Extraer características
            features = extract_features(img_response.content)
            
            # Validar dimensiones
            if features.shape != (1280,):
                raise ValueError(f"Dimensión inválida: {features.shape}")
                
            features_list.append(features)
            valid_filenames.append(filename)
            
            # Pequeña pausa para evitar sobrecargar el servidor
            time.sleep(0.1)
            
        except Exception as e:
            logger.warning(f"⚠️ Error procesando {filename}: {str(e)}")
            continue

    if not features_list:
        raise RuntimeError("🚨 No se encontraron imágenes válidas para indexar")

    try:
        logger.info("🔨 Construyendo índice FAISS...")
        features_array = np.array(features_list, dtype='float32')
        index.add(features_array)
        logger.info(f"🎉 Índice construido con {len(features_list)} imágenes")
        return index, valid_filenames, features_array
    except Exception as e:
        logger.error(f"💥 Error construyendo índice FAISS: {str(e)}")
        raise

def find_similar_images(uploaded_image_path, index, filenames, vectors, top_n=5):
    """Busca imágenes similares con manejo de errores mejorado"""
    try:
        logger.info("🔍 Iniciando búsqueda de imágenes similares...")
        uploaded_vector = extract_features(uploaded_image_path).reshape(1, -1)
        
        if index.ntotal == 0:
            logger.warning("⚠️ Índice FAISS vacío")
            return {'best_match': None, 'others': []}

        distances, indices = index.search(uploaded_vector, top_n)
        
        results = []
        for dist, idx in zip(distances[0], indices[0]):
            if idx < 0 or idx >= len(filenames):
                continue
                
            similarity = 1 / (1 + dist)
            results.append({
                'filename': filenames[idx],
                'similarity': round(similarity * 100, 2),
                'distance': float(dist)
            })

        logger.info(f"✅ Búsqueda completada. Coincidencias encontradas: {len(results)}")
        return {
            'best_match': results[0] if results else None,
            'others': results[1:top_n]
        }
        
    except Exception as e:
        logger.error(f"💥 Error en búsqueda: {str(e)}")
        return {'best_match': None, 'others': []}