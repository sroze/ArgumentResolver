# Argument Resolver

## Priorities

These are the resolution priorities for the arguments:

- Same name and same type
- Same type
- Same name
 
The following cases cause exception to be thrown:
- If multiple available arguments of the name type and at least one required by callable
- A required parameter can't be found

