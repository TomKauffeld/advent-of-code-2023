# [Advent of Code 2023](../../README.md) - [Day 17](../README.md) - Part 1

This looks again like a weighted directional graph problem where each node is a 
city block and each edge is the heat loss of entering the city block.  
Using this graph we can then run a modified version of Dijkstra's algorithm or A* by
dynamically removing edges from the graph if the path gets longer than 3 in a single
direction.

