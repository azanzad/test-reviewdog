#!/bin/bash

# Define the variable
my_var=$GIT_AVI_URL

# Export the variable
export my_var

# Replace a value in a file with the variable using sed
sed -i "s~Gitcred~$my_var~g" composer.json
