import praw
import get_entity


def reddit_data():
    r = praw.Reddit(user_agent='reddit')
    submissions = r.get_subreddit('earthporn').get_hot(limit=5)

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

