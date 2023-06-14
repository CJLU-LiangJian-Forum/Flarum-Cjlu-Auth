<?php

namespace FoskyTech\CjluAuth\Controllers;

use Fosky\CjluAuth\Common\CjluLogin;
use Flarum\Http\RequestUtil;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Flarum\Settings\SettingsRepositoryInterface;
use Fosky\CjluAuth\Users;
class CjluAuthController implements RequestHandlerInterface
{
    protected $settings;
    public function __construct(SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $actor = RequestUtil::getActor($request);
        $actor->assertRegistered();

        if ($actor->cjlu_user) {
            return new JsonResponse([ 'status' => false, 'msg' => 'already bind' ]);
        }

        $params = $request->getParsedBody();
        $query = Users::select('id', 'cjlu_user')
            ->where(['cjlu_user' => $params['username']])
            ->first();

        if ($query) {
            return new JsonResponse([ 'status' => false, 'msg' => 'user_exist' ]);
        }

        $serverUrl = $this->settings->get('cjlu-auth.serverUrl');

        if (CjluLogin::execute($serverUrl, $params['username'], $params['password'])) {
            $user = Users::select('*')
                ->where(['id' => $actor->id])
                ->first();
            $user->cjlu_user = $params['username'];
            /*$user->cjlu_pass = $params['password'];*/
            $user->save();
            return new JsonResponse([ 'status' => true, 'msg' => 'bind success' ]);
        }

        return new JsonResponse([ 'status' => false, 'msg' => 'verify_failed' ]);
        //return new JsonResponse(AliSMS::send($request->getParsedBody(), $actor->id));
    }
}
