# blogbits
**Content Manager Assistant for Tumblr**

blogbits automatically post your images to Tumblr. You can sync all your images from local file system. You can easily 
configure blogbits to post (n) number of posts to your blog. You can also manually post a batch of images. blogbit allows 
you can configure number of posts to be posted for sceduler, automatic and manual tagging and many other features.

#### Note
*This project is currently under development and needs a lot of work. Some features need to be tested and validation 
needs to be implemented for most features.*

## Setup Instructions

####Installation

```
$ cd /path/to/project
$ composer install
$ sudo chmod 775 -R storage bootstrap/cache && sudo chown -R :www-data storage bootstrap/cache

$ npm install
$ bower install
$ composer install
$ grunt
```

#### Environment Configuration

You can use editor of choice to edit .env configuration file. We are using subl to edit configuration.
```
$ sudo subl .env
```
Following environment variables must be defined in .env file for application to work properly.

###### API Keys
```
TUMBLR_CONSUMER_KEY=YOUR_KEY
TUMBLR_CONSUMER_SECRET=YOUR_SECRET
TUMBLR_TOKEN=YOUR_TOKEN
TUMBLR_TOKEN_SECRET=YOUR_TOKEN_SECRET
```
###### Social Authentication
```
FACEBOOK_CLIENT_ID=YOUR_CLIENT_ID
FACEBOOK_CLIENT_SECRET=YOUR_CLIENT_SECRET
FACEBOOK_REDIRECT=YOUR_REDIRECT_URL

GOOGLE_CLIENT_ID=YOUR_CLIENT_ID
GOOGLE_CLIENT_SECRET=YOUR_CLIENT_SECRET
GOOGLE_REDIRECT=YOUR_REDIRECT_URL
```

###### Tumblr Blog and Post
```
TUMBLR_BLOG=TUMBLR_BLOG (blog.tumblr.com)
POST_LINK=POST_IMAGE_CLICK_LINK

PINTEREST=PINTEREST_PROFILE_URL
FACEBOOK=FACEBOOK_PAGE_URL
````

#### Migrations and Seeding
Before running these migrations, you must define database credentials in .env configuration file.
```
$ php artisan migrate
$ php artisan db:seed
```

###### Content Sync Folder
````
SYNC_FOLDER=/path/to/syncfolder
````

#### Folder Permissions
You need to setup permissions for your sync folder in order for blogbits to sync all images.
```
$ sudo chmod -R :www-data /path/to/syncfolder
```

#### File Naming
blogbits automatically sync files on request from sync folder. It automatically detects file name 
and remove any special characters and numbers. The name of your file will be used as caption and slug
for your posts. you should properly name your files and avoid random renaming.

#### Scheduler Configration
You need to add a cron job to your system for blogbits for posting synced content. This command will open
cron jobs file.
```
$ sudo crontab -u www-data -e
```
You need to add following line at the end of your cron file.
```
* * * * * php /path/to/blogbits/artisan schedule:run >> /dev/null 2>&1
```








