# G2T1 AUTOBOTS ROLL OUT

Intelligent Transport Systems: Object Detection in Autonomous Cars using YOLO and Faster RCNN

Data Source: [Udacity Self Driving Car Dataset](https://public.roboflow.com/object-detection/self-driving-car)

Instructions:
1. Create a conda env: `conda create --name g2t1-obj-det --file -c anaconda -c pytorch -c conda-forge requirements.txt`
2. Activate conda env: `conda activate g2t1-obj-det`
3. Run `Data Preprocessing.ipynb` to download data in YOLO TXT format, preprocess & split into train/test/val dataset
4. Run `YOLO/yolov5.ipynb` to train and test YOLO model
5. Run `FRCNN/frcnn_preprocessing.ipynb` to preprocess data for Faster R-CNN models (VOC XML format conversion & annotation files creation)
6. Run `FRCNN/resnet50/train_resnet.ipynb` & `FRCNN/resnet50/test_resnet.ipynb` to train and test Faster R-CNN model with Resnet50 as base network
7. Run `FRCNN/vgg16/train_vgg.ipynb` & `FRCNN/vgg16/test_vgg.ipynb` to train and test Faster R-CNN model with VGG16 as base network
