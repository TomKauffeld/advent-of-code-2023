# [Advent of Code 2023](../../README.md) - [Day 15](../README.md) - Part 2

This one took far too long to solve in the end...

### Algorithm:
To solve this part, I started by reading the file character by character again
just like [Part 1](../part1/README.md). But this time I had a `$buffer` with
the text until it hit a `,` or the end of the file.  
Next to this also two variables `$boxes` with the index being the box number
and the values being the list of labels inside the box. And a dictionary
`$focalLengths` with the index being the label and the values the focalLengths
of the associated label/lens.  
Once the parser hit a `,` or  the end of file, it sends the `$buffer` to the 
`handleBuffer` function, this one checks if the last character is `-` or the
second to last character is `=`.  
- If it's `=`, it sets the corresponding `$focalLengths` to the new focal length.  
  And if the label not yet inside the box, it adds the label to the 
  corresponding list inside `$boxes`.
- If it's `-`, it removes the label from the corresponding list inside `$boxes`.

Once the parsing is done, the next step is just calculating the "focusing power"
by going through each box and each lens inside and multiplying the "focal length"
by the index of the box plus one, and the index of the lens plus one.

### This was done in almost no time, so why did it take so long ?  
**Bugs, bugs and more bugs...**  
- The first one was forgetting to reset the `$buffer` once handled.
  This meant after the first instruction, the next instruction was incorrect
  until the end. And this gave of course the wrong answer.  
  With the exemple data, the instructions were:
  1. rn=1
  2. rn=1cm-
  3. rn=1cm-qp=3
  4. etc...  
- The next bug was the way I was removing the labels from the list: 
  the original indexes were preserved. This meant in to end the label indexes
  could go 0, 1, 3, 4, 5 instead of 0, 1, 2, 3, 4.
- After this, the hash value can be between `0x00` (`0`) inclusive
  and `0xFF` (`255`) **inclusive**, so this means you should take the
  remainder if dividing by adding one to the maximum included number
  (`0x100` or `256` in this case).  
  But by going too fast, I implemented it as a hash value between 
  `0x00` inclusive and `0xFF` **exclusive**.  
  This wasn't the case for [Part 1](../part1/README.md) as there it was just
  Hardcoded, but in [Part 2](./README.md) it was set as a constant `LIMIT`...
