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

# test

```
curl http://localhost:9501/test
```

# ide 

You can use composer

```
composer install
```

# node

It is only suitable for learning and cannot be used in production environment.
Otherwise, we will not be responsible
