import numpy as np
import math
import cv2
import copy
from matplotlib import pyplot as plt
import pandas as pd
from model import data_generators
import os


def format_img_size(img, C):
	""" formats the image size based on config """
	img_min_side = float(C.im_size)
	(height,width,_) = img.shape
		
	if width <= height:
		ratio = img_min_side/width
		new_height = int(ratio * height)
		new_width = int(img_min_side)
	else:
		ratio = img_min_side/height
		new_width = int(ratio * width)
		new_height = int(img_min_side)
	img = cv2.resize(img, (new_width, new_height), interpolation=cv2.INTER_CUBIC)
	return img, ratio	

def format_img_channels(img, C):
	""" formats the image channels based on config """
	img = img[:, :, (2, 1, 0)]
	img = img.astype(np.float32)
	img[:, :, 0] -= C.img_channel_mean[0]
	img[:, :, 1] -= C.img_channel_mean[1]
	img[:, :, 2] -= C.img_channel_mean[2]
	img /= C.img_scaling_factor
	img = np.transpose(img, (2, 0, 1))
	img = np.expand_dims(img, axis=0)
	return img

def format_img(img, C):
	""" formats an image for model prediction based on config """
	img, ratio = format_img_size(img, C)
	img = format_img_channels(img, C)
	return img, ratio

# Method to transform the coordinates of the bounding box to its original size
def get_real_coordinates(ratio, x1, y1, x2, y2):

	real_x1 = int(round(x1 // ratio))
	real_y1 = int(round(y1 // ratio))
	real_x2 = int(round(x2 // ratio))
	real_y2 = int(round(y2 // ratio))

	return (real_x1, real_y1, real_x2 ,real_y2)

def round_decimals_down(number:float, decimals:int=2):
    """
    Returns a value rounded down to a specific number of decimal places.
    """
    if not isinstance(decimals, int):
        raise TypeError("decimal places must be an integer")
    elif decimals < 0:
        raise ValueError("decimal places has to be 0 or more")
    elif decimals == 0:
        return math.floor(number)

    factor = 10 ** decimals
    return math.floor(number * factor) / factor

def plot_one_box(x, img, color=None, label=None, line_thickness=None):
    # Plots one bounding box on image img
    tl = line_thickness or round(0.002 * (img.shape[0] + img.shape[1]) / 2) + 1  # line/font thickness
    c1, c2 = (int(x[0]), int(x[2])), (int(x[1]), int(x[3]))
    cv2.rectangle(img, c1, c2, color, thickness=tl, lineType=cv2.LINE_AA)
    tf = max(tl - 1, 1)  # font thickness
    t_size = cv2.getTextSize(label, 0, fontScale=tl / 3, thickness=tf)[0]
    c2 = c1[0] + t_size[0], c1[1] - t_size[1] - 3
    cv2.rectangle(img, c1, c2, color, -1, cv2.LINE_AA)  # filled
    cv2.putText(img, label, (c1[0], c1[1] - 2), 0, tl / 3, [225, 255, 255], thickness=tf, lineType=cv2.LINE_AA)
        
def plot_images(images, class_to_color, path=None, test_path = None, mode = 'gt', fname='images.jpg', names=None, max_size=640, max_subplots=16):
    tl = 3  # line thickness
    tf = max(tl - 1, 1)  # font thickness
    targets = []
    imread_img = []
    class_dict = {'0': 'Biker', '1':'Car', '2':'Pedestrian', '3': 'Traffic Light', '4':'Traffic Light Green', '5': 'Traffic Light Red', '6': 'Traffic Light Yellow', '7': 'Truck', 'bg':'bg'}
    for img_id in images:
        target = []
        filepath = os.path.join(test_path, img_id)
        img = cv2.imread(filepath)
        height, width, c = img.shape
        imread_img.append(img)
        bboxes = images[img_id]
        for box in bboxes:
            xmin = box['xmin']
            xmax = box['xmax']
            ymin = box['ymin']
            ymax = box['ymax']
            label_name = box['cls']
            if mode == 'pred':
                probs = box['probs']
                target.append(np.array([xmin, xmax, ymin, ymax, label_name, img_id, probs]))
            else: 
                target.append(np.array([xmin, xmax, ymin, ymax, label_name, img_id]))
        targets.append(np.array(target))
        
    targets = np.array(targets)
    imread_img = np.array(imread_img)
    bs ,h, w, _ = imread_img.shape # batch size, _, height, width
    bs = min(bs, max_subplots)  # limit plot images
    ns = np.ceil(bs ** 0.5)  # number of subplots (square)

    # Check if we should resize
    scale_factor = max_size / max(h, w)
    if scale_factor < 1:
        h = math.ceil(scale_factor * h)
        w = math.ceil(scale_factor * w)

    # Empty array for output
    mosaic = np.full((int(ns * h), int(ns * w), 3), 255, dtype=np.uint8)

    for i, img in enumerate(imread_img):
        if i == max_subplots:  # if last batch has fewer images than we expect
            break

        block_x = int(w * (i // ns))
        block_y = int(h * (i % ns))

        if scale_factor < 1:
            img = cv2.resize(img, (w, h))
        
        mosaic[block_y:block_y + h, block_x:block_x + w, :] = img
        image_targets = np.array(targets[i])
    
        boxes = image_targets[:, :4].T.astype('int')
        classes = image_targets[:, 4]

        boxes[[0, 1]] += block_x
        boxes[[2, 3]] += block_y
        if mode == 'pred':
            probs = image_targets[:, 6]
        for j, box in enumerate(boxes.T):
            cls = classes[j]
            color = (int(class_to_color[cls][0]), int(class_to_color[cls][1]), int(class_to_color[cls][2]))
            if mode == 'pred':
                label = '{}: {}'.format(cls, probs[j]) 
                plot_one_box(box, mosaic, label=label, color=color, line_thickness=tl)
            else:
                plot_one_box(box, mosaic, label=cls, color=color, line_thickness=tl)

        # Draw image filename labels
        label =  image_targets[:, 5][0]
        t_size = cv2.getTextSize(label, 0, fontScale=tl / 3, thickness=tf)[0]
        cv2.putText(mosaic, label, (block_x + 5, block_y + t_size[1] + 5), 0, tl / 3, [220, 220, 220], thickness=tf,
                            lineType=cv2.LINE_AA)

        # Image border
        cv2.rectangle(mosaic, (block_x, block_y), (block_x + w, block_y + h), (255, 255, 255), thickness=3)


    # mosaic = cv2.resize(mosaic, (int(ns * w * 0.5), int(ns * h * 0.5)), interpolation=cv2.INTER_AREA)
    cv2.imwrite(path, mosaic)

def get_map(pred, gt, f):
	T = {}
	P = {}
	iou_result = 0
	fx, fy = f
	class_dict = {'0': 'Biker', '1':'Car', '2':'Pedestrian', '3': 'Traffic Light', '4':'Traffic Light Green', '5': 'Traffic Light Red', '6': 'Traffic Light Yellow', '7': 'Truck', 'bg':'bg'}
	n = 0
	for bbox in gt:
		bbox['bbox_matched'] = False

	pred_probs = np.array([s['prob'] for s in pred])
	box_idx_sorted_by_prob = np.argsort(pred_probs)[::-1]

	for box_idx in box_idx_sorted_by_prob:
		pred_box = pred[box_idx]
		pred_class = pred_box['class']
		pred_x1 = pred_box['x1']
		pred_x2 = pred_box['x2']
		pred_y1 = pred_box['y1']
		pred_y2 = pred_box['y2']
		pred_prob = pred_box['prob']
		if pred_class not in P:
			P[pred_class] = []
			T[pred_class] = []
		P[pred_class].append(pred_prob)
		found_match = False

		for gt_box in gt:
			gt_class = class_dict[str(gt_box['class'])]
			gt_x1 = gt_box['x1']/fx
			gt_x2 = gt_box['x2']/fx
			gt_y1 = gt_box['y1']/fy
			gt_y2 = gt_box['y2']/fy
			gt_seen = gt_box['bbox_matched']
			if gt_class != pred_class:
				continue
			if gt_seen:
				continue
			iou_score = 0
			iou_score = data_generators.iou((pred_x1, pred_y1, pred_x2, pred_y2), (gt_x1, gt_y1, gt_x2, gt_y2))
			
			if iou_score >= 0.5:
				found_match = True
				gt_box['bbox_matched'] = True
				iou_result += iou_score
				n += 1
				break
			else:
				continue

		T[pred_class].append(int(found_match))
	for gt_box in gt:
		if not gt_box['bbox_matched']: # and not gt_box['difficult']:
			if class_dict[str(gt_box['class'])] not in P:
				P[class_dict[str(gt_box['class'])]] = []
				T[class_dict[str(gt_box['class'])]] = []

			T[class_dict[str(gt_box['class'])]].append(1)
			P[class_dict[str(gt_box['class'])]].append(0)
	if n == 0:
		ret_iou = iou_result
	else:
		ret_iou =  iou_result/n
	#import pdb
	#pdb.set_trace()
	return T, P, ret_iou

def format_img_map(img, C):
	img_min_side = float(C.im_size)
	(height,width,_) = img.shape
	
	if width <= height:
		f = img_min_side/width
		new_height = int(f * height)
		new_width = int(img_min_side)
	else:
		f = img_min_side/height
		new_width = int(f * width)
		new_height = int(img_min_side)
	fx = width/float(new_width)
	fy = height/float(new_height)
	img = cv2.resize(img, (new_width, new_height), interpolation=cv2.INTER_CUBIC)
	img = img[:, :, (2, 1, 0)]
	img = img.astype(np.float32)
	img[:, :, 0] -= C.img_channel_mean[0]
	img[:, :, 1] -= C.img_channel_mean[1]
	img[:, :, 2] -= C.img_channel_mean[2]
	img /= C.img_scaling_factor
	img = np.transpose(img, (2, 0, 1))
	img = np.expand_dims(img, axis=0)
	return img, fx, fy




    
  