import praw
import get_entity


def main():
    r = praw.Reddit(user_agent='reddit')
    submissions = r.get_subreddit('earthporn').get_hot(limit=5)
    sentences = [x.title for x in submissions]
    chunks = get_entity.convert_sentences(sentences)

    entity_names = []
    for tree in chunks:
        entity_names.append(get_entity.extract_entity_names(tree))

    return entity_names

print main()
