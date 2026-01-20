#!/usr/bin/env bash

source ./definitions

SH_ACTION=${1:-}
if [ "$SH_ACTION" == "doctor" ]; then
  if [ $EUID != 0 ]; then
      echo "Please run doctor mode as root or with sudo."
      exit 1
  fi

  if ! command -v php 2>&1 >/dev/null; then
    echo "php command not found. Checking required commands to install."
    COMMANDS_REQUIRED=( "curl" "jq" "tar" )
    COMMANDS_MISSING=( )
    for COMMAND in "${COMMANDS_REQUIRED[@]}"; do
      if ! command -v "${COMMAND}" 2>&1 >/dev/null; then
        COMMANDS_MISSING=( "${COMMAND}" )
      fi
    done

    if (( ${#COMMANDS_MISSING[@]} > 0 )); then
      echo "Commands [${COMMANDS_MISSING[@]}] required to install static php binary."
      echo "Please install the required packages for commands and re-try."
      exit 1
    fi
  fi

  if ! command -v composer 2>&1 >/dev/null; then
    echo "Composer not found."
    install_composer
  fi

  echo "Everything seems OK"
  exit 0
else
  exec "${PATH_FILE_BIN_APP} ${@:1}"
fi

