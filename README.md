# Mcrypt-php
php mcrypt 加解密类

#Usage
```php

$Key = Mcrypt::newInstance()->getKey();


$en = Mcrypt::newInstance()->encrypt($Key,"This is data");

$dn = Mcrypt::newInstance()->decrypt($Key,$en);
```
