package main

import (
	"flag"
	"fmt"
	"net"
	"os"
	"strings"
	"sync"
	"time"
)

func probeAddress(protocol string, address string) bool {
	conn, err := net.DialTimeout(protocol, address, 2*time.Second)

	if err != nil {
		return false
	}
	defer conn.Close()
	return true
}

type Targets []string

func (t *Targets) String() string {
	return fmt.Sprintln(*t)
}

func (t *Targets) Set(s string) error {
	*t = strings.Split(s, ",")
	return nil
}

func main() {
	var targets Targets
	flag.Var(&targets, "t", "A single host in the form: 127.0.0.1:8000, or a comma seperated list of hosts")
	flag.Usage = func() {
		w := flag.CommandLine.Output()
		fmt.Fprintf(w, "Usage of %s -t TARGET [-h]\n", os.Args[0])
		flag.PrintDefaults()
	}
	flag.Parse()

	if len(targets) == 0 {
		flag.Usage()
	}

	var wg sync.WaitGroup
	wg.Add(len(targets))

	for _, target := range targets {
		go func(target string) {
			defer wg.Done()
			splitted := strings.Split(target, ":")
			if len(splitted) < 2 {
				fmt.Printf("Could not parse '%s' did you add a port number?\n", target)
				return
			}
			host := splitted[0]
			port := splitted[1]
			open := probeAddress("tcp", target)
			status := "FAIL"
			if open {
				status = "OK"
			}
			fmt.Printf("Probing target %s -- Host %s on TCP port %s ... %s\n", target, host, port, status)

		}(target)

	}
	wg.Wait()
}
