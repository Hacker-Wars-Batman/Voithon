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

