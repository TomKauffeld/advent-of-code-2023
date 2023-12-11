# [Advent of Code 2023](../../README.md) - [Day 11](../README.md) - Part 2

This is the same as [Part 1](../part1/README.md) but instead of adding 1
for each offset, we add a million (minus 1).

Then we create a distanceMap in the format
``distanceMap[startingPoint][endpoint] = distance`` again.  
(see [../utils.php](../utils.php) for most code)

And lastly just loop through every pair and sum the distance to get the result.
