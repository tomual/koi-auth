# koi-auth
Authentication API service written using Slim. 
The idea is that the developer is the gardener, and the gardener may have multiple ponds (apps) where he may keep his koi (users).

Live version can be found [here](https://coco.lat/koi-auth/public/api/user/create)

![Home page](https://raw.githubusercontent.com/tomual/koi-auth/master/images/ss1.PNG)

## Features

* API key & API secret authorization
* API call to create user
* API call to log in user
* Developer may have multiple apps
* Rate limiting (per day)
* Dashboard for each pond to show number of calls made, and list of users

## Installation
1. Place files in server running PHP 7.0+
2. `composer install` to install packages
3. Modify `bootstrap/app.php` to insert database credentials
4. Run `sql/tables.sql` to create tables

## Example

Example of creating a user in PHP

```
// User data
$data = [
	'email' => 'treello123123@mail.com',
	'username' => 'manapool9',
	'password' => '123123',
];

// Setting up token
$key = 'a6e49cb4eaf556e228ddf5f5c215cfcf';
$secret = '78dcf7bdf57d38d59dbf4ed1ee338d20';
$hash = md5($secret . date('Y-m-d'));
$usrpwd = $key . ':' . $hash;
$token = 'Basic ' . base64_encode($usrpwd);

// Sending the request
$url = 'https://coco.lat/koi-auth/public/api/user/create';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POST,true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: ' . $token));
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$result = curl_exec($ch);
curl_close($ch);
echo($result);
```

The response will contain the user ID and other details.

```
{
  "message": {
    "pond_id": 5,
    "email": "treello123123@mail.com",
    "username": "manapool9",
    "updated_at": "2018-07-18 01:48:21",
    "created_at": "2018-07-18 01:48:21",
    "id": 21
  }
}
```


## Screenshots

Here is an API call made to the registration endpoint
![API call](https://raw.githubusercontent.com/tomual/koi-auth/master/images/ss2.PNG)
