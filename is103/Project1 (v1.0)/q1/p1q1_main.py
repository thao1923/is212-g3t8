# q1_main.py
# do NOT submit this file. Run it to test your solution. 
from p1q1 import *

# the following functions are used for answer checking  
# test case functions return True (if successful) or False (if failed)

# --------------------------------------------  
# test case 1
def test_case1():
  m = [[3],[1]]
  expected_answer = [[7],[2]]
  your_answer = q1(m)
  
  return your_answer == expected_answer
# --------------------------------------------  
# test case 2
def test_case2():
  m = [[1,-3],[4, 5]]
  expected_answer = [[3,-1],[13,10]]
  your_answer = q1(m)
  
  return your_answer == expected_answer
# --------------------------------------------  
# test case 3
def test_case3():
  m = [[1,2,3],[4,5,6],[7,8,9]]
  expected_answer = [[18,20,21],[26,24,21],[31,25,18]]
  your_answer = q1(m)

  return your_answer == expected_answer
# --------------------------------------------  
def test_case4():
  m = [[2, 1, 3, 4],[4, 9, 8, 2],[6, 2, 7, 3],[5, 2, 4, 2]]
  expected_answer = [[27, 22, 29, 15], [38, 32, 29, 9], [29, 16, 21, 8], [18, 10, 10, 4]]
  your_answer = q1(m)

return your_answer == expected_answer 
# --------------------------------------------  
# actual code that runs 
print("Test case 1 returned : ", end="")
print(test_case1())
print("Test case 2 returned : ", end="")
print(test_case2())
print("Test case 3 returned : ", end="")
print(test_case3()) 
print("Test case 4 returned : ", end="")
print(test_case4())
