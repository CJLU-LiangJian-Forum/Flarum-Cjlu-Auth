<?php

/*
 * This file is part of foskytech/cjlu-auth.
 *
 * Copyright (c) 2023 FoskyM.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace FoskyTech\CjluAuth;

use Flarum\Extend;
use Flarum\Api\Serializer\UserSerializer;
use Flarum\Api\Serializer\ForumSerializer;
use Flarum\User\Event\Saving;
use Flarum\User\User;

use Fosky\CjluAuth\Listener\SaveCjluAuth;
use Fosky\CjluAuth\Middlewares\DiscussionMiddleware;

use Flarum\Foundation\Paths;
use Flarum\Http\UrlGenerator;

return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__ . '/js/dist/forum.js')
        ->css(__DIR__ . '/less/forum.less'),
    (new Extend\Frontend('admin'))
        ->js(__DIR__ . '/js/dist/admin.js')
        ->css(__DIR__ . '/less/admin.less'),
    new Extend\Locales(__DIR__ . '/locale'),
//接口
    (new Extend\Routes('api'))
        ->post('/auth/cjlu/bind', 'auth.cjlu.api.bind', Controllers\CjluAuthController::class),

    //发帖限制
    (new Extend\ApiSerializer(ForumSerializer::class))
        ->attribute('canStartDiscussion', function (ForumSerializer $serializer) {
            if ($serializer->getActor()->cjlu_user) return true;
            return false;
        }),

    //接口限制
    (new Extend\Middleware('api'))->add(DiscussionMiddleware::class),

    //事件监听
    /*(new Extend\Event())->listen(Saving::class, SaveCjluAuth::class),*/

    //初始化页面状态
    (new Extend\ApiSerializer(UserSerializer::class))
        ->attributes(function ($serializer, $user, $attributes) {
            $isAuth = false;
            if ($user->cjlu_user) {
                $isAuth = true;
            }

            $attributes['CjluAuth'] = [
                'isAuth' => $isAuth
            ];

            return $attributes;
        }),
];
