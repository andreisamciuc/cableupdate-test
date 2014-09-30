#!/bin/bash
date --set "$1 $2:00"
hwclock -w -f /dev/rtc1


