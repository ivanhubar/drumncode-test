# drumncode-test

## To deploy project run

```shell
make init
```

After deploying - u have filled database (with 3 users). <br>

Api-doc available: http://localhost:88/api/doc/ (88 - NGINX_EXPOSE_PORT)

In console u will see auth jwt tokens for all users with their nicknames

### Docker env parameters(.env):

|      Parameter      |      Description       |
|:-------------------:|:----------------------:|
| `NGINX_EXPOSE_PORT` |   Nginx expose port    |
|   `POSTGRES_USER`   | Database user username |
| `POSTGRES_PASSWORD` | Database user password |
| `POSTGRES_DATABASE` |     Database name      |
|   `POSTGRES_PORT`   |  Database expose port  |

## Makefile commands

|     Command     |                  Description                  |
|:---------------:|:---------------------------------------------:|
|     `start`     |                 Start project                 |
|     `init`      |              Initialize project               |
| `load-fixtures` |                 Load fixtures                 |
|  `auth-token`   | Get auth jwt tokens for all users in database |
