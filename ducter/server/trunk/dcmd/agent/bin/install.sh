#!/bin/sh
if [ $# -lt 2 ]; then
  echo "Too less arguement."
  exit 1
fi
agent_path=$1
package_path=$2
if [ 3 -eq $# ];then
 conf_path=$3
fi

if [ ! -f $package_path/bin/dcmd_agent ]; then
   echo "[$package_path/bin/dcmd_agent] doesn't exist."
   exit 1
fi
if [ 3 -eq $# ]; then
  if [ ! -f $conf_path/dcmd_agent.conf ]; then
   echo "[$conf_path/dcmd_agent.conf] doesn't exist."
   exit 1
  fi
fi

if [ -f $agent_path/bin/dcmd_agent ];then
  rm -f $agent_path/bin/dcmd_agent
fi
if [ ! -d $agent_path/bin ]; then
  mkdir $agent_path/bin
fi
cp $package_path/bin/dcmd_agent $agent_path/bin/dcmd_agent  
ret=$?
if [ "0" -ne $ret ]; then
  exit $ret 
fi
cp -f $package_path/bin/start.sh $agent_path/bin
ret=$?
if [ "0" -ne $ret ]; then
  exit $ret
fi
cp -f $package_path/bin/stop.sh $agent_path/bin
ret=$?
if [ "0" -ne $ret ]; then
  exit $ret
fi
cp -f $package_path/bin/install.sh $agent_path/bin
ret=$?
if [ "0" -ne $ret ]; then
  exit $ret
fi
chmod 755 $agent_path/bin/dcmd_agent
chmod 755 $agent_path/bin/start.sh
chmod 755 $agent_path/bin/stop.sh
chmod 755 $agent_path/bin/install.sh

if [ 3 -eq $# ]; then
  if [ -f $agent_path/bin/dcmd_agent.conf ];then
     rm -f $agent_path/bin/dcmd_agent.conf
  fi
  cp $conf_path/dcmd_agent.conf $agent_path/bin/dcmd_agent.conf
  ret=$?
  if [ 0 -ne $ret ]; then
    exit $ret
  fi
else
  if [ ! -f $agent_path/bin/dcmd_agent.conf ]; then
     if [ -f $package_path/bin/dcmd_agent.conf ]; then
        cp $package_path/bin/dcmd_agent.conf $agent_path/bin/dcmd_agent.conf
        ret=$?
        if [ 0 -ne $ret]; then
           exit $ret
        fi
     fi
  fi
fi
exit 0
