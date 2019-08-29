Simple Event Tracking
=====================

Sometimes, all you need to do is track how often an event occurs. But, Google Analytics returns this data too slowly and Mixpanel is too expensive.

Simple Event Tracking is a Laravel application you can use for logging events, and then querying how often they occur (with realtime results).

[![CircleCI](https://circleci.com/gh/danielbachhuber/simple-event-tracking.svg?style=svg)](https://circleci.com/gh/danielbachhuber/simple-event-tracking)

## Installing

This project assumes you know how to deploy a Laravel application. If you don't, there are plenty of guides on the internet. So, deploy the Simple Event Tracking application on whatever infrastructure you think most appropriate.

Once you've done so, you'll need to set `SET_ACCESS_TOKEN` in your `.env` to some randomized token. This token is used to authorize `GET` requests.

## Using

After you've deployed the application, simply `POST` to the `/api/write` endpoint:

```bash
$ http POST simple-event-tracking.test/api/write key=foo value=bar
{
    "status": "ok"
}
```

Once you have some events, you can query for summaries with a `GET` to the `/api/read` endpoint:

```bash
$ http GET simple-event-tracking.test/api/read key=foo --json --auth-type=token --auth="Bearer:<token-value>"
{
    "bar": 6
}
```

Easy mode!

## License

Simple Event Tracking is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

