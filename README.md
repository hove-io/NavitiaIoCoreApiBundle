README
======

What is NAVITIA.IO ?
------------------

- [Git](https://github.com/CanalTP/NavitiaIoCoreApiBundle)


Requirements
-------------

- postGreSQL database


Installation
-------------

    public function getParent()
    {
        return 'CanalTPNavitiaIoCoreApiBundle';
    }

### 1. Routing configuration

    Add this lines in routing.yml of your application

```
    rest_user:
        type: rest
        resource: "@CanalTPNavitiaIoCoreApiBundle/Resources/config/routing_rest.yml"

```

### 2. JMS Serializer configuration

    Add this lines in your config.yml

```
    jms_serializer:
        metadata:
            debug: "%kernel.debug%"
            file_cache:
                dir: "%kernel.cache_dir%/serializer"
            auto_detection: true
            directories:
                FOSUserBundle:
                    namespace_prefix: FOS\UserBundle
                    path: "@CanalTPNavitiaIoCoreApiBundle/Resources/config/serializer/FOSUserBundle"
                KnpPaginatorBundle:
                    namespace_prefix: Knp\Bundle\PaginatorBundle
                    path: "@CanalTPNavitiaIoCoreApiBundle/Resources/config/serializer/KnpPaginatorBundle"
                KnpPager:
                    namespace_prefix: Knp\Component\Pager
                    path: "@CanalTPNavitiaIoCoreApiBundle/Resources/config/serializer/KnpPager"
```

### 3. Behat configuration

    Add "suite" in your behat.yml

```
        rest_api_users:
            type: symfony_bundle
            bundle: 'CanalTPNavitiaIoRestBundle'
```

### 4. Parameters

    Add this lines in your parameters.yml

```
    rest_api_users:
        user_test:
            password: password_test
            roles: ROLE_API_USER
    test_database_host: localhost
    test_database_user: navio_local_test
    test_database_name: sam
    test_database_password: sam
```

Contributing
-------------

1. Thomas Noury - thomas.noury@canaltp.fr
2. Julien Maulny - julien.maulny@canaltp.fr
3. Ludovic Roche - ludovic.roche@canaltp.fr
4. Rémy Abi Khalil - remy.abi-khalil@canaltp.fr
