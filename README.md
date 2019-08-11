# DYNOVH

A DynDNS like for ovh

## How to use it

- install it with `composer require keversc/dynovh` or clone this repository
- run `composer install`
- copy the `.env.dist` file to a `.env` file : `cp .env.dist .env`
- [create an application on Ovh and get an api key](https://api.ovh.com/createToken/index.cgi)
- configure the environment variables in the `.env` file. Read the [environment variables](#environment-variables) section for more infos on these.
- add a cron with this command : `php <path_to_this_repo>/index.php dynovh:set-ip`

### What does it do ?

This command can accept an ip as it first parameter:

```
php index.php dynovh:set-ip 127.0.0.1
```

If no ip is provided, it will fetch it from a webservice on your internet provider box.

To do so, you will need to provide the internet provider name to the command via the `--provider` (or `-p) option :

```
php index.php dynovh:set-ip -p orange
```

Then, it will update your ovh dns record with this new ip address, using the Ovh api.

### Environment variables

There are 5 environment variables to configure in order for this command to work :

-`OVH_APP_KEY` : Your ovh application key

-`OVH_APP_SECRET` : Your ovh application secret

-`OVH_CONSUMER_KEY` : Your ovh consumer key

-`OVH_ZONE_NAME` : The domain name you want to update (e.g. `my_domain.com`)

-`OVH_RECORD_ID` : The record id to update (you can find it on the [ovh console](https://api.ovh.com/console/#/domain/zone/%7BzoneName%7D/record#GET))

You can get the first 3 keys by registering your application on Ovh on this page :
[https://api.ovh.com/createToken/index.cgi](https://api.ovh.com/createToken/index.cgi)

#### Warning

This currently, only works with the `Orange` internet provider.

Feel free to do a PR if you want to add another provider.
