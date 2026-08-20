<?php

namespace Jetcar\Jds\Console;

use Illuminate\Console\Command;
use Jetcar\Jds\JdsServiceProvider;

final class InstallCommand extends Command
{
    protected $signature = 'jds:install
                            {--force : 기존 JDS 파일을 최신 파일로 덮어씁니다}';

    protected $description = 'JDS CSS, JavaScript, 폰트와 아이콘 번들을 설치합니다';

    public function handle(): int
    {
        $arguments = [
            '--provider' => JdsServiceProvider::class,
            '--tag' => 'jds-assets',
        ];

        if ($this->option('force')) {
            $arguments['--force'] = true;
        }

        $exitCode = $this->call('vendor:publish', $arguments);

        if ($exitCode !== self::SUCCESS) {
            $this->components->error('JDS 파일을 설치하지 못했습니다.');

            return $exitCode;
        }

        $this->components->info('JDS 파일을 public/vendor/jds에 설치했습니다.');

        return self::SUCCESS;
    }
}
