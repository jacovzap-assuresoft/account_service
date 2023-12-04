FROM postgres:latest

ENV POSTGRES_PASSWORD assure
ENV POSTGRES_USER ad-trainees
ENV POSTGRES_DB account_service

COPY Database.sql /docker-entrypoint-initdb.d/

RUN chmod +r /docker-entrypoint-initdb.d/Database.sql
