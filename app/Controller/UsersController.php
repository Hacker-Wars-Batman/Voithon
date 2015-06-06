<?php

App::uses('AppController', 'Controller');

class UsersController extends AppController {

    public $components = array('RequestHandler');

    /**
     * ユーザ登録
     */
    public function register() {
        $name = $this->request->data['name'];
        $pass = $this->request->data['pass'];

        $imgFile = $this->params['form']['imgFile'];
        $imgType = str_replace('image/', '', $imgFile['type']);

        try {
            $this->User->save([
                'name' => $name,
                'password' => $pass,
                'img' => 'http://' . $_SERVER['REMOTE_ADDR'] . ':8000/webroot/img/' . $name . '.' . $imgType,
            ]);

            move_uploaded_file($imgFile['tmp_name'], IMAGES . $name . '.' . $imgType);

            $result = [
                'status' => 'OK',
                'name' => $name,
            ];
        } catch (Exception $e) {
            if ($e->getCode() === "23000") {
                $result = [
                    'status' => 'Error',
                    'message' => 'この名前は既に登録されています',
                ];
            }
        }

        $this->viewClass = 'Json';
        $this->set(compact('result'));
        $this->set('_serialize', 'result');
    }

    public function login() {
        $name = $this->request->query['name'];
        $pass = $this->request->query['pass'];

        $num = $this->User->find('count', [
            'conditions' => [
                'User.name' => $name,
                'User.password' => $pass,
            ]
        ]);

        if ($num !== 1) {
            $result = [
                'status' => 'Error',
                'message' => 'パスワードまたはIDを確認してください',
            ];

            $this->viewClass = 'Json';
            $this->set(compact('result'));
            $this->set('_serialize', 'result');

            return;
        }

        $result = [
            'status' => 'OK',
        ];

        $this->viewClass = 'Json';
        $this->set(compact('result'));
        $this->set('_serialize', 'result');
    }

}
