<?php

App::uses('AppController', 'Controller');

class UsersController extends AppController {

    public $components = array('RequestHandler');

    /**
     * ユーザ登録
     */
    public function register() {

        $name = $this->request->query['name'];
        $pass = $this->request->query['pass'];

        try {
            $this->User->save([
                'name' => $name,
                'password' => $pass,
            ]);

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

}
