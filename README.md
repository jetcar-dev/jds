# JetCar Design System

Laravel Blade 컴포넌트 패키지입니다. ERP 프로젝트에서는 빌드가 완료된
CSS와 JavaScript를 사용하므로 npm을 별도로 실행하지 않습니다.

## 요구 사항

- PHP 8.3 이상
- Laravel 13 이상

## 설치

프로젝트 폴더에서 실행합니다.

```bash
composer require "jetcar/jds:^1.0"
composer show jetcar/jds
```

서비스 프로바이더, Blade 컴포넌트, CSS와 JavaScript가 자동으로 연결됩니다.
레이아웃 파일에 별도의 `<link>` 또는 `<script>`를 작성하지 않아도 됩니다.

## 이미 설치한 경우

저장소를 처음 받은 컴퓨터에서는 다음 명령을 실행합니다.

```bash
composer install
php artisan optimize:clear
```

## 업데이트

```bash
composer clear-cache
composer update jetcar/jds -W
php artisan optimize:clear
composer show jetcar/jds
```

## 완전히 제거한 후 다시 설치

```bash
composer remove jetcar/jds
composer clear-cache
composer require "jetcar/jds:^1.0"
php artisan optimize:clear
```

## 문제 해결

프로젝트의 Composer 스크립트가 Laravel 자산 복사를 실행하지 않는 경우에만
다음 명령을 한 번 실행합니다.

```bash
php artisan vendor:publish --tag=jds-assets --force
```

Vite에서 JDS 원본을 직접 불러오는 개발 앱은 `.env`에서 자동 연결을 끌 수 있습니다.

```dotenv
JDS_AUTO_ASSETS=false
```
