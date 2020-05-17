# Name: Bui Phuong Thao
# Section: G4

# p1q2

# All statements should only be in functions. Do not include statements outside functions in this file.
# You may insert additional helper functions into this file if desired. 

def dictionary_conversion(pref):
    pref_dict = {}
    for person in pref:
        pref_dict[person[0]] = person[1:]
    return pref_dict

def is_stable(n, pref, solution):
    pref_dict = dictionary_conversion(pref)
    sol_dict = dictionary_conversion(solution)
    # TODO: modify this function
    for pair in solution:
        man = pair[0]
        woman = pair[1]
        woman_pref = pref_dict[woman]
        man_place_in_w_pref = woman_pref[int(man[1]) - 1]
        if man_place_in_w_pref > 1:
            for i in range(1, man_place_in_w_pref):
                woman_preferred_man = "m" + str(woman_pref.index(i) + 1)
                woman_preferred_man_pref = pref_dict[woman_preferred_man]
                if woman_preferred_man_pref[int(woman[1]) - 1] < woman_preferred_man_pref[int(sol_dict[woman_preferred_man][0][1]) -1]:
                    return False

        

    return True
  
