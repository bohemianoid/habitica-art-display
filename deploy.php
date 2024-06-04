<?php

namespace Deployer;

require 'recipe/laravel.php';

// Config

set('repository', getenv('REPOSITORY'));

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host(getenv('HOST'))
    ->set('remote_user', getenv('REMOTE_USER'))
    ->set('deploy_path', getenv('DEPLOY_PATH'));

// Tasks

task('deploy:writable')->disable();

// Hooks

after('artisan:migrate', 'artisan:queue:restart');
after('deploy:failed', 'deploy:unlock');
