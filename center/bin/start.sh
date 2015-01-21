#!/bin/sh
killall -9 dcmd_center
cd `dirname $0`
./dcmd_center -f dcmd_center.conf>/dev/null
