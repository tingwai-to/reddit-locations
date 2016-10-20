import praw
import get_entity
import googlemaps
from pprint import pprint as pp


def reddit_data():
    r = praw.Reddit(user_agent='reddit')
    submissions = r.get_subreddit('earthporn').get_hot(limit=10)

    next(submissions)       # skip first pinned post
    titles = []
    urls = []
    for item in submissions:
        titles.append(item.title)
        urls.append(item.url)
    chunks = get_entity.convert_sentences(titles)

    entity_names = []
    for tree in chunks:
        entity_names.append(get_entity.extract_entity_names(tree))

    return urls, titles, entity_names

urls, titles, entity_names = reddit_data()
pp(titles)
pp(entity_names)

def maps_location(string):
    with open('apikey.txt') as f:
        mykey = f.read()
    gmaps = googlemaps.Client(key=mykey)
    result = gmaps.places_autocomplete(string)
    return result

x = maps_location('google')
