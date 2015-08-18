NavitiaIoCoreApiBundle
======================

This bundle extends FOSUserBundle and provide an user rest API using Tyr.


## Installation

Install via composer

``` js
{
    "require": {
        "canaltp/navitia-io-core-api-bundle": "dev-master"
    }
}
```

Updating **AppKernel.php**:

``` php
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new CanalTP\TyrBundle\CanalTPTyrBundle(),
            new CanalTP\NavitiaIoCoreApiBundle\CanalTPNavitiaIoCoreApiBundle(),
        );
    }
```

Tyr api configuration

``` yml
# app/config.yml
canal_tp_tyr:
    url:            %tyr_url%
    end_point_id:   2
```

Add parameters in **parameters.yml**:

``` yml
parameters:
    tyr_url: http://tyr.dev.canaltp.fr/v0/
```

Default configurations for FosUser and JmsSerializer:

``` yml
# app/config.yml
imports:
    - { resource: "@CanalTPNavitiaIoCoreApiBundle/Resources/config/fos_user.yml"}
    - { resource: "@CanalTPNavitiaIoCoreApiBundle/Resources/config/fos_rest.yml"}
    - { resource: "@CanalTPNavitiaIoCoreApiBundle/Resources/config/jms_serializer.yml"}
```

Configuration for FOSUser:

``` yml
fos_user:
    user_class: Acme\AppBundle\Entity\User # your user class
```

Mount api:

``` yml
# app/routing.yml
navitia_io_core_api_rest_user:
    type: rest
    resource: "@CanalTPNavitiaIoCoreApiBundle/Resources/config/routing_rest.yml"
    prefix: /api
```


## Usage

Set this bundle as parent of your user bundle:

``` php
    public function getParent()
    {
        return 'CanalTPNavitiaIoCoreApiBundle';
    }
```

Extending [User](Entity/User.php) class:

``` php
namespace Acme\AppBundle\Entity;

use CanalTP\NavitiaIoCoreApiBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="CanalTP\NavitiaIoCoreApiBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    // Custom fields
}
```


## Contributors

1. Thomas Noury - thomas.noury@canaltp.fr
2. Julien Maulny - julien.maulny@canaltp.fr
3. Ludovic Roche - ludovic.roche@canaltp.fr
4. Rémy Abi Khalil - remy.abi-khalil@canaltp.fr


## License

This project is under [GPL-3.0 License](LICENSE).
