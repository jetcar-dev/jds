# JetCar Design System

Laravel Blade 컴포넌트 패키지입니다.

## 요구 사항

- PHP 8.3 이상
- Laravel 13 이상

## 설치

프로젝트의 `composer.json`에 JDS 저장소를 등록합니다.

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

패키지를 설치합니다.

```bash
composer require jetcar/jds:^1.0
```

## 자산 배포

```bash
php artisan vendor:publish --tag=jds-assets --force
```

```blade
<link rel="stylesheet" href="{{ asset('vendor/jds/jds.css') }}">
<script type="module" src="{{ asset('vendor/jds/jds.js') }}"></script>
```

## 사용

Laravel Blade에서 `x-` 컴포넌트로 사용합니다.

```blade
<x-button variant="primary">저장</x-button>

<x-input
    name="email"
    type="email"
    placeholder="name@example.com"
/>

<x-date-picker name="date" placeholder="YYYY-MM-DD" />
```

## 업데이트

```bash
composer update jetcar/jds
php artisan vendor:publish --tag=jds-assets --force
```
