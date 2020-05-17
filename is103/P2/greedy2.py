def get_best_position(your_route, flags, v, flags_dict):
    flags_at_best_position = []
    curr_dist = get_dist(your_route, flags_dict, v)
    for flag in flags:
        if flag[0] not in your_route:
            best_fitness_score = 0
            index = None
            best_dist = None
            point = float(flag[1])
            for i in range(len(your_route)):    
                temp_route = your_route[:]
                temp_route.insert(i, flag[0])
                dist = get_dist(temp_route, flags_dict, v)
                fitness_score = point / (dist - curr_dist)
                if fitness_score > best_fitness_score:
                    best_fitness_score = fitness_score
                    index = i
                    best_dist = dist
            flags_at_best_position.append([flag[0], index, best_dist])
    

    flags_at_best_position.sort(key= lambda x: -x[2])
    return flags_at_best_position[0] 
