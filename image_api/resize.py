from PIL import Image

def resize_image(image_path, size=(300, 300)):
    img = Image.open(image_path)
    img = img.resize(size, Image.Resampling.LANCZOS)
    img.save(image_path)
    return image_path