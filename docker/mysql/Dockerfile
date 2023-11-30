FROM mysql:8.0 as builder

RUN ["sed", "-i", "s/exec \"$@\"/echo \"not running $@\"/", "/usr/local/bin/docker-entrypoint.sh"]
ENV MYSQL_ROOT_PASSWORD=root

COPY ./docker/mysql/initdb.sql /docker-entrypoint-initdb.d/
RUN ["chmod", "777", "/docker-entrypoint-initdb.d/initdb.sql"]
RUN ["/usr/local/bin/docker-entrypoint.sh", "mysqld", "--datadir", "/initialized-db"]

FROM mysql:8.0

COPY --from=builder /initialized-db /var/lib/mysql