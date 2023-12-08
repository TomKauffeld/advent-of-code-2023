# [Advent of Code 2023](../../README.md) - [Day 8](../README.md) - Part 2

This one is more difficult.


### [Step 1](./try1.php)
I started with the same algorithm as [Part 1](../part1/README.md)
but with multiple inputs.  
However using bruteforce like this takes far too long.
And I would not solve the puzzle this year.

### [Step 2](./try2.php)
Next I thought I would use a smarter brute force: only check the steps
that do go to the end point.  
Using this script I did find that for my input, the result should be
below `21 003 205 388 413`.
- Find when each path loops and find when each path finds a
  partial solution (every time the path comes across a `**Z` location).  
- Then using an offset find the first partial solution.  
- Check if it's a full solution.  
- If not set the offset to the partial solution.
- Add to each partial solution under the offset the loop length of that path.  
- Set the offset to the next first partial solution, and check again.  

This would take around 6 hours worst case scenario on my pc...

### [Step 3](./main.php)
Now I do need to find a better solution if I want to solve it today.  
So this is what I do know already:
- the length of each path (until it loops).
- when each path hits an endpoint inside its loop.
- the length until all paths loop together.

So how can we get the offsets in the global loop that result in every
path getting on an endpoint ?  
And more crucially, the first time it happens.

Soooo... I did get a somewhat 
[spoiler](https://www.reddit.com/r/adventofcode/comments/18dg1hw/2023_day_8_part_2_about_the_correctness_of_a/)
and saw that the loop length is actually the answer.  
So my 2nd solution was printing the answer every 5 seconds without
me understanding it...
