# WebChek
This is a laravel project. 
https://laravel.com/docs/8.x

This is stored on github https://github.com/ddell003/webchek-api

### Getting Started

#### For windows
1. Install xampp https://divpusher.com/blog/how-to-run-laravel-on-windows-with-xampp/
2. Install xamp and point it to the php.exe inside of xampp
3. Place code or do git clone of repo into the htdocs
#### For Mac



1. ```composer install```
2. ```cp .env-example .env```
3. ```php artisan config:cache```
4. set up the .env variables (includes setting up database credientials)
    - For testing locally just use sqlite ```touch /database/database.sqlite```
5. Run Migrations to populate database ```php artisan migrate```
6. setup local server by: ```php artisan serve``` copy the returned url to paste into the UI

### Architecture
#### Api
The API utilizes a Service Repository Pattern. 

So laravel utilizes MVC has a router which basically say given a url and method ex. GET users, direct them to a controller. 
The controller then determines which service needs to be called. Services hold all the business logic and abstract it out to reusable/callable code.
This came in handy when the api/PartyController needed to know party information and the RsvpController needed to get party information as well. 
Instead of having duplicate logic, it has been abstracted out to the PartyService. The services then perform the various business logic 
such as determining if a user is allowed to perform an action and other process such as deciding how to "RSVP" a user. To interact with the databases, 
the service calls a repository which sits on top of the model which directly interacts with the database. The repository contains information 
pertinent to the database. The repositories extend my base repository class which contains all the CRUD logic. Thus the individual libraries 
are very simple but allow you to introduce specific complex logic as needed. 
 
## Deployments

I am using Heroku as my server
https://www.heroku.com/

Install the Heroku CLI
Download and install the Heroku CLI.

If you haven't already, log in to your Heroku account and follow the prompts to create a new SSH public key.

```$ heroku login```
Clone the repository
Use Git to clone project's source code to your local machine.

`````
$ heroku git:clone -a webchek
$ cd parkersbookstore
`````
Deploy your changes
Make some changes to the code you just cloned and deploy them to Heroku using Git.
``````
$ git add .
$ git commit -am "make it better"
$ git push heroku master
``````
### Setting Up .env Variables in Heroku
https://devcenter.heroku.com/articles/config-vars
``````
$ heroku config
$ heroku config:set DB_CONNECTION=pgsql
$ heroku config:set DB_PASSWORD='password'
$ git push heroku master
``````
### Running Migrations
``````
$ heroku run bash 
$ php artisan migrate
$ php artisan user:create --first_name=Parker --last_name=Dell --email=parkerdell94@gmail.com --password=testtest --admin=1
``````

## Interacting With API
### Authentication
The API uses basic API tokens for authentication. Not the best security but it works for the class.
To get a token, you need to login at the login endpoint shown in postman, then copy the api_token key returned in the response
Then, when making API calls via  postman, select the Authorization tab and then select "Bearer Token" as authorization type and past your token into the form field
1. Install postman
2. Import postman collection stored in root of project
3. hit the stored end points (see )

## Testing
in terminal run ```php artisan test```

Running specific tests ``` php artisan test --filter AppTest```

Running Methods ````php artisan test --filter testBasicTest ````
