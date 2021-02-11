#!/bin/bash

let COMMITMSG
COMMITMSG='Push from webserver: generic message'

if [ $# -eq 1 ] ; then
	COMMITMSG=$1
fi

sudo git add -A
sudo git commit -m "$COMMITMSG"
sudo git push
