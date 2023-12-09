# [Advent of Code 2023](../../README.md) - [Day 9](../README.md) - Part 2

So for this one is almost the same as [Part 1](../part1/README.md).
- parse the line into numbers
- generate the pyramid row by row going down
- add a zero to the first column on the last row
- generate the pyramid row by row going up
  (just the first column for each row)
- get the number in the first column on the first row
- add it to the accumulator
