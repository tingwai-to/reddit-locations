from __future__ import print_function
import os
import boto3


def upload_image(data):
    s3 = boto3.client('s3')
    s3.upload_file('/tmp/'+data['id'],
                   os.environ['bucket_name'],
                   data['subreddit'] + '/' + data['id'])
    print(data['id'] + ' uploaded to s3')
