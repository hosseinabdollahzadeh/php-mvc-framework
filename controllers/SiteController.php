<?php
namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;

class SiteController extends Controller
{
    public function actionHome()
    {
        $params = [
            'name' => 'Hossein'
        ];
        return $this->render('home', $params);
    }

    public function actionContact()
    {
        return $this->render('contact');
    }
    public function actionHandleContact(Request $request)
    {
        $body = $request->getBody();
        var_dump($body);die();
        return 'handling ...';
    }

}