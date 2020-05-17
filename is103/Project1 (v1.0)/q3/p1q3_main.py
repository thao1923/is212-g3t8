# p1q3_main.py
# Do not submit this file
# You may modify this file for testing purposes, 
# but your final p1q3.py must be able to run with the original p1q3_main.py.

from p1q3 import *
from p1q3_utility import *
import time
import copy
import sys 

# (1) ----- prepare data ------
print("")
budget = int(input("Enter budget (e.g. 10):"))
file_name_followers = input("Enter followers CSV file (e.g. case1.csv) :")
file_name_costs     = input("Enter costs     CSV file (e.g. cv_11a.csv):")
file_name_values    = input("Enter values    CSV file (e.g. cv_11b.csv):")

# read data from CSV files
print("(1) Reading followers from " + file_name_followers + " now...")
f = read_followers_file(file_name_followers)
n = len(f) # number of users in the system
print("    No. of users : " + str(n))
print("    Reading costs from " + file_name_costs + " now...")
c = read_cv_file(file_name_costs)
print("    Reading values from " + file_name_values + " now...")
v = read_cv_file(file_name_values)

# make clones of f, c and v 
f_clone = copy.deepcopy(f)
c_clone = copy.deepcopy(c)
v_clone = copy.deepcopy(v)


# (2) ----- run the test case ------
print("\n(2) Starting timer...")
print("    Calling select_advertisers now...")
start_time = time.time()
answer = select_advertisers(budget, f, c, v) # calls your function 
time_taken = time.time() - start_time
print("    Stopping timer...")
print("    Execution time " + str(time_taken) + " seconds.")    # display time lapsed

# (3) ----- syntax correctness checking code ------ 
print("\n(3) Checking your answer for syntax errors...")
print("    Your select_advertisers function returned this: " + str(answer))
error_message = get_error_message(answer, n)
if error_message != None:
  print("Your function is not correctly written :-(")
  print(error_message)
  sys.exit()

# (4) ----- budget correctness checking code ------ 
print("\n(4) Checking if your answer is within budget...")
total_cost = get_total_cost(answer, c_clone)
print("    total cost of your advertisers : " + str(total_cost))
if total_cost > budget: 
  print("Error: total cost has exceeded the budget of " + str(budget))
  sys.exit()

# (5) ----- all OK. get quality score ------    
print("\nYour function returned a valid answer - you may upload p1q3.py to the submission server")
reached_audience = get_unique_followers2 (answer, f_clone)
print("The following users will get the advertisement : " + str(reached_audience))
reached_audience += answer # include advertisers in reached_audience
quality_score = get_total_value(reached_audience, v_clone) 
print("Quality score is the sum of values for all users who have gotten the advertisement (higher is better)")
print("Quality score : " + str(quality_score))
