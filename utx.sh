#!/usr/bin/env bash

source ./definitions

SH_ACTION=${1:-}
if [ "$SH_ACTION" == "doctor" ]; then
  if [ $EUID != 0 ]; then
      echo "Please run doctor mode as root or with sudo."
      exit 1
  fi

  if command -v php 2>&1 >/dev/null; then
    echo "Everything seems OK"
    exit 0
  fi

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

  echo "Downloading and installing static PHP binary... Please wait."
  curl -1sSLf "${URL_PHP_BIN}" | tar -xz -C /tmp
  mv /tmp/php "${PATH_DIR_BIN}/php"
  chown root:root "${PATH_DIR_BIN}/php"
  chmod +x "${PATH_DIR_BIN}/php"
  if command -v setcap 2>&1 >/dev/null; then
    setcap "cap_net_bind_service=+ep" "${PATH_DIR_BIN}/php"
  fi
  echo "Static PHP binary installed to: ${PATH_DIR_BIN}/php"

  if ! command -v composer 2>&1 >/dev/null; then
    echo "Composer not found. Installing composer... Please wait."
    curl -1sLf https://getcomposer.org/installer | "${PATH_DIR_BIN}/php" -- --install-dir="${PATH_DIR_BIN}" --filename="composer"
    chown root:root "${PATH_DIR_BIN}/composer"
    chmod +x "${PATH_DIR_BIN}/composer"
  fi


#
#
#
#  if ! command -v jq 2>&1 >/dev/null; then
#       COMMANDS_MISSING+=( "jq" )
#  fi

else
  echo "${PATH_FILE_BIN_APP} ${@:1}"
fi

#echo "${OS_TYPE} -- ${OS_ARCH}"
#
#
#SH_ACTION=${1:-''}
#if [ "$SH_ACTION" == "app:update" ]; then
#    if [ ! -f "${PATH_BIN_COMPOSER}" ]; then
#        curl -1sLf https://getcomposer.org/installer | "$PATH_BIN_PHP" -- --install-dir="$(dirname -- "$PATH_BIN_COMPOSER")" --filename=composer
#        chmod +x "$PATH_BIN_COMPOSER"
#    fi
#
#    if [ ! -d "$PATH_APP_VENDOR" ]; then
#        git -C "$PATH_DIR_APP" reset --hard --quiet
#        git -C "$PATH_DIR_APP" pull --quiet
#        COMPOSER_ALLOW_SUPERUSER=1 composer --working-dir="$PATH_DIR_APP" --quiet --no-interaction install
#    fi
#
#    if [ ! -f "${PATH_APP_SCRIPT_SYMLINK}" ]; then
#        ln -s "${PATH_APP_BIN_SCRIPT}" "${PATH_APP_SCRIPT_SYMLINK}"
#    fi
#
#    if [ ! -f "${PATH_APP_SETTINGS}" ]; then
#        echo '{}' | tee "$PATH_APP_SETTINGS" >/dev/null
#    fi
#
#    if ! settings_has "composer.last_hash"; then
#        settings_set 'composer.last_hash' $(jq -rc '."content-hash"' "$PATH_APP_COMPOSER_LOCK")
#    fi
#
#    if [ ! -f "$PATH_APP_ENV" ]; then
#        cp "$PATH_APP_ENV_INIT" "$PATH_APP_ENV"
#        ${PATH_BIN_BLRM_CLI} key:generate --ansi --silent --no-interaction
#    fi
#
#    APP_DB_CONN=$(sed -n '/^DB_CONNECTION=/ { s/^DB_CONNECTION=//; p; q; }' "$PATH_DIR_APP/.env")
#
#    if [ "$APP_DB_CONN" == "sqlite" ]; then
#        APP_DB_PATH=$(sed -n '/^DB_DATABASE=/ { s/^DB_DATABASE=//; p; q; }' "$PATH_DIR_APP/.env")
#        APP_DB_PATH=${APP_DB_PATH:-'database/database.sqlite'}
#        if [ ! -f "${PATH_DIR_APP}/${APP_DB_PATH}" ]; then
#            touch "${PATH_DIR_APP}/${APP_DB_PATH}"
#            ${PATH_APP_BIN_ARTISAN} migrate --force --silent --no-interaction
#        fi
#    fi
#
#    if ! app_has_changes; then
#        exit 0
#    fi
#
#    git -C "$PATH_DIR_APP" reset --hard --quiet
#    git -C "$PATH_DIR_APP" pull --quiet
#
#    if [ "$(settings_get 'composer.last_hash')" !=  "$(jq -rc '."content-hash"' "$PATH_APP_COMPOSER_LOCK")" ]; then
#        COMPOSER_ALLOW_SUPERUSER=1 composer --working-dir="$PATH_DIR_APP" --quiet --no-interaction install
#        settings_set 'composer.last_hash' $(jq -rc '."content-hash"' "$PATH_APP_COMPOSER_LOCK")
#    fi
#
#    if [ -f "${PATH_SERVICES}/${APP_SERVICE_SCHEDULE}" ]; then
#        if service_enabled "${APP_SERVICE_SCHEDULE}"; then
#            systemctl restart "${APP_SERVICE_SCHEDULE}" >/dev/null
#        fi
#    fi
#
#    if [ -f "${PATH_SERVICES}/${APP_SERVICE_QUEUE}" ]; then
#        if service_enabled "${APP_SERVICE_QUEUE}"; then
#            systemctl restart "${APP_SERVICE_QUEUE}" >/dev/null
#        fi
#    fi
#
#    echo "BLRM Cli updated."
#elif [ "$SH_ACTION" == "app:pull" ]; then
#    git -C "$PATH_DIR_APP" reset --hard --quiet
#    git -C "$PATH_DIR_APP" pull --quiet
#    echo "Repo pulled."
#else
#    exec "${PATH_APP_BIN_ARTISAN}" "${@:1}"
#fi

