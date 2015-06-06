<?php

App::uses('AppController', 'Controller');

class RunController extends AppController {

	public $uses = array('RunHistory', 'RunFriends');

	public function begin() {
        $name = $this->request->query['name'];
        $target = $this->request->query['target'];
        $latitude = $this->request->query['latitude'];//緯度
        $longitude = $this->request->query['longitude'];//経度

        $res = file_get_contents("http://geoapi.heartrails.com/api/json?method=searchByGeoLocation&x={$longitude}&y={$latitude}");
        $locationInfo = json_decode($res)->response->location[0];

        $location = $locationInfo->prefecture . ' ' . $locationInfo->city;

        try {
            $this->toGiveUp($name);

            $this->RunHistory->save([
                'name' => $name,
                'target' => $target,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'status' => 'running',
                'position' => 0.0,
                'location' => $location,
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

        $run = $this->RunHistory->find('first', [
            'conditions' => [
                'RunHistory.name' => $name,
                'RunHistory.status' => 'running',
            ]
        ]);

        if (!empty($run)) {
            $friendNames = $this->addFriends($run['RunHistory']);
        }

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
            $this->toGiveUp($name);

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

        try {
            $histories = $this->RunHistory->find('all', [
                'RunHistory.name' => $name,
                'RunHistory.status !=' => 'running',
            ]);

            $result = [
                'status' => 'OK',
                'histories' => $this->parseHistories($histories),
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

    private function parseHistories($histories) {
        foreach ($histories as $history) {
            $parsed[] = $history["RunHistory"];
        }

        return $parsed;
    }

    public function getFriends() {
        $runId = $this->request->query['run_id'];

        $allFriends = $this->RunFriends->find('all', [
            'conditions' => ['RunFriends.run_id' => $runId],
        ]);

        if (empty($allFriends)) {
            $result = [
                'status' => 'OK',
                'friends' => []
            ];

            $this->viewClass = 'Json';
            $this->set(compact('result'));
            $this->set('_serialize', 'result');

            return;
        }

        var_dump($allFriends);

                    $this->viewClass = 'Json';
            $this->set(compact('result'));
            $this->set('_serialize', 'result');
            
        die();

    }

    private function addFriends($run) {
        $sql = 'SELECT name FROM run_histories ';
        $sql .= "WHERE UNIX_TIMESTAMP('{$run['date']}') < UNIX_TIMESTAMP(finish) AND ";
        $sql .= "UNIX_TIMESTAMP(finish) < UNIX_TIMESTAMP(NOW())";

        $result = $this->RunHistory->query($sql);

        if (empty($result)) {
            return [];
        }

        foreach ($result as $val) {
            $friendsList[] = $val['run_histories']['name'];
        }

        $friends = implode(',', $friendsList);

        $this->RunFriends->save([
            'run_id' => $run['run_id'],
            'friends' => $friends
        ]);

        return $friendsList;
    }

    private function toGiveUp($name) {
            $this->RunHistory->updateAll(
                [
                    'Runhistory.status' => "'give_up'",
                ],
                [
                    'RunHistory.name' => $name,
                    'RunHistory.status' => 'running'
                ]
            );
    }
}
