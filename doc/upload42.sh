#!/bin/bash
echo 'http://42.meup.org/'$(curl -s -F file=@./"$1" api.meup.org/42)
