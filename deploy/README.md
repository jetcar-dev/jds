# JDS Docs 최초 서버 설정

JDS Docs는 기존 ERP와 별개의 Compose 프로젝트로 실행됩니다.

```text
ERP       127.0.0.1:8080 → HTTPS 443
Storage   127.0.0.1:9000 → HTTPS 9443
Console   127.0.0.1:9001 → HTTPS 9444
JDS Docs  127.0.0.1:8088 → HTTPS 9445
```

## 1. 배포 사용자와 디렉터리

root로 한 번만 실행합니다.

```bash
adduser deploy
usermod -aG docker deploy
mkdir -p /opt/jds-docs
chown -R deploy:deploy /opt/jds-docs
```

`deploy` 사용자의 `/home/deploy/.ssh/authorized_keys`에 GitHub Actions
배포 공개키를 등록합니다.

## 2. Nginx

`deploy/nginx-jds.conf`를 기존 ERP 설정과 분리된 사이트 파일로
등록합니다.

```bash
cp /tmp/nginx-jds.conf /etc/nginx/sites-available/jds-docs
ln -s /etc/nginx/sites-available/jds-docs /etc/nginx/sites-enabled/jds-docs
nginx -t
systemctl reload nginx
```

이미 심볼릭 링크가 있다면 `ln -s`는 다시 실행하지 않습니다.

JDS Docs는 Nginx의 `allow`/`deny` 설정으로 현재 사내 공인 IP
`183.107.55.234/32`에서만 접근할 수 있습니다. UFW나 Cafe24 방화벽
규칙은 변경하지 않습니다. 접근할 사내 IP를 추가할 때는
`nginx-jds.conf`의 `allow` 행을 추가합니다.

## 3. GitHub production secrets

```text
DEPLOY_HOST=175.125.21.198
DEPLOY_PORT=22
DEPLOY_USER=deploy
DEPLOY_SSH_KEY=배포 개인키 전체
DEPLOY_KNOWN_HOSTS=ssh-keyscan 결과
DOCS_APP_KEY=php artisan key:generate --show 결과
```

GHCR 인증은 워크플로 실행 중 자동 발급되는 `GITHUB_TOKEN`을 사용하므로
개인 `GHCR_USER`, `GHCR_TOKEN` Secret은 등록하지 않습니다.

`DEPLOY_KNOWN_HOSTS`는 신뢰할 수 있는 환경에서 다음 명령으로 생성합니다.

```bash
ssh-keyscan -H 175.125.21.198
```

## 4. 첫 배포

GitHub에 `v1.0.0` 태그를 push하면 `/opt/jds-docs`에 Compose 파일과
환경 파일이 업로드되고 이미지가 자동으로 시작됩니다.

```bash
curl -I https://jetcarerp.cafe24.com:9445/up
```
