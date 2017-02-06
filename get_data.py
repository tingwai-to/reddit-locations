from __future__ import print_function
import os
import urllib
import requests
import imghdr
import praw


def reddit_data(subname):
    LIMIT=15

    reddit = praw.Reddit(client_id = os.environ['client_id'],
                         client_secret = os.environ['client_secret'],
                         username = os.environ['username'],
                         password = os.environ['password'],
                         user_agent = os.environ['user_agent'])
    subreddit = reddit.subreddit(subname)

    hot = []
    for submission in subreddit.hot(limit=LIMIT):
        if submission.stickied:
            continue
        hot.append({'id': submission.id,                            # string
                    'score': submission.score,                      # int
                    'created_utc': int(submission.created_utc),     # int
                    'title': submission.title,                      # string
                    'url': submission.url,                          # string
                    'thumbnail': submission.thumbnail,              # string
                    'author': submission.author.name,               # string
                    'subreddit': submission.subreddit.display_name  # string
                    })
    return hot

def save_image(data):
    fname = '/tmp/'+data['id']

    try:
        if requests.head(data['url']).headers['Content-Type'] == 'image/jpeg':
            urllib.urlretrieve(data['url'], fname)
            print(data['id'] + ' saved to /tmp')
            return True

        else:
            urllib.urlretrieve(data['url'], fname)

            if imghdr.what(fname) == 'jpeg':
                print(data['id'] + ' saved to /tmp')
                return True
            else:
                # os.remove(data['id'])
                print(data['id'] + ' not jpeg')
                return False

    except Exception as exc:
        print(exc)
        print('Unable to save image {}'.format(data['id']))
        return False