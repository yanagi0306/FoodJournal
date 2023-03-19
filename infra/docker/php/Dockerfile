FROM php:8.1-fpm-bullseye AS base

WORKDIR /workspace

# adminユーザーの作成
RUN useradd -m admin && echo "admin:password" | chpasswd

# root権限 password追加
RUN echo 'root:password' |chpasswd

# adminユーザー password追加
RUN echo "admin:password" | chpasswd

RUN echo "admin   ALL=(ALL)       ALL" >> /etc/sudoers
RUN echo "admin   ALL=(ALL)       NOPASSWD: ALL" >> /etc/sudoers

# タイムゾーンを設定
ENV TZ=Asia/Tokyo \
  # ロケールを設定
  LANG=en_US.UTF-8 \
  LANGUAGE=en_US:en \
  LC_ALL=en_US.UTF-8 \
  # Composerの環境変数を設定
  COMPOSER_ALLOW_SUPERUSER=1 \
  COMPOSER_HOME=/composer

COPY --from=composer:2.4 /usr/bin/composer /usr/bin/composer

# 必要なパッケージをインストール
RUN apt-get update \
  && apt-get -y install --no-install-recommends \
    coreutils \
    findutils \
    grep \
    curl \
    wget \
    less \
    vim \
    nano \
    locales \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    postgresql-client \
    postgresql \
    postgresql-server-dev-all \
    libpq-dev \
    iputils-ping \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/* \
  # ロケールを設定
  && locale-gen en_US.UTF-8 \
  && localedef -f UTF-8 -i en_US en_US.UTF-8 \
  # PHP拡張をインストール
  && docker-php-ext-install \
    intl \
    pdo_pgsql \
    zip \
    bcmath \
  # Composerの設定
  && composer config -g process-timeout 3600 \
  && composer config -g repos.packagist composer https://packagist.org

# パスにPostgreSQLの実行可能ファイルのディレクトリを追加
ENV PATH="/usr/lib/postgresql/13/bin:${PATH}"

RUN curl -sL https://deb.nodesource.com/setup_16.x | bash -
RUN apt-get -y install --no-install-recommends nodejs
RUN npm install -g npm

# 開発環境用のDockerfile
FROM base AS development

# PHPの設定ファイルをコピー
COPY ./infra/docker/php/php.development.ini /usr/local/etc/php/php.ini

# Xdebugをインストール
FROM development AS development-xdebug

RUN pecl install xdebug && \
  docker-php-ext-enable xdebug

# Xdebugの設定ファイルをコピー
COPY ./infra/docker/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# 本番環境用のDockerfile
FROM base AS deploy

# PHPの設定ファイルをコピー
COPY ./infra/docker/php/php.deploy.ini /usr/local/etc/php/php.ini
# アプリケーションのソースコードをコピー
COPY ./src /workspace

# Composerを使って必要なパッケージをインストール
RUN composer install -q -n --no-ansi --no-dev --no-scripts --no-progress --prefer-dist \
  # storageとbootstrap/cacheディレクトリを書き込み可能に変更
  && chmod -R 777 storage bootstrap/cache \
  # Laravelのキャッシュをクリア
  && php artisan optimize:clear \
  # Laravelの最適化
  && php artisan optimize \
  # npmパッケージのインストール
  && npm install \
  # npmパッケージのビルド
  && npm run dev

RUN apt-get update && apt-get install -y openssh-server openssh-client

# SSHサーバーのインストール
RUN apt-get update && \
    apt-get install -y --no-install-recommends openssh-server && \
    rm -rf /var/lib/apt/lists/* && \
    sed -i "s/#PermitRootLogin prohibit-password/PermitRootLogin yes/" /etc/ssh/sshd_config && \
    sed -i "s/#PasswordAuthentication yes/PasswordAuthentication yes/" /etc/ssh/sshd_config && \
    mkdir /run/sshd

# SSHのポートを公開
EXPOSE 22

CMD ["/usr/sbin/sshd", "-D"]

# apt-getのキャッシュを削除
RUN rm -rf /var/lib/apt/lists/*
