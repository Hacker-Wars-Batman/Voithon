<?php

App::uses('AppController', 'Controller');

class RunController extends AppController {

	public $uses = array('RunHistory', 'RunFriends');

	public function begin() {
        $name = $this->request->query['name'];
        $target = $this->request->query['target'];
        $latitude = $this->request->query['latitude'];//緯度
        $longitude = $this->request->query['longitude'];//経度

        try {
            $this->RunHistory->save([
                'name' => $name,
                'target' => $target,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'status' => 'running',
                'position' => 0.0,
            ]);

            $result = [
                'status' => 'OK',
            ];
        } catch (Exception $ex) {
            $result = [
                'status' => 'Error',
                'message' => $ex->getMessage(),
            ];
        }

        $this->viewClass = 'Json';
        $this->set(compact('result'));
        $this->set('_serialize', 'result');
	}

    public function finish() {
        $name = $this->request->query['name'];

        try {
            $this->RunHistory->updateAll(
                [
                    'Runhistory.finish' => 'NOW()',
                    'Runhistory.status' => "'finished'",
                ],
                [
                    'RunHistory.name' => $name,
                    'RunHistory.status' => 'running'
                ]
            );

            $result = [
                'status' => 'OK'
            ];
        } catch (Exception $ex) {
            $result = [
                'status' => 'Error',
                'message' => $ex->getMessage(),
            ];
        }

        $this->viewClass = 'Json';
        $this->set(compact('result'));
        $this->set('_serialize', 'result');
    }

    public function giveUp() {
        $name = $this->request->query['name'];

        try {
            $this->RunHistory->updateAll(
                [
                    'Runhistory.status' => "'give_up'",
                ],
                [
                    'RunHistory.name' => $name,
                    'RunHistory.status' => 'running'
                ]
            );

            $result = [
                'status' => 'OK'
            ];
        } catch (Exception $ex) {
            $result = [
                'status' => 'Error',
                'message' => $ex->getMessage(),
            ];
        }

        $this->viewClass = 'Json';
        $this->set(compact('result'));
        $this->set('_serialize', 'result');
    }

    public function getHistories() {
        $name = $this->request->query['name'];


    }
}
