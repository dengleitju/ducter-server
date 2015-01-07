#!/bin/sh
killall -9 dcmd_agent
cd `dirname $0`
./dcmd_agent -f dcmd_agent.conf>/dev/null
