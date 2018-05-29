#!/bin/bash

docker run -it --rm -p 8888:8888 -v "$PWD":/usr/src/myapp -w /usr/src/myapp php:7.2-cli-alpine php "$@"