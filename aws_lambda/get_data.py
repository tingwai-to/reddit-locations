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

        metadata = {'id': submission.id,                            # string
                    'score': submission.score,                      # int
                    'created_utc': int(submission.created_utc),     # int
                    'title': submission.title,                      # string
                    'thumbnail': submission.thumbnail,              # string
                    'author': submission.author.name,               # string
                    'subreddit': submission.subreddit.display_name  # string
                    }

        if hasattr(submission, 'preview'):
            metadata['url'] = post_preview(submission)
            metadata['preview'] =  post_preview(submission, downscale_quality=True)
        else:
            metadata['url'] = submission.url
            metadata['preview'] = None

        hot.append(metadata)

    return hot

def save_image(data):
    fname = '/tmp/'+data['id']

    try:
        urllib.urlretrieve(data['url'], fname)

        if requests.head(data['url']).headers['Content-Type'] == 'image/jpeg':
            print(data['id'] + ' saved to /tmp and is jpg')
            return True

        elif imghdr.what(fname) == 'jpeg':
            # when url is image but header doesn't reflect that
            print(data['id'] + ' saved to /tmp and is jpg')
            return True

        elif 'imgur.com' in data['url']:
            # when url links to imgur gallery, not direct link to image
            insert_pos = data['url'].find('imgur.com')
            imgur_url = data['url'][:insert_pos] + 'i.' + data['url'][insert_pos:] + '.jpg'
            urllib.urlretrieve(imgur_url, fname)

            if imghdr.what(fname) == 'jpeg':
                print(data['id'] + ' saved to /tmp and is jpg')
                return True
            else:
                print(data['id'] + ' not jpeg')
                return False

        else:
            print(data['id'] + ' not jpeg')
            return False

    except Exception as exc:
        print(exc)
        print('Unable to save image {}'.format(data['id']))
        return False

def post_preview(submission, downscale_quality = False):
    try:
        downscale = submission.preview['images'][0]['resolutions']
        source = submission.preview['images'][0]['source']

        if downscale_quality:
            previews = downscale + [source]
            for size in previews:
                if size['height'] >= 400:
                    return size['url']
        else:
            return source['url']


    except Exception as exc:
        print(exc)
        return None
