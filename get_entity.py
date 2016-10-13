import nltk
import os
cwd = os.getcwd() + '/nltk_data'
nltk.data.path.append(str(cwd))

def convert_sentences(sentences):
    tokenized_sentences = [nltk.word_tokenize(sentence) for sentence in sentences]
    tagged_sentences = [nltk.pos_tag(sentence) for sentence in tokenized_sentences]
    chunked_sentences = nltk.ne_chunk_sents(tagged_sentences, binary=True)
    return chunked_sentences


def extract_entity_names(t):
    entity_names = []

    if hasattr(t, 'label') and t.label:
        if t.label() == 'NE':
            entity_names.append(' '.join([child[0] for child in t]))
        else:
            for child in t:
                entity_names.extend(extract_entity_names(child))

    return entity_names


"""
Example code

with open('sample.txt', 'r') as f:
    sample = f.read().splitlines()
    sentences = nltk.sent_tokenize(sample)

chunks = convert_sentences(sentences)
entity_names = []
for tree in sentences:
    entity_names.append(extract_entity_names(tree))
"""