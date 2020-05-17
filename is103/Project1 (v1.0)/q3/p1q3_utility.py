# p1q3_utility.py
# Do not submit this file

# Ensure that this file is in the same folder as p1q3_main.py

# reads a followers CSV file and returns a 2D list of ints
# e.g. read_followers_file("case1.csv") will return: [[2], [0, 3], [0, 1], [1, 2, 4, 5], [1, 6, 10], [], [7, 8], [], [], [8], [9]
def read_followers_file(file_name):
  input = []
  with open("data/" + file_name, "r") as file:
    for line in file:
      line = line.rstrip("\n")
      current_list = line.split(",")
      index = int(current_list.pop(0))  # 1st element is the index. assumption: the index is always in sequence: 0, 1, 2,.... etc
      current_list = [int(i) for i in current_list]  # convert all elements from strings into ints 
      input.append(current_list)        # insert into list   
  return input

# reads a costs or value CSV file and returns a 1D list of costs or values
# e.g. read_costs_file("cv_11a.csv") will return [1, 2, 1, 2, 2, 1, 7, 2, 2, 5, 3]
def read_cv_file(file_name):
  with open("data/" + file_name, "r") as file:
    input = [int(line) for line in file]
  return input

# takes in an answer (e.g. [1,5,3,6,8] and n (the number of users in the Tweeter system). and returns either
# - an error message (string), or
# - None (meaning there is no error with syntax of answer). 
# answer must be a list of unique integers within range (0 to n-1).
# note: this function sorts the original answer in ascending order
def get_error_message(answer, n):
  if answer == None:
    return "Error : your function returned None. It should return a list of 5 integers."
  elif type(answer) is not list:
    return "Error : your function returned something other than a list. It should return a list of user IDs (integers)."
  elif len(answer) == 0:
    return "Error : your function returned an empty list. It should return a list of user IDs (integers)."  
  elif not all(isinstance(i, int) for i in answer):  # check if all elements in answer are int
    return "Error : your function returned a list of elements, but not all of them are integers. It should return a list of user IDs (integers)."
  elif len(answer) != len(set(answer)): # check if there are duplicate ints in answer
    return "Error : there are duplicate user IDs in your list."
  else: # check if the IDs are within range
    answer.sort()
    if answer[0] < 0:
      return "Error : There is a user ID in your answer that is <0."
    if answer[-1] >= n:
      return "Error : There is a user ID in your answer that is >= " + str(n)
  # no problem if u reach here
  return None  
    
 
# takes in 2 arguments:
#   - selected is an list of user IDs (advertisers)
#   - f is a 2D list of followers 
# returns an list of unique followers for the advertisers in sorted order (excluding the advertisers)
def get_unique_followers2 (advertisers, f):
  users_who_get_advert = []
  
  for a in advertisers:
    users_who_get_advert += f[a]
  users_who_get_advert = set(users_who_get_advert) # remove duplicates. put in set
  
  # remove original advertisers
  for a in advertisers:  
    users_who_get_advert.discard(a)

  return sorted(list(users_who_get_advert))  # return a sorted list of users_who_get_advert

# return the total value of all users 
# returns -1 if there is at least 1 user that cannot be found in v
def get_total_value(users, v):
  return get_total_cost(users, v) 

# return the total costs of advertisers. all costs are expected to be positive.
# returns -1 if there is at least 1 advertiser that cannot be found in c 
def get_total_cost(advertisers, c):
  total_cost = 0
  for a in advertisers:
    if a>=0 and a<len(c): 
      total_cost += c[a]
    else:
      return -1 #error 
  return total_cost
