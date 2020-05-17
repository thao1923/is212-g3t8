# Name: Bui Phuong Thao
# Section: G4

# p1q1

# TODO: fill q1_recursive
# m is a matrix represented by a 2D list of integers. e.g. m = [[1,2,3],[4,5,6],[7,8,9]]
# This function returns the another 2D list based on the specified logic in the requirements.

# DO NOT EDIT q1(m)
def q1(m):
  # creating an output 2D list with the same dimensions and initializing all values to None
  # q1_recursive function will update this 2D list accordingly with the computed values
  output = [[None for i in range(len(m[0]))] for j in range(len(m))]
  q1_recursive(m, output, 0, 0)
  return output
  
def q1_recursive(m, output, row, col):
    # base case
    numCols = len(m[0])
    numRows = len(m)
    if row == numRows:
        return output
    # reduction step - valid cell => process across and down and compute total
    else:
        across = 0
        down = 0
        for i in range(col, numCols):
            across += m[row][i]
        for j in range(row, numRows):
            down += m[j][col]  
        output[row][col] = across + down
        if col  == numCols - 1:
            q1_recursive(m, output, row + 1, 0)
        else:
            q1_recursive(m, output, row, col + 1)
  
 
   
