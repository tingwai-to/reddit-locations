from __future__ import print_function
import os
import logging
import boto3
from boto3.dynamodb.types import TypeSerializer
import urllib
import handle_rds


rekognition = boto3.client('rekognition')
logger = logging.getLogger()
logger.setLevel(logging.INFO)


def detect_labels(bucket, key):
    response = rekognition.detect_labels(Image={"S3Object": {"Bucket": bucket, "Name": key}})

    return response

def lambda_handler(event, context):
    bucket = event['Records'][0]['s3']['bucket']['name']
    key = urllib.unquote_plus(event['Records'][0]['s3']['object']['key'].encode('utf8'))
    if key.startswith('logs/'):
        return

    print("Log stream name: ", context.log_stream_name)
    print("Log group name: ",  context.log_group_name)
    print('Received event: Bucket: {0}, Name: {1}'.format(bucket, key))

    try:
        # Calls rekognition DetectFaces API to detect faces in S3 object
        response = detect_labels(bucket, key)
        handle_rds.insert_tag(key, response)

    except Exception as e:
        print(e)
        # print("Error processing object {} from bucket {}. ".format(key, bucket))
        raise e
