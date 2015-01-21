#!/bin/sh
if [ $# -lt 2 ]; then
  echo "Too less arguement."
  exit 1
fi
install_path=$1
package_path=$2
if [ 3 -eq $# ];then
 conf_path=$3
fi

if [ ! -f $package_path/bin/dcmd_center ]; then
   echo "[$package_path/bin/dcmd_center] doesn't exist."
   exit 1
fi
if [ 3 -eq $# ]; then
  if [ ! -f $conf_path/dcmd_center.conf ]; then
   echo "[$conf_path/dcmd_center.conf] doesn't exist."
   exit 1
  fi
fi

if [ -f $install_path/bin/dcmd_center ];then
  rm -f $install_path/bin/dcmd_center
fi
if [ ! -d $install_path/bin ]; then
  mkdir $install_path/bin
fi
cp $package_path/bin/dcmd_center $install_path/bin/dcmd_center 
ret=$?
if [ "0" -ne $ret ]; then
  exit $ret 
fi
cp -f $package_path/bin/start.sh $install_path/bin
ret=$?
if [ "0" -ne $ret ]; then
  exit $ret
fi
cp -f $package_path/bin/stop.sh $install_path/bin
ret=$?
if [ "0" -ne $ret ]; then
  exit $ret
fi
cp -f $package_path/bin/install.sh $install_path/bin
ret=$?
if [ "0" -ne $ret ]; then
  exit $ret
fi
chmod 755 $install_path/bin/dcmd_center
chmod 755 $install_path/bin/start.sh
chmod 755 $install_path/bin/stop.sh
chmod 755 $install_path/bin/install.sh

if [ ! -d $install_path/bin/task_script ];then
  mkdir $install_path/bin/task_script
  ret=$?
  if [ "0" -ne $ret ]; then
    exit $ret
  fi
fi
if [ ! -d $install_path/bin/opr_script ];then
  mkdir $install_path/bin/opr_script
  ret=$?
  if [ "0" -ne $ret ]; then
    exit $ret
  fi
fi
if [ ! -d $install_path/bin/monitor_script ];then
  mkdir $install_path/bin/monitor_script
  ret=$?
  if [ "0" -ne $ret ]; then
    exit $ret
  fi
fi
if [ ! -d $install_path/bin/cron_script ];then
  mkdir $install_path/bin/cron_script
  ret=$?
  if [ "0" -ne $ret ]; then
    exit $ret
  fi
fi


if [ 3 -eq $# ]; then
  if [ -f $install_path/bin/dcmd_center.conf ];then
     rm -f $install_path/bin/dcmd_center.conf
  fi
  cp $conf_path/dcmd_center.conf $install_path/bin/dcmd_center.conf
  ret=$?
  if [ 0 -ne $ret ]; then
    exit $ret
  fi
else
  if [ ! -f $install_path/bin/dcmd_center.conf ]; then
     if [ -f $package_path/bin/dcmd_center.conf ]; then
        cp $package_path/bin/dcmd_center.conf $install_path/bin/dcmd_center.conf
        ret=$?
        if [ 0 -ne $ret]; then
           exit $ret
        fi
     fi
  fi
fi
exit 0
