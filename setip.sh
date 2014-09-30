#!/bin/bash

#this file to be located at /bin
#usage "sudo thisScript ip netmask gate dns"

sudo /sbin/ifconfig eth0:1 $1
sudo /sbin/ifconfig eth0:1 netmask $2
sudo route del default
sudo route add default gw $3
sudo echo 'nameserver '$4 > /etc/resolv.conf



