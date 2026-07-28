# JetCar Design System

Laravel Blade 컴포넌트 패키지입니다. ERP 프로젝트에서는 빌드가 완료된
CSS와 JavaScript를 사용하므로 npm을 별도로 실행하지 않습니다.

## 요구 사항

- PHP 8.3 이상
- Laravel 13 이상

### 1. 패키지 주소 등록

프로젝트의 `composer.json`에 `repositories`를 추가합니다.

```json
{
    "repositories": [
        {
            "type": "composer",
            "url": "https://raw.githubusercontent.com/jetcar-dev/jds/main/composer-repository"
        }
    ]
}
```

## 설치

프로젝트 폴더에서 실행합니다.

```bash
composer require "jetcar/jds:^1.0"
php artisan vendor:publish --tag=jds-assets --force
composer show jetcar/jds
```

### CSS와 JavaScript 복사

```bash
php artisan vendor:publish --tag=jds-assets --force
```
### 연결

`<head>` 안에 CSS를 추가합니다.

```blade
<link rel="stylesheet" href="{{ asset('vendor/jds/jds.css') }}">
```

`</body>` 앞에 JavaScript를 추가합니다.

```blade
<script type="module" src="{{ asset('vendor/jds/jds.js') }}"></script>
```

## 이미 설치한 경우

```bash
composer install
php artisan vendor:publish --tag=jds-assets --force
php artisan optimize:clear
```
### 업데이트

```bash
composer clear-cache
composer update jetcar/jds -W
php artisan vendor:publish --tag=jds-assets --force
php artisan optimize:clear
composer show jetcar/jds
```

## 완전히 제거한 후 다시 설치

```bash
composer remove jetcar/jds
composer clear-cache
composer require "jetcar/jds:^1.0"
php artisan vendor:publish --tag=jds-assets --force
php artisan optimize:clear
```
