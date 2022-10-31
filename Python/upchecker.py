import requests
import argparse
from pathlib import Path
import yaml
import socket
import os


parser = argparse.ArgumentParser(description="Check if list of servers are available")
parser.add_argument("target", metavar='TARGET', help="must be a file formatted in YAML scheme, a single host in the form: 127.0.0.1:8000, or a comma seperated list of hosts")
args = parser.parse_args()

if os.path.isfile(args.target):
    with open(args.target) as yaml_file:
        targets = yaml.load(yaml_file)

else:
    hosts = args.target.split(",")
    targets = dict()
    for host in hosts:
        try:
            domain, port = host.split(":")
        except:
            print(f"Couldn't parse {host}, did you forget to add a port?")
        else:
            targets[host] = {"host":domain, "port":port}

for name in targets:
    target = targets[name]
    s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    print(f"Probing target {name} -- Host {target['host']} on TCP port {target['port']} ... ", end="")
    try:
        s.connect((target["host"], int(target["port"])))
        s.shutdown(2)
    except socket.error:
        print("FAIL")
    else:
        print("OK")



