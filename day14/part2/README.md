# [Advent of Code 2023](../../README.md) - [Day 14](../README.md) - Part 2

For this one the first step is creating the field 
(just like [Part 1](../part1/README.md)).  
Once that is done we create the shift methods to shift the field.  
Then a cycle method that shifts the field in the demanded direction.  
And lastly "just loop" `1 000 000 000` times (and wait for hours).

With the basic script done, the next part was to detect loops in the cycled field.
This was done by creating a unique key for each field layout and keeping in 
a `fields` dictionary the index the key was first found.  
At some point (for my input at index `107`) it finds there is already an index set
inside the dictionary (for my input `96`) and so we can calculate the loop size
(`11`). Next we calculate how much can skip:
```php
$cycles_todo = $total_cycles - $current_index - 1;
$remaining_loops = floor($cycles_todo / $loop_size);
$skip_possibility = $remaining_loops * $cycles_todo;
```
So with my input I could skip `999 999 891` cycles and go directly from index
`107` to `999999998` without recomputing the steps in between.  

After that we just calculate the score on the field.
