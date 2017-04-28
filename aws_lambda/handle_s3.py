from __future__ import print_function
import os
import boto3
import botocore


s3 = boto3.client('s3')

def upload_image(data):
    """
    Uploads image to S3 in respective subreddit folder.
    
    Args:
        data (dict): Reddit metadata
        
    Returns:
        None  
    
    """
    s3.upload_file('/tmp/'+data['id'],
                   os.environ['bucket_name'],
                   data['subreddit'] + '/' + data['id'])
    print(data['id'] + ' uploaded to s3')

def file_exists(data):
    """
    Checks if file already exists in S3.
    
    Args:
        data (dict): Reddit metadata
        
    Returns:
        exists (bool): True if file already exists
        
    """
    s3 = boto3.resource('s3')
    fname = data['subreddit'] + '/' + data['id']
    exists = False

    try:
        s3.Object('reddit-locations', fname).load()
    except botocore.exceptions.ClientError as e:
        if e.response['Error']['Code'] == "404":
            exists = False
        else:
            raise
    else:
        exists = True

    print('File exists = {}'.format(exists))
    return exists
