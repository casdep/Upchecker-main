# Upchecker Python

# Usage:

```
usage: upchecker.py [-h] TARGET

Check if list of servers are available

positional arguments:
TARGET must be a file formatted in YAML scheme, a single host in the form: 127.0.0.1:8000, or a comma seperated list of hosts

optional arguments:
-h, --help show this help message and exit
```

# Examples

```
python upchecker.py example.com:80,example.net:80
```

```
python upchecker.py ../targets.yaml
```
