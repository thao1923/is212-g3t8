# Name: Bui Phuong Thao
# Section: G4

# p1q3

# All statements should only be in functions. Do not include statements outside functions in this file.
# You may insert additional helper functions into this file if desired. 
def get_unique_followers2 (advertisers, f):
    users_who_get_advert = []
  
    for a in advertisers:
        users_who_get_advert += f[a]
    users_who_get_advert = set(users_who_get_advert) # remove duplicates. put in set
  
    # remove original advertisers
    for a in advertisers:  
        users_who_get_advert.discard(a)

    return sorted(list(users_who_get_advert))  # return a sorted list of users_who_get_advert

def get_total_cost(advertisers, c):
    total_cost = 0
    for a in advertisers:
        if a>=0 and a<len(c): 
            total_cost += c[a]
        else:
            return -1 #error 
    return total_cost

def get_total_value(users, v):
    return get_total_cost(users, v) 

def select_advertisers(budget, followers, c, v):
    # TODO: modify this function
    cost = 0
    ans = []
    gotten_msg = []
    while cost < budget:
        temp = []
        for i in range(len(followers)):
            if i not in ans and c[i] < v[i]:
                val = get_total_value(followers[i] + [i], v)
                temp.append([i, val])

        if len(temp) > 0:
            temp.sort(key= lambda x: -x[1])
            top_user_id = temp[0][0]
            ans.append(top_user_id)

            gotten_msg = followers[top_user_id]
        else:
            return ans
        
        for j in range(len(followers)):
            followers[j] = list(set(followers[j]) - set(gotten_msg)) 

        cost = get_total_cost(ans, c)
    return ans[:len(ans) - 1]
        
  
