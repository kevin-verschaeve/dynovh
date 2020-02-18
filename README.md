# DYNOVH

A DynDNS like for ovh

## How to use it

- install it with `composer require keversc/dynovh` or clone this repository
- run `composer install`
- [create an application on Ovh and get an api key](https://api.ovh.com/createToken/index.cgi)
- copy the `config/ovh.yaml.dist` file to a `config/ovh.yaml` file : `cp config/ovh.yaml.dist config/ovh.yaml` and configure it with the keys you've just created
- add a cron with this command : `php <path_to_this_repo>/index.php dynovh:set-ip <your_dns_zone> <ip_or_provider>`

### What does it do ?

This command can accept a DNS zone and an ip:

```
php index.php dynovh:set-ip my-website.com 127.0.0.1
```

If no ip is provided, it will fetch it from a webservice on your internet provider box.

To do so, you will need to provide the internet provider name to the command via the `--provider` (or `-p`) option :

```
php index.php dynovh:set-ip my_website.com -p orange
```

Then, it will update your ovh dns record with this new ip address, using the Ovh api.

### Configuration

There are 3 keys to configure in order for this command to work :

-`app_key` : Your ovh application key

-`app_secret` : Your ovh application secret

-`consumer_key` : Your ovh consumer key

If you have not already did it, you can get these 3 keys by registering your application on Ovh on this page :
[https://api.ovh.com/createToken/index.cgi](https://api.ovh.com/createToken/index.cgi)

#### Warning

This currently, only works with the `Orange` internet provider.

Feel free to do a PR if you want to add another provider.
