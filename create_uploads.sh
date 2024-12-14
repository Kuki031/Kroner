#!/bin/bash

DIR="uploads"
if [ ! -d "$DIR" ]; then
    mkdir "$DIR"
else
    echo "Directory '$DIR' already exists."
fi
