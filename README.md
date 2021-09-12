# Landingi recruitment application

## Installation
Requires:
- Docker [https://docs.docker.com/engine/install](https://docs.docker.com/engine/install)

1. `git clone git@github.com:landingi/recruitment-junior-php.git`
2. `cd recruitment-junior-php`
3. `make up`
4. Go to [http://recruitment.localhost](http://recruitment.localhost)
5. Profit

## Everyday use

- Use `make up`, `make stop` and `make down` to start/stop/kill the docker containers.
- `docker-compose exec app bash` - get into container
- `make build` rebuilds the images if needed
- `make ci` runs the test suite

## Curl Testing

- Creating new user (/users POST)

`curl http://recruitment.localhost/users -X POST -d 'email=yourusername@wp.pl'`
  
- Removal user with all articles (/users DELETE)
  
`curl -H "API-KEY: {uuid}" http://recruitment.localhost/users -X DELETE`
  
- Adding new article (/articles POST)

`curl -H "API-KEY: {uuid}" http://recruitment.localhost/articles -X POST -d 'title=TestTitle&content=TestContent'`
  
- Get all user articles
  
` curl -H "API-KEY: {uuid}" http://recruitment.localhost/articles -X GET`


## Tips 

- Pagination is available with query param `eg. http://recruitment.localhost/articles?limit=3 `
  
  
  
  