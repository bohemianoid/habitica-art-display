<?php

namespace Deployer;

require 'contrib/npm.php';
require 'recipe/laravel.php';

// Config

set('repository', getenv('REPOSITORY'));
set('bin/php', 'php83');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host(getenv('HOST'))
    ->set('remote_user', getenv('REMOTE_USER'))
    ->set('deploy_path', getenv('DEPLOY_PATH'));

// Tasks

task('deploy:writable')->disable();
task('npm:run:build', function () {
    run('cd {{release_path}} && {{bin/npm}} run build');
});

// Hooks

after('deploy:update_code', 'npm:install');
after('npm:install', 'npm:run:build');
after('artisan:migrate', 'artisan:queue:restart');
after('deploy:failed', 'deploy:unlock');
