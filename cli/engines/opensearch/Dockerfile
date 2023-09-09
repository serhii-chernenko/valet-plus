FROM opensearchproject/opensearch:1.2.0

ENV cluster.name=docker-cluster
ENV bootstrap.memory_lock=true
ENV discovery.type=single-node

RUN bin/opensearch-plugin install analysis-icu || true
RUN bin/opensearch-plugin install analysis-phonetic || true
