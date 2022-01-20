# yaf_swoole
yaf swoole plugin, you can use yaf on [swoole](https://github.com/swoole/swoole-src) environment

# install 

dokcer install

step 1

```
dokcer build -t swoole_yaf .
```

step 2

```
docker run --name test_swoole_yaf -p 9501:9501 -v /root:/var/www swoole_yaf
```

step 3

```
composer install
```

# test

```
curl http://localhost:9501/test
```

more test code,  view [Test.php](https://github.com/waming/yaf_swoole/blob/main/app/controllers/Test.php)

# node

It is only suitable for learning and cannot be used in production environment.
Otherwise, we will not be responsible
Welcome fork and submit PR!
