# Voithon

## 仕様

### 登録

URL: /users/register

#### パラメータ

+ name: ユーザの名前
+ pass: パスワード

#### 戻り値

+ 成功例

```
{
    status: "OK"
    name: "test"
}
```

+ 失敗の場合

```
{
    status: "Error"
    message: "この名前は既に登録されています"
}
```

### Begin Run

URL: /run/begin

#### パラメータ

+ name: ユーザの名前
+ target: 目標距離 (例) 5.5
+ latitude: 緯度 (例) 35.681368
+ longitude: 経度 (例) 139.766076