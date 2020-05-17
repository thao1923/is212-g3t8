# p1q2_main.py
# do NOT submit this file. Run it to test your solution. 
from p1q2 import *

# the following functions are used for answer checking  
# test case functions return True (if successful) or False (if failed)

# --------------------------------------------  
# test case a1
def test_case_a1():
  pref = [["m1", 1, 3, 2, 4], ["m2", 1, 2, 4, 3], ["m3", 1, 2, 3, 4], ["m4", 2, 3, 1, 4], ["f1", 2, 1, 3, 4], ["f2", 4, 3, 2, 1], ["f3", 1, 2, 3, 4], ["f4", 3, 4, 2, 1]]
  solution = [['m1', 'f3'], ['m3', 'f4'], ['m4', 'f2'], ['m2', 'f1']]
  expected_answer = True
  your_answer = is_stable(4, pref, solution)
 
  return your_answer == expected_answer
  
# --------------------------------------------  
# test case a2
def test_case_a2():
  pref = [["m1", 1, 3, 2, 4], ["m2", 1, 2, 4, 3], ["m3", 1, 2, 3, 4], ["m4", 2, 3, 1, 4], ["f1", 2, 1, 3, 4], ["f2", 4, 3, 2, 1], ["f3", 1, 2, 3, 4], ["f4", 3, 4, 2, 1]]
  solution = [["m1", "f3"], ["m2", "f1"], ["m3", "f4"], ["m4", "f2"]]
  expected_answer = True
  your_answer = is_stable(4, pref, solution)
 
  return your_answer == expected_answer

# --------------------------------------------  
# test case a3
def test_case_a3():
  pref = [["m1", 1, 3, 2, 4], ["m2", 1, 2, 4, 3], ["m3", 1, 2, 3, 4], ["m4", 2, 3, 1, 4], ["f1", 2, 1, 3, 4], ["f2", 4, 3, 2, 1], ["f3", 1, 2, 3, 4], ["f4", 3, 4, 2, 1]]
  solution = [['m4', 'f2'], ['m3', 'f4'], ['m2', 'f1'], ['m1', 'f3']]
  expected_answer = True
  your_answer = is_stable(4, pref, solution)
 
  return your_answer == expected_answer
  
# --------------------------------------------  
# test case a4
def test_case_a4():
  pref = [["m1", 1, 3, 2, 4], ["m2", 1, 2, 4, 3], ["m3", 1, 2, 3, 4], ["m4", 2, 3, 1, 4], ["f1", 2, 1, 3, 4], ["f2", 4, 3, 2, 1], ["f3", 1, 2, 3, 4], ["f4", 3, 4, 2, 1]]
  solution = [['m3', 'f4'], ['m2', 'f1'], ['m1', 'f3'], ['m4', 'f2']]
  expected_answer = True
  your_answer = is_stable(4, pref, solution)
 
  return your_answer == expected_answer
  
# --------------------------------------------  
# test case a5
def test_case_a5():
  pref = [["m1", 1, 3, 2, 4], ["m2", 1, 2, 4, 3], ["m3", 1, 2, 3, 4], ["m4", 2, 3, 1, 4], ["f1", 2, 1, 3, 4], ["f2", 4, 3, 2, 1], ["f3", 1, 2, 3, 4], ["f4", 3, 4, 2, 1]]
  solution = [['m2', 'f1'], ['m3', 'f4'], ['m1', 'f3'], ['m4', 'f2']]
  expected_answer = True
  your_answer = is_stable(4, pref, solution)
 
  return your_answer == expected_answer
  
# --------------------------------------------  
# test case a6
def test_case_a6():
  pref = [["m1", 1, 3, 2, 4], ["m2", 1, 2, 4, 3], ["m3", 1, 2, 3, 4], ["m4", 2, 3, 1, 4], ["f1", 2, 1, 3, 4], ["f2", 4, 3, 2, 1], ["f3", 1, 2, 3, 4], ["f4", 3, 4, 2, 1]]
  solution = [["m1", "f1"], ["m2", "f3"], ["m3", "f2"], ["m4", "f4"]]
  expected_answer = False
  your_answer = is_stable(4, pref, solution)
 
  return your_answer == expected_answer
  
# --------------------------------------------  
# test case a7
def test_case_a7():
  pref = [["m1", 1, 3, 2, 4], ["m2", 1, 2, 4, 3], ["m3", 1, 2, 3, 4], ["m4", 2, 3, 1, 4], ["f1", 2, 1, 3, 4], ["f2", 4, 3, 2, 1], ["f3", 1, 2, 3, 4], ["f4", 3, 4, 2, 1]]
  solution = [['m1', 'f3'], ['m4', 'f4'], ['m3', 'f1'], ['m2', 'f2']]
  expected_answer = False
  your_answer = is_stable(4, pref, solution)
 
  return your_answer == expected_answer

# --------------------------------------------  
# test case a8
def test_case_a8():
  pref = [["m1", 1, 3, 2, 4], ["m2", 1, 2, 4, 3], ["m3", 1, 2, 3, 4], ["m4", 2, 3, 1, 4], ["f1", 2, 1, 3, 4], ["f2", 4, 3, 2, 1], ["f3", 1, 2, 3, 4], ["f4", 3, 4, 2, 1]]
  solution = [['m4', 'f4'], ['m3', 'f2'], ['m2', 'f1'], ['m1', 'f3']]
  expected_answer = False
  your_answer = is_stable(4, pref, solution)
 
  return your_answer == expected_answer

# --------------------------------------------  
# test case a9
def test_case_a9():
  pref = [["m1", 1, 3, 2, 4], ["m2", 1, 2, 4, 3], ["m3", 1, 2, 3, 4], ["m4", 2, 3, 1, 4], ["f1", 2, 1, 3, 4], ["f2", 4, 3, 2, 1], ["f3", 1, 2, 3, 4], ["f4", 3, 4, 2, 1]]
  solution = [['m4', 'f1'], ['m2', 'f2'], ['m3', 'f4'], ['m1', 'f3']]
  expected_answer = False
  your_answer = is_stable(4, pref, solution)
 
  return your_answer == expected_answer
  
# --------------------------------------------  
# test case a10
def test_case_a10():
  pref = [["m1", 1, 3, 2, 4], ["m2", 1, 2, 4, 3], ["m3", 1, 2, 3, 4], ["m4", 2, 3, 1, 4], ["f1", 2, 1, 3, 4], ["f2", 4, 3, 2, 1], ["f3", 1, 2, 3, 4], ["f4", 3, 4, 2, 1]]
  solution = [['m3', 'f2'], ['m4', 'f3'], ['m2', 'f1'], ['m1', 'f4']]
  expected_answer = False
  your_answer = is_stable(4, pref, solution)
 
  return your_answer == expected_answer

# --------------------------------------------
# test case b1
def test_case_b1():
  pref = [["m1", 1, 4, 7, 8, 5, 2, 3, 6], ["m2", 6, 5, 7, 8, 1, 4, 3, 2], ["m3", 4, 1, 5, 3, 6, 2, 8, 7], ["m4", 6, 2, 4, 5, 1, 3, 8, 7], ["m5", 5, 1, 8, 3, 4, 2, 6, 7], ["m6", 3, 7, 2, 4, 1, 5, 8, 6], ["m7", 1, 3, 8, 2, 5, 4, 6, 7], ["m8", 4, 5, 1, 8, 3, 7, 2, 6],        ["f1", 7, 4, 5, 8, 1, 3, 2, 6], ["f2", 2, 7, 5, 8, 6, 4, 3, 1], ["f3", 4, 8, 3, 2, 1, 6, 7, 5], ["f4", 6, 3, 5, 7, 8, 2, 4, 1], ["f5", 1, 7, 6, 4, 5, 8, 3, 2], ["f6", 5, 2, 3, 8, 6, 7, 1, 4], ["f7", 1, 6, 4, 7, 8, 2, 3, 5], ["f8", 5, 4, 6, 2, 1, 8, 3, 7]]
  solution = [['m1', 'f7'], ['m2', 'f8'], ['m3', 'f6'], ['m4', 'f3'], ['m5', 'f1'], ['m6', 'f4'], ['m7', 'f2'], ['m8', 'f5']]
  expected_answer = True
  your_answer = is_stable(8, pref, solution)
 
  return your_answer == expected_answer
# --------------------------------------------
# test case b2
def test_case_b2():
  pref = [["m1", 1, 4, 7, 8, 5, 2, 3, 6], ["m2", 6, 5, 7, 8, 1, 4, 3, 2], ["m3", 4, 1, 5, 3, 6, 2, 8, 7], ["m4", 6, 2, 4, 5, 1, 3, 8, 7], ["m5", 5, 1, 8, 3, 4, 2, 6, 7], ["m6", 3, 7, 2, 4, 1, 5, 8, 6], ["m7", 1, 3, 8, 2, 5, 4, 6, 7], ["m8", 4, 5, 1, 8, 3, 7, 2, 6],        ["f1", 7, 4, 5, 8, 1, 3, 2, 6], ["f2", 2, 7, 5, 8, 6, 4, 3, 1], ["f3", 4, 8, 3, 2, 1, 6, 7, 5], ["f4", 6, 3, 5, 7, 8, 2, 4, 1], ["f5", 1, 7, 6, 4, 5, 8, 3, 2], ["f6", 5, 2, 3, 8, 6, 7, 1, 4], ["f7", 1, 6, 4, 7, 8, 2, 3, 5], ["f8", 5, 4, 6, 2, 1, 8, 3, 7]]
  solution = [['m1', 'f7'], ['m2', 'f8'], ['m3', 'f6'], ['m4', 'f5'], ['m5', 'f1'], ['m6', 'f4'], ['m7', 'f2'], ['m8', 'f3']]
  expected_answer = True
  your_answer = is_stable(8, pref, solution)
 
  return your_answer == expected_answer
# --------------------------------------------
# test case b3
def test_case_b3():
  pref = [["m1", 1, 4, 7, 8, 5, 2, 3, 6], ["m2", 6, 5, 7, 8, 1, 4, 3, 2], ["m3", 4, 1, 5, 3, 6, 2, 8, 7], ["m4", 6, 2, 4, 5, 1, 3, 8, 7], ["m5", 5, 1, 8, 3, 4, 2, 6, 7], ["m6", 3, 7, 2, 4, 1, 5, 8, 6], ["m7", 1, 3, 8, 2, 5, 4, 6, 7], ["m8", 4, 5, 1, 8, 3, 7, 2, 6],        ["f1", 7, 4, 5, 8, 1, 3, 2, 6], ["f2", 2, 7, 5, 8, 6, 4, 3, 1], ["f3", 4, 8, 3, 2, 1, 6, 7, 5], ["f4", 6, 3, 5, 7, 8, 2, 4, 1], ["f5", 1, 7, 6, 4, 5, 8, 3, 2], ["f6", 5, 2, 3, 8, 6, 7, 1, 4], ["f7", 1, 6, 4, 7, 8, 2, 3, 5], ["f8", 5, 4, 6, 2, 1, 8, 3, 7]]
  solution = [['m1', 'f7'], ['m2', 'f4'], ['m3', 'f8'], ['m4', 'f1'], ['m5', 'f3'], ['m6', 'f2'], ['m7', 'f6'], ['m8', 'f5']]
  expected_answer = False
  your_answer = is_stable(8, pref, solution)
 
  return your_answer == expected_answer
# --------------------------------------------
# test case b4
def test_case_b4():
  pref = [["m1", 1, 4, 7, 8, 5, 2, 3, 6], ["m2", 6, 5, 7, 8, 1, 4, 3, 2], ["m3", 4, 1, 5, 3, 6, 2, 8, 7], ["m4", 6, 2, 4, 5, 1, 3, 8, 7], ["m5", 5, 1, 8, 3, 4, 2, 6, 7], ["m6", 3, 7, 2, 4, 1, 5, 8, 6], ["m7", 1, 3, 8, 2, 5, 4, 6, 7], ["m8", 4, 5, 1, 8, 3, 7, 2, 6],        ["f1", 7, 4, 5, 8, 1, 3, 2, 6], ["f2", 2, 7, 5, 8, 6, 4, 3, 1], ["f3", 4, 8, 3, 2, 1, 6, 7, 5], ["f4", 6, 3, 5, 7, 8, 2, 4, 1], ["f5", 1, 7, 6, 4, 5, 8, 3, 2], ["f6", 5, 2, 3, 8, 6, 7, 1, 4], ["f7", 1, 6, 4, 7, 8, 2, 3, 5], ["f8", 5, 4, 6, 2, 1, 8, 3, 7]]
  solution = [['m1', 'f8'], ['m2', 'f3'], ['m3', 'f7'], ['m4', 'f6'], ['m5', 'f4'], ['m6', 'f2'], ['m7', 'f1'], ['m8', 'f5']]
  expected_answer = False
  your_answer = is_stable(8, pref, solution)
 
  return your_answer == expected_answer  
# --------------------------------------------
# test case b5
def test_case_b5():
  pref = [["m1", 1, 4, 7, 8, 5, 2, 3, 6], ["m2", 6, 5, 7, 8, 1, 4, 3, 2], ["m3", 4, 1, 5, 3, 6, 2, 8, 7], ["m4", 6, 2, 4, 5, 1, 3, 8, 7], ["m5", 5, 1, 8, 3, 4, 2, 6, 7], ["m6", 3, 7, 2, 4, 1, 5, 8, 6], ["m7", 1, 3, 8, 2, 5, 4, 6, 7], ["m8", 4, 5, 1, 8, 3, 7, 2, 6],        ["f1", 7, 4, 5, 8, 1, 3, 2, 6], ["f2", 2, 7, 5, 8, 6, 4, 3, 1], ["f3", 4, 8, 3, 2, 1, 6, 7, 5], ["f4", 6, 3, 5, 7, 8, 2, 4, 1], ["f5", 1, 7, 6, 4, 5, 8, 3, 2], ["f6", 5, 2, 3, 8, 6, 7, 1, 4], ["f7", 1, 6, 4, 7, 8, 2, 3, 5], ["f8", 5, 4, 6, 2, 1, 8, 3, 7]]
  solution = [['m1', 'f5'], ['m2', 'f6'], ['m3', 'f3'], ['m4', 'f2'], ['m5', 'f8'], ['m6', 'f7'], ['m7', 'f1'], ['m8', 'f4']]
  expected_answer = False
  your_answer = is_stable(8, pref, solution)
 
  return your_answer == expected_answer  
# --------------------------------------------
# test case c1
def test_case_c1():
  pref = [["m1", 1, 3, 2], ["m2", 2, 3, 1], ["m3", 2, 1, 3], ["f1", 3, 1, 2], ["f2", 2, 3, 1], ["f3", 2, 1, 3]]
  solution = [['m1', 'f1'], ['m2', 'f3'], ['m3', 'f2']]
  expected_answer = True
  your_answer = is_stable(3, pref, solution)
 
  return your_answer == expected_answer
# --------------------------------------------
# test case c2
def test_case_c2():
  pref = [["m1", 1, 3, 2], ["m2", 2, 3, 1], ["m3", 2, 1, 3], ["f1", 3, 1, 2], ["f2", 2, 3, 1], ["f3", 2, 1, 3]]
  solution = [['m1', 'f3'], ['m2', 'f1'], ['m3', 'f2']]
  expected_answer = False
  your_answer = is_stable(3, pref, solution)
 
  return your_answer == expected_answer
# --------------------------------------------
# test case c3
def test_case_c3():
  pref = [["m1", 1, 3, 2], ["m2", 2, 3, 1], ["m3", 2, 1, 3], ["f1", 3, 1, 2], ["f2", 2, 3, 1], ["f3", 2, 1, 3]]
  solution = [['m1', 'f2'], ['m2', 'f3'], ['m3', 'f1']]
  expected_answer = False
  your_answer = is_stable(3, pref, solution)
 
  return your_answer == expected_answer
# --------------------------------------------
# test case c4
def test_case_c4():
  pref = [["m1", 1, 3, 2], ["m2", 2, 3, 1], ["m3", 2, 1, 3], ["f1", 3, 1, 2], ["f2", 2, 3, 1], ["f3", 2, 1, 3]]
  solution = [['m1', 'f2'], ['m2', 'f1'], ['m3', 'f3']]
  expected_answer = False
  your_answer = is_stable(3, pref, solution)
 
  return your_answer == expected_answer  
# --------------------------------------------
# test case c5
def test_case_c5():
  pref = [["m1", 1, 3, 2], ["m2", 2, 3, 1], ["m3", 2, 1, 3], ["f1", 3, 1, 2], ["f2", 2, 3, 1], ["f3", 2, 1, 3]]
  solution = [['m1', 'f3'], ['m2', 'f2'], ['m3', 'f1']]
  expected_answer = False
  your_answer = is_stable(3, pref, solution)
 
  return your_answer == expected_answer  
# --------------------------------------------  
  
# actual code that runs 
print("Passed Test case a1?  : " + str(test_case_a1()))
print("Passed Test case a2?  : " + str(test_case_a2()))
print("Passed Test case a3?  : " + str(test_case_a3()))
print("Passed Test case a4?  : " + str(test_case_a4()))
print("Passed Test case a5?  : " + str(test_case_a5()))
print("Passed Test case a6?  : " + str(test_case_a6()))
print("Passed Test case a7?  : " + str(test_case_a7()))
print("Passed Test case a8?  : " + str(test_case_a8()))
print("Passed Test case a9?  : " + str(test_case_a9()))
print("Passed Test case a10? : " + str(test_case_a10()))
print()
print("Passed Test case b1?  : " + str(test_case_b1()))
print("Passed Test case b2?  : " + str(test_case_b2()))
print("Passed Test case b3?  : " + str(test_case_b3()))
print("Passed Test case b4?  : " + str(test_case_b4()))
print("Passed Test case b5?  : " + str(test_case_b5()))
print()
print("Passed Test case c1?  : " + str(test_case_c1()))
print("Passed Test case c2?  : " + str(test_case_c2()))
print("Passed Test case c3?  : " + str(test_case_c3()))
print("Passed Test case c4?  : " + str(test_case_c4()))
print("Passed Test case c5?  : " + str(test_case_c5()))