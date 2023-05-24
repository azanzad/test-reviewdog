#!/bin/bash

# upate package manager
apt-get update -y
apt-get install curl -y
apt-get install git -y
apt-get update -yqq
apt-get install -yqq git unzip -y 

apt-get install software-properties-common -y
add-apt-repository ppa:ondrej/php -y
apt-get update -y 

# avoding user interactive in shell for php installation  
export DEBIAN_FRONTEND=noninteractive 
apt-get install -y php8.1-cli php8.1-common php8.1-mysql php8.1-zip php8.1-gd php8.1-mbstring php8.1-curl php8.1-xml php8.1-bcmath
