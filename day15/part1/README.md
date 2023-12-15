# [Advent of Code 2023](../../README.md) - [Day 15](../README.md) - Part 1

So the first part was quite easy, and so I tried once again to
optimize the memory usage.  
The idea is go through the file character per character. By having a `$hash` 
variable with the current hash value (initialized at `0`), and a `$sum` variable
(also initialized at `0`) that contains the result.  
- Then if the character is `\r` or `\n` ignore.  
- If the character is `,` then add the current `$hash` value to the `$sum` and 
  set `$hash` back to `0`.  
- Else calculate the hash of the current character using the `$hash`
  as the initial value, and save the result back to `$hash`.

In the end also add `$hash` to `$sum` as the file might not end with the `,`
character.
