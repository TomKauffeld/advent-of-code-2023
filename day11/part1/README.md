# [Advent of Code 2023](../../README.md) - [Day 11](../README.md) - Part 1

At first, I thought we needed to solve the travelling salesman problem and got
scared.  
However in the end this was a simple puzzle.

Inorder to solve it first we need to load the map. For this we go line by line.  
And on each line we go letter by letter. If the letter is a galaxy we add its
position to the positions map (with a yOffset). And we note that the x column
and y row shouldn't be offset.  
At the end of the row, we check if y row should be offset. If yes, 
add 1 to the yOffset.  
At the end create a xOffsets with the offsets required on each column
(using the notes from earlier). And add the xOffsets to the positions.

Then we create a distanceMap in the format 
``distanceMap[startingPoint][endpoint] = distance``.  
(see [../utils.php](../utils.php) for most code)

And lastly just loop through every pair and sum the distance to get the result.
