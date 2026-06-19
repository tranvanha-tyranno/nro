FROM eclipse-temurin:17-jdk-jammy AS build

WORKDIR /build

COPY . /build/

RUN set -eux; \
    mkdir -p build/classes; \
    find src -name "*.java" | sort > /tmp/sources.txt; \
    find lib -name "*.jar" | sort > /tmp/libs.txt; \
    CLASSPATH="build/classes"; \
    while IFS= read -r jar; do CLASSPATH="$CLASSPATH:$jar"; done < /tmp/libs.txt; \
    javac -encoding UTF-8 -source 17 -target 17 -cp "$CLASSPATH" -processorpath "$CLASSPATH" -d build/classes @/tmp/sources.txt

FROM eclipse-temurin:17-jre-jammy

WORKDIR /app

ENV TZ=Asia/Ho_Chi_Minh

RUN apt-get update \
    && apt-get install -y --no-install-recommends gettext-base netcat-openbsd \
    && rm -rf /var/lib/apt/lists/*

COPY --from=build /build/build/classes /app/build/classes
COPY --from=build /build/lib /app/lib
COPY --from=build /build/data /app/data
COPY docker/config.template.properties /app/Config.template.properties
COPY docker/entrypoint.sh /app/entrypoint.sh

RUN chmod +x /app/entrypoint.sh

EXPOSE 14445/tcp

ENTRYPOINT ["/app/entrypoint.sh"]
