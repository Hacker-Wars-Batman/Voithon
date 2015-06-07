# Voithon

## 仕様

成功の場合はstatus => OK  
失敗の場合はstatus => Error

### 登録

URL: /users/register

multipart/form-dataでPOSTしてください

#### パラメータ

+ name: ユーザの名前
+ pass: パスワード
+ imgFile: 画像ファイル

#### 戻り値

+ 成功例

```
{
    status: "OK",
    name: "ユーザ名"
}
```

+ 失敗の場合

```
{
    status: "Error",
    message: "この名前は既に登録されています"
}
```

### ログイン

URL: /users/login

#### パラメータ

+ name: ユーザの名前
+ pass: パスワード

### Begin Run

URL: /run/begin

#### パラメータ

+ name: ユーザの名前
+ target: 目標距離 (例) 5.5
+ latitude: 緯度 (例) 35.681368
+ longitude: 経度 (例) 139.766076

### Run Finish

URL: /run/finish

#### パラメータ

+ name: ユーザの名前

#### レスポンス例

+ friends: 一緒に走った人


```
{
    "status": "OK",
    "friends": [
        {
            "location": "東京都 港区",
            "name": "test17",
            "img": "http://192.168.100.25:8000/webroot/img/test17.png"
        },
        {
            "location": "東京都 港区",
            "name": "test18",
            "img": "http://192.168.100.25:8000/webroot/img/test18.png"
        }
    ]
}
```

### Give Up

URL: /run/giveup

#### パラメータ

+ name: ユーザの名前

### 履歴の取得

URL: /run/history

#### パラメータ

+ name: ユーザの名前

#### レスポンス例

+ histories: 走った履歴
 + run_id: 一意のID（一緒に走った友だちの取得に使います）
 + target: 設定した目標
 + status: finished=完走、giveup=ギブアップ
 + location: 走った場所
 + date: 走り始めた時間
 + finished: 走り終えた時間

```
{
    "status": "OK",
    "histories": [
        {
            "run_id": "48",
            "name": "test17",
            "target": "5.5",
            "position": "0",
            "status": "finished",
            "latitude": "35.6718076",
            "longitude": "139.72135079999998",
            "date": "2015-06-07 09:23:21",
            "finish": "2015-06-07 09:23:32",
            "location": "\u6771\u4eac\u90fd \u6e2f\u533a"
        },
        {
            "run_id": "49",
            "name": "test18",
            "target": "5.5",
            "position": "0",
            "status": "finished",
            "latitude": "35.6718076",
            "longitude": "139.72135079999998",
            "date": "2015-06-07 09:23:26",
            "finish": "2015-06-07 09:24:04",
            "location": "\u6771\u4eac\u90fd \u6e2f\u533a"
        },
        {
            "run_id": "50",
            "name": "test17",
            "target": "5.5",
            "position": "0",
            "status": "finished",
            "latitude": "35.6718076",
            "longitude": "139.72135079999998",
            "date": "2015-06-07 09:25:05",
            "finish": "2015-06-07 09:26:13",
            "location": "\u6771\u4eac\u90fd \u6e2f\u533a"
        },
    ]
}
```

### 一緒に走った人の取得

URL: /run/friends

#### パラメータ

+ run_id: /run/historyから取得したrun_id

#### レスポンス例

+ friends: 一緒に走った人達
 + location: 走った場所
 + name: 名前
 + img: 画像URL

```
{
    "status": "OK",
    "friends": [
        {
            "location": "\u6771\u4eac\u90fd \u6e2f\u533a",
            "name": "test17",
            "img": "http:\/\/192.168.100.25:8000\/webroot\/img\/test17.png"
        },
        {
            "location": "\u6771\u4eac\u90fd \u6e2f\u533a",
            "name": "test18",
            "img": "http:\/\/192.168.100.25:8000\/webroot\/img\/test18.png"
        }
    ]
}
```