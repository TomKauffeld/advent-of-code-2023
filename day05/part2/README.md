# [Advent of Code 2023](../../README.md) - [Day 5](../README.md) - Part 2

Well this one was a bit more complicated. And I spend far too much time
trying to bruteforce the answer.

The first way was to add to the list of seeds all the numbers
so for the input ``seeds: 79 14 55 13`` I would have a list with
``79, 80, 81, ..., 90, 91, 92, 55, 56, 57, ..., 65, 66, 67``.  
But on the real input this would mean ``2 658 467 274`` entries. Or around
``19.8 GiB`` of data... so this didn't work.

After this I would try to resolve it per seed series, so for the input
``seeds: 79 14 55 13`` I would first have a list with 
``79, 80, 81, ..., 90, 91, 92`` and resolve it for that, and later a list
with ``55, 56, 57, ..., 65, 66, 67`` and again get the lowest for this list.
After that I would search for the lowest in every series.  
This did reduce the memory usage (the largest list would be
around ``6 GiB``). But this still meant to run the loop more than 
2 billion times...

Now the bruteforce didn't seem to work, and I actually needed to use
my brain to find a solution :(  
So the idea was to calculate everything using ranges 
(with a start and a length parameter).  
``NumberRange`` contains a Range of numbers  
``NumberMap`` contains one rule (ex: ``50 98 2``)  
``NumberMaps`` contains all the rules for one step (ex: ``seed-to-soil map``)
When a ``NumberMap`` offsets a ``NumberRange`` it returns
a new ``NumberRange`` with the offset start and up to two ``NumberRange`` 
that weren't converted. (using the ``NumberMapResult`` class)  
Then the ``NumberMaps`` takes a list of ``NumberRanges`` 
(inside the ``NumberRangeQueue``, called ``todo``)


It takes the first ``NumberRange`` from ``todo`` queue and finds
the smallest ``NumberMap`` that fits the ``NumberRange`` and converts it.  
It adds the converted ``NumberRange`` in the ``done`` list and adds the
ignored ``NumberRange`` in to ``todo`` queue.  
This gets repeated until the ``todo`` queue is empty.

Then the next step can be done, until finished.  
And the smallest number can be retrieved.
