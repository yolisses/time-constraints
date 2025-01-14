# Operations

## Glossary

| Symbol              | Meaning                               | Comments                                                |
| ------------------- | ------------------------------------- | ------------------------------------------------------- |
| E                   | EmptyTimeInterval                     |                                                         |
| S                   | SimpleTimeInterval                    |                                                         |
| C                   | CompositeTimeInterval                 |                                                         |
| \|                  | Union                                 | What is contained in at least one of the sets           |
| &                   | Intersection                          | What is contained in both sets simultaneously           |
| -                   | Difference                            | What is contained in the first set but not in the other |
| A, B, C, etc        | Example sets                          |                                                         |
| S1, S2, C1, C2, etc | Different example sets with same type |                                                         |

## Rules

C.intervals does not contain any other C. Every time C.setIntervals(T) is called, it appends T.intervals if T is a C and T otherwise.

```
class C {
    function setIntervals(intervals: T[]){
        intervals = []
        for interval of intervals{
            if interval is a C {
                intervals = intervals.join(interval.intervals)
            } else {
                intervals.push(interval)
            }
        }
    }
}
```

## EmptyTimeInterval

```
E | E = E
E & E = E
E - E = E
```

```
E | S = S
E & S = E
E - S = E
```

```
E | C = C
E & C = E
E - C = E
```

## SimpleTimeInterval

```
S | E = S
S & E = E
S - E = S
```

```
S | S = S if they overlap, C otherwise
S & S = S if they overlap, E otherwise
S1 - S2 = C if S2 is inside S1, E if S2 contains S1, S otherwise
```

```
S | C = union(C.intervals, S)
S & C = union(foreach(C, T -> S & T))
S - C = union(foreach(C, T -> S - T))
```

## CompositeTimeInterval

```
C | E = C
C & E = E
C - E = C
```

```
C | S = union(C.intervals, S)
C & S = union(foreach(C, T -> T & S))
C - S = union(foreach(C, T -> T - S))
```

```
C1 | C2 = union(C1.intervals, C2.intervals)
C1 & C2 = union(foreach(C1, T1 -> union(foreach(C2, T2 -> T1 & T2))))
C1 - C2 = union(foreach(C1, T1 -> union(foreach(C2, T2 -> T1 - T2))))
```

## Optimizing

### E | T = T

Use `T.clone()` just for immutability.

```
E | E = E
E | S = S
E | C = C
S | E = S
C | E = C
```

### E & T = E

Use `new E()` just for immutability.

```
E & E = E
E & S = E
E & C = E
S & E = E
C & E = E
```

### E - T = E

```
E - E = E
E - S = E
E - C = E
```

### T - E = T

```
S - E = S
C - E = C
```

```
S | S = S if they overlap, C otherwise
S | C = union(C.intervals, S)
C | S = union(C.intervals, S)
C1 | C2 = union(C1.intervals, C2.intervals)

S & S = S if they overlap, E otherwise
S & C = union(foreach(C, T -> S & T))
C & S = union(foreach(C, T -> T & S))
C1 & C2 = union(foreach(C1, T1 -> union(foreach(C2, T2 -> T1 & T2))))

S1 - S2 = C if S2 is inside S1, E if S2 contains S1, S otherwise
S - C = union(foreach(C, T -> S - T))
C - S = union(foreach(C, T -> T - S))
C1 - C2 = union(foreach(C1, T1 -> union(foreach(C2, T2 -> T1 - T2))))
```

```
function union (t1, t2) {
    if (t1 is E or t2 is E){
        return new E()
    }

     = []
}
```
