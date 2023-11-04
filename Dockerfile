FROM php:fpm
ARG UID
ARG GID

ENV UID=${UID}
ENV GID=${GID}

RUN addgroup --gid ${GID} --system php
RUN usermod -g ${GID} -u ${UID} www-data