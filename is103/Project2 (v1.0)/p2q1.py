# <Your Team ID>
# <Team members' names>

# project 2 Q1

# replace the content of this function with your own algorithm
# inputs: 
#   p: min target no. of points player must collect. p>0
#   v: 1 (non-cycle) or 2 (cycle)
#   flags: 2D list [[flagID, value, x, y], [flagID, value, x, y]....]
# returns:
#   1D list of flagIDs to represent a route. e.g. [F002, F005, F003, F009]
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

def get_dist(your_route, flags_dict, v):
  # calculate distance and points
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
    
  return dist # no error


def generate_flags_dict(flags_list):
  d = {}
  for item in flags_list:
    #             flagID,  points,       x,              y
    d[item[0]] =[item[0], int(item[1]), float(item[2]), float(item[3])]
  return d

  
def get_route(p, v, flags):
  # code here
  flags_dict = generate_flags_dict(flags)
  flag_lst =[]
  for flag in flags:
    fitness  = get_distance([0,0,0,0], flags_dict[flag[0]]) / float(flag[1])
    flag_lst.append([flag[0], fitness])
        
  flag_lst.sort(key= lambda x: x[1])
  comparing = []
  for i in range(5):
    pocket = flags_dict[flag_lst[i][0]][1]
    result = [flag_lst[i][0]]
    while pocket < p:
        visited = []
        last_node  = result[-1]
        for flag in flags:
            if flag[0] not in result:
                distance = get_distance(flags_dict[last_node], flags_dict[flag[0]])
                short = (distance ) / float(flag[1]) 
                visited.append([flag[0], short, flag[2], flag[3]])
        visited.sort(key= lambda x: x[1])
        result.append(visited[0][0])
        pocket += flags_dict[visited[0][0]][1]
    comparing.append([result, get_dist(result, flags_dict, v)])
  comparing.sort(key= lambda x: x[1])

  return two_opt(comparing[0][0], flags_dict)
 
