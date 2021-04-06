#!/bin/bash
COMPOSER_VERSION="2"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

help() {
  echo -e "${YELLOW}COMMANDS:"
  echo -e "${GREEN}./sc.sh composer"
  echo -e "${NC}"
}

if test "$1" = "composer"
then
  docker run --rm --interactive --tty --volume $PWD:/app --volume $COMPOSER_HOME:/tmp composer:$COMPOSER_VERSION ${@}
else
  help
fi