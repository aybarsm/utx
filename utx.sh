#!/usr/bin/env bash

source ./definitions

if [ "${1:-}" == "doctor" ]; then
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

  if [ ! -d "${PATH_DIR_APP}/vendor" ]; then
    echo "vendor directory not found."
    echo "Please provide arguments for composer install command:"
    read -r composer_args
    echo "Installing components... Please wait."
    cmd_exec "COMPOSER_ALLOW_SUPERUSER=1 && composer --working-dir=${PATH_DIR_APP} install ${composer_args}"
    echo "Components installed."
  fi

  echo "Everything seems OK"
  exit 0
else
  exec "${PATH_FILE_BIN_APP} ${@:1}"
fi

