<?php

namespace App\Filament\Resources\UpdateLogResource\Pages;

use App\Filament\Resources\UpdateLogResource;
use App\Models\UpdateLog;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Closure;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ListUpdateLogs extends ListRecords
{
    protected static string $resource = UpdateLogResource::class;

    protected function getTableRecordUrlUsing(): ?Closure
    {
        return null;
    }

//    protected function getActions(): array
//    {
//        return [
//            Action::make('settings')
//                ->label('Update')
//                ->action('update'),
//        ];
//    }

    private $workingDirectory = '/var/www/html/';

    private $php_user = '';
    private $npm_user = '';
    private $git_user = '';
    private $composer_user = '';

    public function update()
    {
        $this->php_user = env('PHP_USER');
        $this->npm_user = env('NPM_USER');
        $this->git_user = env('GIT_USER');
        $this->composer_user = env('COMPOSER_USER');

        $backup = $this->backup();
        $gitPull = $this->gitPull();
        $composerInstall = $this->composerInstall();
        $npmInstall = $this->npmInstall();
        $numRunBuild = $this->npmRunBuild();
        $artisanMigrate = $this->artisanMigrate();
    }

    public function backup()
    {
        $process = new Process([$this->php_user, 'artisan', 'backup:run']);

        return $this->getResponse('artisan backup:run', $process);
    }

    public function gitPull()
    {
        $process = new Process([$this->git_user,'pull']);

        return $this->getResponse('git pull', $process);
    }

    public function composerInstall()
    {
        $process = new Process([$this->composer_user, 'install']);

        return $this->getResponse('composer install', $process);
    }

    public function npmInstall()
    {
        $process = new Process([$this->npm_user, 'install']);

        return $this->getResponse('npm install', $process);
    }

    public function npmRunBuild()
    {
        $process = new Process([$this->npm_user, 'run', 'build']);

        $env = ['PATH' => $this->workingDirectory.'node_modules/.bin/vite
        '.$this->workingDirectory.'node_modules/vite:/usr/bin'];
        $process->setEnv($env);

        return $this->getResponse('npm run build', $process);
    }

    public function artisanMigrate()
    {
        $process = new Process([$this->php_user, 'artisan', 'migrate']);

        return $this->getResponse('artisan migrate', $process);
    }

    public function getResponse($action, $process)
    {
        $process->setWorkingDirectory($this->workingDirectory);
        $response['status'] = true;
        $response['msg'] = 'success';
        try {
            $process->mustRun();
        } catch (ProcessFailedException $exception) {
            $response['status'] = false;
            $response['msg'] = 'error: '.$exception->getMessage();
        }

        UpdateLog::create([
            'action' => $action,
            'response' => $response['msg'],
        ]);

        return $response;

    }

}

