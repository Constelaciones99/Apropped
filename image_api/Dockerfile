FROM python:3.10-slim

WORKDIR /app
COPY . .

RUN pip install tensorflow==2.10.0 faiss-cpu flask requests pillow opencv-python

CMD ["python", "app.py"]