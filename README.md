# JetCar Design System

Laravel Blade 컴포넌트 패키지와 문서 사이트를 함께 관리합니다.

```text
package/  Composer 패키지 원본
docs/     Laravel 문서 사이트
deploy/   운영 Docker Compose와 Nginx 설정
```

GitHub 저장소는 `jetcar-dev/jds` 하나만 사용합니다. 저장소 루트의
`composer.json`은 Composer가 `package/src`를 패키지 소스로 인식하게
연결하는 파일이며, 실제 컴포넌트 파일은 `package/`에 그대로 둡니다.

## 로컬 개발

개발은 서버가 아니라 로컬 저장소에서 진행합니다.

```bash
cd package
npm install
npm run build

cd ../docs
composer install
npm install
composer run dev
```

## 첫 GitHub 업로드

```bash
git add .
git commit -m "feat: JDS 초기 버전 구성"
git push -u origin main
```

## 태그 배포

모든 브랜치 push와 pull request에서 패키지·문서 빌드 및 테스트를
검증합니다. `v*.*.*` 태그를 push하면 동일한 검증을 통과한 뒤 현재
저장소에 GitHub Release 생성, Docs GHCR 이미지 발행, 운영 서버
배포를 직렬로 실행합니다. 앞 단계가 실패하면 이후 단계는 실행되지
않습니다.

```bash
git tag v1.0.0
git push origin v1.0.0
```

운영 문서 주소는 `https://jetcarerp.cafe24.com:9445`입니다. 서버 최초
설정은 [deploy/README.md](deploy/README.md)를 따릅니다.

## ERP에서 설치

저장소가 private이면 ERP 서버의 Composer에 GitHub 접근 토큰을 먼저
등록합니다. 토큰은 저장소 읽기 권한만 있으면 됩니다.

```bash
composer config --global github-oauth.github.com YOUR_GITHUB_TOKEN
```

ERP 프로젝트의 `composer.json`에 현재 저장소를 VCS 저장소로 등록한 뒤
릴리스 버전을 설치합니다.

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/jetcar-dev/jds.git"
        }
    ]
}
```

```bash
composer require jetcar/jds:^1.0
php artisan vendor:publish --tag=jds-assets --force
```
