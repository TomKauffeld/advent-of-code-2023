# [Advent of Code 2023](../../README.md) - [Day 8](../README.md) - Part 1

Well it was somewhat simple: create a lookup table  with locations
and where they take you depending on the direction.  
Set the current location to `AAA` and loop until it's equal to `ZZZ`.  
Foreach loop increment the `steps` counter.  
Take the modulus of the `steps` counter with the amount of instructions
to find the current instruction.  
And finally change the current location to the location indicated 
inside the nodes list.

And lastly don't forget to start at `0` not `1`:
```php
#if steps = 0 then this returns 0 and steps is equal to 1 after this line
$steps++ % count($directions)

#if steps = 0 then this returns 1 and steps is equal to 1 after this line
++$steps % count($directions)
```
