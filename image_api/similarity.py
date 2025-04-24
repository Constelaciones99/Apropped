# similarity.py
from sklearn.metrics.pairwise import cosine_similarity

def calculate_similarity(image1_features, image2_features):
    return cosine_similarity([image1_features], [image2_features])[0][0]
