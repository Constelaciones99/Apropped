# parallel.py
from concurrent.futures import ThreadPoolExecutor
from image_api.image_processing import extract_features, calculate_similarity  # Importamos de image_processing

def process_image(upload_path, image_path):
    uploaded_features = extract_features(upload_path)  # Usamos extract_features correctamente
    image_features = extract_features(image_path)  # Usamos extract_features correctamente
    similarity = calculate_similarity(uploaded_features, image_features)
    return image_path, similarity

def process_all_in_parallel(upload_path, image_paths):
    with ThreadPoolExecutor() as executor:
        futures = [executor.submit(process_image, upload_path, path) for path in image_paths]
        results = [f.result() for f in futures]
    return results
