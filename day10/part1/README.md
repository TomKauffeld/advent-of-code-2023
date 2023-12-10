# [Advent of Code 2023](../../README.md) - [Day 10](../README.md) - Part 1

So this is a Graph problem.  
This means the first step is to parse the file as an 
undirected graph.  
Then starting from the starting node calculate all the
distances:
1. Add the starting node in a todo list and set its 
  distance to 0
2. Get the node with the smallest distance from the todo list
3. Get all connecting nodes not in the todo or done list
4. Set their distance to the node distance + 1
5. Add them to the todo list
6. Add the current node to the done list
7. Get back to step 2 until no more nodes are in the todo list
8. Go through all nodes and find the one with the largest
  distance

This only works as long as node with the largest distance is
inside a cycle with the starting point. Else it gives a
distance too large. However, this does seem the case for my
input.
