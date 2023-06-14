<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

//新增 users 表字段 cjlu_user
return [
    'up' => function (Builder $schema) {
        if (!$schema->hasColumn('users', 'cjlu_user')) {
            $schema->table('users', function (Blueprint $table) use ($schema) {
                $table->string('cjlu_user', 255)->nullable();
                /*$table->text('cjlu_cookies');*/
            });
        }
    },
    'down' => function (Builder $schema) {
        $schema->table('users', function (Blueprint $table) {
            $table->dropColumn('cjlu_user');
            /*$table->dropColumn('cjlu_cookies');*/
        });
    },
];
