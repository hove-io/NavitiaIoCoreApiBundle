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
            new CanalTP\TyrBundle\CanalTPTyrBundle(),
            new CanalTP\NavitiaIoCoreApiBundle\CanalTPNavitiaIoCoreApiBundle(),
        );
    }
```

Tyr configuration

``` yml
# Tyr api configuration
canal_tp_tyr:
    url:            %tyr_url%
    end_point_id:   2
```

Add parameters in **parameters.yml**:

``` yml
parameters:
    tyr_url: http://tyr.dev.canaltp.fr/v0/
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
 * @ORM\MappedSuperclass
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
