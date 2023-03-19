FROM postgres:13

ENV TZ=Asia/Tokyo \
    POSTGRES_USER=admin \
    POSTGRES_PASSWORD=password

COPY ./infra/docker/postgres/postgresql.conf /etc/postgresql/postgresql.conf
COPY ./infra/docker/postgres/pg_hba.conf /etc/postgresql/pg_hba.conf
COPY ./infra/docker/postgres/init.sql /docker-entrypoint-initdb.d/init.sql


