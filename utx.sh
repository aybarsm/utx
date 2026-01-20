#!/usr/bin/env bash

source "$(dirname "$(realpath "${BASH_SOURCE[0]}")")/definitions"

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

  if [ ! -d "${PATH_DIR_APP}/.env" ]; then
    echo "Env file not found."
    cp "${PATH_DIR_APP}/.env.example" "${PATH_DIR_APP}/.env"
    echo "Example env file copied"
  fi

  if [ ! -d "${PATH_DIR_APP}/.tempest" ]; then
    mkdir -p "${PATH_DIR_APP}/.tempest"
  fi

  if [ ! -d "${PATH_DIR_APP}/vendor" ]; then
    echo "vendor directory not found."
    echo "Please provide arguments for composer install command:"
    read -r composer_args
    if [[ -n "${composer_args}" ]] && [[ ! -f "${PATH_DIR_APP}/.tempest/composer_install_args" ]]; then
        echo "${composer_args}" | tee "${PATH_DIR_APP}/.tempest/composer_install_args" > /dev/null
    fi
    echo "Installing components... Please wait."
    COMPOSER_ALLOW_SUPERUSER=1 cmd_exec "composer --working-dir=${PATH_DIR_APP} install ${composer_args}"
    echo "Components installed."
  fi

  if [ ! -f "${PATH_DIR_BIN}/utx" ]; then
    echo "UTX binary not found."
    ln -s "${PATH_DIR_APP}/utx.sh" "${PATH_DIR_BIN}/utx"
    echo "UTX binary linked."
  fi

  echo "Everything seems OK"
  exit 0
elif [ "${1:-}" == "app:update" ]; then
  if [ $EUID != 0 ]; then
      echo "Please run doctor mode as root or with sudo."
      exit 1
  fi
  git -C "${PATH_DIR_APP}" reset --hard --quiet
  git -C "${PATH_DIR_APP}" pull
  if [ ! -f "${PATH_DIR_APP}/.tempest/composer_install_args" ]; then
    composer_args=$(cat "${PATH_DIR_APP}/.tempest/composer_install_args")
  else
    echo "Please provide arguments for composer install command:"
    read -r composer_args
  fi
  if [[ -n "${composer_args}" ]] && [[ ! -f "${PATH_DIR_APP}/.tempest/composer_install_args" ]]; then
      echo "${composer_args}" | tee "${PATH_DIR_APP}/.tempest/composer_install_args" > /dev/null
  fi
  COMPOSER_ALLOW_SUPERUSER=1 cmd_exec "composer --working-dir=${PATH_DIR_APP} install ${composer_args}"
  echo "App updated."
else
  exec "${PATH_FILE_BIN_APP}" "${@:1}"
fi

