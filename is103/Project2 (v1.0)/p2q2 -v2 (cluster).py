# <Your Team ID>
# <Team members' names>

# project 2 Q2
from sklearn.cluster import KMeans
from sklearn.metrics import silhouette_score
import pandas as pd
import numpy as np
# replace the content of this function with your own algorithm
# inputs: 
#   p: min target no. of points team must collect. p>0
#   v: 1 (non-cycle) or 2 (cycle)
#   flags: 2D list [[flagID, value, x, y], [flagID, value, x, y]....]
# returns:
#   A list of n lists. Each "inner list" represents a route. There must be n routes in your answer
def two_opt(arr, flags_dict):
    line_arr = generate_line_arr(arr, flags_dict)
    next_arr = resolve_lines(arr, line_arr)
    while next_arr != arr:
        arr = next_arr
        line_arr = generate_line_arr(arr, flags_dict)
        next_arr = resolve_lines(arr, line_arr)
        line_arr = generate_line_arr(arr, flags_dict)
    return next_arr
    
def resolve_lines(result, lineArr):
    for i in range(len(lineArr) - 2):
        for j in range(i + 2, len(lineArr)):
            if do_lines_intersect(lineArr[i], lineArr[j]):
                temp = result[:i + 1]
                temp += result[j:i : -1]
                temp += result[j + 1:]
                return temp 
    return result
      
      
def has_intersect_lines(lineArr):
    for i in range(0, len(lineArr) - 2):
        for j in range(i + 2, len(lineArr)):
            if do_lines_intersect(lineArr[i], lineArr[j]):
                return True
    return False

def generate_line_arr(result, flags_dict):
    lineArr = []
    for i in range(0, len(result) - 1):
        lineArr.append(Line(Point(x = flags_dict[result[i]][2], y = flags_dict[result[i]][3] ), Point(x = flags_dict[result[i + 1]][2], y = flags_dict[result[i + 1]][3])))
    return lineArr
  
class Point:
    def __init__(self, x = 0, y = 0, v = None):
        if v != None:
            self.x = v.x 
            self.y = v.y
        else:
            self.x = x
            self.y = y 
  
    def get_x(self):
        return self.x
  
    def get_y(self):
        return self.y 
  
    def __repr__(self):
        return '(' + str(self.x) + ', ' + str(self.y) + ')'
class Line:
    def __init__(self, p1, p2):
        self.point1 = p1
        self.point2 = p2 
  
    def get_first(self):
        return self.point1
  
    def get_second(self):
        return self.point2
  
    def __repr__(self):
        return 'p1: ' + str(self.point1) + ', p2: ' + str(self.point2)

def cross_product(a, b):
    return a.x * b.y - b.x * a.y

def is_point_on_line(line, point):
    tempLine = Line(Point(0, 0), Point(line.get_second().x - line.get_first().x, line.get_second().y - line.get_first().y))
    tempPoint = Point(point.x - line.get_first().x, point.y - line.get_first().y)
    r = cross_product(tempLine.get_second(), tempPoint)
    return abs(r) < 0.000001

def is_point_right_of_line(line, point): 
    tempLine = Line(Point(0, 0), Point(line.get_second().x - line.get_first().x, line.get_second().y - line.get_first().y));
    tempPoint = Point(point.x - line.get_first().x, point.y - line.get_first().y);
    return cross_product(tempLine.get_second(), tempPoint) < 0

def line_segment_touches_or_crosses_line(line1, line2):
    return (is_point_right_of_line(line1, line2.get_first()) ^ is_point_right_of_line(line1, line2.get_second()))

def do_lines_intersect(line1, line2):
    return line_segment_touches_or_crosses_line(line1, line2) and line_segment_touches_or_crosses_line(line2, line1)


def get_distance(node_A, node_B):
  return ((node_A[2] - node_B[2]) ** 2 + (node_A[3] - node_B[3]) ** 2) ** 0.5

def generate_flags_dict(flags_list):
  d = {}
  for item in flags_list:
    #             flagID,  points,       x,              y
    d[item[0]] = [item[0], int(item[1]), float(item[2]), float(item[3])]
  return d

def get_dist_and_points1(your_route, flags_dict, v):

  # check for syntax error first
  dist = 0
  points = 0

  start_node = ["Start", 0, 0, 0] # starting point (0, 0)
  last_node = start_node
  
  for flagID in your_route:
    curr_node = flags_dict[flagID]
    dist_to_curr_node = get_distance(last_node, curr_node)
    dist += dist_to_curr_node
    points += curr_node[1]

    last_node = curr_node

  # to go back to SP?
  if v == 2:   # cycle back to SP
    dist += get_distance(last_node, start_node)
    
  return dist, points # no error

def get_dist_and_points2(your_routes, flags_dict, v, n):
  
  # need to call get_dist_and_points_q1 for every route in your_routes
  tot_dist = 0
  tot_points = 0
  
  for route in your_routes:
    curr_dist, curr_points = get_dist_and_points1(route, flags_dict, v)

    tot_dist += curr_dist
    tot_points += curr_points
      
  return tot_dist, tot_points   # all OK

def get_most_suitable_flag(last_node, flags, touched_flags, flags_dict):
  lst = []
  for flag in flags:
    if flag[0] not in touched_flags:
      distance = get_distance(flags_dict[last_node], flags_dict[flag[0]])
      fitness = float(flag[1])  / distance
      lst.append([flag , fitness])

  lst.sort(key= lambda x: -x[1])
  return [lst[0][0][0], lst[0][1]]

# def get_most_suitable_flag(route, flags, touched_flags, flags_dict, v):
#   lst = []
#   curr_dist, curr_point = get_dist_and_points1(route, flags_dict, v)
#   for flag in flags:
#     if flag[0] not in touched_flags:
#       temp = route[:]
#       temp.append(flag[0])
#       distance, p = get_dist_and_points1(temp, flags_dict, v)
#       fitness = float(flag[1])  / (distance - curr_dist)
#       lst.append([flag , fitness])

#   lst.sort(key= lambda x: -x[1])
#   return [lst[0][0][0], lst[0][1]]


# def get_best_position(route, flags, v, flags_dict, touched_flags):
#     flags_at_best_position = []
#     curr_dist, curr_point = get_dist_and_points1(route, flags_dict, v)
#     for flag in flags:
#         if flag[0] not in touched_flags:
#             best_fitness_score = 0
#             index = None
#             pointt = float(flag[1])
#             for i in range(len(route)):    
#                 temp_route = route[:]
#                 temp_route.insert(i, flag[0])
#                 dist, point = get_dist_and_points1(temp_route, flags_dict, v)
#                 fitness_score = pointt / (dist - curr_dist)
#                 if fitness_score > best_fitness_score:
#                     best_fitness_score = fitness_score
#                     index = i
#             flags_at_best_position.append([flag, index, best_fitness_score])
    
#     flags_at_best_position.sort(key= lambda x: -x[2])
#     insert_flag = flags_at_best_position[0][0]
#     insert_index = flags_at_best_position[0][1]
#     flags.remove(insert_flag)
#     return insert_flag[0], insert_index, flags


# def cluster_flags(flags,n):
# 	df = pd.DataFrame(flags, columns = ['flagID', "point", "X", "Y"])
# 	df1 = df.loc[:,"X":"Y"]
# 	model1 = KMeans(n_clusters = n, random_state = 99)
# 	model1.fit(df1)
# 	df['player'] = model1.labels_
# 	clustered_flags = df.values.tolist()
# 	return clustered_flags

# def choose_cluster(flags, flags_dict):
#   temp = []
#   for flag in flags:
#     dist = get_distance([0,0,0,0], flags_dict[flag[0]]) 
#     point = float(flag[1])
#     temp.append([flag[0], point, dist])     
  
#   data = pd.DataFrame(temp, columns = ['flagID', "point", "dist"])
#   prepared_df = data[['point', 'dist']]
#   sil = []
#   for k in range(2, 11):
#     kmeans = KMeans(n_clusters = k).fit(prepared_df)
#     labels = kmeans.labels_
#     sil.append([k, silhouette_score(prepared_df, labels)])
#   sil.sort(key=lambda x: -x[1])
  
#   n_clusters = sil[0][0]
#   model = KMeans(n_clusters = n_clusters, random_state = 99)
#   model.fit(prepared_df)
#   data['cluster'] = model.labels_
#   a = data.groupby('cluster')['dist'].max().values.tolist()
  
#   min_dist = a[0]
#   chosen = 0
#   for i in range(1, len(a)):
#     if a[i] < min_dist:
#       min_dist = a[i]
#       chosen = i
#   return_flags = data[data['cluster'] == i]['flagID'].to_list()

#   return return_flags




def get_routes(p, v, flags, n):
  # code here
  flags_dict = generate_flags_dict(flags)

  temp = []
  for flag in flags:
    dist = get_distance([0,0,0,0], flags_dict[flag[0]]) 
    fitness = float(flag[1]) / dist 
    temp.append([flag, fitness])     
  temp.sort(key= lambda x: -x[1])
  

  comparing = []
  for i in range(0, n*10, n):
    return_lst = [[] for i in range(n)]
    fitness_lst = [[] for i in range(n)]
    touched_flags = []
    j = i
    for k in range(len(return_lst)):
      last_node = temp[k][0][0]
      return_lst[k].append(last_node)
      touched_flags.append(last_node)
      fitness_lst[k] = [k] + get_most_suitable_flag(last_node, flags, touched_flags, flags_dict)
      j += 1
    tot_dist, tot_points = get_dist_and_points2(return_lst, flags_dict, v, n)

    while tot_points < p:
      fitness_lst.sort(key= lambda x: -x[2])
      insert_flag = fitness_lst[0][1]
      insert_route = fitness_lst[0][0]
      if insert_flag not in touched_flags:
        return_lst[insert_route].append(insert_flag)
        touched_flags.append(insert_flag)

        tot_dist, tot_points = get_dist_and_points2(return_lst, flags_dict, v, n)
 

      if tot_points >= p:
        break

      last_node = return_lst[insert_route][-1]
      fitness_lst[0] = [insert_route] + get_most_suitable_flag(last_node, flags, touched_flags, flags_dict)

    comparing.append([return_lst, tot_dist])
  comparing.sort(key= lambda x: x[1])
  return_lst = comparing[0][0]
  for lst in return_lst:
    lst = two_opt(lst, flags_dict)
  return return_lst
