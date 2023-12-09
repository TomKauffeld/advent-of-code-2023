# [Advent of Code 2023](../../README.md) - [Day 9](../README.md) - Part 1

So for this one I solved it using some simple steps:
- parse the line into numbers
- generate the pyramid row by row going down
- add a zero to the last cell (last row and column)
- generate the pyramid row by row going up 
  (just the last column for each row)
- get the number in the last column on the first row
- add it to the accumulator
