DEPLOY_IP="#ip/servername here"
DEPLOY_USER=root
APP_PATH="/var/www/latestnews"
DEPLOY_PATH_LIVE="${APP_PATH}/latest";
DEPLOY_PATH_RELEASE="${APP_PATH}/build";

ssh ${DEPLOY_USER}@$DEPLOY_IP "if [ -d ${DEPLOY_PATH_RELEASE} ]; then rm ${DEPLOY_PATH_RELEASE} -rf ; fi &&
  mkdir -p ${DEPLOY_PATH_RELEASE} &&
  wget -q https://github.com/angelk/tmp-wirednews/archive/master.zip -O ${DEPLOY_PATH_RELEASE}/master.zip &&
  unzip -q ${DEPLOY_PATH_RELEASE}/master.zip -d ${DEPLOY_PATH_RELEASE} &&
  mv ${DEPLOY_PATH_RELEASE}/tmp-wirednews-master/* ${DEPLOY_PATH_RELEASE} &&
  rm ${DEPLOY_PATH_RELEASE}/master.zip &&
  export SYMFONY_ENV=prod &&
  composer install -d ${DEPLOY_PATH_RELEASE} &&
  rm ${DEPLOY_PATH_RELEASE}/app/config/parameters.yml &&
  ln -s ${APP_PATH}/shared/app/config/parameters.yml ${DEPLOY_PATH_RELEASE}/app/config/parameters.yml &&
  ln -s ${APP_PATH}/shared/var/cache/prod/user_cache ${DEPLOY_PATH_RELEASE}/var/cache/prod/user_cache
  chown www-data:www-data -R ${DEPLOY_PATH_RELEASE} &&
  if [ -d ${DEPLOY_PATH_LIVE} ]; then rm ${DEPLOY_PATH_LIVE} -rf ; fi &&
  mv ${DEPLOY_PATH_RELEASE} ${DEPLOY_PATH_LIVE}
"
