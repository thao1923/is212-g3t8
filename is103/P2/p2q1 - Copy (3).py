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
def get_route(p, v, flags):
  # code here
  flag = {}
  for f in flags:
    flag[f[0]] = [f[0],int(f[1]),float(f[2]),float(f[3])]
  pocket = 0
  result = [[0]]
  while pocket <= p:
      visited = []
      if result[-1][0] == 0:
        secondp = [0, 0, 0, 0]
      else:
        secondp = flag[result[-1]]
      for f in flag:
          firstp = flag[f]
          if firstp[0] not in result:
              distance = ((secondp[2]-firstp[2])**2+(secondp[3]-firstp[3])**2)**0.5
              short = distance / firstp[1]
              visited.append([firstp[0], short, firstp[2], firstp[3]])
      visited.sort(key= lambda x: x[1])
      result.append(visited[0][0])
      pocket += flag[visited[0][0]][1]
  print(result[1:])
  return result[1:]
