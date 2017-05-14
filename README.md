# reddit-locations
[r/EarthPorn](https://www.reddit.com/r/EarthPorn/) is a subreddit for natural landscape pictures on Reddit. As a fan of the subreddit, one thing that I felt was missing was the ability to filter images by landscape. So I built this tag-based search tool to filter images based on objects detected in images. All tags are generated using [AWS Rekognition](https://aws.amazon.com/rekognition/), a deep-learning image analysis service.

# Set Up
The entire project was deployed on AWS. Elastic Beanstalk for web app deployment (PHP, Javascript, & Bootstrap). AWS Lambda (Python) for routine updates to web app. Amazon RDS for MySQL database. A [many-to-many relationship](https://en.wikipedia.org/wiki/Many-to-many_(data_model)) was used to store the tags and images. For every image from Reddit it is described with certain tags by means of an associative table.

## Built With
* LAMP (Linux, Apache, MySQL, and PHP)
* Python 2.7
    * PRAW
    * awsebcli
* Javascript packages (included in repo)
    * Bootstrap
    * jquery
    * select2
    * flex_images
    * lightbox
    * load_images

# Installation
In `aws_lambda` directory run following:
```
python create_deployment.py
```
This will `pip install` the required packages to run the lambda scripts and bundle all the necessary files into a .zip. Then just upload the .zip to a lambda function. I used two functions for my "backend". One function is executed hourly to add the latest posts from Reddit to S3 and RDS. The other lambda function is configured to trigger whenever a new file is put to S3,  then it'll take that file and run it through Rekognition to get the tags and also put it into RDS.

In `public` folder, run the following the install Elastic Beanstalk CLI:
```
pip install --upgrade --user awsebcli
```
Feel free to use other services like Heroku to deploy too.

Next
```
eb init
```
then go through the set up process (pick PHP for platform) and
```
eb deploy
```

That should be all you'll need to get the site running.

# Notes
Some people wonder why not run the entire project inside out using Python and Django. I chose to use the LAMP stack to try something new. This may have also made the SQL queries more verbose but explicitly writing it out gives me a much better understanding of the internals.
